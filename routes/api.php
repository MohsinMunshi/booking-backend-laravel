<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\showsController;
use App\Http\Controllers\bookingController;
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
Route::get('/show', [showsController::class, 'index']);
Route::post('/show/newshow', [showsController::class, 'store']);

Route::get('/booking/search/{email}', [bookingController::class, 'show']);
Route::post('/booking/newbooking', [bookingController::class, 'store']);
Route::post('/booking/update', [bookingController::class, 'update']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
