<?php

namespace Modules\Teacher\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Teacher\Models\Note;
use Modules\Teacher\Models\ReportCard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportCardGenerationService
{
    public function generatePdf(int $reportCardId): string
    {
        Log::info("Attempting to generate PDF for report card ID: {$reportCardId}.");
        try {
            $reportCard = ReportCard::with(['etudiant', 'class'])->findOrFail($reportCardId);
            $student = $reportCard->etudiant;
            $class = $reportCard->class;
            $period = $reportCard->period;
            $general_average = $reportCard->general_average;
            $rank = $reportCard->rank;
            $appreciation = $reportCard->appreciation;
            $mention = $reportCard->mention;
            $notes = Note::where('etudiant_id', $student->id)
                ->where('class_id', $class->id)
                ->where('period', $period)
                ->get();

            $subjectAverages = [];

              // Groupement par nom de matière à partir de la relation "subject"
            foreach ($notes->groupBy(fn($note) => $note->subject->name) as $subjectName => $subjectNotes) {
                // Moyenne simple des notes
                $average = $subjectNotes->avg(fn($note) => ($note->note_devoir + $note->note_exam) / 2);

                // Récupération du coefficient de la matière via la relation
                $coefficient = $subjectNotes->first()->subject->coefficient ?? 1;

                // Construction du tableau final
                $subjectAverages[$subjectName] = [
                    'coefficient' => $coefficient,
                    'average' => $average,
                ];
            }

          // Utilisé ensuite pour le PDF
            $subjects = $subjectAverages;
          //  $subjects =$reportCard->subject_averages;
            Log::info('Sujet averages:', $reportCard->subject_averages);

            $pdf = PDF::loadView('teacher::bulletin', compact('student', 'class', 'period', 'general_average', 'rank', 'appreciation', 'mention', 'subjects'));

            $filename = "bulletin_etudiant_{$student->id}_{$period}.pdf";
            Storage::disk('public')->put("report_cards/{$filename}", $pdf->output());

            $path = Storage::disk('public')->url("report_cards/{$filename}");

            Log::info("PDF generated successfully for report card ID: {$reportCardId}. Path: {$path}");
            return $path;
        } catch (\Throwable $e) {
            Log::error("Error generating PDF for report card ID {$reportCardId}: " . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }
}
