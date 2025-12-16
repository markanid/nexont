<?php

use Illuminate\Support\Facades\Route;
use Modules\Projection\app\Http\Controllers\ProjectionController;

Route::group(['middleware'=>'auth'],function(){

    $resources = [
        'projections'       => ProjectionController::class,
    ];
    
    foreach ($resources as $resource => $controller) {
        Route::get("$resource", [$controller, 'index'])->name("$resource.index");
        Route::get("$resource/create", [$controller, 'createOrEdit'])->name("$resource.create")->middleware('role:PMO');
        Route::get("$resource/{id}", [$controller, 'show'])->name("$resource.show");
        Route::get("$resource/edit/{id}", [$controller, 'createOrEdit'])->name("$resource.edit")->middleware('role:PMO');
        Route::post("$resource/update", [$controller, 'storeOrUpdate'])->name("$resource.update");
        Route::get("$resource/delete/{id}", [$controller, 'destroy'])->name("$resource.delete")->middleware('role:PMO');
    }

});
