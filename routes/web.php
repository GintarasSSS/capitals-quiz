<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuizController::class, 'index']);
Route::get('/random-country', [QuizController::class, 'getRandomCountry']);
Route::post('/capital-answer', [QuizController::class, 'postCapitalAnswer']);
Route::delete('/exit-quiz', [QuizController::class, 'exit']);
