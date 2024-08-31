<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/token', [AuthController::class, 'generateToken']);


Route::prefix('task')->middleware('auth:sanctum')->group(function () {
    Route::get('/view/{id}', [TaskController::class, 'show']); 
    Route::post('/store', [TaskController::class, 'storeTask'])->name('task.storeTask');
    Route::put('/update/{id}', [TaskController::class, 'update']); 
    Route::delete('/delete/{id}', [TaskController::class, 'destroy']); 
});
