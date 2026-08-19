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

    public function test_admin_can_create_a_project_with_images(): void
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
                'new_images' => [
                    UploadedFile::fake()->image('one.png', 800, 500),
                    UploadedFile::fake()->image('two.png', 800, 500),
                ],
                'logo' => UploadedFile::fake()->image('logo.png', 256, 256),
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
        $this->assertCount(2, $project->images);
        Storage::disk('public')->assertExists($project->images[0]);
        Storage::disk('public')->assertExists($project->images[1]);
        $this->assertNotNull($project->logo);
        Storage::disk('public')->assertExists($project->logo);
    }

    public function test_admin_can_replace_the_project_logo(): void
    {
        Storage::fake('public');

        $oldLogo = UploadedFile::fake()->image('old-logo.png')->store('projects/logos', 'public');

        $project = Project::create([
            'title' => 'Con logo',
            'slug' => 'con-logo',
            'description' => ['es' => 'x', 'en' => 'x'],
            'logo' => $oldLogo,
        ]);

        $this->actingAs(User::factory()->create())
            ->put("/admin/projects/{$project->id}", [
                ...$this->baseUpdatePayload(),
                'logo' => UploadedFile::fake()->image('new-logo.png', 256, 256),
            ])
            ->assertRedirect('/admin/projects');

        $project->refresh();

        $this->assertNotSame($oldLogo, $project->logo);
        Storage::disk('public')->assertMissing($oldLogo);
        Storage::disk('public')->assertExists($project->logo);
    }

    public function test_the_project_logo_survives_an_update_that_does_not_touch_it(): void
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('logo.png')->store('projects/logos', 'public');

        $project = Project::create([
            'title' => 'Con logo',
            'slug' => 'con-logo',
            'description' => ['es' => 'x', 'en' => 'x'],
            'logo' => $logo,
        ]);

        $this->actingAs(User::factory()->create())
            ->put("/admin/projects/{$project->id}", $this->baseUpdatePayload())
            ->assertRedirect('/admin/projects');

        $project->refresh();

        $this->assertSame($logo, $project->logo);
        Storage::disk('public')->assertExists($logo);
    }

    public function test_admin_can_remove_the_project_logo(): void
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('logo.png')->store('projects/logos', 'public');

        $project = Project::create([
            'title' => 'Con logo',
            'slug' => 'con-logo',
            'description' => ['es' => 'x', 'en' => 'x'],
            'logo' => $logo,
        ]);

        $this->actingAs(User::factory()->create())
            ->put("/admin/projects/{$project->id}", [
                ...$this->baseUpdatePayload(),
                'remove_logo' => '1',
            ])
            ->assertRedirect('/admin/projects');

        $project->refresh();

        $this->assertNull($project->logo);
        $this->assertNull($project->logo_url);
        Storage::disk('public')->assertMissing($logo);
    }

    /**
     * Campos mínimos que el formulario de edición siempre envía.
     *
     * @return array<string, mixed>
     */
    private function baseUpdatePayload(): array
    {
        return [
            'title' => 'Con logo',
            'slug' => 'con-logo',
            'description' => ['es' => 'x', 'en' => 'x'],
            'demo_url' => '',
            'repo_url' => '',
            'existing_images' => [],
            'is_featured' => '0',
            'is_published' => '1',
            'sort_order' => '0',
            'technologies' => [],
        ];
    }

    public function test_admin_can_update_a_project_replacing_images(): void
    {
        Storage::fake('public');

        $oldImage = UploadedFile::fake()->image('old.png')->store('projects', 'public');

        $project = Project::create([
            'title' => 'Original',
            'slug' => 'original',
            'description' => ['es' => 'Original', 'en' => 'Original'],
            'images' => [$oldImage],
        ]);

        $this->actingAs(User::factory()->create())
            ->put("/admin/projects/{$project->id}", [
                'title' => 'Actualizado',
                'slug' => 'original',
                'description' => ['es' => 'Nueva', 'en' => 'New'],
                'demo_url' => '',
                'repo_url' => '',
                'existing_images' => [], // se quita la imagen anterior
                'new_images' => [UploadedFile::fake()->image('new.png')],
                'is_featured' => '0',
                'is_published' => '1',
                'sort_order' => '0',
                'technologies' => [],
            ])
            ->assertRedirect('/admin/projects');

        $project->refresh();

        $this->assertSame('Actualizado', $project->title);
        $this->assertSame('Nueva', $project->description['es']);
        $this->assertCount(1, $project->images);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($project->images[0]);
    }

    public function test_admin_can_keep_and_append_images_on_update(): void
    {
        Storage::fake('public');

        $keptImage = UploadedFile::fake()->image('kept.png')->store('projects', 'public');

        $project = Project::create([
            'title' => 'Con galería',
            'slug' => 'con-galeria',
            'description' => ['es' => 'x', 'en' => 'x'],
            'images' => [$keptImage],
        ]);

        $this->actingAs(User::factory()->create())
            ->put("/admin/projects/{$project->id}", [
                'title' => 'Con galería',
                'slug' => 'con-galeria',
                'description' => ['es' => 'x', 'en' => 'x'],
                'demo_url' => '',
                'repo_url' => '',
                'existing_images' => [$keptImage],
                'new_images' => [UploadedFile::fake()->image('added.png')],
                'is_featured' => '0',
                'is_published' => '1',
                'sort_order' => '0',
                'technologies' => [],
            ])
            ->assertRedirect('/admin/projects');

        $project->refresh();

        $this->assertCount(2, $project->images);
        $this->assertSame($keptImage, $project->images[0]);
        Storage::disk('public')->assertExists($project->images[0]);
        Storage::disk('public')->assertExists($project->images[1]);
    }

    public function test_admin_can_delete_a_project_and_its_images(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('gone.png')->store('projects', 'public');
        $logo = UploadedFile::fake()->image('gone-logo.png')->store('projects/logos', 'public');

        $project = Project::create([
            'title' => 'Para borrar',
            'slug' => 'para-borrar',
            'description' => ['es' => 'x', 'en' => 'x'],
            'images' => [$image],
            'logo' => $logo,
        ]);

        $this->actingAs(User::factory()->create())
            ->delete("/admin/projects/{$project->id}")
            ->assertRedirect('/admin/projects');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        Storage::disk('public')->assertMissing($image);
        Storage::disk('public')->assertMissing($logo);
    }
}
