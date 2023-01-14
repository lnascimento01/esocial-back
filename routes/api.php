<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DomainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/register', [AuthenticationController::class, 'register']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('/domain')->group(function () {
        Route::get('/{id}', [DomainController::class, 'getDomain']);
        Route::get('/', [DomainController::class, 'list']);
        Route::post('/', [DomainController::class, 'create']);
        Route::post('/batch', [DomainController::class, 'batch']);
        Route::patch('/', [DomainController::class, 'patch']);
        Route::delete('/{id}', [DomainController::class, 'delete']);
    });
});
