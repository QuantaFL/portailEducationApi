<?php

use Illuminate\Support\Facades\Route;
use Modules\AnneAcademique\Http\Controllers\AnneAcademiqueController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('anneacademiques', AnneAcademiqueController::class)->names('anneacademique');
});
