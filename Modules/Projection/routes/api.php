<?php

use Illuminate\Support\Facades\Route;
use Modules\Projection\Http\Controllers\ProjectionController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('projections', ProjectionController::class)->names('projection');
});
