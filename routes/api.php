<?php

use App\Http\Controllers\API\Transaction\TransactionController;
use App\Http\Controllers\API\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/transaction')->group(function () {
    Route::post('/', TransactionController::class . '@transfer');
});
Route::prefix('/users')->group(function () {
    Route::get('/', UserController::class . '@index');
    Route::get('/{userId}/wallet', UserController::class . '@getWallet');
    Route::post('/', UserController::class . '@store');
    Route::put('/{userId}', UserController::class . '@update');
});
