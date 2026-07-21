<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_certificates_admin(): void
    {
        $this->get('/admin/certificates')->assertRedirect('/login');
    }

    public function test_admin_can_upload_a_certificate(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())
            ->post('/admin/certificates', [
                'title' => 'Laravel Avanzado',
                'image' => UploadedFile::fake()->image('cert.png', 1200, 850),
                'sort_order' => '2',
            ])
            ->assertRedirect('/admin/certificates');

        $certificate = Certificate::firstWhere('title', 'Laravel Avanzado');

        $this->assertNotNull($certificate);
        $this->assertSame(2, $certificate->sort_order);
        $this->assertTrue($certificate->is_published);
        Storage::disk('public')->assertExists($certificate->image);
    }

    public function test_admin_can_toggle_publish_state(): void
    {
        Storage::fake('public');

        $certificate = Certificate::create([
            'title' => 'Curso X',
            'image' => UploadedFile::fake()->image('x.png')->store('certificates', 'public'),
        ]);

        $this->actingAs(User::factory()->create())
            ->patch("/admin/certificates/{$certificate->id}", ['is_published' => false])
            ->assertRedirect('/admin/certificates');

        $this->assertFalse($certificate->refresh()->is_published);
    }

    public function test_admin_can_delete_a_certificate_and_its_image(): void
    {
        Storage::fake('public');

        $certificate = Certificate::create([
            'title' => 'Para borrar',
            'image' => UploadedFile::fake()->image('gone.png')->store('certificates', 'public'),
        ]);
        $image = $certificate->image;

        $this->actingAs(User::factory()->create())
            ->delete("/admin/certificates/{$certificate->id}")
            ->assertRedirect('/admin/certificates');

        $this->assertDatabaseMissing('certificates', ['id' => $certificate->id]);
        Storage::disk('public')->assertMissing($image);
    }

    public function test_only_published_certificates_are_shown_on_home(): void
    {
        Certificate::create(['title' => 'Visible', 'image' => 'https://picsum.photos/seed/v/10/10', 'is_published' => true]);
        Certificate::create(['title' => 'Oculto', 'image' => 'https://picsum.photos/seed/o/10/10', 'is_published' => false]);

        $this->get('/')
            ->assertInertia(fn ($page) => $page
                ->component('Home')
                ->has('certificates', 1)
                ->where('certificates.0.title', 'Visible'));
    }
}
