<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nueva columna: lista ordenada de imágenes. La primera es la portada.
        Schema::table('projects', function (Blueprint $table) {
            $table->json('images')->nullable()->after('repo_url');
        });

        // Migra la imagen única existente al primer elemento de la lista.
        DB::table('projects')
            ->whereNotNull('preview_image')
            ->where('preview_image', '!=', '')
            ->orderBy('id')
            ->get(['id', 'preview_image'])
            ->each(function (object $project): void {
                DB::table('projects')
                    ->where('id', $project->id)
                    ->update(['images' => json_encode([$project->preview_image])]);
            });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('preview_image');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('preview_image')->nullable()->after('repo_url');
        });

        // Restaura la primera imagen de la lista como imagen única.
        DB::table('projects')
            ->whereNotNull('images')
            ->orderBy('id')
            ->get(['id', 'images'])
            ->each(function (object $project): void {
                $images = json_decode($project->images ?? '[]', true) ?: [];

                DB::table('projects')
                    ->where('id', $project->id)
                    ->update(['preview_image' => $images[0] ?? null]);
            });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
