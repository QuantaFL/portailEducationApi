<?php

namespace Modules\AnneAcademique\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\AnneAcademique\Facades\AnneeAcademiqueFacade;
use Modules\AnneAcademique\Http\Requests\AnneeAcademiqueRequest;

class AnneAcademiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $controllerName = 'AnneAcademiqueController';
    public function index()
    {
        //
        try {
            $result = AnneeAcademiqueFacade::getAll();
            return response()->json($result, 200);
        } catch (\Throwable $e) {
            Log::error("[$this->controllerName] Erreur lors du chargement des années académiques", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des années académiques.',
                'error'   => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnneeAcademiqueRequest $request)
    {
        //
        try {
            $result = AnneeAcademiqueFacade::createAA($request);
            return response()->json($result, $result['success'] ? 201 : 400);
        } catch (\Throwable $e) {
            Log::error("[$this->controllerName] Erreur lors de la création d'une année academique ", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l’année académique.',
                'error'   => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $result = AnneeAcademiqueFacade::getById($id);
            return response()->json($result, $result['success'] ? 200 : 404);
        } catch (\Throwable $e) {
            Log::error("[$this->controllerName] Erreur lors de la  récupération d'une année academique ", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l’année académique.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnneeAcademiqueRequest $request, $id)
    {
        //
        try {
            $result = AnneeAcademiqueFacade::updateAA($id, $request);
            return response()->json($result, $result['success'] ? 200 : 400);
        } catch (\Throwable $e) {
            Log::error("[$this->controllerName] Erreur lors de la  mise à jour d'une année academique ", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour.',
                'error'   => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = AnneeAcademiqueFacade::deleteAA($id);
            return response()->json($result, $result['success'] ? 200 : 404);
        } catch (\Throwable $e) {
            Log::error("[$this->controllerName] Erreur lors de la  récupération d'une année academique ", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
