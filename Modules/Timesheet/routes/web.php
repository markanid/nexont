<?php

use Illuminate\Support\Facades\Route;
use Modules\Timesheet\app\Http\Controllers\TimesheetController;

Route::group(['middleware'=>'auth'],function(){

    $resources = [
        'timesheets'       => TimesheetController::class,
    ];
    
    foreach ($resources as $resource => $controller) {
        Route::get("$resource", [$controller, 'index'])->name("$resource.index");
        Route::get("$resource/create", [$controller, 'createOrEdit'])->name("$resource.create");
        Route::get("$resource/show", [$controller, 'show'])->name("$resource.show");
        Route::get("$resource/edit", [$controller, 'createOrEdit'])->name("$resource.edit");
        Route::post("$resource/update", [$controller, 'storeOrUpdate'])->name("$resource.update");
        Route::get("$resource/delete", [$controller, 'destroy'])->name("$resource.delete");
    }

});
