<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
 

// Route::get('/feedback', [FeedbackController::class, 'showForm']);

// Route::post('/feedback/submit', [FeedbackController::class, 'submitFeedback'])->name('feedback.submit');

// Route to show the feedback form
Route::get('/feedback/form', [FeedbackController::class, 'showForm'])->name('feedback.form');

// Route to handle the feedback submission
Route::post('/feedback/submit', [FeedbackController::class, 'submitFeedback'])->name('feedback.submit');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/password/reset', function (Request $request) {
    $token = $request->query('token');
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');
Route::post('/password/update', [ResetPasswordController::class, 'update'])->name('password.update');
// Custom success route
Route::get('/password/success', function () {
    return view('auth.password-success');
})->name('password.success');

