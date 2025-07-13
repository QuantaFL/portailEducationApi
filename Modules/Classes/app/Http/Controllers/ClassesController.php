<?php

namespace Modules\Classes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Classes\Http\facades\ClassesFacade;
use Modules\Classes\Http\Requests\ClassesRequest;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
private $controllerName = "ClassesController";
    public function index()
    {
        try {
            $result = ClassesFacade::getAll();
            return response()->json($result, 200);
        } catch (\Throwable $e) {
            Log::error($this->controllerName . " Erreur lors du chargement des classes", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des classes.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassesRequest $request)
    {
        //

        try {
            $result = ClassesFacade::createClasse($request);
            return response()->json($result, $result['success'] ? 201 : 400);
        }
        catch (\Throwable $e) {
            Log::error($this->controllerName ."Erreur lors de la création d'une classe", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la classe.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        //

        try {
            $result = ClassesFacade::getById($id);
            return response()->json($result, $result['success'] ? 200 : 404);
        }
        catch (ModelNotFoundException $e) {
            Log::warning($this->controllerName . " | Classe non trouvée", [
                'exception' => $e,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée.',
                'error' => $e->getMessage()
            ], 404);

        }
        catch (\Throwable $e) {
            Log::error("[$this->controllerName] Erreur lors de la récupération d'une classe", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la classe.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassesRequest $request, $id)
    {
        try {
            $result = ClassesFacade::updateClasse($id, $request);
            return response()->json($result, $result['success'] ? 200 : 400);
        }
        catch (ModelNotFoundException $e) {
            Log::warning($this->controllerName . " | Classe non trouvée", [
                'exception' => $e,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée.',
                'error' => $e->getMessage()
            ], 404);

        }
        catch (\Throwable $e) {
            Log::error("[$this->controllerName] Erreur lors de la mise à jour d'une classe", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la classe.',
                'error'   => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            $result = ClassesFacade::deleteClasse($id);
            return response()->json($result, $result['success'] ? 200 : 404);
        }
        catch (ModelNotFoundException $e) {
            Log::warning($this->controllerName . " | Classe non trouvée", [
                'exception' => $e,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée.',
                'error' => $e->getMessage()
            ], 404);

        }
        catch (\Throwable $e) {
            Log::error("[$this->controllerName] Erreur lors de la suppression d'une classe", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la classe.',
                'error'   => $e->getMessage(),
            ], 500);
        }


    }
}
