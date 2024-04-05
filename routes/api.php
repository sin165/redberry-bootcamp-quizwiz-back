<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
	Route::post('/register', 'register')->name('register');
	Route::post('/login', 'login')->name('login');
	Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware('signed')->name('verification.verify');
	Route::post('/email/verification-notification', 'resendEmail')->middleware('throttle:6,1')->name('verification.send');
	Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
});
