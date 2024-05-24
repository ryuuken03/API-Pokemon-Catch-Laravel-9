<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokeAPIController;

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
Route::get('/all', [PokeAPIController::class, 'all']);
Route::get('/detail', [PokeAPIController::class, 'detail']);
Route::get('/catch', [PokeAPIController::class, 'catch']);
Route::get('/release', [PokeAPIController::class, 'release']);
Route::get('/rename', [PokeAPIController::class, 'rename']);
