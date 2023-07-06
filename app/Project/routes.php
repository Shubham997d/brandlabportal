<?php

namespace App\Project\Controllers;

use Illuminate\Support\Facades\Route;

// Web

Route::middleware('web')->group(function () {
    
    Route::middleware(['auth'])->group(function () {
        Route::get('data/projects/{project}', [ProjectController::class, 'show']);
        
        Route::get('data/projects/get/{category}/{status}', [ProjectController::class, 'index']);

        Route::get('data/project/chart/user/{project}', [ProjectController::class, 'getUserDurationChartData']);

        Route::get('data/project/details/{project}', [ProjectController::class, 'getProjectDetails']);

        Route::post('data/projects', [ProjectController::class, 'store']);

        Route::post('data/projects/{id}', [ProjectController::class, 'update']);

        Route::get('data/project/status', [ProjectController::class, 'getStatus']);

        Route::delete('data/projects/{project}', [ProjectController::class, 'delete']);

        Route::post('public-projects/{project}', [PublicProjectController::class, 'store']);
        
        Route::delete('public-projects/{project}', [PublicProjectController::class, 'delete']);

        Route::post('data/project/deal/exists', [ProjectController::class, 'checkIfProjectDealExists']);

        /* Assets page routes */

        Route::get('data/project/assets', [AssetController::class, 'index']);

        Route::post('data/project/assets', [AssetController::class, 'store']);        

        Route::put('data/project/assets', [AssetController::class, 'update']);

        Route::delete('data/project/assets', [AssetController::class, 'delete']);

        

        
    });
});



// Api

Route::middleware(['api', 'auth:api'])->prefix('api')->group(function () {
    Route::get('projects/', [ProjectController::class, 'index']);

    Route::post('projects', [ProjectController::class, 'store']);

    Route::delete('projects/{project}', [ProjectController::class, 'delete']);

    Route::post('public-projects/{project}', [PublicProjectController::class, 'store']);

    Route::delete('public-projects/{project}', [PublicProjectController::class, 'delete']);
});
