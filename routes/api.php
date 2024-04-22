<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Models\Category;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
	Route::post('/register', 'register')->name('register');
	Route::post('/login', 'login')->name('login');
	Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware('signed')->name('verification.verify');
	Route::post('/email/verification-notification', 'resendEmail')->middleware('throttle:6,1')->name('verification.send');
	Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
	Route::post('/forgot-password', 'sendResetLink')->name('password.email');
	Route::post('/reset-password', 'resetPassword')->name('password.update');
});

Route::get('/categories', function () {
	return Category::all();
});

Route::controller(QuizController::class)->group(function () {
	Route::get('/quizzes', 'index')->name('quizzes.index');
	Route::get('/quizzes/{quiz}', 'show')->name('quizzes.show');
});
