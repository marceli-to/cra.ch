<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\GridItemController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\VideoController;

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

  // Videos
  Route::get('videos', [VideoController::class, 'get']);
  Route::post('video/order', [VideoController::class, 'order']);
  Route::get('video/{video}', [VideoController::class, 'find']);
  Route::post('video', [VideoController::class, 'store']);
  Route::put('video/{video}', [VideoController::class, 'update']);
  Route::get('video/state/{video}', [VideoController::class, 'toggle']);
  Route::delete('video/{video}', [VideoController::class, 'destroy']);

  // Grid items
  Route::get('grid/items', [GridItemController::class, 'get']);
  Route::post('grid/item/store/event', [GridItemController::class, 'storeEvent']);
  Route::post('grid/item/store/teaser', [GridItemController::class, 'storeTeaser']);
  Route::delete('grid/item/{gridItem}', [GridItemController::class, 'destroy']);
  Route::post('grid/item/order', [GridItemController::class, 'order']);

  // Pages
  Route::get('pages/{constraint?}', [PageController::class, 'get']);
  Route::get('page/{page}', [PageController::class, 'find']);
  Route::post('page', [PageController::class, 'store']);
  Route::put('page/{page}', [PageController::class, 'update']);
  Route::get('page/state/{page}', [PageController::class, 'toggle']);
  Route::delete('page/{page}', [PageController::class, 'destroy']);

});



