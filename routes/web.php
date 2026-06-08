<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;

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
});
