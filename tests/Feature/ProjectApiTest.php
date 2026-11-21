<?php

namespace Tests\Feature;

use App\Enums\TechCategory;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN = 'token-de-prueba-0123456789';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.portfolio_api.token' => self::TOKEN]);
        Storage::fake('public');
    }

    private function api(): self
    {
        return $this->withToken(self::TOKEN);
    }

    private function technology(string $name): Technology
    {
        return Technology::create([
            'name' => $name,
            'category' => TechCategory::Backend,
            'sort_order' => 1,
        ]);
    }

    private function project(array $attributes = []): Project
    {
        return Project::create([
            'title' => 'Existente',
            'slug' => 'existente',
            'description' => ['es' => 'Hola', 'en' => 'Hello'],
            'is_published' => true,
            ...$attributes,
        ]);
    }

    public function test_rejects_requests_without_a_valid_token(): void
    {
        $this->getJson('/api/technologies')->assertUnauthorized();
        $this->withToken('otro')->getJson('/api/technologies')->assertUnauthorized();
    }

    public function test_the_api_is_off_when_no_token_is_configured(): void
    {
        config(['services.portfolio_api.token' => '']);

        $this->withToken('')->getJson('/api/technologies')->assertUnauthorized();
        $this->withToken(self::TOKEN)->getJson('/api/technologies')->assertUnauthorized();
    }

    public function test_responses_are_never_cached(): void
    {
        $response = $this->api()->getJson('/api/technologies')->assertOk();
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));

        $response = $this->withoutToken()->getJson('/api/technologies')->assertUnauthorized();
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
    }

    public function test_lists_technologies(): void
    {
        $this->technology('Laravel');

        $this->api()->getJson('/api/technologies')
            ->assertOk()
            ->assertJsonPath('technologies.0.name', 'Laravel')
            ->assertJsonPath('technologies.0.category', 'backend');
    }

    public function test_shows_a_project_or_404(): void
    {
        $this->api()->getJson('/api/projects/no-existe')->assertNotFound();

        $this->project();

        $this->api()->getJson('/api/projects/existente')
            ->assertOk()
            ->assertJsonPath('project.title', 'Existente');
    }

    public function test_creates_a_draft_project_matching_technologies_by_name(): void
    {
        $laravel = $this->technology('Laravel');
        $vue = $this->technology('Vue.js');

        $response = $this->api()->putJson('/api/projects/mi-app', [
            'title' => 'Mi App',
            'description' => ['es' => 'Descripción', 'en' => 'Description'],
            'demo_url' => 'https://mi-app.pablomandile.com.ar',
            'technologies' => ['laravel', ' VUE.JS ', 'Rust'],
        ]);

        $response->assertCreated()
            ->assertJsonPath('created', true)
            ->assertJsonPath('unknown_technologies', ['Rust'])
            ->assertJsonPath('project.is_published', false);

        $project = Project::firstWhere('slug', 'mi-app');

        $this->assertNotNull($project);
        $this->assertFalse($project->is_published);
        $this->assertSame([], $project->images);
        $this->assertEqualsCanonicalizing([$laravel->id, $vue->id], $project->technologies->pluck('id')->all());
        $response->assertJsonPath('admin_url', route('admin.projects.edit', $project));

        // Un borrador no aparece en la home.
        $this->get('/')->assertInertia(fn ($page) => $page->where('projects', []));
    }

    public function test_creating_requires_title_and_both_descriptions(): void
    {
        $this->api()->putJson('/api/projects/mi-app', ['description' => ['es' => 'Hola']])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'description.en']);

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_validation_errors_are_json_even_without_accept_header(): void
    {
        $this->api()->put('/api/projects/mi-app', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    public function test_rejects_slugs_that_are_not_url_friendly(): void
    {
        $this->api()->putJson('/api/projects/Mi_App', ['title' => 'x'])->assertNotFound();
    }

    public function test_updating_only_touches_the_fields_sent(): void
    {
        $image = UploadedFile::fake()->image('a.png')->store('projects', 'public');
        $project = $this->project(['images' => [$image], 'is_published' => true]);
        $project->technologies()->sync([$this->technology('Laravel')->id]);

        $this->api()->putJson('/api/projects/existente', ['title' => 'Nuevo título'])
            ->assertOk()
            ->assertJsonPath('created', false);

        $project->refresh();

        $this->assertSame('Nuevo título', $project->title);
        $this->assertSame(['es' => 'Hola', 'en' => 'Hello'], $project->description);
        $this->assertTrue($project->is_published);
        $this->assertSame([$image], $project->images);
        $this->assertCount(1, $project->technologies);
        $this->assertDatabaseCount('projects', 1);
    }

    public function test_replace_mode_swaps_all_images_and_deletes_the_old_files(): void
    {
        $old = UploadedFile::fake()->image('old.png')->store('projects', 'public');
        $this->project(['images' => [$old]]);

        $response = $this->api()->post('/api/projects/existente/images', [
            'mode' => 'replace',
            'images' => [
                UploadedFile::fake()->image('uno.webp', 1440, 900),
                UploadedFile::fake()->image('dos.webp', 1440, 900),
            ],
        ])->assertOk();

        $images = Project::firstWhere('slug', 'existente')->images;

        $this->assertCount(2, $images);
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($images);
        $this->assertCount(2, $response->json('project.image_urls'));
    }

    public function test_append_mode_adds_images_at_the_end(): void
    {
        $old = UploadedFile::fake()->image('old.png')->store('projects', 'public');
        $this->project(['images' => [$old]]);

        $this->api()->post('/api/projects/existente/images', [
            'mode' => 'append',
            'images' => [UploadedFile::fake()->image('nueva.png')],
        ])->assertOk();

        $images = Project::firstWhere('slug', 'existente')->images;

        $this->assertCount(2, $images);
        $this->assertSame($old, $images[0]);
        Storage::disk('public')->assertExists($old);
    }

    public function test_a_project_cannot_go_over_twelve_images(): void
    {
        $this->project(['images' => array_fill(0, 11, 'https://example.com/x.png')]);

        $this->api()->post('/api/projects/existente/images', [
            'mode' => 'append',
            'images' => [UploadedFile::fake()->image('a.png'), UploadedFile::fake()->image('b.png')],
        ])->assertUnprocessable()->assertJsonValidationErrors('images');

        $this->assertCount(11, Project::firstWhere('slug', 'existente')->images);
    }

    public function test_uploading_images_to_a_missing_project_is_404(): void
    {
        $this->api()->post('/api/projects/no-existe/images', [
            'mode' => 'replace',
            'images' => [UploadedFile::fake()->image('a.png')],
        ])->assertNotFound();
    }

    public function test_logo_replaces_the_previous_one(): void
    {
        $oldLogo = UploadedFile::fake()->image('old-logo.png')->store('projects/logos', 'public');
        $this->project(['logo' => $oldLogo]);

        $this->api()->post('/api/projects/existente/logo', [
            'logo' => UploadedFile::fake()->image('logo.png', 256, 256),
        ])->assertOk();

        $logo = Project::firstWhere('slug', 'existente')->logo;

        $this->assertNotSame($oldLogo, $logo);
        Storage::disk('public')->assertExists($logo);
        Storage::disk('public')->assertMissing($oldLogo);
    }
}
