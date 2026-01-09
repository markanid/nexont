<?php

use Illuminate\Support\Facades\Route;
use Modules\Projection\app\Http\Controllers\ProjectionController;
use Modules\Projection\app\Http\Controllers\RunningProjectController;

Route::group(['middleware'=>'auth'],function(){

    $resources = [
        'projections'       => ProjectionController::class,
        'runningprojects'   => RunningProjectController::class,
    ];
    
    foreach ($resources as $resource => $controller) {
        Route::get("$resource", [$controller, 'index'])->name("$resource.index");
        Route::get("$resource/create", [$controller, 'createOrEdit'])->name("$resource.create")->middleware('role:PMO,Admin');
        Route::get("$resource/edit/{id}", [$controller, 'createOrEdit'])->name("$resource.edit")->middleware('role:PMO,Admin');
        Route::get("$resource/adddetails", [$controller, 'addOrEdit'])->name("$resource.adddetails")->middleware('role:Project Manager');
        Route::get("$resource/editdetails/{id}", [$controller, 'addOrEdit'])->name("$resource.editdetails")->middleware('role:Project Manager');
        Route::get("$resource/{id}", [$controller, 'show'])->name("$resource.show");
        Route::post("$resource/update", [$controller, 'storeOrUpdate'])->name("$resource.update");
        Route::get("$resource/delete/{id}", [$controller, 'destroy'])->name("$resource.delete")->middleware('role:PMO,Admin');
        Route::get("$resource/deletedetails/{id}", [$controller, 'delete'])->name("$resource.deletedetails")->middleware('role:Project Manager');
    }

    Route::get('projections/{projection}/running-projects',[ProjectionController::class, 'filterRunningProjects'])->name('projections.runningprojects.filter');

});
