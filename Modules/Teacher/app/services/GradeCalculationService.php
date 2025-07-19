<?php

namespace Modules\Teacher\Services;

use Modules\Etudiant\Models\Etudiant;
use Modules\Teacher\Models\Note;
use Modules\Teacher\Models\ReportCard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class GradeCalculationService
{
    public function calculateAndSaveReportCard(int $etudiantId, int $classId, string $period): ReportCard
    {
        Log::info("Starting report card calculation for student {$etudiantId} in class {$classId} for period {$period}.");

        $etudiant = Etudiant::findOrFail($etudiantId);

        $notes = Note::where('etudiant_id', $etudiantId)
                     ->where('class_id', $classId)
                     ->where('period', $period)
                     ->get();

        $subjectAverages = $this->calculateSubjectAverages($notes);
        $generalAverage = $this->calculateGeneralAverage($subjectAverages);
        $mention = $this->getMention($generalAverage);
        $appreciation = $this->getAppreciation($generalAverage);

        // Rank will be calculated after all students' general averages are known for the class/period
        $rank = null; // Placeholder for now

        $reportCard = ReportCard::updateOrCreate(
            ['etudiant_id' => $etudiantId, 'class_id' => $classId, 'period' => $period], // search key
            [
                'general_average' => $generalAverage,
                'mention' => $mention,
                'rank' => $rank,
                'appreciation' => $appreciation,
                'subject_averages' => $subjectAverages,
            ] // updated values
        );

        Log::info("Report card calculated and saved for student {$etudiantId}.", ['report_card_id' => $reportCard->id]);

        return $reportCard;
    }

    private function calculateSubjectAverages(Collection $notes): array
    {
        $subjectAverages = [];
        $notesBySubject = $notes->groupBy('subject_id');

        foreach ($notesBySubject as $subjectId => $subjectNotes) {
            $totalWeight = 0;
            $weightedSum = 0;

            foreach ($subjectNotes as $note) {
                $note_exam = $note->note_exam ?? 0;
                $note_devoir = $note->note_devoir ?? 0;

                // Assuming exam has a weight of 2 and devoir a weight of 1
                $weightedSum += ($note_exam * 2) + $note_devoir;
                $totalWeight += 3; // 2 for exam, 1 for devoir
            }

            $average = ($totalWeight > 0) ? $weightedSum / $totalWeight : 0;
            $subjectAverages[$subjectId] = round($average, 2);
        }

        return $subjectAverages;
    }

    private function calculateGeneralAverage(array $subjectAverages): float
    {
        if (empty($subjectAverages)) {
            return 0.0;
        }
        return round(array_sum($subjectAverages) / count($subjectAverages), 2);
    }

    private function getMention(float $average): string
    {
        if ($average >= 19) return 'la vraie excellence';
        if ($average >= 17) return 'Excellent';
        if ($average >= 16) return 'Très bien';
        if ($average >= 14) return 'Bien';
        if ($average >= 12) return 'Assez bien';
        if ($average >= 10) return 'Passable';
        return 'Insuffisant';
    }

    private function getAppreciation(float $average): string
    {
        if ($average >= 19) return 'le meilleur des meilleurs';
        if ($average >= 17) return 'En route pour l\'excellence et au delà,continuez ainsi';
        if ($average >= 16) return 'Excellent travail, continuez ainsi !';
        if ($average >= 14) return 'Très bon résultats, félicitations.';
        if ($average >= 12) return 'Bons résultats, avec quelques points à améliorer.';
        if ($average >= 10) return 'Résultats corrects, mais des efforts supplémentaires sont nécessaires.';
        return 'Des difficultés importantes sont rencontrées, un travail approfondi est indispensable.';
    }

    public function calculateClassRank(int $classId, string $period): void
    {
        Log::info("Calculating ranks for class {$classId} for period {$period}.");

        $reportCards = ReportCard::where('class_id', $classId)
                                 ->where('period', $period)
                                 ->orderByDesc('general_average')
                                 ->get();

        $rank = 1;
        $previousAverage = null;
        foreach ($reportCards as $reportCard) { // work bcz reportCards was sorted by Desc btw the greasted AVG alaways be on the top of collection like that : 19,18,17,16,etc
            if ($previousAverage !== null && $reportCard->general_average < $previousAverage) {
                $rank++;
            }
            $reportCard->rank = $rank;
            $reportCard->save();
            $previousAverage = $reportCard->general_average;
        }
        Log::info("Ranks calculated and saved for class {$classId} for period {$period}.");
    }
}
