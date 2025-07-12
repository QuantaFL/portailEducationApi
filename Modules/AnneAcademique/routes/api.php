<?php

use Illuminate\Support\Facades\Route;
use Modules\AnneAcademique\Http\Controllers\AnneAcademiqueController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('anneacademiques', AnneAcademiqueController::class)->names('anneacademique');
});
