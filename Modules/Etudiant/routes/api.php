<?php

use Illuminate\Support\Facades\Route;
use Modules\Etudiant\Http\Controllers\EtudiantController;
Route::prefix("v1")->group(function (){
    Route::apiResource('etudiants', EtudiantController::class)->names('etudiant');

});

