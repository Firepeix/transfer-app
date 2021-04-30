<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('transfer');
})->name('transfer');

Route::get('/user/create/standard', function () {
    return view('user.create-standard');
})->name('create-standard');

Route::get('/user/create/store-keeper', function () {
    return view('user.create-store-keeper');
})->name('create-store-keeper');
