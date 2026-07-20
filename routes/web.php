<?php

use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\CvController as AdminCvController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\TechnologyController as AdminTechnologyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::post('contact', ContactController::class)
    ->middleware('throttle:5,1')
    ->name('contact');

Route::middleware(['auth'])->group(function () {
    Route::redirect('dashboard', '/admin/projects')->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('projects', AdminProjectController::class)->except('show');
        Route::resource('technologies', AdminTechnologyController::class)->except('show');
        Route::get('about', [AdminAboutController::class, 'edit'])->name('about.edit');
        Route::put('about', [AdminAboutController::class, 'update'])->name('about.update');
        Route::get('cv', [AdminCvController::class, 'edit'])->name('cv.edit');
        Route::post('cv', [AdminCvController::class, 'update'])->name('cv.update');
        Route::delete('cv', [AdminCvController::class, 'destroy'])->name('cv.destroy');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
