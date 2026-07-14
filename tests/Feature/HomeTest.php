<?php

namespace Tests\Feature;

use App\Enums\TechCategory;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_with_profile_technologies_and_published_projects(): void
    {
        $technology = Technology::create([
            'name' => 'Laravel',
            'category' => TechCategory::Backend,
            'sort_order' => 1,
        ]);

        $published = Project::create([
            'title' => 'Proyecto visible',
            'slug' => 'proyecto-visible',
            'description' => ['es' => 'Descripción', 'en' => 'Description'],
            'is_published' => true,
        ]);
        $published->technologies()->sync([$technology->id]);

        Project::create([
            'title' => 'Borrador oculto',
            'slug' => 'borrador-oculto',
            'description' => ['es' => 'Borrador', 'en' => 'Draft'],
            'is_published' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->where('profile.name', 'Pablo Mandile')
                ->has('technologies.backend', 1)
                ->has('projects', 1)
                ->where('projects.0.slug', 'proyecto-visible')
                ->has('projects.0.technologies', 1)
            );
    }
}
