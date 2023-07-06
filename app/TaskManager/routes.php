<?php

namespace App\TaskManager\Controllers;

use Illuminate\Support\Facades\Route;

// Web

Route::middleware('web')->group(function () {
    
    Route::middleware(['auth'])->group(function () {
        Route::get('data/tasks', [TaskController::class, 'index']);

        Route::post('data/tasks', [TaskController::class, 'store']);

        Route::get('data/tasks/{task}', [TaskController::class, 'show']);

        Route::put('data/tasks/{task}', [TaskController::class, 'update']);

        Route::delete('data/tasks/{task}', [TaskController::class, 'delete']);

        Route::put('data/tasks/{task}/statuses/{status}', [TaskStatusController::class, 'update']);

        Route::get('data/tasks/{task}/steps/', [TaskProgressController::class, 'index']);        

        Route::get('data/tasks/{task}/duration/', [TaskDurationController::class, 'index']);        

        Route::post('data/tasks/{task}/steps/', [TaskProgressController::class, 'store']);

        Route::post('data/tasks/{task}/tags', [TaskTagController::class, 'store']);

        Route::delete('data/tasks/{task}/tags/{tag}', [TaskTagController::class, 'delete']);

        Route::get('data/assigned_to/{id}/user/', [TaskDurationController::class, 'userInfo']);
        
        Route::get('data/statuses', [StatusController::class, 'index']);
    });

    /**********************************
        Status
    **********************************/

    Route::post('data/statuses', [StatusController::class, 'store'])->middleware('auth');
});

// Api

Route::middleware(['api', 'auth:api'])->prefix('api')->group(function () {
    Route::get('tasks', [TaskController::class, 'index']);

    Route::post('tasks', [TaskController::class, 'store']);

    Route::get('tasks/{task}', [TaskController::class, 'show']);

    Route::put('tasks/{task}', [TaskController::class, 'update']);

    Route::delete('tasks/{task}', [TaskController::class, 'delete']);

    Route::put('tasks/{task}/statuses/{status}', [TaskStatusController::class, 'update']);

    Route::get('tasks/{task}/steps/', [TaskProgressController::class, 'index']);

    Route::post('tasks/{task}/steps/', [TaskProgressController::class, 'store']);

    Route::post('tasks/{task}/tags', [TaskTagController::class, 'store']);

    Route::delete('tasks/{task}/tags/{tag}', [TaskTagController::class, 'delete']);

    // Status

    Route::get('statuses', [StatusController::class, 'index']);

    Route::post('statuses', [StatusController::class, 'store']);
});
