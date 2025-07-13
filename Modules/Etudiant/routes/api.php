<?php

use Illuminate\Support\Facades\Route;
use Modules\Etudiant\Http\Controllers\EtudiantController;

Route::apiResource('etudiants', EtudiantController::class)->names('etudiant');

