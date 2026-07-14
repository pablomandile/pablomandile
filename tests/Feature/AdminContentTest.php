<?php

namespace Tests\Feature;

use App\Enums\TechCategory;
use App\Models\Experience;
use App\Models\Setting;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_technologies_or_about(): void
    {
        $this->get('/admin/technologies')->assertRedirect('/login');
        $this->get('/admin/about')->assertRedirect('/login');
    }

    public function test_admin_can_create_a_technology(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/technologies', [
                'name' => 'Livewire',
                'category' => TechCategory::Backend->value,
                'icon' => null,
                'sort_order' => 5,
            ])
            ->assertRedirect('/admin/technologies');

        $this->assertDatabaseHas('technologies', ['name' => 'Livewire', 'sort_order' => 5]);
    }

    public function test_admin_can_update_about_and_experiences(): void
    {
        $this->actingAs(User::factory()->create())
            ->put('/admin/about', [
                'about' => ['es' => 'Nueva bio en español.', 'en' => 'New bio in English.'],
                'experiences' => [
                    [
                        'role' => ['es' => 'Dev', 'en' => 'Dev'],
                        'company' => 'ACME',
                        'period' => ['es' => '2020 — Hoy', 'en' => '2020 — Now'],
                    ],
                ],
            ])
            ->assertRedirect('/admin/about');

        $this->assertSame('Nueva bio en español.', Setting::get('about')['es']);
        $this->assertCount(1, Experience::all());
        $this->assertSame('ACME', Experience::first()->company);
    }
}
