<?php

use App\Http\Controllers\{AuthController, ProfileController};
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>'guest'],function(){
    Route::get('/', [AuthController::class, 'getLogin'])->name('auth.login');
    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('auth.authenticate');
    Route::get('/registration', [AuthController::class, 'registration'])->name('auth.registration');
    Route::post('/register-process', [AuthController::class, 'registerProcess'])->name('auth.registerProcess');
});

Route::group(['middleware'=>'auth'],function(){
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/dbbackup', [ProfileController::class, 'dbbackup'])->name('dbbackup');
    Route::get('/profile/restore', [ProfileController::class, 'restore'])->name('restore');
    Route::post('/profile/dbrestore', [ProfileController::class, 'restoreBackup'])->name('restoreDB');
});