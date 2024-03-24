<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('/user/update',[UserController::class, 'updateUser']);
    Route::post('/user/delete',[UserController::class, 'deleteUser']);
    

    Route::post('/projects/assign',[ProjectUserController::class, 'assignUserToProject']);
    
    Route::post('/projects',  [ProjectController::class, 'createProject']);
    Route::post('/projects/update',  [ProjectController::class, 'updateProject']);
    Route::post('/projects/delete',  [ProjectController::class, 'deleteProject']);
    Route::get('/projects',  [ProjectController::class, 'getAllProjects']);
    Route::get('/projects/{id}',  [ProjectController::class, 'getProjectById']);

   Route::post('/timesheets',  [TimesheetController::class, 'createTimesheet']);
   Route::post('/timesheets/delete',  [TimesheetController::class, 'deleteTimesheet']);
   Route::post('/timesheets/update',  [TimesheetController::class, 'updateTimesheet']);
   Route::get('/timesheets',  [TimesheetController::class, 'getUserTimesheets']);
   Route::get('/timesheets/{id}', [TimesheetController::class, 'getTimesheetById']);



});