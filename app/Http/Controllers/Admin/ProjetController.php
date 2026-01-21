<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Service;
use App\Events\ProjectCreated;

class ProjetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:viewAny,App\Models\Projet')->only(['index']);
        $this->middleware('can:view,projet')->only(['show']);
        $this->middleware('can:create,App\Models\Projet')->only(['create', 'store']);
        $this->middleware('can:update,projet')->only(['edit', 'update']);
        $this->middleware('can:delete,projet')->only(['destroy']);
        $this->middleware('can:moderate,projet')->only(['publish', 'unpublish']);
    }

    /**
     * Sécurise le contenu HTML en gardant uniquement les balises autorisées
     */
    private function sanitizeHtml($content)
    {
        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><a><img>';
        $content = strip_tags($content, $allowedTags);
        
        // Nettoyer les attributs dangereux
        $content = preg_replace('/on\w+="[^"]*"/i', '', $content);
        $content = preg_replace('/javascript:/i', '', $content);
        
        return $content;
    }

   public function index(Request $request)
{
    
        $this->authorize('viewAny', Projet::class);
$query = Projet::with('service');

    // Recherche textuelle
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('resume', 'like', "%{$search}%");
        });
    }

    // Filtre par état
    if ($request->filled('etat')) {
        $query->where('etat', $request->etat);
    }

    // Filtre par publication
    if ($request->filled('is_published')) {
        $query->where('is_published', $request->is_published == '1');
    }

    // Filtre par service
    if ($request->filled('service_id')) {
        $query->where('service_id', $request->service_id);
    }

    // Filtre par année
    if ($request->filled('annee')) {
        $query->whereYear('date_debut', $request->annee);
    }

    // Filtre par dates de création
    if ($request->filled('created_at_from')) {
        $query->whereDate('created_at', '>=', $request->created_at_from);
    }

    if ($request->filled('created_at_to')) {
        $query->whereDate('created_at', '<=', $request->created_at_to);
    }

    $projets = $query->latest()->paginate(12);

    // Pour le filtre année
    $anneesDisponibles = Projet::selectRaw('YEAR(date_debut) as annee')
                                ->whereNotNull('date_debut')
                                ->distinct()
                                ->orderBy('annee', 'desc')
                                ->pluck('annee')
                                ->toArray();

    // Calcul du budget total
    $budgetTotal = Projet::whereNotNull('budget')->sum('budget');

    // Services pour les filtres
    $services = Service::orderBy('nom')->get();

    return view('admin.projets.index', compact('projets', 'anneesDisponibles', 'budgetTotal', 'services'));
}

    /**
     * Recherche AJAX pour les projets
     */
    public function search(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $query = Projet::with('service');

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('resume', 'like', "%{$search}%")
                  ->orWhereHas('service', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par état
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        // Filtre par publication
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published == '1');
        }

        // Filtre par service
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Filtre par année
        if ($request->filled('annee')) {
            $query->whereYear('date_debut', $request->annee);
        }

        // Filtre par dates de création
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->created_at_from);
        }

        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->created_at_to);
        }

        $projets = $query->latest()->paginate(12);

        // Retourner le HTML partiel pour les résultats
        $html = view('admin.projets.partials.projects-list', compact('projets'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'total' => $projets->total(),
            'current_page' => $projets->currentPage(),
            'last_page' => $projets->lastPage(),
            'per_page' => $projets->perPage(),
            'from' => $projets->firstItem(),
            'to' => $projets->lastItem()
        ]);
    }

    public function create()
    {
        
        $this->authorize('create', Projet::class);
$services=Service::All();
        return view('admin.projets.create', compact('services'));
    }

    public function store(Request $request)
    {
        
        $this->authorize('create', Projet::class);
try {
            // Vérification anti-CSRF et honeypot améliorée
            if ($request->has('website') && !empty(trim($request->input('website')))) {
                // Log pour debug - peut être un faux positif
                \Log::warning('Honeypot déclenché - vérification approfondie', [
                    'website_value' => $request->input('website'),
                    'user_agent' => $request->userAgent(),
                    'user_id' => auth()->id(),
                    'ip' => $request->ip(),
                    'all_inputs' => $request->except(['_token', 'image'])
                ]);
                
                // Vérifier si c'est vraiment un bot ou un faux positif
                $websiteValue = trim($request->input('website'));
                $suspiciousPatterns = [
                    'http://', 'https://', 'www.', '.com', '.org', '.net',
                    'viagra', 'casino', 'poker', 'loan', 'mortgage'
                ];
                
                $isSuspicious = false;
                foreach ($suspiciousPatterns as $pattern) {
                    if (stripos($websiteValue, $pattern) !== false) {
                        $isSuspicious = true;
                        break;
                    }
                }
                
                // Si vraiment suspect, bloquer
                if ($isSuspicious || strlen($websiteValue) > 10) {
                    abort(422, 'Requête suspecte détectée.');
                } else {
                    // Sinon, juste logger et continuer (probablement un faux positif)
                    \Log::info('Honeypot: faux positif détecté, traitement continué', [
                        'website_value' => $websiteValue,
                        'user_id' => auth()->id()
                    ]);
                }
            }

            // Rate limiting par IP et utilisateur
            $key = 'projet_creation_' . auth()->id() . '_' . $request->ip();
            if (cache()->has($key)) {
                abort(429, 'Trop de tentatives. Veuillez patienter avant de créer un nouveau projet.');
            }
            cache()->put($key, true, now()->addMinutes(2));

            // Validation des données avec messages personnalisés et sécurité renforcée
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'required|string|max:10000',
                'date_debut' => 'required|date|before_or_equal:' . now()->addYears(5)->toDateString(),
                'service_id' => 'required|exists:services,id',
                'resume' => 'nullable|string|max:1000',
                'date_fin' => 'nullable|date|after_or_equal:date_debut|before_or_equal:' . now()->addYears(10)->toDateString(),
                'etat' => 'required|string|in:en cours,terminé,suspendu',
                'budget' => 'nullable|numeric|min:0|max:999999999.99',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240|dimensions:min_width=400,min_height=300',
                'beneficiaires_hommes' => 'nullable|integer|min:0|max:1000000',
                'beneficiaires_femmes' => 'nullable|integer|min:0|max:1000000',
                'beneficiaires_enfants' => 'nullable|integer|min:0|max:1000000',
                'beneficiaires_total' => 'nullable|integer|min:0|max:1000000',
            ], [
                'nom.required' => 'Le nom du projet est obligatoire.',
                'nom.max' => 'Le nom du projet ne peut pas dépasser 255 caractères.',
                'description.required' => 'La description du projet est obligatoire.',
                'description.min' => 'La description doit contenir au moins 50 caractères.',
                'description.max' => 'La description ne peut pas dépasser 10 000 caractères.',
                'date_debut.required' => 'La date de début est obligatoire.',
                'date_debut.date' => 'La date de début doit être une date valide.',
                'date_debut.before_or_equal' => 'La date de début ne peut pas être dans plus de 5 ans.',
                'service_id.required' => 'Veuillez sélectionner un service responsable.',
                'service_id.exists' => 'Le service sélectionné n\'existe pas.',
                'resume.max' => 'Le résumé ne peut pas dépasser 1000 caractères.',
                'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
                'date_fin.before_or_equal' => 'La date de fin ne peut pas être dans plus de 10 ans.',
                'etat.required' => 'L\'état du projet est obligatoire.',
                'etat.in' => 'L\'état du projet doit être : en cours, terminé ou suspendu.',
                'budget.numeric' => 'Le budget doit être un nombre.',
                'budget.min' => 'Le budget ne peut pas être négatif.',
                'budget.max' => 'Le budget ne peut pas dépasser 999 999 999,99.',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format : JPG, JPEG, PNG, GIF ou WebP.',
                'image.max' => 'La taille de l\'image ne peut pas dépasser 10 MB.',
                'image.dimensions' => 'L\'image doit avoir au minimum 400x300 pixels.',
                'beneficiaires_hommes.integer' => 'Le nombre de bénéficiaires hommes doit être un nombre entier.',
                'beneficiaires_hommes.min' => 'Le nombre de bénéficiaires hommes ne peut pas être négatif.',
                'beneficiaires_hommes.max' => 'Le nombre de bénéficiaires hommes ne peut pas dépasser 1 000 000.',
                'beneficiaires_femmes.integer' => 'Le nombre de bénéficiaires femmes doit être un nombre entier.',
                'beneficiaires_femmes.min' => 'Le nombre de bénéficiaires femmes ne peut pas être négatif.',
                'beneficiaires_femmes.max' => 'Le nombre de bénéficiaires femmes ne peut pas dépasser 1 000 000.',
                'beneficiaires_enfants.integer' => 'Le nombre de bénéficiaires enfants doit être un nombre entier.',
                'beneficiaires_enfants.min' => 'Le nombre de bénéficiaires enfants ne peut pas être négatif.',
                'beneficiaires_enfants.max' => 'Le nombre de bénéficiaires enfants ne peut pas dépasser 1 000 000.',
                'beneficiaires_total.integer' => 'Le nombre total de bénéficiaires doit être un nombre entier.',
                'beneficiaires_total.min' => 'Le nombre total de bénéficiaires ne peut pas être négatif.',
                'beneficiaires_total.max' => 'Le nombre total de bénéficiaires ne peut pas dépasser 1 000 000.',
            ]);

            // Validation spécifique pour CKEditor - vérifier le contenu texte
            $descriptionText = strip_tags($validated['description']);
            $descriptionText = trim(preg_replace('/\s+/', ' ', $descriptionText));
            
            if (strlen($descriptionText) < 50) {
                throw new ValidationException(validator([], []), [
                    'description' => "La description doit contenir au moins 50 caractères de texte. (" . strlen($descriptionText) . "/50)"
                ]);
            }

            // Sécuriser les données avec filtrage strict
            $validated['nom'] = trim(strip_tags($validated['nom']));
            $validated['description'] = $this->sanitizeHtml($validated['description']);
            
            if (isset($validated['resume'])) {
                $validated['resume'] = trim(strip_tags($validated['resume']));
            }

            // Vérifications de sécurité supplémentaires
            if (strlen($validated['nom']) < 3) {
                throw new ValidationException(validator([], []), ['nom' => 'Le nom du projet doit contenir au moins 3 caractères.']);
            }

            // Gestion de l'upload d'image avec sécurité renforcée
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Vérifier d'abord si le fichier a été correctement uploadé
                if ($image->getError() !== UPLOAD_ERR_OK) {
                    $uploadErrors = [
                        UPLOAD_ERR_INI_SIZE => 'Le fichier dépasse la taille maximale autorisée par le serveur.',
                        UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale du formulaire.',
                        UPLOAD_ERR_PARTIAL => 'Le fichier n\'a été que partiellement uploadé.',
                        UPLOAD_ERR_NO_FILE => 'Aucun fichier n\'a été uploadé.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant sur le serveur.',
                        UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire le fichier sur le disque.',
                        UPLOAD_ERR_EXTENSION => 'Upload arrêté par une extension PHP.'
                    ];
                    
                    $errorMessage = $uploadErrors[$image->getError()] ?? 'Erreur inconnue lors de l\'upload.';
                    
                    return back()
                        ->withInput()
                        ->withErrors(['image' => $errorMessage])
                        ->with('error', 'Erreur d\'upload du fichier image.');
                }
                
                // Vérifications de sécurité approfondies
                if (!$image->isValid()) {
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Le fichier image est corrompu ou invalide.'])
                        ->with('error', 'Problème avec le fichier image uploadé.');
                }

                // Vérifier si le fichier est réellement une image
                if (!$image->isValid() || !getimagesize($image->getPathname())) {
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Le fichier uploadé n\'est pas une image valide.'])
                        ->with('error', 'Le fichier ne semble pas être une image.');
                }

                // Vérifier le type MIME réel
                $mimeType = $image->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($mimeType, $allowedMimes)) {
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Type de fichier non autorisé. Formats acceptés : JPG, PNG, GIF, WebP.'])
                        ->with('error', 'Le format du fichier image n\'est pas supporté.');
                }

                // Vérifier la taille du fichier
                if ($image->getSize() > 10485760) { // 10MB
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Le fichier est trop volumineux. Taille maximum : 10 MB.'])
                        ->with('error', 'Le fichier image dépasse la taille autorisée.');
                }

                // Vérifier les dimensions minimales
                list($width, $height) = getimagesize($image->getPathname());
                if ($width < 400 || $height < 300) {
                    return back()
                        ->withInput()
                        ->withErrors(['image' => "Les dimensions de l'image sont trop petites. Minimum requis : 400x300 pixels. Reçu : {$width}x{$height} pixels."])
                        ->with('error', 'L\'image ne respecte pas les dimensions minimales.');
                }
                
                try {
                    // Vérifier que le dossier de destination existe
                    $uploadPath = storage_path('app/public/assets/images/projets');
                    if (!is_dir($uploadPath)) {
                        if (!mkdir($uploadPath, 0755, true)) {
                            throw new \Exception('Impossible de créer le dossier de destination.');
                        }
                    }

                    // Générer un nom sécurisé avec hash
                    $extension = $image->getClientOriginalExtension();
                    $filename = 'projets/' . hash('sha256', uniqid() . time() . auth()->id()) . '.' . $extension;
                    $path = $image->storeAs('images', $filename, 'public');
                    
                    if (!$path) {
                        throw new \Exception('Échec de la sauvegarde du fichier.');
                    }
                    
                    $validated['image'] = $path;
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'upload d\'image', [
                        'error' => $e->getMessage(),
                        'user_id' => auth()->id(),
                        'file_size' => $image->getSize(),
                        'file_mime' => $image->getMimeType()
                    ]);
                    
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage()])
                        ->with('error', 'Impossible de sauvegarder le fichier image.');
                }
            } else {
                // Aucune image uploadée - c'est normal car le champ est nullable
                // Mais vérifier si l'utilisateur a tenté un upload qui a échoué
                if ($request->has('image') && empty($request->file('image'))) {
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Erreur lors de la réception du fichier image. Veuillez réessayer.'])
                        ->with('error', 'Problème lors de l\'upload de l\'image.');
                }
            }

            // Calculer automatiquement le slug sécurisé
            $validated['slug'] = \Str::slug($validated['nom']) . '-' . uniqid();

            // Calculer automatiquement le total des bénéficiaires
            $hommes = (int) ($validated['beneficiaires_hommes'] ?? 0);
            $femmes = (int) ($validated['beneficiaires_femmes'] ?? 0);
            $enfants = (int) ($validated['beneficiaires_enfants'] ?? 0);
            $validated['beneficiaires_total'] = $hommes + $femmes + $enfants;

            // Assigner l'utilisateur créateur
            $validated['created_by'] = auth()->id();

            // Créer le projet avec transaction pour assurer l'intégrité
            \DB::beginTransaction();
            try {
                $projet = Projet::create($validated);

                // Log de sécurité
                \Log::info('Projet créé avec succès', [
                    'projet_id' => $projet->id,
                    'user_id' => auth()->id(),
                    'user_email' => auth()->user()->email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                // NOTE: L'événement ProjectCreated sera déclenché uniquement 
                // lors de l'action de modération "publier" pour respecter le workflow

                \DB::commit();
                
                return redirect()->route('admin.projets.index')
                    ->with('success', 'Projet créé avec succès ! Il sera visible après modération.');

            } catch (\Illuminate\Database\QueryException $e) {
                \DB::rollBack();
                
                // Analyser l'erreur de base de données pour donner des messages spécifiques
                $errorCode = $e->errorInfo[1] ?? null;
                $errorMessage = $e->getMessage();
                
                if (strpos($errorMessage, 'nom') !== false || strpos($errorMessage, 'UNIQUE') !== false) {
                    return back()
                        ->withInput()
                        ->withErrors(['nom' => 'Ce nom de projet existe déjà. Veuillez choisir un autre nom.'])
                        ->with('error', 'Le nom du projet doit être unique.');
                }
                
                if (strpos($errorMessage, 'slug') !== false) {
                    return back()
                        ->withInput()
                        ->withErrors(['nom' => 'Erreur lors de la génération de l\'identifiant unique. Veuillez modifier le nom.'])
                        ->with('error', 'Problème avec l\'identifiant du projet.');
                }
                
                if (strpos($errorMessage, 'service_id') !== false || strpos($errorMessage, 'FOREIGN KEY') !== false) {
                    return back()
                        ->withInput()
                        ->withErrors(['service_id' => 'Le service sélectionné n\'existe pas. Veuillez sélectionner un service valide.'])
                        ->with('error', 'Service invalide sélectionné.');
                }
                
                // Erreur de base de données générale mais avec plus de détails
                \Log::error('Erreur de base de données lors de la création du projet', [
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage,
                    'request_data' => $request->except(['_token', 'image']),
                    'user_id' => auth()->id()
                ]);
                
                // Message plus spécifique selon le type d'erreur de base de données
                $specificError = 'Erreur de base de données lors de la création du projet.';
                $fieldError = null;
                
                if (strpos($errorMessage, 'Data too long') !== false) {
                    $specificError = 'Un des champs contient trop de caractères.';
                    if (strpos($errorMessage, 'nom') !== false) {
                        $fieldError = ['nom' => 'Le nom du projet est trop long. Maximum 255 caractères.'];
                    } elseif (strpos($errorMessage, 'description') !== false) {
                        $fieldError = ['description' => 'La description est trop longue. Maximum 10 000 caractères.'];
                    } elseif (strpos($errorMessage, 'resume') !== false) {
                        $fieldError = ['resume' => 'Le résumé est trop long. Maximum 1000 caractères.'];
                    }
                } elseif (strpos($errorMessage, 'Incorrect integer') !== false || strpos($errorMessage, 'Out of range') !== false) {
                    $specificError = 'Une valeur numérique est invalide ou hors limites.';
                    if (strpos($errorMessage, 'budget') !== false) {
                        $fieldError = ['budget' => 'Le budget saisi est invalide ou trop élevé.'];
                    } elseif (strpos($errorMessage, 'beneficiaires') !== false) {
                        $fieldError = ['beneficiaires_total' => 'Le nombre de bénéficiaires est invalide.'];
                    }
                } elseif (strpos($errorMessage, 'Incorrect date') !== false || strpos($errorMessage, 'Invalid datetime') !== false) {
                    $specificError = 'Une date saisie est invalide.';
                    $fieldError = ['date_debut' => 'Vérifiez le format des dates saisies.'];
                } elseif (strpos($errorMessage, 'Cannot add or update a child row') !== false) {
                    $specificError = 'Une référence à un autre élément est invalide.';
                    $fieldError = ['service_id' => 'Le service sélectionné n\'existe pas ou n\'est plus disponible.'];
                } elseif (strpos($errorMessage, 'Connection refused') !== false || strpos($errorMessage, 'server has gone away') !== false) {
                    $specificError = 'Problème de connexion à la base de données. Veuillez réessayer dans un moment.';
                } elseif (strpos($errorMessage, 'Deadlock') !== false) {
                    $specificError = 'Conflit temporaire dans la base de données. Veuillez réessayer.';
                }
                
                $response = back()->withInput()->with('error', $specificError);
                
                if ($fieldError) {
                    $response = $response->withErrors($fieldError);
                }
                
                return $response;
                    
            } catch (\Exception $e) {
                \DB::rollBack();
                
                // Log détaillé pour debugging
                \Log::error('Erreur générale lors de la création du projet', [
                    'error_type' => get_class($e),
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'request_data' => $request->except(['_token', 'image']),
                    'user_id' => auth()->id()
                ]);
                
                // Message d'erreur plus informatif selon le contexte
                $specificError = 'Une erreur inattendue est survenue lors de la création du projet. [DEBUG: CATCH-DB-507]';
                $suggestions = [];
                
                if (strpos($e->getMessage(), 'timeout') !== false) {
                    $specificError = 'L\'opération a pris trop de temps. Le serveur est peut-être surchargé.';
                    $suggestions[] = 'Veuillez réessayer dans quelques minutes.';
                } elseif (strpos($e->getMessage(), 'memory') !== false) {
                    $specificError = 'Le serveur manque de mémoire pour traiter cette requête.';
                    $suggestions[] = 'Essayez de réduire la taille de l\'image si vous en uploadez une.';
                } elseif (strpos($e->getMessage(), 'disk') !== false || strpos($e->getMessage(), 'space') !== false) {
                    $specificError = 'Le serveur manque d\'espace de stockage.';
                    $suggestions[] = 'Contactez l\'administrateur système.';
                } elseif (strpos($e->getMessage(), 'permission') !== false) {
                    $specificError = 'Problème de permissions sur le serveur.';
                    $suggestions[] = 'Contactez l\'administrateur système.';
                } elseif (strpos($e->getMessage(), 'CSRF') !== false) {
                    $specificError = 'Votre session a expiré ou le formulaire n\'est plus valide.';
                    $suggestions[] = 'Rechargez la page et réessayez.';
                } else {
                    $suggestions[] = 'Vérifiez que tous les champs sont correctement remplis.';
                    $suggestions[] = 'Si le problème persiste, contactez le support technique.';
                }
                
                $errorMessage = $specificError;
                if (!empty($suggestions)) {
                    $errorMessage .= ' ' . implode(' ', $suggestions);
                }
                
                return back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erreurs de validation détectées. Veuillez corriger les champs indiqués.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du projet', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'image', 'password']),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
            
            // Analyser le type d'erreur pour donner un message plus spécifique
            $errorMessage = 'Une erreur système est survenue lors de la création du projet. [DEBUG: CATCH-FINAL-555]';
            $fieldError = null;
            
            // Log détaillé pour debug
            \Log::error('Exception non catégorisée lors de la création de projet', [
                'exception_class' => get_class($e),
                'exception_message' => $e->getMessage(),
                'exception_code' => $e->getCode(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token', 'image']),
                'timestamp' => now()->toDateTimeString()
            ]);
            
            if (strpos($e->getMessage(), 'nom') !== false) {
                $fieldError = ['nom' => 'Problème avec le nom du projet. Vérifiez qu\'il ne contient pas de caractères spéciaux.'];
                $errorMessage = 'Erreur liée au nom du projet.';
            } elseif (strpos($e->getMessage(), 'description') !== false) {
                $fieldError = ['description' => 'Problème avec la description. Vérifiez le contenu et la longueur.'];
                $errorMessage = 'Erreur liée à la description du projet.';
            } elseif (strpos($e->getMessage(), 'budget') !== false) {
                $fieldError = ['budget' => 'Problème avec le budget. Vérifiez que le montant est valide.'];
                $errorMessage = 'Erreur liée au budget du projet.';
            } elseif (strpos($e->getMessage(), 'date') !== false) {
                $fieldError = ['date_debut' => 'Problème avec les dates. Vérifiez que les dates sont valides et cohérentes.'];
                $errorMessage = 'Erreur liée aux dates du projet.';
            } elseif (strpos($e->getMessage(), 'service') !== false) {
                $fieldError = ['service_id' => 'Problème avec le service sélectionné. Veuillez choisir un service valide.'];
                $errorMessage = 'Erreur liée au service responsable.';
            } elseif (strpos($e->getMessage(), 'beneficiaires') !== false) {
                $fieldError = ['beneficiaires_total' => 'Problème avec les données des bénéficiaires. Vérifiez les nombres saisis.'];
                $errorMessage = 'Erreur liée aux bénéficiaires du projet.';
            } elseif (strpos($e->getMessage(), 'file') !== false || strpos($e->getMessage(), 'image') !== false) {
                $fieldError = ['image' => 'Problème avec le fichier image. Vérifiez le format et la taille.'];
                $errorMessage = 'Erreur liée au fichier image.';
            } elseif (strpos($e->getMessage(), 'permission') !== false || strpos($e->getMessage(), 'unauthorized') !== false) {
                $errorMessage = 'Vous n\'avez pas les permissions nécessaires pour créer ce projet.';
            } elseif (strpos($e->getMessage(), 'disk') !== false || strpos($e->getMessage(), 'storage') !== false) {
                $errorMessage = 'Problème d\'espace de stockage sur le serveur. Contactez l\'administrateur.';
            } elseif (strpos($e->getMessage(), 'memory') !== false || strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage = 'Le serveur rencontre des difficultés. Veuillez réessayer dans quelques minutes.';
            }
            
            $response = back()->withInput()->with('error', $errorMessage);
            
            if ($fieldError) {
                $response = $response->withErrors($fieldError);
            }
            
            return $response;
        }
    }

    public function edit(Projet $projet)
    {
        
        $this->authorize('update', $projet);
        
        $services = Service::all();
        return view('admin.projets.edit', compact('projet', 'services'));
    }

    public function update(Request $request, Projet $projet)
    {
        
        $this->authorize('update', $projet);
        
        try {
            // Validation avec règles de sécurité renforcées
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'required|string',
                'date_debut' => 'required|date',
                'service_id' => 'required|exists:services,id',
                'resume'=>'nullable|string',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'etat' => 'required|string|in:en cours,terminé,suspendu',
                'budget' => 'nullable|numeric|min:0|max:999999999.99',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,svg|max:10240|dimensions:min_width=400,min_height=300',
                'beneficiaires_hommes' => 'nullable|integer|min:0|max:1000000',
                'beneficiaires_femmes' => 'nullable|integer|min:0|max:1000000',
                'beneficiaires_enfants' => 'nullable|integer|min:0|max:1000000',
                'beneficiaires_total' => 'nullable|integer|min:0|max:1000000',
            ], [
                'nom.required' => 'Le nom du projet est obligatoire.',
                'nom.max' => 'Le nom du projet ne peut pas dépasser 255 caractères.',
                'description.required' => 'La description du projet est obligatoire.',
                'description.min' => 'La description doit contenir au moins 50 caractères.',
                'date_debut.required' => 'La date de début est obligatoire.',
                'service_id.required' => 'Veuillez sélectionner un service responsable.',
                'service_id.exists' => 'Le service sélectionné n\'existe pas.',
                'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
                'etat.required' => 'L\'état du projet est obligatoire.',
                'etat.in' => 'L\'état du projet doit être : en cours, terminé ou suspendu.',
                'budget.numeric' => 'Le budget doit être un nombre.',
                'budget.min' => 'Le budget ne peut pas être négatif.',
                'budget.max' => 'Le budget ne peut pas dépasser 999 999 999,99.',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format : JPG, JPEG, PNG, GIF, WebP ou SVG.',
                'image.max' => 'La taille de l\'image ne peut pas dépasser 10 MB.',
                'image.dimensions' => 'L\'image doit avoir au minimum 400x300 pixels.',
            ]);

            // Validation spécifique pour CKEditor - vérifier le contenu texte
            $descriptionText = strip_tags($validated['description']);
            $descriptionText = trim(preg_replace('/\s+/', ' ', $descriptionText));
            
            if (strlen($descriptionText) < 50) {
                throw new ValidationException(validator([], []), [
                    'description' => "La description doit contenir au moins 50 caractères de texte. (" . strlen($descriptionText) . "/50)"
                ]);
            }

            // Sécuriser les données
            $validated['nom'] = strip_tags($validated['nom']);
            $validated['description'] = $this->sanitizeHtml($validated['description']);
            if (isset($validated['resume'])) {
                $validated['resume'] = strip_tags($validated['resume']);
            }

            // Gestion sécurisée de l'upload d'image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Vérifier d'abord si le fichier a été correctement uploadé
                if ($image->getError() !== UPLOAD_ERR_OK) {
                    $uploadErrors = [
                        UPLOAD_ERR_INI_SIZE => 'Le fichier dépasse la taille maximale autorisée par le serveur.',
                        UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale du formulaire.',
                        UPLOAD_ERR_PARTIAL => 'Le fichier n\'a été que partiellement uploadé.',
                        UPLOAD_ERR_NO_FILE => 'Aucun fichier n\'a été uploadé.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant sur le serveur.',
                        UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire le fichier sur le disque.',
                        UPLOAD_ERR_EXTENSION => 'Upload arrêté par une extension PHP.'
                    ];
                    
                    $errorMessage = $uploadErrors[$image->getError()] ?? 'Erreur inconnue lors de l\'upload.';
                    
                    return back()
                        ->withInput()
                        ->withErrors(['image' => $errorMessage])
                        ->with('error', 'Erreur d\'upload du fichier image.');
                }
                
                // Vérifications de sécurité supplémentaires
                if (!$image->isValid()) {
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Le fichier image est corrompu ou invalide.'])
                        ->with('error', 'Problème avec le fichier image uploadé.');
                }

                // Vérifier si le fichier est réellement une image
                if (!getimagesize($image->getPathname())) {
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Le fichier uploadé n\'est pas une image valide.'])
                        ->with('error', 'Le fichier ne semble pas être une image.');
                }

                // Vérifier le type MIME réel
                $mimeType = $image->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
                if (!in_array($mimeType, $allowedMimes)) {
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Type de fichier non autorisé. Formats acceptés : JPG, PNG, GIF, WebP, SVG.'])
                        ->with('error', 'Le format du fichier image n\'est pas supporté.');
                }

                // Vérifier la taille du fichier
                if ($image->getSize() > 10485760) { // 10MB
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Le fichier est trop volumineux. Taille maximum : 10 MB.'])
                        ->with('error', 'Le fichier image dépasse la taille autorisée.');
                }

                // Vérifier les dimensions minimales (sauf pour SVG)
                if ($mimeType !== 'image/svg+xml') {
                    list($width, $height) = getimagesize($image->getPathname());
                    if ($width < 400 || $height < 300) {
                        return back()
                            ->withInput()
                            ->withErrors(['image' => "Les dimensions de l'image sont trop petites. Minimum requis : 400x300 pixels. Reçu : {$width}x{$height} pixels."])
                            ->with('error', 'L\'image ne respecte pas les dimensions minimales.');
                    }
                }
                
                try {
                    // Supprimer l'ancienne image de manière sécurisée
                    if ($projet->image && Storage::disk('public')->exists($projet->image)) {
                        Storage::disk('public')->delete($projet->image);
                    }

                    // Vérifier que le dossier de destination existe
                    $uploadPath = storage_path('app/public/assets/images/projets');
                    if (!is_dir($uploadPath)) {
                        if (!mkdir($uploadPath, 0755, true)) {
                            throw new \Exception('Impossible de créer le dossier de destination.');
                        }
                    }

                    // Générer un nom sécurisé
                    $extension = $image->getClientOriginalExtension();
                    $filename = 'projets/' . hash('sha256', uniqid() . time() . auth()->id()) . '.' . $extension;
                    $path = $image->storeAs('images', $filename, 'public');
                    
                    if (!$path) {
                        throw new \Exception('Échec de la sauvegarde du fichier.');
                    }
                    
                    $validated['image'] = $path;
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'upload d\'image (update)', [
                        'error' => $e->getMessage(),
                        'user_id' => auth()->id(),
                        'projet_id' => $projet->id,
                        'file_size' => $image->getSize(),
                        'file_mime' => $image->getMimeType()
                    ]);
                    
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage()])
                        ->with('error', 'Impossible de sauvegarder le fichier image.');
                }
            } else {
                // Gérer le cas où l'utilisateur veut supprimer l'image existante
                if ($request->has('remove_image') && $request->remove_image == '1') {
                    if ($projet->image && Storage::disk('public')->exists($projet->image)) {
                        Storage::disk('public')->delete($projet->image);
                    }
                    $validated['image'] = null;
                }
            }

            // Calculer automatiquement le total des bénéficiaires
            $hommes = (int) ($validated['beneficiaires_hommes'] ?? 0);
            $femmes = (int) ($validated['beneficiaires_femmes'] ?? 0);
            $enfants = (int) ($validated['beneficiaires_enfants'] ?? 0);
            $validated['beneficiaires_total'] = $hommes + $femmes + $enfants;

            try {
                // Mettre à jour le projet
                $projet->update($validated);

                return redirect()->route('admin.projets.index')
                    ->with('success', 'Projet mis à jour avec succès !');
                    
            } catch (\Illuminate\Database\QueryException $e) {
                // Analyser l'erreur de base de données pour donner des messages spécifiques
                $errorMessage = $e->getMessage();
                
                if (strpos($errorMessage, 'nom') !== false || strpos($errorMessage, 'UNIQUE') !== false) {
                    return back()
                        ->withInput()
                        ->withErrors(['nom' => 'Ce nom de projet existe déjà. Veuillez choisir un autre nom.'])
                        ->with('error', 'Le nom du projet doit être unique.');
                }
                
                if (strpos($errorMessage, 'service_id') !== false || strpos($errorMessage, 'FOREIGN KEY') !== false) {
                    return back()
                        ->withInput()
                        ->withErrors(['service_id' => 'Le service sélectionné n\'existe pas. Veuillez sélectionner un service valide.'])
                        ->with('error', 'Service invalide sélectionné.');
                }
                
                \Log::error('Erreur de base de données lors de la mise à jour du projet', [
                    'error_message' => $errorMessage,
                    'request_data' => $request->except(['_token', 'image']),
                    'user_id' => auth()->id(),
                    'projet_id' => $projet->id
                ]);
                
                return back()
                    ->withInput()
                    ->with('error', 'Erreur de base de données lors de la mise à jour. Vérifiez que tous les champs sont correctement remplis.');
            }

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erreurs de validation détectées. Veuillez corriger les champs indiqués.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du projet: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'image']),
                'user_id' => auth()->id(),
                'projet_id' => $projet->id
            ]);
            
            // Analyser le type d'erreur pour donner un message plus spécifique
            $errorMessage = 'Une erreur système est survenue lors de la mise à jour du projet.';
            $fieldError = null;
            
            if (strpos($e->getMessage(), 'nom') !== false) {
                $fieldError = ['nom' => 'Problème avec le nom du projet. Vérifiez qu\'il ne contient pas de caractères spéciaux.'];
                $errorMessage = 'Erreur liée au nom du projet.';
            } elseif (strpos($e->getMessage(), 'description') !== false) {
                $fieldError = ['description' => 'Problème avec la description. Vérifiez le contenu et la longueur.'];
                $errorMessage = 'Erreur liée à la description du projet.';
            } elseif (strpos($e->getMessage(), 'budget') !== false) {
                $fieldError = ['budget' => 'Problème avec le budget. Vérifiez que le montant est valide.'];
                $errorMessage = 'Erreur liée au budget du projet.';
            } elseif (strpos($e->getMessage(), 'date') !== false) {
                $fieldError = ['date_debut' => 'Problème avec les dates. Vérifiez que les dates sont valides et cohérentes.'];
                $errorMessage = 'Erreur liée aux dates du projet.';
            } elseif (strpos($e->getMessage(), 'service') !== false) {
                $fieldError = ['service_id' => 'Problème avec le service sélectionné. Veuillez choisir un service valide.'];
                $errorMessage = 'Erreur liée au service responsable.';
            } elseif (strpos($e->getMessage(), 'beneficiaires') !== false) {
                $fieldError = ['beneficiaires_total' => 'Problème avec les données des bénéficiaires. Vérifiez les nombres saisis.'];
                $errorMessage = 'Erreur liée aux bénéficiaires du projet.';
            } elseif (strpos($e->getMessage(), 'file') !== false || strpos($e->getMessage(), 'image') !== false) {
                $fieldError = ['image' => 'Problème avec le fichier image. Vérifiez le format et la taille.'];
                $errorMessage = 'Erreur liée au fichier image.';
            } elseif (strpos($e->getMessage(), 'permission') !== false || strpos($e->getMessage(), 'unauthorized') !== false) {
                $errorMessage = 'Vous n\'avez pas les permissions nécessaires pour modifier ce projet.';
            } elseif (strpos($e->getMessage(), 'disk') !== false || strpos($e->getMessage(), 'storage') !== false) {
                $errorMessage = 'Problème d\'espace de stockage sur le serveur. Contactez l\'administrateur.';
            } elseif (strpos($e->getMessage(), 'memory') !== false || strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage = 'Le serveur rencontre des difficultés. Veuillez réessayer dans quelques minutes.';
            }
            
            $response = back()->withInput()->with('error', $errorMessage);
            
            if ($fieldError) {
                $response = $response->withErrors($fieldError);
            }
            
            return $response;
        }
    }

    public function show(Projet $projet)
    {
        
        $this->authorize('view', $projet);
        
        $projet->load(['medias', 'publishedBy', 'rapports']);
        
        // Charger tous les rapports pour la sélection
        $rapportsDisponibles = \App\Models\Rapport::orderBy('titre')->get();
        
        return view('admin.projets.show', compact('projet', 'rapportsDisponibles'));
    }

    public function destroy(Projet $projet)
    {
        
        $this->authorize('delete', $projet);
        
        try {
            if ($projet->image && Storage::disk('public')->exists($projet->image)) {
                Storage::disk('public')->delete($projet->image);
            }

            $projet->delete();

            return redirect()->route('admin.projets.index')
                ->with('alert', '<span class="text-green-600">Projet supprimé avec succès.</span>');

        } catch (\Exception $e) {
            return back()
                ->with('alert', '<span class="text-red-600">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Publier un projet
     */
    public function publish(Request $request, Projet $projet)
    {
        // Vérification stricte des permissions
        $this->authorize('moderate', $projet);
        
        // Vérification supplémentaire du rôle utilisateur
        if (!auth()->user()->canModerate()) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour modérer ce contenu.');
        }
        
        // Validation du commentaire de modération
        $request->validate([
            'comment' => 'nullable|string|max:1000'
        ]);
        
        try {
            $projet->update([
                'is_published' => true,
                'published_at' => now(),
                'published_by' => auth()->id(),
                'moderation_comment' => $request->input('comment')
            ]);
            
            // Log de l'action de modération
            \Log::info('Projet publié', [
                'projet_id' => $projet->id,
                'moderator_id' => auth()->id(),
                'moderator_email' => auth()->user()->email,
                'comment' => $request->input('comment'),
                'timestamp' => now()
            ]);
            
            // Déclencher l'événement newsletter lors de la publication officielle
            if (class_exists('App\Events\ProjectCreated')) {
                try {
                    ProjectCreated::dispatch($projet);
                    \Log::info('Événement ProjectCreated déclenché lors de la publication', [
                        'projet_id' => $projet->id,
                        'titre' => $projet->nom
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Erreur lors du déclenchement de l\'événement ProjectCreated', [
                        'projet_id' => $projet->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Projet publié avec succès'
                ]);
            }
            
            return redirect()->route('admin.projets.show', $projet)
                ->with('alert', '<span class="text-green-600">✅ Projet publié avec succès ! Il est maintenant visible par le public.</span>');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la publication du projet', [
                'projet_id' => $projet->id,
                'error' => $e->getMessage(),
                'moderator_id' => auth()->id()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la publication : ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.projets.show', $projet)
                ->with('alert', '<span class="text-red-600">❌ Erreur lors de la publication : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Dépublier un projet
     */
    public function unpublish(Request $request, Projet $projet)
    {
        // Vérification stricte des permissions
        $this->authorize('moderate', $projet);
        
        // Vérification supplémentaire du rôle utilisateur
        if (!auth()->user()->canModerate()) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour modérer ce contenu.');
        }
        
        // Validation du commentaire de modération
        $request->validate([
            'comment' => 'nullable|string|max:1000'
        ]);
        
        try {
            $projet->update([
                'is_published' => false,
                'published_at' => null,
                'published_by' => null,
                'moderation_comment' => $request->input('comment')
            ]);
            
            // Log de l'action de modération
            \Log::info('Projet dépublié', [
                'projet_id' => $projet->id,
                'moderator_id' => auth()->id(),
                'moderator_email' => auth()->user()->email,
                'comment' => $request->input('comment'),
                'timestamp' => now()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Projet dépublié avec succès'
                ]);
            }
            
            return redirect()->route('admin.projets.show', $projet)
                ->with('alert', '<span class="text-orange-600">⚠️ Projet dépublié avec succès ! Il n\'est plus visible par le public.</span>');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.projets.show', $projet)
                ->with('alert', '<span class="text-red-600">❌ Erreur lors de la dépublication : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Voir les éléments en attente de modération
     */
    public function pendingModeration()
    {
        $projets = Projet::pendingModeration()
                        ->with(['service'])
                        ->latest()
                        ->paginate(10);

        return view('admin.projets.pending', compact('projets'));
    }

    /**
     * Attacher des rapports à un projet
     */
    public function attachRapports(Request $request, Projet $projet)
    {
        $this->authorize('update', $projet);

        $request->validate([
            'rapports' => 'required|array',
            'rapports.*' => 'exists:rapports,id'
        ]);

        try {
            // Attacher les rapports sélectionnés
            $projet->rapports()->attach($request->rapports);

            return redirect()
                ->route('admin.projets.show', $projet)
                ->with('success', 'Rapport(s) lié(s) avec succès à ce projet.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'attachement des rapports: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la liaison des rapports.');
        }
    }

    /**
     * Détacher un rapport d'un projet
     */
    public function detachRapport(Projet $projet, $rapportId)
    {
        $this->authorize('update', $projet);

        try {
            $projet->rapports()->detach($rapportId);

            return redirect()
                ->route('admin.projets.show', $projet)
                ->with('success', 'Rapport détaché avec succès de ce projet.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors du détachement du rapport: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors du détachement du rapport.');
        }
    }
}
