<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Models\Template;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', LandingController::class . '@index')->name('landing');
// ou
Route::get('/landing', LandingController::class . '@index')->name('landing');
Route::get('/terms', function () {
    return view('terms'); // Assumindo que você tem
})->name('terms.show');

Route::get('/policy', function () {
    return view('policy'); // Assumindo que você tem
})->name('policy.show');


Route::middleware(['auth'])->group(function () {
    // Aponta para o seu AdminController existente
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Mantenha suas outras rotas de admin (users, etc)
 //   Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
 //   Route::get('/admin/users/{user}/edit', [AdminController::class, 'userEdit'])->name('admin.user.edit');
  //  Route::put('/admin/users/{user}', [AdminController::class, 'userUpdate'])->name('admin.user.update');
  //  Route::delete('/admin/users/{user}', [AdminController::class, 'userDestroy'])->name('admin.user.destroy');
});

// --- FLUXO DO USUÁRIO (A REFATORAÇÃO) ---
Route::middleware(['auth'])->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // 1. Dashboard do Usuário (Lista de Projetos)
    Route::get('/user/dashboard', \App\Livewire\ProjectList::class)->name('user.dashboard');

    // 2. Rota para a Galeria de Templates
   Route::get('/projects/create', \App\Livewire\TemplateGallery::class)->name('projects.create');

    // 3. Rota para o Editor de Site
   Route::get('/projects/{project}/edit', \App\Livewire\SiteEditor::class)->name('projects.edit');

});

// 4. Rota de Assets Dinâmicos (para previews e iframe)
Route::get('/template-assets/{templateName}/{assetPath}', function ($templateName, $assetPath) {
    if (Str::contains($templateName, '..') || Str::contains($assetPath, '..')) {
        abort(403);
    }
    $template = Template::where('name', $templateName)->firstOrFail();
    $path = storage_path("app/templates/{$template->name}/{$assetPath}");
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->where('assetPath', '.*')->name('template.asset');


require __DIR__.'/auth.php';