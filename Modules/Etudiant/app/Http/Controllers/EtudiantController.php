<?php

namespace Modules\Etudiant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Etudiant\Facades\EtudiantFacade;
use Modules\Etudiant\Http\Requests\EtudiantRequest;

class EtudiantController extends Controller
{
    private $controllerName = 'EtudiantController';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $etudiants = EtudiantFacade::getAllStudent();
            return response()->json([
                'message' =>'liste des etudiants chargés',
                'etudiants'=>$etudiants
            ]);

        }catch (\Exception $e) {
            Log::error("[$this->controllerName] Erreur lors de l'ajout d'un étudiant", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            //   Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EtudiantRequest $request)
    {
        try {
            Log::info("entry controller ");
            $result = EtudiantFacade::createStd($request->validated());

            return response()->json([
                'message' => 'Étudiant inscrit avec succès.',
                'data' => [
                    'token' => $result['token'],
                    // 'token_type' => 'bearer',
                    // 'expires_in' => auth('api')->factory()->getTTL() * 60,
                    'user' => $result['user'],
                    'etudiant' => $result['etudiant'],
                ]
            ], 201);
        }catch (\Exception $e) {
            Log::error("[$this->controllerName] Erreur lors de l'ajout d'un étudiant", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            //   Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        //

        return response()->json([]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //

        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //

        return response()->json([]);
    }
}
