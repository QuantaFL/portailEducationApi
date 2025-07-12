<?php

namespace Modules\AnneAcademique\app\services;

use Exception;
use Illuminate\Support\Facades\Log;
use Modules\AnneAcademique\Http\Requests\AnneeAcademiqueRequest;
use Modules\AnneAcademique\Models\AnneAcademique;

class AnneeAcademiqueService
{
    private $serviceName = 'AnneeAcademiqueService';
    public function createAA(AnneeAcademiqueRequest $request)
    {
        try {
            $data = $request->validated();

            $annee = AnneAcademique::create($data);

            return [
                'success' => true,
                'message' => 'Année académique créée avec succès.',
                'annee'   => $annee,
            ];

        } catch (Exception $e) {
            Log::error('AnneeAcademique'. '::createAA - Erreur lors de la création : ' . $e->getMessage());
            throw $e;

        }
    }
    public function updateAA(int $id, AnneeAcademiqueRequest $request): array
    {
        try {
            Log::info($this->serviceName. '::updateAA - Tentative de mise à jour', ['id' => $id, 'data' => $request]);

            $annee = AnneAcademique::findOrFail($id);
            $annee->update($request);

            return [
                'success' => true,
                'message' => 'Année académique mise à jour avec succès.',
                'annee'   => $annee,
            ];
        } catch (\Throwable $e) {
            Log::error($this->serviceName.'::updateAA erreur lors de la mise à jour de l anne academique avec id  ',['id'=>$id]);
           throw $e ;
        }
    }
    public function deleteAA(int $id): array
    {
        try {
            Log::info($this->serviceName . '::deleteAA - Suppression en cours', ['id' => $id]);

            $annee = AnneAcademique::findOrFail($id);
            $annee->delete();

            return [
                'success' => true,
                'message' => 'Année académique supprimée avec succès.',
            ];
        } catch (\Throwable $e) {
            Log::error($this->serviceName . '::deleteAA - Erreur lors de la suppression', ['error' => $e->getMessage()]);
           throw $e ;
        }
    }
    public function getAll(): array
    {
        try {
            Log::info($this->serviceName . '::getAll - Récupération de toutes les années');

            $annees = AnneAcademique::all();

            return [
                'success' => true,
                'annees'  => $annees,
            ];
        } catch (\Throwable $e) {
            Log::error($this->serviceName. '::getAll - Échec récupération', ['error' => $e->getMessage()]);
          throw $e;
        }
    }
    public function getById(int $id): array
    {
        try {
            Log::info($this->serviceName . '::getById - Recherche de l’année', ['id' => $id]);

            $annee = AnneAcademique::findOrFail($id);

            return [
                'success' => true,
                'annee'   => $annee,
            ];
        } catch (\Throwable $e) {
            Log::error($this->serviceName . '::getById - Année introuvable', ['error' => $e->getMessage()]);
          throw $e ;
        }
    }
    public function setAAAsCurrent(int $id): array
    {
        try {
            Log::info($this->serviceName . '::setAAAsCurrent - Activation de l’année courante', ['id' => $id]);

            AnneAcademique::where('est_courante', true)->update(['est_courante' => false]);

            $annee = AnneAcademique::findOrFail($id);
            $annee->update(['est_courante' => true]);

            return [
                'success' => true,
                'message' => 'Année académique définie comme courante.',
                'annee'   => $annee,
            ];
        } catch (\Throwable $e) {
            Log::error($this->serviceName . '::setAAAsCurrent - Échec d’activation', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Erreur lors de la définition de l’année courante.',
            ];
        }
    }
}
