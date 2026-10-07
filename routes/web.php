<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

// Auth
Route::middleware('guest')->group(function () {
  Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
  Route::post('/login', [AuthController::class, 'login']);
  Route::get('/password/reset', [AuthController::class, 'showForgotPassword'])->name('password.request');
  Route::post('/password/email', [AuthController::class, 'sendResetLink'])->middleware('throttle:6,1')->name('password.email');
  Route::get('/password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
  Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

// The admin header links to GET /logout
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Frontend - Home
Route::get('/', [HomeController::class, 'index'])->name('page.home');
Route::get('/leistungen', [ServiceController::class, 'index'])->name('page.service');
Route::get('/kontakt', [ContactController::class, 'index'])->name('page.contact');
Route::get('/werkliste', [ProjectController::class, 'list'])->name('page.worklist');
Route::get('/projekt/{project:slug}', [ProjectController::class, 'show'])->name('page.project.show');
Route::get('/ueber-uns/team', [AboutController::class, 'team'])->name('page.about.team');
Route::get('/ueber-uns/tagebuch', [AboutController::class, 'diary'])->name('page.about.diary');

// Images
Route::get('/img/original/{filename}', [ImageController::class, 'original']);
Route::get('/img/thumbnail/{filename}', [ImageController::class, 'thumbnail']);
Route::get('/img/crop/{filename}/{maxSize?}/{coords?}/{ratio?}', [ImageController::class, 'crop']);

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:sanctum')->group(function() {
  Route::get('administration/{any?}', function () {
    return view('layout.authenticated');
  })->where('any', '.*')->middleware('role:admin')->name('authenticated');
});