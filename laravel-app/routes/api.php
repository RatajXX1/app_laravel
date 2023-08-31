<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Posts;
use App\Http\Controllers\UsesrController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['web', "auth"]], function () {
    Route::get('/authorization', [LoginController::class, "authorization"]);
    Route::resource("/users", UsesrController::class);
    Route::resource("/posts", Posts::class);
    Route::get('/tags', [Posts::class, "allTags"]);
    Route::post('/search', [Posts::class, "Search"]);
});


Route::group(['middleware' => ['web']], function () {
    Route::post('/auth', [LoginController::class, "authenticate"]);
});
