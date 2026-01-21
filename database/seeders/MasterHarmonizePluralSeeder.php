<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MasterHarmonizePluralSeeder extends Seeder
{
    /**
     * Exécuter l'harmonisation complète vers le format PLURIEL
     */
    public function run(): void
    {
        $this->command->info("🚀 HARMONISATION COMPLÈTE VERS FORMAT PLURIEL");
        $this->command->info(str_repeat("=", 60));
        $this->command->info("Cette opération va harmoniser TOUS les noms de permissions");
        $this->command->info("vers le format PLURIEL dans:");
        $this->command->info("  • Base de données");
        $this->command->info("  • Policies"); 
        $this->command->info("  • Vues Blade");
        $this->command->info(str_repeat("=", 60));
        
        // 1. Harmoniser les permissions en base de données
        $this->command->info("📊 ÉTAPE 1/3: Base de données");
        $this->call(HarmonizePermissionsPluralSeeder::class);
        
        // 2. Mettre à jour les policies
        $this->command->info("\n🔧 ÉTAPE 2/3: Policies");
        $this->call(UpdatePoliciesPluralSeeder::class);
        
        // 3. Mettre à jour les vues
        $this->command->info("\n👁️ ÉTAPE 3/3: Vues");
        $this->call(UpdateViewsPluralSeeder::class);
        
        $this->command->info("\n" . str_repeat("=", 60));
        $this->command->info("🎉 HARMONISATION PLURIEL TERMINÉE AVEC SUCCÈS!");
        $this->command->info("Toutes les permissions utilisent maintenant le format PLURIEL");
        $this->command->info("L'utilisateur admin@ucbc.org devrait maintenant avoir accès");
        $this->command->info("à tous les boutons et fonctionnalités.");
        $this->command->info(str_repeat("=", 60));
    }
}
