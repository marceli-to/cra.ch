<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ContactController;

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
Route::get('/leistungen', [ServiceController::class, 'index'])->name('page.service');
Route::get('/kontakt', [ContactController::class, 'index'])->name('page.contact');
Route::get('/werkliste', [ProjectController::class, 'list'])->name('page.worklist');
Route::get('/projekt/{project:slug}', [ProjectController::class, 'show'])->name('page.project.show');
Route::get('/ueber-uns/team', [AboutController::class, 'team'])->name('page.about.team');
Route::get('/ueber-uns/tagebuch', [AboutController::class, 'diary'])->name('page.about.diary');

// Frontend - url based images
Route::get('/img/{template}/{filename}/{maxSize?}/{coords?}', [ImageController::class, 'getResponse']);

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


