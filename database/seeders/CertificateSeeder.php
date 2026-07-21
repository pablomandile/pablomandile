<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $certificates = [
            ['title' => 'Laravel desde cero', 'image' => 'https://picsum.photos/seed/cert1/1200/850'],
            ['title' => 'Vue 3 + TypeScript', 'image' => 'https://picsum.photos/seed/cert2/1200/850'],
            ['title' => 'Diseño de bases de datos', 'image' => 'https://picsum.photos/seed/cert3/1200/850'],
        ];

        foreach ($certificates as $index => $data) {
            Certificate::firstOrCreate(
                ['title' => $data['title']],
                [
                    'image' => $data['image'],
                    'is_published' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
