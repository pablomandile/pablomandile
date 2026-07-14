<?php

namespace Database\Seeders;

use App\Enums\TechCategory;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            // Backend
            ['name' => 'PHP', 'category' => TechCategory::Backend, 'icon' => 'devicon-php-plain', 'sort_order' => 1],
            ['name' => 'Laravel', 'category' => TechCategory::Backend, 'icon' => 'devicon-laravel-original', 'sort_order' => 2],
            ['name' => 'Symfony', 'category' => TechCategory::Backend, 'icon' => 'devicon-symfony-original', 'sort_order' => 3],
            ['name' => 'Python', 'category' => TechCategory::Backend, 'icon' => 'devicon-python-plain', 'sort_order' => 4],

            // Frontend
            ['name' => 'JavaScript', 'category' => TechCategory::Frontend, 'icon' => 'devicon-javascript-plain', 'sort_order' => 1],
            ['name' => 'Vue.js', 'category' => TechCategory::Frontend, 'icon' => 'devicon-vuejs-plain', 'sort_order' => 2],
            ['name' => 'React', 'category' => TechCategory::Frontend, 'icon' => 'devicon-react-original', 'sort_order' => 3],
            ['name' => 'Angular / Ionic', 'category' => TechCategory::Frontend, 'icon' => 'devicon-angular-plain', 'sort_order' => 4],
            ['name' => 'HTML / CSS', 'category' => TechCategory::Frontend, 'icon' => 'devicon-html5-plain', 'sort_order' => 5],
            ['name' => 'Sass', 'category' => TechCategory::Frontend, 'icon' => 'devicon-sass-original', 'sort_order' => 6],
            ['name' => 'Tailwind CSS', 'category' => TechCategory::Frontend, 'icon' => 'devicon-tailwindcss-original', 'sort_order' => 7],
            ['name' => 'Bootstrap', 'category' => TechCategory::Frontend, 'icon' => 'devicon-bootstrap-plain', 'sort_order' => 8],

            // Database
            ['name' => 'MySQL', 'category' => TechCategory::Database, 'icon' => 'devicon-mysql-original', 'sort_order' => 1],
            ['name' => 'PostgreSQL', 'category' => TechCategory::Database, 'icon' => 'devicon-postgresql-plain', 'sort_order' => 2],

            // Tools
            ['name' => 'Git · GitHub · GitLab', 'category' => TechCategory::Tools, 'icon' => 'devicon-git-plain', 'sort_order' => 1],
            ['name' => 'Figma', 'category' => TechCategory::Tools, 'icon' => 'devicon-figma-plain', 'sort_order' => 2],
            ['name' => 'WordPress', 'category' => TechCategory::Tools, 'icon' => 'devicon-wordpress-plain', 'sort_order' => 3],
            ['name' => 'PrestaShop / WooCommerce', 'category' => TechCategory::Tools, 'icon' => 'devicon-woocommerce-plain', 'sort_order' => 4],
        ];

        foreach ($technologies as $technology) {
            Technology::updateOrCreate(
                ['name' => $technology['name']],
                $technology
            );
        }
    }
}
