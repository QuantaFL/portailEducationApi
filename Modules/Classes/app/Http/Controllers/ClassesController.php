<?php

namespace Modules\Classes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Classes\Http\Requests\ClassesRequest;
use Modules\Classes\Http\services\ClassesService;
use OpenApi\Annotations as OA;

class ClassesController extends Controller
{
    protected $classesService;

    public function __construct(ClassesService $classesService)
    {
        $this->classesService = $classesService;
    }

    /**
     * Display a listing of the resource.
     */
    private $controllerName = "ClassesController";
    /**
     * @OA\Get(
     *     path="/api/classes",
     *     tags={"Classes"},
     *     summary="Lister toutes les classes",
     *     description="Récupère la liste complète des classes.",
     *     operationId="listClasses",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des classes récupérée avec succès.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="6ème"),
     *                 @OA\Property(property="academicYear", type="string", example="2024-2025"),
     *                 @OA\Property(property="createdAt", type="string", format="date-time", example="2024-07-13T10:00:00Z"),
     *                 @OA\Property(property="updatedAt", type="string", format="date-time", example="2024-07-13T10:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne du serveur.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur lors du chargement des classes."),
     *             @OA\Property(property="error", type="string", example="Exception message.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $result = $this->classesService->getAll();
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
            $result = $this->classesService->createClasse($request);
            return response()->json($result, 201);
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
            $result = $this->classesService->getById($id);
            return response()->json($result, 200);
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
            $result = $this->classesService->updateClasse($id, $request);
            return response()->json($result, 200);
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
            $result = $this->classesService->deleteClasse($id);
            return response()->json(null, 204);
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
