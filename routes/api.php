<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\ProjectController;
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

  // Projects
  Route::get('projects', [ProjectController::class, 'get']);
  Route::get('project/{project}', [ProjectController::class, 'find']);
  Route::post('project', [ProjectController::class, 'store']);
  Route::put('project/{project}', [ProjectController::class, 'update']);
  Route::post('projects/order', [ProjectController::class, 'order']);
  Route::get('project/state/{project}', [ProjectController::class, 'toggle']);
  Route::delete('project/{project}', [ProjectController::class, 'destroy']);

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



