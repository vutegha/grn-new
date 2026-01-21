<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterSubscriptionRequest;
use Illuminate\Http\Request;
use App\Http\Requests\JobApplicationRequest;
use App\Http\Requests\ContactRequest;
use App\Models\Publication;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Actualite;
use App\Models\Media;
use App\Models\Service;
use App\Models\Rapport;
use App\Models\Contact;
use App\Models\Newsletter;
use App\Models\NewsletterPreference;
use App\Models\Evenement;
use App\Models\Projet;
use App\Models\JobOffer;
use App\Models\JobApplication;
use App\Models\Partenaire;
use App\Models\ChercheurAffilie;
use App\Models\{Project, OurService, Event, User};
use App\Mail\{ContactMessage, ContactMessageWithCopy};
use App\Models\EmailSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Log, Storage, DB};
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;

class SiteController extends Controller
{
public function index(Request $request)
{
    $query = Publication::published()->with('auteurs', 'categorie');

    if ($request->filled('auteur')) {
        $query->where('auteur_id', $request->auteur);
    }

    if ($request->filled('categorie')) {
        $query->where('categorie_id', $request->categorie);
    }

    $publications = $query->latest()->take(4)->get();
    
    // Récupérer également les rapports récents publiés (EXCLURE ceux liés à des actualités)
    $rapports = Rapport::published()
        ->whereDoesntHave('actualites') // Exclure les rapports liés à des actualités
        ->latest()
        ->take(4)
        ->get();
    
    // Combiner publications et rapports pour l'affichage
    $documentsRecents = $publications->merge($rapports)->sortByDesc('created_at')->take(8);

    $auteurs = Auteur::all();
    $categories = Categorie::all();
    // $publications= $this->publications($request);
     $actualites = Actualite::published()
                             ->where(function($query) {
                                 $query->where('en_vedette', true)
                                       ->orWhere('a_la_une', true);
                             })
                             ->latest()
                             ->take(4)
                             ->get();
     $services = Service::published()->get();
     
     // Récupérer les événements pour la sidebar (total de 5)
     // Récupération des événements en vedette et publiés
     $evenementsEnVedette = Evenement::where('en_vedette', true)
                                     ->where(function($query) {
                                         $query->where('is_published', true)
                                               ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                                     })
                                     ->orderBy('date_evenement', 'asc')
                                     ->take(5)
                                     ->get();
     
     $nombreEvenementsEnVedette = $evenementsEnVedette->count();
     $evenementsAutres = collect();

     // Si moins de 5 événements en vedette, compléter avec d'autres événements publiés
     if ($nombreEvenementsEnVedette < 5) {
         $nombreEvenementsAutres = 5 - $nombreEvenementsEnVedette;
         
         // Récupérer d'abord les événements à venir non en vedette mais publiés
         $evenementsAVenir = Evenement::where('en_vedette', false)
                                     ->where(function($query) {
                                         $query->where('is_published', true)
                                               ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                                     })
                                     ->aVenir()
                                     ->orderBy('date_evenement', 'asc')
                                     ->take($nombreEvenementsAutres)
                                     ->get();
                                     
         $nombreRestant = $nombreEvenementsAutres - $evenementsAVenir->count();
         
         // Si toujours pas assez, compléter avec des événements passés publiés
         if ($nombreRestant > 0) {
             $evenementsPasses = Evenement::where('en_vedette', false)
                                         ->where(function($query) {
                                             $query->where('is_published', true)
                                                   ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                                         })
                                         ->passe()
                                         ->orderBy('date_evenement', 'desc')
                                         ->take($nombreRestant)
                                         ->get();
             $evenementsAutres = $evenementsAVenir->merge($evenementsPasses);
         } else {
             $evenementsAutres = $evenementsAVenir;
         }
     }

     $evenements = $evenementsEnVedette->merge($evenementsAutres);     // Statistiques des projets (uniquement les projets publiés)
     $statsProjects = [
         'total_projets' => Projet::published()->count(),
         'projets_en_cours' => Projet::published()->where('etat', 'en cours')->count(),
         'projets_termines' => Projet::published()->where('etat', 'terminé')->count(),
         'total_beneficiaires' => Projet::published()->sum('beneficiaires_total') ?: 0,
         'beneficiaires_hommes' => Projet::published()->sum('beneficiaires_hommes') ?: 0,
         'beneficiaires_femmes' => Projet::published()->sum('beneficiaires_femmes') ?: 0,
         'zones_intervention' => Projet::published()->whereNotNull('service_id')->distinct('service_id')->count(),
         'projets_par_secteur' => Service::published()->withCount(['projets' => function($query) {
             $query->published();
         }])->get(),
         'beneficiaires_par_secteur' => Service::published()->with(['projets' => function($query) {
             $query->published();
         }])->get()->map(function($service) {
             return [
                 'nom' => $service->nom,
                 'total_beneficiaires' => $service->projets->sum('beneficiaires_total'),
                 'beneficiaires_hommes' => $service->projets->sum('beneficiaires_hommes'),
                 'beneficiaires_femmes' => $service->projets->sum('beneficiaires_femmes'),
             ];
         })
     ];

     // Récupérer les partenaires avec leurs logos pour l'affichage
     $partenaires = \App\Models\Partenaire::whereNotNull('logo')
                                         ->publics()
                                         ->actifs()
                                         ->ordonnes()
                                         ->get();

    return view('index', compact('documentsRecents', 'auteurs', 'actualites', 'categories', 'services', 'evenements', 'statsProjects', 'partenaires', 'request'));
}


public function actualites(Request $request)
{
    $query = Actualite::published();

    if ($request->filled('categorie')) {
        $query->where('categorie_id', $request->categorie);
    }

    $actualites = $query->latest()->paginate(20)->appends($request->query());

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Actualités', 'url' => null]
    ];

    return view('actualites', compact('actualites', 'breadcrumbs'));
}

public function actualiteShow($slug)
{
    $actualite = Actualite::published()
                    ->with(['categorie', 'service', 'user', 'rapports'])
                    ->where('slug', $slug)
                    ->firstOrFail();
    
    // Récupérer les actualités de la même catégorie ou du même service
    $relatedActualites = collect();
    
    // D'abord, récupérer les actualités de la même catégorie
    if ($actualite->categorie_id) {
        $relatedActualites = Actualite::published()
            ->with(['categorie', 'service'])
            ->where('categorie_id', $actualite->categorie_id)
            ->where('id', '!=', $actualite->id)
            ->latest()
            ->take(8)
            ->get();
    }
    
    // Si pas assez d'actualités de la même catégorie, compléter avec celles du même service
    if ($relatedActualites->count() < 8 && $actualite->service_id) {
        $remainingSlots = 8 - $relatedActualites->count();
        $serviceActualites = Actualite::published()
            ->with(['categorie', 'service'])
            ->where('service_id', $actualite->service_id)
            ->where('id', '!=', $actualite->id)
            ->whereNotIn('id', $relatedActualites->pluck('id'))
            ->latest()
            ->take($remainingSlots)
            ->get();
        
        $relatedActualites = $relatedActualites->merge($serviceActualites);
    }
    
    // Si toujours pas assez, compléter avec les actualités récentes
    if ($relatedActualites->count() < 8) {
        $remainingSlots = 8 - $relatedActualites->count();
        $recentActualites = Actualite::published()
            ->with(['categorie', 'service'])
            ->where('id', '!=', $actualite->id)
            ->whereNotIn('id', $relatedActualites->pluck('id'))
            ->latest()
            ->take($remainingSlots)
            ->get();
        
        $relatedActualites = $relatedActualites->merge($recentActualites);
    }

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Actualités', 'url' => route('site.actualites')],
        ['title' => $actualite->titre, 'url' => null]
    ];

    return view('showactualite', compact('actualite','relatedActualites', 'breadcrumbs'));
}

public function actualiteShowById($id)
{
    $actualite = Actualite::findOrFail($id);
    
    // Rediriger vers l'URL avec slug pour le SEO
    return redirect()->route('site.actualite', ['slug' => $actualite->slug]);
}

public function evenementShow($slug)
{
    $evenement = Evenement::where('slug', $slug)
                          ->where(function($query) {
                              $query->where('is_published', true)
                                    ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                          })
                          ->firstOrFail();
    
    // Récupérer d'autres événements récents publiés pour suggestions
    $autresEvenements = Evenement::where('id', '!=', $evenement->id)
                                 ->where(function($query) {
                                     $query->where('is_published', true)
                                           ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                                 })
                                 ->orderBy('date_evenement', 'desc')
                                 ->take(4)
                                 ->get();
    
    return view('show-evenement', compact('evenement', 'autresEvenements'));
}

public function services(Request $request)
{
    // Récupérer tous les services publiés avec leurs statistiques
    $services = \App\Models\Service::published()
                    ->with(['projets' => function($query) {
                        $query->published();
                    }, 'actualites' => function($query) {
                        $query->published();
                    }])
                    ->get();

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Domaines d\'intervention', 'url' => null]
    ];

    return view('services', compact('services', 'breadcrumbs'));
}

public function serviceshow($slug)
{
    // Charger le service publié par son slug
    $service = \App\Models\Service::published()->where('slug', $slug)->first();

    // Vérification : si aucun service trouvé, on renvoie quand même un objet vide
    if (!$service) {
        $service = new \App\Models\Service(); // un objet vide
        return view('showservice', compact('service'));
    }

    // Charger les projets publiés avec 4 médias aléatoires pour chaque projet ET les actualités publiées liées
    $service->load([
        'projets' => function($query) {
            $query->published()->with(['medias' => function($mediaQuery) {
                $mediaQuery->inRandomOrder()->limit(4);
            }]);
        },
        'actualites' => function($query) {
            $query->published()->latest()->limit(10); // Les 10 actualités les plus récentes publiées
        }
    ]);

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Domaines d\'intervention', 'url' => route('site.services')],
        ['title' => $service->nom, 'url' => null]
    ];

    return view('showservice', compact('service', 'breadcrumbs'));
}

public function projetShow($slug)
{
    $projet = \App\Models\Projet::published()
        ->where('slug', $slug)
        ->with(['service', 'medias', 'publishedRapports'])
        ->firstOrFail();
    
    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Projets', 'url' => route('site.projets')],
        ['title' => $projet->nom, 'url' => null]
    ];
    
    return view('showprojet', compact('projet', 'breadcrumbs'));
}

public function projets(Request $request)
{
    $query = \App\Models\Projet::published()->with(['service', 'medias']);

    // Filtrer par service si spécifié
    if ($request->filled('service')) {
        $query->where('service_id', $request->service);
    }

    // Filtrer par statut si spécifié
    if ($request->filled('etat')) {
        $query->where('etat', $request->etat);
    }

    $projets = $query->latest()->paginate(12)->appends($request->query());

    // Charger les services publiés pour le filtrage
    $services = \App\Models\Service::published()->get();

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Projets', 'url' => null]
    ];

    return view('projets', compact('projets', 'services', 'request', 'breadcrumbs'));
}

public function serviceProjects($slug)
{
    $service = \App\Models\Service::where('slug', $slug)->firstOrFail();
    
    $projets = $service->projets()->with('medias')->latest()->paginate(12);
    $services = \App\Models\Service::all();
    
    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Domaines d\'intervention', 'url' => route('site.services')],
        ['title' => $service->nom, 'url' => route('site.service.show', $service->slug)],
        ['title' => 'Projets', 'url' => null]
    ];
    
    return view('projets', compact('projets', 'services', 'service', 'breadcrumbs'));
}

public function serviceActualites($slug)
{
    $service = \App\Models\Service::where('slug', $slug)->firstOrFail();
    
    $actualites = $service->actualites()->latest()->paginate(20);
    
    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Domaines d\'intervention', 'url' => route('site.services')],
        ['title' => $service->nom, 'url' => route('site.service.show', $service->slug)],
        ['title' => 'Actualités', 'url' => null]
    ];
    
    return view('actualites', compact('actualites', 'service', 'breadcrumbs'));
}

// public function publicationShow($id)
// {
//     $publication = Publication::with(['auteur', 'categorie'])->findOrFail($id);
//     $fichierPath = storage_path('app/public/' . $publication->fichier_pdf);
//     $extension = strtolower(pathinfo($fichierPath, PATHINFO_EXTENSION));
//     $contenuHtml = null;

//     if (in_array($extension, ['doc', 'docx'])) {
//         $contenuHtml = $this->convertirDocxEnHtml($fichierPath);
//     }
    
//     return view('publication.show', compact('publication', 'contenuHtml', 'extension'));
// }


public function convertirImage()
{
    $publications = \App\Models\Publication::all();

    return view('convert', compact('publications'));
}

public function convertirImageUnique(\App\Models\Publication $publication)
{
    try {
        if (!extension_loaded('imagick')) {
            throw new \Exception("L'extension Imagick n'est pas chargée. Vérifiez la configuration du serveur.");
        }

        $pdfPath = storage_path('app/public/' . $publication->fichier_pdf);
        if (!file_exists($pdfPath)) {
            throw new \Exception("Le fichier PDF n'existe pas à l'emplacement prévu.");
        }

        $thumbName = pathinfo($publication->fichier_pdf, PATHINFO_FILENAME) . '.jpg';
        $thumbPath = storage_path('app/public/thumbnails/' . $thumbName);

        if (!file_exists($thumbPath)) {
            // S'assure que le répertoire existe
            \Storage::disk('public')->makeDirectory('thumbnails');

            // Conversion
            $image = new \Imagick();
            $image->setResolution(150, 150);
            $image->readImage($pdfPath . '[0]');
            $image->setImageFormat('jpg');
            $image->writeImage($thumbPath);
            $image->clear();
            $image->destroy();
        }

        // Succès
        return back()->with('alert', "<span class='alert alert-success'>Image générée avec succès pour : {$publication->titre}</span>");

    } catch (\Exception $e) {
        // Log en plus pour dev / production
        \Log::error("Erreur génération thumbnail PDF (ID {$publication->id}): " . $e->getMessage());

        return back()->with('alert', "<span class='alert alert-danger'>Erreur lors de la génération : {$e->getMessage()}</span>");
    }
}





// public function CovnertImage(){
//     $publication=Publication::All();
//     return view('convert', compact('publications'));
// }

public function publications(Request $request)
{
    // Récupérer les publications
    $queryPublications = Publication::published()->with('auteurs', 'categorie');
    
    // Récupérer les rapports (EXCLURE ceux liés à des actualités)
    $queryRapports = Rapport::published()
        ->with('categorie')
        ->whereDoesntHave('actualites'); // Exclure les rapports liés à des actualités

    // Filtre de recherche par texte
    if ($request->filled('q')) {
        $searchTerm = $request->input('q');
        
        $queryPublications->where(function($query) use ($searchTerm) {
            $query->where('titre', 'like', "%{$searchTerm}%")
                  ->orWhere('resume', 'like', "%{$searchTerm}%")
                  ->orWhere('contenu', 'like', "%{$searchTerm}%");
        });
        
        $queryRapports->where(function($query) use ($searchTerm) {
            $query->where('titre', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
        });
    }

    if ($request->filled('auteur')) {
        $queryPublications->where('auteur_id', $request->auteur);
        // Les rapports n'ont pas d'auteur spécifique, donc on les ignore pour ce filtre
    }

    if ($request->filled('categorie')) {
        $queryPublications->where('categorie_id', $request->categorie);
        $queryRapports->where('categorie_id', $request->categorie);
    }

    // Récupérer les résultats
    $publications = $queryPublications->latest()->get();
    $rapports = $queryRapports->latest()->get();
    
    // Combiner en évitant les conflits d'ID - Ajouter un préfixe unique
    $allDocuments = collect();
    
    // Ajouter les publications avec préfixe
    foreach ($publications as $pub) {
        $pub->unique_id = 'pub_' . $pub->id;
        $allDocuments->push($pub);
    }
    
    // Ajouter les rapports avec préfixe
    foreach ($rapports as $rap) {
        $rap->unique_id = 'rap_' . $rap->id;
        $allDocuments->push($rap);
    }
    
    // Trier par date de création décroissante
    $allDocuments = $allDocuments->sortByDesc('created_at')->values();
    
    // Paginer manuellement les résultats combinés
    $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
    $perPage = 20;
    $currentItems = $allDocuments->slice(($currentPage - 1) * $perPage, $perPage)->values();
    
    $publications = new \Illuminate\Pagination\LengthAwarePaginator(
        $currentItems,
        $allDocuments->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'pageName' => 'page',
        ]
    );
    
    $publications->appends($request->query());

    // charger les catégories
    $categories = Categorie::all();

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Publications', 'url' => null]
    ];

    return view('publications', compact('publications', 'categories', 'request', 'breadcrumbs'));
}




public function search(Request $request)
{
    $start = microtime(true);
    $query = $request->input('q', $request->input('search')); // Support des deux paramètres

    // Vérifier si la requête est vide
    if (empty(trim($query))) {
        return redirect()->back()->with('error', 'Veuillez saisir un terme de recherche.');
    }

    // Publications - Rechercher uniquement dans titre et resume
    $publications = Publication::with('categorie', 'auteurs')
        ->where('titre', 'like', "%{$query}%")
        ->orWhere('resume', 'like', "%{$query}%")
        ->get()
        ->map(function($item) {
            $item->type_global = 'Publication';
            $item->date_global = $item->created_at;
            return $item;
        });

    // Actualités (titre, resume, texte)
    $actualites = Actualite::where('titre', 'like', "%{$query}%")
        ->orWhere('resume', 'like', "%{$query}%")
        ->orWhere('texte', 'like', "%{$query}%")
        ->get()
        ->map(function($item) {
            $item->type_global = 'Actualité';
            $item->date_global = $item->created_at;
            return $item;
        });

    // Rapports - Rechercher uniquement dans titre et description
    $rapports = Rapport::where('titre', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")
        ->get()
        ->map(function($item) {
            $item->type_global = 'Rapport';
            $item->date_global = $item->created_at;
            return $item;
        });

    // Projets
    $projets = \App\Models\Projet::with('service')
        ->where('nom', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")
        ->orWhere('resume', 'like', "%{$query}%")
        ->get()
        ->map(function($item) {
            $item->type_global = 'Projet';
            $item->date_global = $item->created_at;
            $item->titre = $item->nom; // Pour la compatibilité avec la vue
            return $item;
        });

    // Fusionner tout et trier par date décroissante
    $results = $publications->merge($actualites)->merge($rapports)->merge($projets)
        ->sortByDesc('date_global')->values();

    // Pagination manuelle
    $page = $request->get('page', 1);
    $perPage = 12;
    $paginated = new LengthAwarePaginator(
        $results->forPage($page, $perPage),
        $results->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    // Temps d'exécution
    $elapsed = round(microtime(true) - $start, 2);

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Recherche', 'url' => null]
    ];

    return view('search_results', [
        'results' => $paginated,
        'query' => $query,
        'elapsed' => $elapsed,
        'totalResults' => $results->count(),
        'breadcrumbs' => $breadcrumbs
    ]);
}




public function convertirDocxEnHtml($fileUrl)
{
    if (!file_exists($fileUrl)) {
        return '<p>Fichier introuvable.</p>';
    }
    
    // Vérification du type mime réel
    $expectedMime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    if (mime_content_type($fileUrl) !== $expectedMime) {
        return '<p>Le fichier fourni n’est pas un fichier .docx valide (type mime incorrect).</p>';
    }

    try {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($fileUrl);
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

        // Capture la sortie HTML générée
        ob_start();
        $writer->save('php://output');
        $contenuHtml = ob_get_clean();

        return $contenuHtml;
    } catch (\Exception $e) {
        return '<p>Erreur lors de la lecture du fichier Word : ' . $e->getMessage() . '</p>';
    }
}

public function galerie(Request $request)
{
    // $medias = Media::inRandomOrder()->get(); // Aléatoire
    $query = Media::published(); // Utiliser le scope pour récupérer les médias publiés et publics

    if ($request->has('type') && in_array($request->type, ['image', 'video'])) {
        $query->where('type', $request->type);
    }

    $medias = $query->orderBy('created_at', 'desc')->paginate(20);

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Galerie', 'url' => null]
    ];

    // return view('admin.media.index', compact('medias'));
    return view('galerie', compact('medias', 'breadcrumbs'));
}

/**
 * Télécharger un média publié
 */
public function downloadMedia($id)
{
    $media = Media::published()
                  ->where('id', $id)
                  ->firstOrFail();

    $filePath = storage_path('app/public/' . $media->medias);
    
    if (!file_exists($filePath)) {
        abort(404, 'Fichier non trouvé');
    }

    $fileName = $media->titre ? Str::slug($media->titre) : 'media';
    $extension = pathinfo($media->medias, PATHINFO_EXTENSION);
    $downloadName = $fileName . '.' . $extension;

    return response()->download($filePath, $downloadName);
}



public function publicationShow($slug)
{
    // Essayer de trouver une publication d'abord
    $publication = Publication::published()
        ->with(['auteurs', 'categorie'])
        ->where('slug', $slug)
        ->first();

    // Si pas trouvé, chercher dans les rapports
    if (!$publication) {
        $rapport = Rapport::published()
            ->with(['categorie'])
            ->where('slug', $slug)
            ->first();
            
        if ($rapport) {
            // Transformer le rapport en format compatible avec la vue
            $publication = $rapport;
            $publication->fichier_pdf = $rapport->fichier; // Mapper le champ fichier vers fichier_pdf
            $publication->resume = $rapport->description; // Mapper description vers resume
            $publication->auteurs = collect(); // Les rapports n'ont pas d'auteurs
        }
    }
    
    if (!$publication) {
        abort(404, 'Document non trouvé');
    }

    $fichierPath = storage_path('app/public/' . $publication->fichier_pdf);
    $extension = strtolower(pathinfo($fichierPath, PATHINFO_EXTENSION));
    $fileUrl = Storage::url($publication->fichier_pdf);

    $autresPublications = collect();
    $auteur = null;
    $contenuHtml = null;

    if (in_array($extension, ['doc', 'docx'])) {
        $contenuHtml = $this->convertirDocxEnHtml($fichierPath);
    }

    // Gérer les relations many-to-many avec auteurs (seulement pour les vraies publications)
    if ($publication->auteurs && $publication->auteurs->count() > 0) {
        // Prendre le premier auteur pour les publications similaires
        $premierAuteur = $publication->auteurs->first();
        
        $autresPublications = $premierAuteur->publications()
            ->published()
            ->where('publications.id', '!=', $publication->id)
            ->orderBy('publications.created_at', 'desc')
            ->take(5)
            ->get();
    }

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Publications', 'url' => route('site.publications')],
        ['title' => $publication->titre, 'url' => null]
    ];

    return view('showpublication', compact(
        'publication', 'contenuHtml', 'auteur',
        'extension', 'fileUrl', 'autresPublications', 'breadcrumbs'
    ));
}


//      public function convertirDocxEnHtml($fileUrl)
// {
//     if (!file_exists($fileUrl)) {
//         return '<p>Fichier introuvable.</p>';
//     }
//     // Vérification du type mime réel
//     $expectedMime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
//     if (mime_content_type($fileUrl) !== $expectedMime) {
//         return '<p>Le fichier fourni n’est pas un fichier .docx valide (type mime incorrect).</p>';
//     }

//     try {
//         $phpWord = IOFactory::load($fileUrl);
//         $writer = IOFactory::createWriter($phpWord, 'HTML');

//         // Capture la sortie HTML générée
//         ob_start();
//         $writer->save('php://output');
//         $contenuHtml = ob_get_clean();

//         return $contenuHtml;
//     } catch (\Exception $e) {
//         return '<p>Erreur lors de la lecture du fichier Word : ' . $e->getMessage() . '</p>';
//     }
// }

public function about()
{
    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'À propos', 'url' => null]
    ];
    
    return view('about', compact('breadcrumbs'));
}

public function contact()
{
    // R\u00e9cup\u00e9rer les informations de contact
    $contactInfos = \App\Models\ContactInfo::actif()->ordered()->get()->groupBy('type');
    
    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Contact', 'url' => null]
    ];
    
    return view('contact', compact('breadcrumbs', 'contactInfos'));
}

public function storeContact(ContactRequest $request)
{
    try {
        $contact = null;
        $emailResult = null;
        
        // Utiliser une transaction pour s'assurer que tout se passe bien
        DB::transaction(function () use ($request, &$contact, &$emailResult) {
            // 1. Enregistrer le message de contact
            $contact = Contact::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'sujet' => $request->sujet,
                'message' => $request->message,
                'statut' => 'nouveau'
            ]);

            // 2. Ajouter automatiquement l'email à la newsletter (si pas déjà présent)
            $newsletter = Newsletter::firstOrCreate(['email' => $request->email]);

            // 3. Envoyer les emails avec le système de copie
            $emailResult = ContactMessageWithCopy::sendToConfiguredEmails($contact);
        });

        
        // Vérifier le résultat de l'envoi d'email
        if ($emailResult && $emailResult['success']) {
            $successMessage = 'Votre message a été envoyé avec succès ! ' .
                            'Nous vous répondrons dans les plus brefs délais. ' .
                            'Un email de confirmation vous a été envoyé. ' .
                            'Vous avez également été ajouté à notre liste de diffusion.';
                            
            // Log du succès pour le suivi
            Log::info('Message de contact envoyé avec succès', [
                'contact_id' => $contact->id,
                'total_emails_sent' => $emailResult['total_sent']
            ]);
        } else {
            $successMessage = 'Votre message a été enregistré avec succès ! ' .
                            'Cependant, il y a eu un problème avec l\'envoi automatique des emails. ' .
                            'Nous vous contacterons directement. ' .
                            'Vous avez été ajouté à notre liste de diffusion.';
                            
            // Log de l'erreur pour investigation
            Log::warning('Échec partiel envoi email contact', [
                'contact_id' => $contact->id,
                'email_error' => $emailResult['error'] ?? 'Erreur inconnue'
            ]);
        }

        return redirect()->route('site.contact')->with('success', $successMessage);

    } catch (\Exception $e) {
        Log::error('Erreur complète lors de la soumission du contact', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->except(['_token'])
        ]);
        
        return redirect()->route('site.contact')
                         ->with('error', 'Une erreur s\'est produite lors de l\'envoi de votre message. Veuillez réessayer ou nous contacter directement.')
                         ->withInput();
    }
}

/**
 * Gérer l'inscription newsletter depuis le footer
 */
public function subscribeNewsletter(NewsletterSubscriptionRequest $request)
{
    // Debug log pour vérifier si la méthode est appelée
    \Log::info('🔍 Newsletter subscription method called', [
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'data' => $request->all()
    ]);
    
    try {
        // Protection contre les attaques par force brute
        $ip = $request->ip();
        $cacheKey = 'newsletter_attempts_' . $ip;
        $attempts = cache()->get($cacheKey, 0);
        
        if ($attempts > 10) { // Max 10 tentatives par heure par IP
            $redirectUrl = $request->input('redirect_url');
            $redirect = $redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL) 
                ? redirect($redirectUrl) 
                : back();
            return $redirect->withErrors(['email' => 'Trop de tentatives. Réessayez dans une heure.'])
                            ->withInput($request->only('email', 'nom'));
        }
        
        cache()->put($cacheKey, $attempts + 1, now()->addHour());
        
        // Protection honeypot - Si les champs cachés sont remplis, c'est un bot
        if (!empty($request->website) || !empty($request->phone)) {
            \Log::warning('Bot détecté lors de l\'inscription newsletter', [
                'ip' => $ip,
                'user_agent' => $request->userAgent()
            ]);
            // Ne pas révéler la détection du bot
            $redirectUrl = $request->input('redirect_url');
            $redirect = $redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL) 
                ? redirect($redirectUrl) 
                : back();
            return $redirect->with('success', 'Inscription réussie ! Vous recevrez bientôt nos actualités.');
        }

        \Log::info('Newsletter subscription attempt', [
            'email' => $request->validated('email'),
            'nom' => $request->validated('nom'),
            'preferences' => $request->validated('preferences'),
            'ip' => $ip,
            'user_agent' => substr($request->userAgent(), 0, 200)
        ]);

        // Utilisation des données validées et nettoyées
        $validatedData = $request->validated();

        // Préparer les préférences par défaut si aucune n'est fournie
        $preferences = ['actualites' => true, 'publications' => true]; // Valeurs par défaut
        
        if (!empty($validatedData['preferences']) && is_array($validatedData['preferences'])) {
            // Si des préférences sont envoyées, les utiliser
            $preferences = [];
            foreach(['actualites', 'publications', 'rapports', 'evenements', 'projets'] as $type) {
                $preferences[$type] = in_array($type, $validatedData['preferences']);
            }
            
            // S'assurer qu'au moins une préférence est activée
            if (!array_filter($preferences)) {
                $preferences = ['actualites' => true, 'publications' => true];
            }
        }

        \Log::info('Prepared preferences', ['preferences' => $preferences]);

        // Utilisation de transactions pour garantir l'intégrité
        \DB::beginTransaction();
        
        try {
            // Vérifier si l'email existe déjà avec requête préparée
            $existing = \DB::table('newsletters')
                          ->where('email', $validatedData['email'])
                          ->lockForUpdate() // Verrouillage pour éviter les conditions de course
                          ->first();
            
            if ($existing) {
                if ($existing->actif) {
                    \DB::rollback();
                    \Log::info('Newsletter subscription attempt for existing active user', ['email' => $validatedData['email']]);
                    
                    $redirectUrl = $request->input('redirect_url');
                    $redirect = $redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL) 
                        ? redirect($redirectUrl) 
                        : back();
                    return $redirect->with('info', 'Cette adresse email est déjà inscrite à notre newsletter. Vous recevez déjà nos actualités !');
                } else {
                    // Réactiver l'abonnement avec requête préparée
                    \DB::table('newsletters')
                        ->where('email', $validatedData['email'])
                        ->update([
                            'actif' => 1,
                            'nom' => $validatedData['nom'] ?: $existing->nom ?: 'Abonné',
                            'preferences' => json_encode($preferences),
                            'updated_at' => now(),
                        ]);
                    
                    \DB::commit();
                    \Log::info('Newsletter subscription reactivated', ['email' => $validatedData['email']]);
                    
                    $redirectUrl = $request->input('redirect_url');
                    $redirect = $redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL) 
                        ? redirect($redirectUrl) 
                        : back();
                    return $redirect->with('success', 'Votre abonnement à la newsletter a été réactivé avec succès !');
                }
            }

            // Nouvelle inscription avec requête préparée
            $token = bin2hex(random_bytes(32));
            $inserted = \DB::table('newsletters')->insert([
                'email' => $validatedData['email'],
                'nom' => $validatedData['nom'] ?: 'Abonné',
                'token' => $token,
                'actif' => 1,
                'preferences' => json_encode($preferences),
                'emails_sent_count' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if ($inserted) {
                \DB::commit();
                \Log::info('Newsletter subscription successful', [
                    'email' => $validatedData['email'],
                    'preferences' => $preferences
                ]);
                
                // Envoyer l'email de bienvenue pour les nouvelles inscriptions
                try {
                    // Récupérer l'enregistrement nouvellement créé pour avoir l'ID et le token
                    $newsletter = \DB::table('newsletters')->where('email', $validatedData['email'])->first();
                    
                    if ($newsletter) {
                        // Créer une instance Newsletter pour le service
                        $newsletterModel = new \App\Models\Newsletter();
                        $newsletterModel->fill([
                            'id' => $newsletter->id,
                            'email' => $newsletter->email,
                            'nom' => $newsletter->nom,
                            'token' => $newsletter->token,
                            'actif' => $newsletter->actif,
                            'preferences' => json_decode($newsletter->preferences, true)
                        ]);
                        $newsletterModel->exists = true; // Indiquer que c'est un modèle existant
                        
                        $newsletterService = app(\App\Services\NewsletterService::class);
                        $emailSent = $newsletterService->sendWelcomeEmail($newsletterModel);
                        
                        if ($emailSent) {
                            \Log::info('Newsletter welcome email sent successfully', ['email' => $validatedData['email']]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Newsletter welcome email failed', [
                        'email' => $validatedData['email'],
                        'error' => $e->getMessage()
                    ]);
                    // Ne pas faire échouer l'inscription si l'email échoue
                }
                
                // Utiliser l'URL de redirection fournie ou back() en fallback
                $redirectUrl = $request->input('redirect_url');
                $redirect = $redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL) 
                    ? redirect($redirectUrl) 
                    : back();
                
                return $redirect->with('success', 'Inscription réussie ! Merci de vous être abonné à notre newsletter.');
            } else {
                \DB::rollback();
                \Log::error('Newsletter subscription insert failed');
                
                $redirectUrl = $request->input('redirect_url');
                $redirect = $redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL) 
                    ? redirect($redirectUrl) 
                    : back();
                return $redirect->with('error', 'Erreur lors de l\'enregistrement.');
            }        } catch (\Exception $e) {
            \DB::rollback(); // Rollback de la transaction en cas d'erreur
            throw $e; // Relancer l'exception pour les catch suivants
        }
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::warning('Newsletter subscription validation failed', [
            'email' => $request->email ?? 'unknown',
            'errors' => $e->errors()
        ]);
        
        $redirectUrl = $request->input('redirect_url');
        $redirect = $redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL) 
            ? redirect($redirectUrl) 
            : back();
        return $redirect->with('error', 'Veuillez vérifier l\'adresse email saisie.');
        
    } catch (\Exception $e) {
        \Log::error('Newsletter subscription error', [
            'email' => $request->email ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $redirectUrl = $request->input('redirect_url');
        $redirect = $redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL) 
            ? redirect($redirectUrl) 
            : back();
        return $redirect->with('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
    }
}

public function workWithUs()
{
    // Données d'impact réelles depuis la base de données
    $impactStats = [
        'projets_actifs' => Projet::where('etat', 'en_cours')->count(),
        'projets_termines' => Projet::where('etat', 'termine')->count(),
        'publications' => Publication::count(),
        'rapports' => Rapport::count(),
        'services' => Service::count(),
        'actualites' => Actualite::count(),
        'beneficiaires' => Projet::sum('beneficiaires_total'), // Somme des bénéficiaires de tous les projets
        'partenaires' => Partenaire::actifs()->count(), // Nombre de partenaires actifs
    ];

    // Récupérer les offres d'emploi actives
    $jobOffers = JobOffer::active()
        ->orderBy('is_featured', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

    // Statistiques des offres d'emploi
    $jobStats = [
        'total_offers' => JobOffer::count(),
        'active_offers' => JobOffer::active()->count(),
        'expired_offers' => JobOffer::expired()->count(),
        'internal_offers' => JobOffer::bySource(JobOffer::SOURCE_INTERNAL)->active()->count(),
        'partner_offers' => JobOffer::bySource(JobOffer::SOURCE_PARTNER)->active()->count(),
        'total_applications' => JobApplication::count(),
        'pending_applications' => JobApplication::pending()->count(),
    ];

    $breadcrumbData = [
        'currentPage' => 'Travailler avec nous',
        'breadcrumbs' => [
            ['name' => 'Accueil', 'url' => route('site.home')],
            ['name' => 'Travailler avec nous', 'url' => null]
        ]
    ];

    return view('work-with-us', compact('impactStats', 'jobOffers', 'jobStats') + $breadcrumbData);
}

/**
 * Afficher la page des partenariats
 */
public function partenariats()
{
    $breadcrumbData = [
        'currentPage' => 'Partenariats & Collaborations',
        'breadcrumbs' => [
            ['name' => 'Accueil', 'url' => route('site.home')],
            ['name' => 'Partenariats', 'url' => null]
        ]
    ];

    // Récupérer les partenaires publics
    $partenaires = Partenaire::publics()->actifs()->ordonnes()->get();
    
    // Récupérer les chercheurs affiliés
    $chercheurs = ChercheurAffilie::actifs()->publics()->orderBy('ordre_affichage')->get();
    
    // Statistiques d'impact basées sur les données réelles
    $partnershipStats = [
        'universites_partenaires' => Partenaire::parType('universite')->actifs()->count(),
        'organisations_collaboratrices' => Partenaire::parType('organisation_internationale')->actifs()->count(),
        'chercheurs_affilies' => ChercheurAffilie::actifs()->count(),
        'projets_collaboratifs' => Projet::count() // Tous les projets sont considérés comme collaboratifs dans ce contexte
    ];

    return view('partenariats', compact('partnershipStats', 'partenaires', 'chercheurs') + $breadcrumbData);
}

public function showJobApplication(JobOffer $job)
{
    // Vérifier si l'offre est encore active
    if ($job->is_expired || $job->status !== 'active') {
        return redirect()->route('site.work-with-us')->with('error', 'Cette offre d\'emploi n\'est plus disponible.');
    }

    // Incrémenter le nombre de vues
    $job->incrementViews();

    $breadcrumbData = [
        'currentPage' => 'Candidature - ' . $job->title,
        'breadcrumbs' => [
            ['name' => 'Accueil', 'url' => route('site.home')],
            ['name' => 'Travailler avec nous', 'url' => route('site.work-with-us')],
            ['name' => 'Candidature', 'url' => null]
        ]
    ];

    return view('job-application', compact('job') + $breadcrumbData);
}

public function submitJobApplication(JobApplicationRequest $request, JobOffer $job)
{
    // Vérifier si l'offre est encore active
    if ($job->is_expired || $job->status !== 'active') {
        return redirect()->route('site.work-with-us')->with('error', 'Cette offre d\'emploi n\'est plus disponible.');
    }

    // Rate limiting : max 3 candidatures par IP par heure
    $clientIp = $request->ip();
    $cacheKey = 'job_applications_' . md5($clientIp);
    $attemptCount = cache()->get($cacheKey, 0);
    
    if ($attemptCount >= 3) {
        return back()->with('error', 'Trop de tentatives de candidature. Veuillez réessayer dans une heure.');
    }

    // Vérifier le honeypot anti-spam
    if ($request->filled('website')) {
        \Log::warning('Tentative de spam détectée', [
            'ip' => $clientIp,
            'user_agent' => $request->userAgent(),
            'job_id' => $job->id
        ]);
        return back()->with('error', 'Erreur lors de la soumission. Veuillez réessayer.');
    }

    try {
        // Upload sécurisé des fichiers
        $cvPath = null;
        $portfolioPath = null;

        if ($request->hasFile('cv_file')) {
            $cvPath = $this->storeFileSecurely($request->file('cv_file'), 'cv', $job->id, $request->last_name);
            if (!$cvPath) {
                return back()->with('error', 'Erreur lors du téléchargement du CV. Fichier non autorisé.');
            }
        }

        if ($request->hasFile('portfolio_file')) {
            $portfolioPath = $this->storeFileSecurely($request->file('portfolio_file'), 'portfolio', $job->id, $request->last_name);
            if (!$portfolioPath) {
                return back()->with('error', 'Erreur lors du téléchargement du portfolio. Fichier non autorisé.');
            }
        }

        // Créer la candidature avec les données validées et nettoyées
        $application = JobApplication::create([
            'job_offer_id' => $job->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'education' => $request->education,
            'experience' => $request->experience,
            'skills' => $request->skills,
            'motivation_letter' => $request->motivation_letter,
            'criteria_responses' => $request->criteria_responses,
            'cv_path' => $cvPath,
            'portfolio_path' => $portfolioPath,
        ]);

        // Incrémenter le compteur de candidatures
        $job->incrementApplications();

        // Incrémenter le compteur rate limiting
        cache()->put($cacheKey, $attemptCount + 1, now()->addHour());

        // Logger l'activité pour audit
        \Log::info('Nouvelle candidature soumise', [
            'application_id' => $application->id,
            'job_id' => $job->id,
            'email' => $request->email,
            'ip' => $clientIp
        ]);

        // Optionnel : Envoyer un email de confirmation
        // Mail::to($request->email)->send(new JobApplicationConfirmation($application));

        return redirect()->route('site.work-with-us')
            ->with('success', 'Votre candidature a été soumise avec succès. Nous vous contacterons bientôt.');

    } catch (\Exception $e) {
        \Log::error('Erreur lors de la soumission de candidature', [
            'error' => $e->getMessage(),
            'job_id' => $job->id,
            'email' => $request->email,
            'ip' => $clientIp
        ]);
        return back()->with('error', 'Une erreur est survenue lors de la soumission de votre candidature. Veuillez réessayer.');
    }
}

/**
 * Stockage sécurisé des fichiers avec validation avancée
 */
private function storeFileSecurely($file, $type, $jobId, $lastName)
{
    try {
        // Validation supplémentaire du fichier
        if (!$this->isFileSecure($file)) {
            return null;
        }

        // Générer un nom de fichier cryptographiquement sécurisé
        $extension = $file->getClientOriginalExtension();
        $hash = hash('sha256', $file->getContent() . time() . random_bytes(16));
        $fileName = $hash . '_' . $type . '_' . $jobId . '.' . $extension;
        
        // Stocker dans un répertoire privé (non accessible publiquement)
        $path = $file->storeAs('private/job-applications/' . $type, $fileName);
        
        return $path;
        
    } catch (\Exception $e) {
        \Log::error('Erreur stockage fichier', [
            'error' => $e->getMessage(),
            'file_type' => $type,
            'job_id' => $jobId
        ]);
        return null;
    }
}

/**
 * Validation de sécurité avancée des fichiers
 */
private function isFileSecure($file)
{
    // Vérifier la taille réelle
    if ($file->getSize() > 10485760) { // 10MB max
        return false;
    }
    
    // Vérifier le type MIME réel
    $allowedMimes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/zip',
        'application/x-zip-compressed'
    ];
    
    if (!in_array($file->getMimeType(), $allowedMimes)) {
        return false;
    }
    
    // Vérifier que ce n'est pas un exécutable déguisé
    $content = $file->getContent();
    $dangerousSignatures = [
        'MZ', // Exécutable Windows
        '<!DOCTYPE html', // HTML
        '<script', // JavaScript
        '<?php', // PHP
        'PK', // ZIP potentiel avec exécutable
    ];
    
    foreach ($dangerousSignatures as $signature) {
        if (strpos($content, $signature) === 0) {
            \Log::warning('Fichier suspect détecté', [
                'signature' => $signature,
                'mime' => $file->getMimeType()
            ]);
            return false;
        }
    }
    
    return true;
}

/**
 * Télécharger le document d'appel d'offre de manière sécurisée
 */
public function downloadJobDocument(JobOffer $job)
{
    try {
        // Vérifier que l'offre a un document
        if (!$job->hasDocumentAppelOffre()) {
            abort(404, 'Document non trouvé');
        }

        // Vérifier que le fichier existe dans le storage
        if (!Storage::disk('public')->exists($job->document_appel_offre)) {
            abort(404, 'Fichier non trouvé');
        }

        // Déterminer le nom du fichier à télécharger
        $fileName = $job->document_appel_offre_nom ?: 'appel_offre_' . $job->id . '.pdf';
        
        // Incrémenter les vues (optionnel, car c'est un téléchargement)
        $job->incrementViews();

        // Retourner le fichier en téléchargement
        return Storage::disk('public')->download($job->document_appel_offre, $fileName);

    } catch (\Exception $e) {
        Log::error('Erreur lors du téléchargement du document d\'appel d\'offre: ' . $e->getMessage());
        abort(500, 'Erreur lors du téléchargement');
    }
}

public function evenements()
{
    $evenements = Evenement::where(function($query) {
                                $query->where('is_published', true)
                                      ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                            })
                           ->orderBy('date_evenement', 'desc')
                           ->paginate(12);

    return view('site.evenements', compact('evenements'));
}

/**
 * Afficher tous les rapports publics
 */
public function rapports()
{
    $rapports = Rapport::where('statut', 'publie')
                       ->whereDoesntHave('actualites') // Exclure les rapports liés à des actualités
                       ->latest()
                       ->paginate(12);

    return view('site.rapports', compact('rapports'));
}

/**
 * Afficher un rapport spécifique
 */
public function rapportShow($slug)
{
    $rapport = Rapport::where('slug', $slug)
                      ->where('statut', 'publie')
                      ->firstOrFail();

    // Incrémenter les vues
    $rapport->increment('vues');

    return view('site.rapport', compact('rapport'));
}
   
}