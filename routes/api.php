<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TechnologyController;
use Illuminate\Support\Facades\Route;

/*
| API para que un agente (Claude Code, skill subir-al-portfolio) cargue
| proyectos. Todo lo que entra por acá queda como borrador hasta que se
| publica a mano desde el panel.
*/

Route::middleware(['portfolio.token', 'throttle:30,1'])->name('api.')->group(function () {
    Route::get('technologies', TechnologyController::class)->name('technologies.index');

    Route::controller(ProjectController::class)
        ->prefix('projects/{slug}')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*'])
        ->name('projects.')
        ->group(function () {
            Route::get('/', 'show')->name('show');
            Route::put('/', 'upsert')->name('upsert');
            Route::post('images', 'images')->name('images');
            Route::post('logo', 'logo')->name('logo');
        });
});
