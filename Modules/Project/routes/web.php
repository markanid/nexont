<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\app\Http\Controllers\ProjectController;

Route::group(['middleware'=>'auth'],function(){

    $resources = [
        'projects'       => ProjectController::class,
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
