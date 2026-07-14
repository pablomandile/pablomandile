<?php

namespace Tests\Feature;

use App\Enums\TechCategory;
use App\Models\Project;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_the_admin(): void
    {
        $this->get('/admin/projects')->assertRedirect('/login');
    }

    public function test_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_admin_can_create_a_project_with_preview_image(): void
    {
        Storage::fake('public');

        $technology = Technology::create([
            'name' => 'Laravel',
            'category' => TechCategory::Backend,
            'sort_order' => 1,
        ]);

        $this->actingAs(User::factory()->create())
            ->post('/admin/projects', [
                'title' => 'Mi Proyecto Nuevo',
                'slug' => '',
                'description' => ['es' => 'Descripción', 'en' => 'Description'],
                'demo_url' => 'https://example.com',
                'repo_url' => '',
                'preview_image' => UploadedFile::fake()->image('preview.png', 800, 500),
                'is_featured' => '1',
                'is_published' => '1',
                'sort_order' => '5',
                'technologies' => [$technology->id],
            ])
            ->assertRedirect('/admin/projects');

        $project = Project::firstWhere('slug', 'mi-proyecto-nuevo');

        $this->assertNotNull($project);
        $this->assertTrue($project->is_featured);
        $this->assertSame(5, $project->sort_order);
        $this->assertCount(1, $project->technologies);
        Storage::disk('public')->assertExists($project->preview_image);
    }

    public function test_admin_can_update_a_project_replacing_the_image(): void
    {
        Storage::fake('public');

        $project = Project::create([
            'title' => 'Original',
            'slug' => 'original',
            'description' => ['es' => 'Original', 'en' => 'Original'],
            'preview_image' => UploadedFile::fake()->image('old.png')->store('projects', 'public'),
        ]);
        $oldImage = $project->preview_image;

        $this->actingAs(User::factory()->create())
            ->put("/admin/projects/{$project->id}", [
                'title' => 'Actualizado',
                'slug' => 'original',
                'description' => ['es' => 'Nueva', 'en' => 'New'],
                'demo_url' => '',
                'repo_url' => '',
                'preview_image' => UploadedFile::fake()->image('new.png'),
                'is_featured' => '0',
                'is_published' => '1',
                'sort_order' => '0',
                'technologies' => [],
            ])
            ->assertRedirect('/admin/projects');

        $project->refresh();

        $this->assertSame('Actualizado', $project->title);
        $this->assertSame('Nueva', $project->description['es']);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($project->preview_image);
    }

    public function test_admin_can_delete_a_project_and_its_image(): void
    {
        Storage::fake('public');

        $project = Project::create([
            'title' => 'Para borrar',
            'slug' => 'para-borrar',
            'description' => ['es' => 'x', 'en' => 'x'],
            'preview_image' => UploadedFile::fake()->image('gone.png')->store('projects', 'public'),
        ]);
        $image = $project->preview_image;

        $this->actingAs(User::factory()->create())
            ->delete("/admin/projects/{$project->id}")
            ->assertRedirect('/admin/projects');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        Storage::disk('public')->assertMissing($image);
    }
}
