<?php

use App\Http\Controllers\UserController;
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

Route::post('/v1/users', [UserController::class, 'handleGetUsers'])->name('users');
Route::post('/v1/users/create', [UserController::class, 'handleCreateUser'])->name('users.create');
Route::patch('/v1/users/{id}', [UserController::class, 'handleUpdateUser'])->name('users.update');
Route::delete('/v1/users/{id}', [UserController::class, 'handleDeleteUser'])->name('users.delete');
