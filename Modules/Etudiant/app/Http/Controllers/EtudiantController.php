<?php

namespace Modules\Etudiant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Etudiant\Http\Requests\EtudiantRequest;
use Modules\Etudiant\Http\Requests\UpdateEtudiantRequest;
use Modules\Etudiant\Services\EtudiantService;

class EtudiantController extends Controller
{
    protected $etudiantService;

    public function __construct(EtudiantService $etudiantService)
    {
        $this->etudiantService = $etudiantService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $etudiants = $this->etudiantService->getAllEtudiants();
            return response()->json($etudiants);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching students', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EtudiantRequest $request)
    {
        try {
            $etudiant = $this->etudiantService->createEtudiant($request->validated());
            return response()->json($etudiant, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating student', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $etudiant = $this->etudiantService->getEtudiantById($id);
            if (!$etudiant) {
                return response()->json(['message' => 'Student not found'], 404);
            }
            return response()->json($etudiant);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching student', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEtudiantRequest $request, $id)
    {
        try {
            $etudiant = $this->etudiantService->updateEtudiant($id, $request->validated());
            if (!$etudiant) {
                return response()->json(['message' => 'Student not found'], 404);
            }
            return response()->json($etudiant);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating student', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->etudiantService->deleteEtudiant($id);
            if (!$deleted) {
                return response()->json(['message' => 'Student not found'], 404);
            }
            return response()->json(['message' => 'Student deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting student', 'error' => $e->getMessage()], 500);
        }
    }
}
