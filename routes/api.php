<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DifficultyController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StatisticsController;

Route::controller(AuthController::class)->group(function () {
	Route::post('/register', 'register')->name('register');
	Route::post('/login', 'login')->name('login');
	Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware('signed')->name('verification.verify');
	Route::post('/email/verification-notification', 'resendEmail')->middleware('throttle:6,1')->name('verification.send');
	Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
	Route::post('/forgot-password', 'sendResetLink')->name('password.email');
	Route::post('/reset-password', 'resetPassword')->name('password.update');
	Route::get('/user', 'getCurrentUser')->middleware('auth:sanctum')->name('me');
});

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/difficulties', [DifficultyController::class, 'index'])->name('difficulties.index');

Route::controller(QuizController::class)->group(function () {
	Route::get('/quizzes', 'index')->name('quizzes.index');
	Route::get('/quizzes/{quiz}', 'show')->name('quizzes.show');
	Route::post('/quizzes/{quiz}/result', 'take')->name('quizzes.take');
});

Route::get('/statistics', [StatisticsController::class, 'getCounts'])->name('statistics');
