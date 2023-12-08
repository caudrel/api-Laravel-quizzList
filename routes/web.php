<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/quiz', [QuizController::class, 'store']);
Route::get('/quiz', [QuizController::class, 'index']);
Route::patch('/quiz/{id}', [QuizController::class, 'update']);
Route::delete('/quiz/{id}',  [QuizController::class, 'delete']);

Route::post('/category', [CategoryController::class, 'store']);
Route::get('/category', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'getQuizzes']);
Route::patch('/category/{id}', [CategoryController::class, 'update']);
Route::delete('/category/{id}', [CategoryController::class, 'delete']);
