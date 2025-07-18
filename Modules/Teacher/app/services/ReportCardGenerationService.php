<?php

namespace Modules\Teacher\Services;

use Modules\Teacher\Models\ReportCard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportCardGenerationService
{
    public function generatePdf(int $reportCardId): string
    {
        Log::info("Attempting to generate PDF for report card ID: {$reportCardId}.");
        try {
            $reportCard = ReportCard::with(['etudiant.user', 'class'])->findOrFail($reportCardId);

            // Placeholder for PDF generation logic
            $content = "Report Card for: " . $reportCard->etudiant->user->first_name . " " . $reportCard->etudiant->user->last_name . "\n";
            $content .= "Class: " . $reportCard->class->name . "\n";
            $content .= "Period: " . $reportCard->period . "\n";
            $content .= "General Average: " . $reportCard->general_average . "\n";
            $content .= "Mention: " . $reportCard->mention . "\n";
            $content .= "Rank: " . $reportCard->rank . "\n";
            $content .= "Appreciation: " . $reportCard->appreciation . "\n";
            $content .= "Subject Averages: " . json_encode($reportCard->subject_averages) . "\n";

            $filename = "report_card_" . $reportCardId . ".txt";
            Storage::disk('public')->put($filename, $content);

            $path = Storage::disk('public')->path($filename);

            Log::info("PDF (placeholder) generated successfully for report card ID: {$reportCardId}. Path: {$path}");
            return $path;
        } catch (\Throwable $e) {
            Log::error("Error generating PDF for report card ID {$reportCardId}: " . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }
}
