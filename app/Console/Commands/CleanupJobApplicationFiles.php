<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\JobApplication;

class CleanupJobApplicationFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job-applications:cleanup {--days=30 : Nombre de jours avant suppression}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les anciens fichiers de candidature orphelins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Nettoyage des fichiers de candidature de plus de {$days} jours...");
        
        // Récupérer tous les fichiers dans le répertoire des candidatures
        $allFiles = Storage::allFiles('private/job-applications');
        
        $cleanedCount = 0;
        $totalSize = 0;
        
        foreach ($allFiles as $file) {
            // Vérifier l'âge du fichier
            $lastModified = Storage::lastModified($file);
            $ageInDays = (time() - $lastModified) / (60 * 60 * 24);
            
            if ($ageInDays > $days) {
                // Vérifier si le fichier est encore référencé dans la base de données
                $isReferenced = JobApplication::where('cv_path', $file)
                    ->orWhere('portfolio_path', $file)
                    ->exists();
                
                if (!$isReferenced) {
                    $fileSize = Storage::size($file);
                    Storage::delete($file);
                    
                    $cleanedCount++;
                    $totalSize += $fileSize;
                    
                    $this->line("Supprimé: {$file}");
                }
            }
        }
        
        $totalSizeMB = round($totalSize / 1024 / 1024, 2);
        
        $this->info("Nettoyage terminé: {$cleanedCount} fichiers supprimés ({$totalSizeMB} MB libérés)");
        
        return Command::SUCCESS;
    }
}
