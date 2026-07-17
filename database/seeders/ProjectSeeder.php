<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'slug' => 'proyecto-comedores',
                'title' => 'Proyecto Comedores',
                'description' => [
                    'es' => 'Sistema de gestión integral de comedores escolares para el Ministerio de Educación (DGPTE – SSTES). Análisis, relevamiento y reingeniería de procesos junto al área involucrada. Back-end y front-end separados, conectados mediante API.',
                    'en' => 'End-to-end school cafeteria management system for the Ministry of Education (DGPTE – SSTES). Process analysis, survey and re-engineering together with the involved area. Separate back-end and front-end connected through an API.',
                ],
                'demo_url' => null,
                'repo_url' => null,
                'images' => [
                    'https://picsum.photos/seed/comedores1/1200/750',
                    'https://picsum.photos/seed/comedores2/1200/750',
                    'https://picsum.photos/seed/comedores3/1200/750',
                ],
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
                'technologies' => ['Laravel', 'Vue.js', 'Tailwind CSS', 'PostgreSQL'],
            ],
            [
                'slug' => 'portfolio-personal',
                'title' => 'Portfolio personal',
                'description' => [
                    'es' => 'Este mismo sitio: SPA bilingüe construida con Laravel 12, Inertia y Vue 3, con panel de administración para cargar proyectos con imagen de vista previa. Diseño glassmorphism con gradientes sobre fondo oscuro.',
                    'en' => 'This very site: a bilingual SPA built with Laravel 12, Inertia and Vue 3, with an admin panel to manage projects with preview images. Glassmorphism design with gradients over a dark background.',
                ],
                'demo_url' => null,
                'repo_url' => 'https://github.com/pablomandile',
                'images' => [
                    'https://picsum.photos/seed/portfolio1/1200/750',
                    'https://picsum.photos/seed/portfolio2/1200/750',
                ],
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 2,
                'technologies' => ['Laravel', 'Vue.js', 'Tailwind CSS', 'MySQL'],
            ],
            [
                'slug' => 'ospos-kadampa',
                'title' => 'Punto de venta OSPOS — Kadampa',
                'description' => [
                    'es' => 'Implementación y adaptación del sistema open source de punto de venta OSPOS para la tienda de libros del Centro de Meditación Kadampa Argentina, junto con la administración del sitio WordPress (Divi), hosting con cPanel y automatización de inscripciones con Google Apps Script.',
                    'en' => 'Implementation and customization of the open-source OSPOS point-of-sale system for the bookstore of the Kadampa Meditation Center Argentina, along with WordPress (Divi) site administration, cPanel hosting and enrollment automation with Google Apps Script.',
                ],
                'demo_url' => null,
                'repo_url' => null,
                'images' => [
                    'https://picsum.photos/seed/ospos1/1200/750',
                    'https://picsum.photos/seed/ospos2/1200/750',
                    'https://picsum.photos/seed/ospos3/1200/750',
                    'https://picsum.photos/seed/ospos4/1200/750',
                ],
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 3,
                'technologies' => ['PHP', 'MySQL', 'WordPress', 'JavaScript'],
            ],
        ];

        foreach ($projects as $data) {
            $technologies = $data['technologies'];
            unset($data['technologies']);

            $project = Project::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            $ids = Technology::whereIn('name', $technologies)->pluck('id');
            $project->technologies()->sync($ids);
        }
    }
}
