<?php

use App\Http\Controllers\Auth\AuthController;
use App\Livewire\CategoryManager;
use App\Livewire\TaskManager;
use Illuminate\Support\Facades\Route;

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Application Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', \App\Livewire\Dashboard::class)->name('dashboard');

    Route::get('/tasks', TaskManager::class)->name('tasks.index');
    Route::get('/categories', CategoryManager::class)->name('categories.index');
});
