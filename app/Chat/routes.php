<?php

namespace App\Chat\Controllers;

use Illuminate\Support\Facades\Route;

// Web

Route::middleware('web')->group(function () {
    
    Route::middleware(['auth'])->group(function () {
        Route::post('data/workspace', [WorkspaceController::class, 'store']);

        Route::get('data/workspaces', [WorkspaceController::class, 'index']);

        Route::put('data/workspace', [WorkspaceController::class, 'update']);
    
        Route::delete('data/workspace', [WorkspaceController::class, 'delete']);


        /**********************************
                 Direct Message
        **********************************/

        Route::get('data/direct-messages', [DirectMessageController::class, 'index']);

        Route::post('data/direct-messages', [DirectMessageController::class, 'store']);

        Route::put('data/direct-messages/', [DirectMessageController::class, 'update']);

        Route::delete('data/direct-messages/', [DirectMessageController::class, 'delete']);
        
        });

        /**********************************
                 Common
        **********************************/

        Route::get('data/conversation/common', [ChatController::class, 'getCommonConversationData']);
        
});

// Api

Route::middleware(['api', 'auth:api'])->prefix('api')->group(function () {
    Route::get('data/messages', [MessageController::class, 'index']);

    Route::group(['middleware' => 'auth'], function () {
        Route::post('data/messages', [MessageController::class, 'store']);
    
        Route::put('data/messages/{message}', [MessageController::class, 'update']);
    
        Route::delete('data/messages/{message}', [MessageController::class, 'delete']);
    });
});
