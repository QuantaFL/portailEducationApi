<?php

namespace Modules\Teacher\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Teacher\Services\GradeCalculationService;
use Modules\Teacher\Services\ReportCardGenerationService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Modules\Teacher\Models\ReportCard;

class ReportCardController extends Controller
{
    protected $gradeCalculationService;
    protected $reportCardGenerationService;

    public function __construct(GradeCalculationService $gradeCalculationService, ReportCardGenerationService $reportCardGenerationService)
    {
        $this->gradeCalculationService = $gradeCalculationService;
        $this->reportCardGenerationService = $reportCardGenerationService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'etudiant_id' => 'required|exists:etudiants,id',
                'class_id' => 'required|exists:classes,id',
                'period' => 'required|string',
            ]);

            $reportCard = $this->gradeCalculationService->calculateAndSaveReportCard(
                $validatedData['etudiant_id'],
                $validatedData['class_id'],
                $validatedData['period']
            );

            // Calculate rank for the class after all report cards are potentially updated/created
            $this->gradeCalculationService->calculateClassRank(
                $validatedData['class_id'],
                $validatedData['period']
            );

            return response()->json(['status' => 'success', 'message' => 'Report card calculated and saved.', 'data' => $reportCard, 'code' => 201], 201);
        } catch (ValidationException $e) {
            Log::warning('Report card calculation validation failed.', ['errors' => $e->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'code' => 400
            ], 400);
        } catch (\Throwable $e) {
            Log::error('An unexpected error occurred during report card calculation: ' . $e->getMessage(), ['exception' => $e]);
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
            $reportCardPath = $this->reportCardGenerationService->generatePdf($id);
            return response()->json(['status' => 'success', 'message' => 'Report card PDF generated.', 'data' => ['path' => $reportCardPath], 'code' => 200], 200);
        } catch (\Throwable $e) {
            Log::error('An unexpected error occurred during report card PDF generation: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'errors' => [],
                'code' => 500
            ], 500);
        }
    }
}
