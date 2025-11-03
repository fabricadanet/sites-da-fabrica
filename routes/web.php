<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

Route::get('/', LandingController::class . '@index')->name('landing');
// ou
Route::get('/landing', LandingController::class . '@index')->name('landing');