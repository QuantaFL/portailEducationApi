<?php

use Illuminate\Support\Facades\Route;
use Modules\Subject\Http\Controllers\SubjectController;

Route::prefix('v1')->group(function () {
    Route::apiResource('subjects', SubjectController::class)->names('subject');
});
