<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WorklistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

// Auth routes
Auth::routes(['verify' => true, 'register' => false]);
Route::get('/logout', 'Auth\LoginController@logout');

// Frontend - Home
Route::get('/', [HomeController::class, 'index'])->name('page.home');

// Frontend - Projects
Route::get('/projekte', [ProjectController::class, 'index'])->name('page.project.index');
Route::get('/projekt/{slug}/{project}', [ProjectController::class, 'show'])->name('page.project.show');

// Frontend - Worklist
Route::get('/werkliste', [WorklistController::class, 'index'])->name('page.worklist.index');

// Frontend - url based images
Route::get('/img/{template}/{filename}/{maxSize?}/{coords?}/{ratio?}', [ImageController::class, 'getResponse']);


/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:sanctum', 'verified')->group(function() {
  Route::get('administration/{any?}', function () {
    return view('layout.authenticated');
  })->where('any', '.*')->middleware('role:admin')->name('authenticated');
});


