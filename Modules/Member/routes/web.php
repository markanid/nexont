<?php

use Illuminate\Support\Facades\Route;
use Modules\Member\app\Http\Controllers\{EmployeeController, VendorController};

Route::group(['middleware'=>'auth:employee'],function(){

    $resources = [
        'vendors'       => VendorController::class,
        'employees'     => EmployeeController::class,
    ];
    
    foreach ($resources as $resource => $controller) {
        Route::get("$resource", [$controller, 'index'])->name("$resource.index");
        Route::get("$resource/create", [$controller, 'createOrEdit'])->name("$resource.create");
        Route::get("$resource/{id}", [$controller, 'show'])->name("$resource.show");
        Route::get("$resource/edit/{id}", [$controller, 'createOrEdit'])->name("$resource.edit");
        Route::post("$resource/update", [$controller, 'storeOrUpdate'])->name("$resource.update");
        Route::get("$resource/delete/{id}", [$controller, 'destroy'])->name("$resource.delete");
        Route::post("$resource/payment", [$controller, 'payment'])->name("$resource.payment");
    }

});
