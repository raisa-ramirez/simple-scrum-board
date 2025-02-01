<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/category', CategoryController::class);
Route::resource('/user', UserController::class);
Route::resource('/task', TaskController::class);

Route::get('/categoriesByUser/{id}', [CategoryController::class,'categoriesByUser'])->name('categoriesByUser');
Route::put('updatePassword/{id}', [UserController::class, 'updatePassword'])->name('updatePassword');
Route::put('/updateState/{id}', [TaskController::class, 'updateState'])->name('updateState');
Route::get('/tasksByUser/{id}', [TaskController::class, 'tasksByUser'])->name('tasksByUser');