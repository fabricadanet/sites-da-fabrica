<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SiteController;


Route::get('/', LandingController::class . '@index')->name('landing');
// ou
Route::get('/landing', LandingController::class . '@index')->name('landing');

// register route
Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');


Route::get('/dashboard', function () {
    return view('tenant.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dentro de Route::middleware(['auth', 'verified'])->group(function () {
    // Sites
     Route::prefix('sites')->group(function () {
        Route::get('/', [App\Http\Controllers\SiteController::class, 'index'])->name('tenant.sites.index');
        Route::get('/{site:slug}/edit', [App\Http\Controllers\SiteController::class, 'edit'])->name('tenant.sites.edit');
        Route::get('/{site:slug}/preview', [App\Http\Controllers\SiteController::class, 'preview'])->name('tenant.sites.preview');
        Route::get('/{site:slug}/stats', [App\Http\Controllers\SiteController::class, 'stats'])->name('tenant.sites.stats');
        
        Route::patch('/{site:slug}', [App\Http\Controllers\SiteController::class, 'update'])->name('tenant.sites.update');
        Route::post('/{site:slug}/publish', [App\Http\Controllers\SiteController::class, 'publish'])->name('tenant.sites.publish');
        Route::post('/{site:slug}/unpublish', [App\Http\Controllers\SiteController::class, 'unpublish'])->name('tenant.sites.unpublish');
        Route::post('/{site:slug}/duplicate', [App\Http\Controllers\SiteController::class, 'duplicate'])->name('tenant.sites.duplicate');
        Route::delete('/{site:slug}', [App\Http\Controllers\SiteController::class, 'destroy'])->name('tenant.sites.destroy');
    });

    // Páginas
    Route::prefix('sites/{site:slug}/pages')->group(function () {
        Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('tenant.pages.index');
        Route::post('/', [App\Http\Controllers\PageController::class, 'store'])->name('tenant.pages.store');
        Route::get('/{page:slug}/edit', [App\Http\Controllers\PageController::class, 'edit'])->name('tenant.pages.edit');
        Route::patch('/{page:slug}', [App\Http\Controllers\PageController::class, 'update'])->name('tenant.pages.update');
        Route::delete('/{page:slug}', [App\Http\Controllers\PageController::class, 'destroy'])->name('tenant.pages.destroy');
    });

    // Seções
    Route::prefix('sites/{site:slug}/sections')->group(function () {
        Route::post('/', [App\Http\Controllers\SectionController::class, 'store'])->name('tenant.sections.store');
        Route::patch('/{section}', [App\Http\Controllers\SectionController::class, 'update'])->name('tenant.sections.update');
        Route::delete('/{section}', [App\Http\Controllers\SectionController::class, 'destroy'])->name('tenant.sections.destroy');
        Route::post('/{section}/reorder', [App\Http\Controllers\SectionController::class, 'reorder'])->name('tenant.sections.reorder');
    });

    // Mídia
    Route::prefix('sites/{site:slug}/media')->group(function () {
        Route::get('/', [App\Http\Controllers\MediaController::class, 'index'])->name('tenant.media.index');
        Route::post('/upload', [App\Http\Controllers\MediaController::class, 'store'])->name('tenant.media.store');
        Route::delete('/{media}', [App\Http\Controllers\MediaController::class, 'destroy'])->name('tenant.media.destroy');
    });

    // Domínios Customizados
    Route::prefix('sites/{site:slug}/domains')->group(function () {
        Route::get('/', [App\Http\Controllers\CustomDomainController::class, 'index'])->name('tenant.domains.index');
        Route::post('/', [App\Http\Controllers\CustomDomainController::class, 'store'])->name('tenant.domains.store');
        Route::post('/{domain}/verify', [App\Http\Controllers\CustomDomainController::class, 'verify'])->name('tenant.domains.verify');
        Route::delete('/{domain}', [App\Http\Controllers\CustomDomainController::class, 'destroy'])->name('tenant.domains.destroy');
    });
});



require __DIR__.'/auth.php';