<?php

use Illuminate\Support\Facades\Route;
use Modules\Teacher\Http\Controllers\TeacherController;
use Modules\Teacher\Http\Controllers\NoteController;
use Modules\Teacher\Http\Controllers\ReportCardController;

Route::prefix('v1')->group(function () {
    Route::apiResource('teachers', TeacherController::class);
    Route::post('notes', [NoteController::class, 'store']);
    Route::post('report-cards', [ReportCardController::class, 'store']);
    Route::get('report-cards/{id}', [ReportCardController::class, 'show']);
});
