<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteSwaggFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-swagg-files-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modulesPath = base_path('Modules');
        $modules = array_filter(glob($modulesPath . '/*'), 'is_dir');

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $filePath = $modulePath . '/my_swagg';

            if (file_exists($filePath)) {
                unlink($filePath);
                $this->info("Fichier my_swagg supprimé pour le module : " . $moduleName);
            } else {
                $this->info("Fichier my_swagg non trouvé pour le module : " . $moduleName);
            }
        }

        $this->info("Suppression des fichiers my_swagg terminée.");
    }
}
