<?php

namespace App\Console\Commands;

use App\Models\Rapport;
use App\Models\RapportDownload;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RapportDownloadStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rapport:download-stats {--top=10 : Nombre de rapports à afficher} {--period=30 : Période en jours}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Affiche les statistiques de téléchargement des rapports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $top = $this->option('top');
        $period = $this->option('period');
        
        $this->info("📊 Statistiques de téléchargement des rapports");
        $this->newLine();

        // Statistiques globales
        $this->displayGlobalStats($period);
        $this->newLine();

        // Top rapports téléchargés
        $this->displayTopReports($top, $period);
        $this->newLine();

        // Nouveaux inscrits via téléchargements
        $this->displayNewSubscribers($period);
        
        return Command::SUCCESS;
    }

    /**
     * Affiche les statistiques globales
     */
    private function displayGlobalStats($period)
    {
        $startDate = now()->subDays($period);
        
        $totalDownloads = RapportDownload::where('downloaded_at', '>=', $startDate)->count();
        $uniqueEmails = RapportDownload::where('downloaded_at', '>=', $startDate)
            ->distinct('email')
            ->count('email');
        $uniqueReports = RapportDownload::where('downloaded_at', '>=', $startDate)
            ->distinct('rapport_id')
            ->count('rapport_id');

        $this->info("📈 Statistiques globales (derniers {$period} jours):");
        $this->line("   Total téléchargements: " . $totalDownloads);
        $this->line("   Emails uniques: " . $uniqueEmails);
        $this->line("   Rapports téléchargés: " . $uniqueReports);
    }

    /**
     * Affiche les rapports les plus téléchargés
     */
    private function displayTopReports($limit, $period)
    {
        $startDate = now()->subDays($period);
        
        $topReports = RapportDownload::select('rapport_id', DB::raw('count(*) as download_count'))
            ->where('downloaded_at', '>=', $startDate)
            ->groupBy('rapport_id')
            ->orderBy('download_count', 'desc')
            ->limit($limit)
            ->get();

        $this->info("🏆 Top {$limit} rapports les plus téléchargés:");
        
        if ($topReports->isEmpty()) {
            $this->warn("   Aucun téléchargement trouvé pour cette période.");
            return;
        }

        $headers = ['#', 'Rapport', 'Téléchargements', 'Emails uniques'];
        $rows = [];

        foreach ($topReports as $index => $download) {
            $rapport = Rapport::find($download->rapport_id);
            if (!$rapport) continue;

            $uniqueEmails = RapportDownload::where('rapport_id', $download->rapport_id)
                ->where('downloaded_at', '>=', $startDate)
                ->distinct('email')
                ->count('email');

            $rows[] = [
                $index + 1,
                substr($rapport->titre, 0, 50) . (strlen($rapport->titre) > 50 ? '...' : ''),
                $download->download_count,
                $uniqueEmails
            ];
        }

        $this->table($headers, $rows);
    }

    /**
     * Affiche les nouveaux inscrits via téléchargements
     */
    private function displayNewSubscribers($period)
    {
        $startDate = now()->subDays($period);
        
        $newSubscribers = RapportDownload::select('email')
            ->where('downloaded_at', '>=', $startDate)
            ->distinct('email')
            ->whereNotNull('email')
            ->count();

        $this->info("✉️ Nouveaux contacts potentiels:");
        $this->line("   Emails collectés: " . $newSubscribers);
    }
}
