<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('jwt.cookie')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/create', [TicketController::class, 'create']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    Route::get('/tickets/{id}/edit', [TicketController::class, 'edit']);
    Route::put('/tickets/{id}',  [TicketController::class, 'update']);
    Route::delete('/tickets/{id}',  [TicketController::class, 'destroy']);
    Route::post(
        '/tickets/{ticketId}/comments',
        [CommentController::class, 'store']
    );
    Route::get('/notifications',[NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read',[NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all',[NotificationController::class, 'markAllAsRead']);
    Route::get('/users',[UserController::class,'index']);
    Route::get('/users/{id}',[UserController::class,'show']);
    Route::put('/users/{id}',[UserController::class,'update']);
    Route::post('/users/{id}/toggle',[UserController::class,'toggleActive']);
    Route::get('/reports', [ReportController::class,'index']);
});
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('reset-password');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
