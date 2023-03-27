<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\GridController;
use App\Http\Controllers\Api\GridItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware('auth:sanctum')->group(function() {
  Route::get('user', [UserController::class, 'find']);

  // Images
  Route::get('images', [ImageController::class, 'get']);
  Route::post('images/order', [ImageController::class, 'order']);
  Route::get('image/{image}', [ImageController::class, 'find']);
  Route::post('image/upload', [ImageController::class, 'upload']);
  Route::post('image', [ImageController::class, 'store']);
  Route::put('image/coords/{image}', [ImageController::class, 'coords']);
  Route::put('image/{image}', [ImageController::class, 'update']);
  Route::get('image/state/{image}', [ImageController::class, 'toggle']);
  Route::get('image/preview/state/{image}', [ImageController::class, 'preview']);
  Route::delete('image/{image}', [ImageController::class, 'destroy']);

  // Files
  Route::get('files', [FileController::class, 'get']);
  Route::post('files/order', [FileController::class, 'order']);
  Route::get('file/{file}', [FileController::class, 'find']);
  Route::post('file/upload', [FileController::class, 'upload']);
  Route::post('file', [FileController::class, 'store']);
  Route::put('file/{file}', [FileController::class, 'update']);
  Route::get('file/state/{file}', [FileController::class, 'toggle']);
  Route::delete('file/{file}', [FileController::class, 'destroy']);

  // Service
  Route::controller(ServiceController::class)->group(function () {
    Route::get('services', 'get');
    Route::get('service/{service}', 'find');
    Route::post('service', 'store');
    Route::put('service/{service}', 'update');
    Route::get('service/state/{service}', 'toggle');
    Route::delete('service/{service}', 'destroy');
  });

  // Project categories
  Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'get');
    Route::get('category/{category}', 'find');
    Route::post('category', 'store');
    Route::put('category/{category}', 'update');
    Route::delete('category/{category}', 'destroy');
  });

  // Project states
  Route::controller(StateController::class)->group(function () {
    Route::get('states', 'get');
  });

  // Projects
  Route::controller(ProjectController::class)->group(function () {
    Route::get('projects', 'get');
    Route::get('project/{project}', 'find');
    Route::post('project', 'store');
    Route::post('projects/order', 'order');
    Route::put('project/{project}', 'update');
    Route::get('project/state/{project}', 'toggle');
    Route::delete('project/{project}', 'destroy');  
  });


  // Image grid
  Route::post('grid', [GridController::class, 'store']);
  Route::delete('grid/{grid}', [GridController::class, 'destroy']);
  Route::post('grid/order', [GridController::class, 'order']);

  // Image grid item
  Route::post('grid-item', [GridItemController::class, 'store']);
  Route::put('grid-item/{gridItem}', [GridItemController::class, 'reset']);

  // Home
  Route::get('home', [HomeController::class, 'find']);

});



