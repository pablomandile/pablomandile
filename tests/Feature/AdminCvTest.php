<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCvTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_cv_admin(): void
    {
        $this->get('/admin/cv')->assertRedirect('/login');
    }

    public function test_admin_can_upload_a_cv(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())
            ->post('/admin/cv', [
                'cv' => UploadedFile::fake()->create('mi-cv.pdf', 200, 'application/pdf'),
            ])
            ->assertRedirect('/admin/cv');

        $cv = Setting::get('cv');
        $this->assertNotNull($cv['path'] ?? null);
        Storage::disk('public')->assertExists($cv['path']);
        $this->assertSame('mi-cv.pdf', $cv['original_name']);
    }

    public function test_uploading_a_new_cv_replaces_the_previous_one(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/cv', [
            'cv' => UploadedFile::fake()->create('old.pdf', 100, 'application/pdf'),
        ]);
        $oldPath = Setting::get('cv')['path'];
        Storage::disk('public')->assertExists($oldPath);

        $this->actingAs($user)->post('/admin/cv', [
            'cv' => UploadedFile::fake()->create('new.pdf', 100, 'application/pdf'),
        ])->assertRedirect('/admin/cv');

        $newPath = Setting::get('cv')['path'];
        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath); // el anterior se borró
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_non_pdf_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())
            ->post('/admin/cv', [
                'cv' => UploadedFile::fake()->create('foto.jpg', 100, 'image/jpeg'),
            ])
            ->assertSessionHasErrors('cv');
    }

    public function test_admin_can_delete_the_cv(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/cv', [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);
        $path = Setting::get('cv')['path'];

        $this->actingAs($user)->delete('/admin/cv')->assertRedirect('/admin/cv');

        Storage::disk('public')->assertMissing($path);
        $this->assertNull(Setting::get('cv'));
    }
}
