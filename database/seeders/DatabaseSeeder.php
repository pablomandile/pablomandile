<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crea el usuario admin sólo si se proveen credenciales por .env.
        // Sin ellas no se crea ningún admin (evita usuarios con clave por defecto).
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');

        if ($adminEmail && $adminPassword) {
            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Pablo Mandile',
                    'password' => Hash::make($adminPassword),
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->call([
            ProfileSeeder::class,
            TechnologySeeder::class,
            ProjectSeeder::class,
            CertificateSeeder::class,
        ]);
    }
}
