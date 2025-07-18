<?php

namespace Modules\Etudiant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Etudiant\Http\Requests\EtudiantRequest;
use Modules\Etudiant\Http\Requests\UpdateEtudiantRequest;
use Modules\Etudiant\Services\EtudiantService;
use Modules\Etudiant\Exceptions\EtudiantException;
use Modules\Etudiant\Exceptions\EtudiantConflictException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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
        } catch (EtudiantException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EtudiantRequest $request)
    {
        try {
            $etudiant = $this->etudiantService->createEtudiant($request->validated());
            return response()->json(['status' => 'success', 'data' => $etudiant, 'code' => 201], 201);
        } catch (ValidationException $e) {
            Log::warning('Etudiant creation validation failed.', ['errors' => $e->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'code' => 400
            ], 400);
        } catch (EtudiantConflictException $e) {
            Log::warning('Etudiant creation conflict.', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 409
            ], 409);
        } catch (EtudiantException $e) {
            Log::error('Error creating etudiant: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 500
            ], 500);
        } catch (\Throwable $e) {
            Log::error('An unexpected error occurred during etudiant creation: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'errors' => [],
                'code' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $etudiant = $this->etudiantService->getEtudiantById($id);
            return response()->json($etudiant);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Student not found'], 404);
        } catch (EtudiantException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEtudiantRequest $request, $id)
    {
        try {
            $etudiant = $this->etudiantService->updateEtudiant($id, $request->validated());
            return response()->json($etudiant);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Student not found'], 404);
        } catch (EtudiantException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->etudiantService->deleteEtudiant($id);
            return response()->json(['message' => 'Student deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Student not found'], 404);
        } catch (EtudiantException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
}
