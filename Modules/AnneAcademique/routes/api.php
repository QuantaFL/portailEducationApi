<?php

use Illuminate\Support\Facades\Route;
use Modules\AnneAcademique\Http\Controllers\AnneAcademiqueController;

Route::prefix('v1')->group(function () {
    Route::apiResource('anneacademiques', AnneAcademiqueController::class);
});
