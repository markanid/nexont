<?php

use Illuminate\Support\Facades\Route;
use Modules\Master\app\Http\Controllers\{CompanyController, UserController};

Route::group(['middleware'=>'auth'],function(){

    $resources = [
        'companies' => CompanyController::class,
        'users'     => UserController::class,
    ];
    
    foreach ($resources as $resource => $controller) {
        Route::get("$resource", [$controller, 'index'])->name("$resource.index");
        Route::get("$resource/create", [$controller, 'createOrEdit'])->name("$resource.create");
        Route::get("$resource/{id}", [$controller, 'show'])->name("$resource.show");
        Route::get("$resource/edit/{id}", [$controller, 'createOrEdit'])->name("$resource.edit");
        Route::post("$resource/update", [$controller, 'storeOrUpdate'])->name("$resource.update");
        Route::get("$resource/delete/{id}", [$controller, 'destroy'])->name("$resource.delete");
    }

    Route::get('/userprofile/{id}', [UserController::class, 'showProfile'])->name('users.showprofile');
    Route::get('/user/changepassword', [UserController::class, 'showChangePasswordForm'])->name('users.changePasswordForm');
    Route::post('/user/changepassword', [UserController::class, 'changePassword'])->name('users.changepassword');

});
