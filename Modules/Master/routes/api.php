<?php

use Illuminate\Support\Facades\Route;
use Modules\Master\App\Http\Controllers\MasterController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('masters', MasterController::class)->names('master');
});
