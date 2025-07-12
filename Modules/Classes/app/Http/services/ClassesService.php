<?php

namespace Modules\Classes\Http\services;

use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Classes\Http\Requests\ClassesRequest;
use Modules\Classes\Models\Classes;

class ClassesService
{
    private string $serviceName = 'ClassesService';

    public function createClasse(ClassesRequest $request): array
    {
        try {
            Log::info($this->serviceName . '::createClasse - Tentative de création', ['data' => $request->validated()]);

            $classe = Classes::create($request->validated());

            return [
                'success' => true,
                'message' => 'Classe créée avec succès.',
                'classe'  => $classe,
            ];
        } catch (Exception $e) {
            Log::error($this->serviceName . '::createClasse - Erreur de création', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateClasse(int $id, ClassesRequest $request): array
    {
        try {
            Log::info($this->serviceName . '::updateClasse - Tentative de mise à jour', ['id' => $id, 'data' => $request->validated()]);

            $classe = Classes::findOrFail($id);
            $classe->update($request->validated());

            return [
                'success' => true,
                'message' => 'Classe mise à jour avec succès.',
                'classe'  => $classe,
            ];
        } catch (Exception $e) {
            Log::error($this->serviceName . '::updateClasse - Échec de mise à jour', ['id' => $id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteClasse(int $id): array
    {
        try {
            Log::info($this->serviceName . '::deleteClasse - Suppression de classe', ['id' => $id]);

            $classe = Classes::findOrFail($id);
            $classe->delete();

            return [
                'success' => true,
                'message' => 'Classe supprimée avec succès.',
            ];
        } catch (Exception $e) {
            Log::error($this->serviceName . '::deleteClasse - Erreur lors de la suppression', ['id' => $id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getAll(): array
    {
        try {
            Log::info($this->serviceName . '::getAll - Récupération de toutes les classes');

            $classes = Classes::all();

            return [
                'success' => true,
                'classes' => $classes,
            ];
        } catch (Exception $e) {
            Log::error($this->serviceName . '::getAll - Échec récupération', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getById(int $id): array
    {
        try {
            Log::info($this->serviceName . '::getById - Récupération d\'une classe', ['id' => $id]);

            $classe = Classes::findOrFail($id);

            return [
                'success' => true,
                'classe'  => $classe,
            ];
        } catch (Exception $e) {
            Log::error($this->serviceName . '::getById - Classe non trouvée', ['id' => $id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
