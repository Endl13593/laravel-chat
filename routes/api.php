<?php

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::get('/user/me', [UserController::class, 'me'])->name('user.me');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/messages/{user}', [MessageController::class, 'listMessages'])->name('message.listMessages');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('message.store');
});
