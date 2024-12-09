<?php

use App\Http\Controllers\OtpController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/signup', [UserController::class, 'store']);

Route::post('/login', [SessionController::class, 'store']);
Route::delete('/logout', [SessionController::class, 'destory']);

Route::get('/otp', [OtpController::class, 'send']);