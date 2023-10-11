<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login' , [AuthController::class , 'login'])->name('login');
Route::post('register' , [AuthController::class , 'register'])->name('register');

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(["middleware" => "role:admin"], function() {
        Route::apiResource('users', UserController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('orders', OrderController::class);
    });

    Route::post('logout' , [AuthController::class , 'logout'])->name('logout');
});
