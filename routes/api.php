<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SiteController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/sites', [SiteController::class, 'store'])->name('sites.store');
    Route::get('/templates', [SiteController::class, 'getTemplates'])->name('templates.index');
});