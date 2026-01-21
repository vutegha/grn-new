<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Publication;
use App\Models\Projet;
use App\Models\Evenement;
use App\Models\Service;
use App\Models\Newsletter;
use App\Models\Contact;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Le dashboard est accessible à tous les utilisateurs admin connectés
        // Mais chaque section sera conditionnellement affichée selon les permissions
        
        // Statistiques générales - selon les permissions de chaque modèle
        $stats = [
            'actualites' => auth()->user()->can('viewAny', Actualite::class) ? Actualite::count() : null,
            'publications' => auth()->user()->can('viewAny', Publication::class) ? Publication::count() : null,
            'projets' => auth()->user()->can('viewAny', Projet::class) ? Projet::count() : null,
            'evenements' => auth()->user()->can('viewAny', Evenement::class) ? Evenement::count() : null,
            'services' => auth()->user()->can('viewAny', Service::class) ? Service::count() : null,
            'newsletters' => auth()->user()->can('manage_newsletter') ? Newsletter::where('actif', true)->count() : null,
            'messages' => auth()->user()->can('viewAny', Contact::class) ? Contact::count() : null,
            'unread_messages' => auth()->user()->can('viewAny', Contact::class) ? Contact::where('statut', 'nouveau')->count() : null,
        ];

        // Statistiques des projets - seulement si autorisé
        $statsProjects = null;
        if (auth()->user()->can('viewAny', Projet::class)) {
            $statsProjects = [
                'total_projets' => Projet::count(),
                'projets_en_cours' => Projet::where('etat', 'en cours')->count(),
                'projets_termines' => Projet::where('etat', 'terminé')->count(),
                'total_beneficiaires' => Projet::sum('beneficiaires_total') ?: 0,
                'beneficiaires_hommes' => Projet::sum('beneficiaires_hommes') ?: 0,
                'beneficiaires_femmes' => Projet::sum('beneficiaires_femmes') ?: 0,
            ];
        }

        // Statistiques des événements - seulement si autorisé
        $statsEvenements = null;
        if (auth()->user()->can('viewAny', Evenement::class)) {
            $statsEvenements = [
                'total_evenements' => Evenement::count(),
                'evenements_a_venir' => Evenement::aVenir()->count(),
                'evenements_passes' => Evenement::passe()->count(),
                'evenements_en_cours' => Evenement::enCours()->count(),
            ];
        }

        // Dernières activités - conditionnelles selon les permissions
        $dernieresActualites = auth()->user()->can('viewAny', Actualite::class) ? Actualite::latest()->take(5)->get() : collect();
        $dernieresPublications = auth()->user()->can('viewAny', Publication::class) ? Publication::latest()->take(5)->get() : collect();
        $derniersProjets = auth()->user()->can('viewAny', Projet::class) ? Projet::latest()->take(5)->get() : collect();
        $derniersEvenements = auth()->user()->can('viewAny', Evenement::class) ? Evenement::latest('date_evenement')->take(5)->get() : collect();
        $derniersMessages = auth()->user()->can('viewAny', Contact::class) ? Contact::latest()->take(5)->get() : collect();

        // Prochains événements - seulement si autorisé
        $prochainsEvenements = auth()->user()->can('viewAny', Evenement::class) ? 
            Evenement::aVenir()->orderBy('date_evenement', 'asc')->take(3)->get() : 
            collect();

        // Graphiques mensuels (6 derniers mois) - conditionnels selon les permissions
        $moisLabels = [];
        $actualitesData = [];
        $publicationsData = [];
        $projetsData = [];
        
        // Générer les données seulement si l'utilisateur a les permissions appropriées
        $canViewActualites = auth()->user()->can('viewAny', Actualite::class);
        $canViewPublications = auth()->user()->can('viewAny', Publication::class);
        $canViewProjets = auth()->user()->can('viewAny', Projet::class);
        
        if ($canViewActualites || $canViewPublications || $canViewProjets) {
            for ($i = 5; $i >= 0; $i--) {
                $mois = Carbon::now()->subMonths($i);
                $moisLabels[] = $mois->format('M Y');
                
                $actualitesData[] = $canViewActualites ? 
                    Actualite::whereYear('created_at', $mois->year)->whereMonth('created_at', $mois->month)->count() : 0;
                                            
                $publicationsData[] = $canViewPublications ? 
                    Publication::whereYear('created_at', $mois->year)->whereMonth('created_at', $mois->month)->count() : 0;
                                               
                $projetsData[] = $canViewProjets ? 
                    Projet::whereYear('created_at', $mois->year)->whereMonth('created_at', $mois->month)->count() : 0;
            }
        }

        return view('admin.dashboard', compact(
            'stats',
            'statsProjects', 
            'statsEvenements',
            'dernieresActualites',
            'dernieresPublications',
            'derniersProjets',
            'derniersEvenements',
            'derniersMessages',
            'prochainsEvenements',
            'moisLabels',
            'actualitesData',
            'publicationsData',
            'projetsData'
        ));
    }
}