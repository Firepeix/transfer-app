<?php

use App\Http\Controllers\API\Transaction\TransactionController;
use App\Http\Controllers\API\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/transaction')->group(function () {
    Route::post('/', TransactionController::class . '@transfer');
});
Route::prefix('/user')->group(function () {
    Route::post('/', UserController::class . '@store');
    Route::put('/{userId}', UserController::class . '@update');
});
