<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Projet;

class FixBeneficiairesTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projets:fix-beneficiaires-totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalcule et corrige les totaux de bénéficiaires pour tous les projets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Correction des totaux bénéficiaires...');
        $this->newLine();

        $projets = Projet::all();
        $corriges = 0;
        $total = $projets->count();

        $this->info("📊 Nombre de projets à vérifier: {$total}");
        $this->newLine();

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($projets as $projet) {
            $calculManuel = ($projet->beneficiaires_hommes ?? 0) + ($projet->beneficiaires_femmes ?? 0);
            $totalActuel = $projet->beneficiaires_total ?? 0;

            if ($totalActuel != $calculManuel) {
                $projet->update(['beneficiaires_total' => $calculManuel]);
                $corriges++;
                
                $this->newLine();
                $this->comment("✅ Corrigé: {$projet->nom}");
                $this->line("   Hommes: {$projet->beneficiaires_hommes}, Femmes: {$projet->beneficiaires_femmes}");
                $this->line("   Ancien total: {$totalActuel} → Nouveau total: {$calculManuel}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($corriges > 0) {
            $this->info("🎉 Correction terminée ! {$corriges} projet(s) corrigé(s).");
        } else {
            $this->info("✨ Tous les totaux sont déjà corrects !");
        }

        $this->newLine();
        return Command::SUCCESS;
    }
}
