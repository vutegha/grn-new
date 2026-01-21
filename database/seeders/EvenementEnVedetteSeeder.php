<?php

namespace Database\Seeders;

use App\Models\Evenement;
use Illuminate\Database\Seeder;

class EvenementEnVedetteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mettre quelques événements existants en vedette
        $evenements = Evenement::where('statut', 'publie')->take(2)->get();
        
        foreach ($evenements as $evenement) {
            $evenement->update(['en_vedette' => true]);
        }
        
        // Ou créer de nouveaux événements en vedette si nécessaire
        if ($evenements->count() < 2) {
            Evenement::create([
                'titre' => 'Conférence Internationale sur la Recherche Appliquée',
                'resume' => 'Une conférence majeure réunissant les experts en recherche appliquée.',
                'description' => 'Cette conférence internationale rassemblera les meilleurs chercheurs et praticiens dans le domaine de la recherche appliquée pour partager leurs dernières découvertes et innovations.',
                'slug' => 'conference-internationale-recherche-appliquee',
                'date_evenement' => now()->addDays(30),
                'lieu' => 'Centre de Conférence IRI-UCBC',
                'adresse' => 'Beni, Nord-Kivu, RDC',
                'organisateur' => 'IRI-UCBC',
                'statut' => 'publie',
                'type' => 'conference',
                'en_vedette' => true,
            ]);
            
            Evenement::create([
                'titre' => 'Symposium sur l\'Innovation Sociale',
                'resume' => 'Un symposium dédié aux innovations sociales et communautaires.',
                'description' => 'Ce symposium mettra en lumière les innovations sociales qui transforment nos communautés et favorisent le développement durable.',
                'slug' => 'symposium-innovation-sociale',
                'date_evenement' => now()->addDays(45),
                'lieu' => 'Auditorium Principal',
                'adresse' => 'Campus UCBC, Beni',
                'organisateur' => 'IRI-UCBC',
                'statut' => 'publie',
                'type' => 'symposium',
                'en_vedette' => true,
            ]);
        }
    }
}
