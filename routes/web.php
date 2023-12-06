<?php

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


