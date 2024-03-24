<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/projects',  [ProjectController::class, 'createProject']);
    Route::post('/projects/update',  [ProjectController::class, 'updateProject']);
    Route::post('/projects/delete',  [ProjectController::class, 'deleteProject']);

    Route::get('/projects',  [ProjectController::class, 'getAllProjects']);
    Route::get('/projects/{id}',  [ProjectController::class, 'getProjectById']);



});