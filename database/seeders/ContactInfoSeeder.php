<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactInfo;

class ContactInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vider la table avant de la remplir pour éviter les doublons
        ContactInfo::truncate();
        
        // Bureau Principal
        ContactInfo::create([
            'type' => 'bureau_principal',
            'nom' => 'Siège Social IRI',
            'titre' => 'Bureau Principal - Nord-Kivu',
            'adresse' => 'Avenue de l\'Université, Quartier Masiani, Cellule Kipriani',
            'ville' => 'Beni',
            'province' => 'Nord-Kivu',
            'pays' => 'RDC',
            'email' => 'iri@ucbc.org',
            'telephone' => '+243 000 000 000',
            'telephone_secondaire' => null,
            'responsable_nom' => null,
            'responsable_fonction' => null,
            'responsable_email' => null,
            'responsable_telephone' => null,
            'description' => 'Siège social de l\'Institut de Recherche et d\'Innovation de l\'Université Chrétienne Bilingue du Congo',
            'horaires' => "Lundi - Vendredi: 8h00 - 17h00\nSamedi: 8h00 - 12h00",
            'latitude' => 0.4917,
            'longitude' => 29.4743,
            'ordre' => 1,
            'actif' => true
        ]);

        // Bureau Régional - Tanganyika (avec Point Focal intégré)
        ContactInfo::create([
            'type' => 'bureau_regional',
            'nom' => 'Bureau de Liaison - Tanganyika',
            'titre' => 'Bureau Régional de Kalemie',
            'adresse' => 'Avenue Industrielle, Quartier Kitali II, Commune de Lukuga',
            'ville' => 'Kalemie',
            'province' => 'Tanganyika',
            'pays' => 'RDC',
            'email' => 'kalemie@iri.ucbc.org',
            'telephone' => '+243 000 000 000',
            'telephone_secondaire' => null,
            'responsable_nom' => 'Dr. Joseph Mukendi',
            'responsable_fonction' => 'Coordinateur Régional',
            'responsable_email' => 'j.mukendi@iri.ucbc.org',
            'responsable_telephone' => '+243 000 000 001',
            'description' => 'Bureau de liaison pour la région du Tanganyika',
            'horaires' => "Lundi - Vendredi: 8h00 - 16h00",
            'latitude' => -5.9475,
            'longitude' => 29.1949,
            'ordre' => 2,
            'actif' => true
        ]);
    }
}
