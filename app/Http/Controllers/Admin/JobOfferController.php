<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobOfferController extends Controller
{
    /**
     * Afficher la liste des offres d'emploi
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', JobOffer::class);
        
        try {
            $query = JobOffer::with(['applications'])
                            ->withCount('applications');

            // Filtres de recherche
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }

            if ($request->filled('source')) {
                $query->bySource($request->source);
            }

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhere('location', 'like', "%{$searchTerm}%")
                      ->orWhere('partner_name', 'like', "%{$searchTerm}%");
                });
            }

            $jobOffers = $query->orderBy('created_at', 'desc')->paginate(15);

            // Auto-expiration des offres
            $expiredCount = 0;
            foreach ($jobOffers as $offer) {
                if ($offer->autoExpireIfNeeded()) {
                    $expiredCount++;
                }
            }

            if ($expiredCount > 0) {
                session()->flash('info', "$expiredCount offre(s) ont été automatiquement expirées.");
            }

            return view('admin.job-offers.index', compact('jobOffers'));
        } catch (\Exception $e) {
            \Log::error('Erreur dans JobOfferController@index: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des offres: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de création d'une offre
     */
    public function create()
    {
        $this->authorize('create', JobOffer::class);
        
        $statuses = JobOffer::getStatuses();
        $sources = JobOffer::getSources();
        $types = JobOffer::getTypes();
        
        return view('admin.job-offers.create', compact('statuses', 'sources', 'types'));
    }

    /**
     * Sauvegarder une nouvelle offre d'emploi
     */
    public function store(Request $request)
    {
        $this->authorize('create', JobOffer::class);
        
        try {
            // Utilisation des règles de validation du modèle
            $validated = $request->validate(
                JobOffer::validationRules(),
                JobOffer::validationMessages()
            );

            // Validation personnalisée pour les requirements (méthode STORE)
            $requirementsJson = $validated['requirements'];
            
            // Debug : log des données reçues
            \Log::info('STORE - Requirements reçus:', ['data' => $requirementsJson, 'type' => gettype($requirementsJson)]);
            
            // Traitement flexible des requirements
            if (empty($requirementsJson) || $requirementsJson === 'null') {
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Veuillez ajouter au moins une exigence pour le poste.']);
            }
            
            // Si c'est déjà un array (cas rare mais possible)
            if (is_array($requirementsJson)) {
                $requirementsArray = $requirementsJson;
            } else {
                // Décoder le JSON si c'est une chaîne
                $requirementsArray = json_decode($requirementsJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('STORE - Erreur JSON requirements:', [
                        'data' => $requirementsJson,
                        'error' => json_last_error_msg()
                    ]);
                    return back()
                        ->withInput()
                        ->withErrors(['requirements' => 'Format des exigences invalide. Erreur: ' . json_last_error_msg()]);
                }
            }
            
            // Vérifier que c'est bien un array
            if (!is_array($requirementsArray)) {
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Les exigences doivent être une liste valide.']);
            }

            // Filtrer les exigences vides et vérifier qu'il y en a au moins une
            $filteredRequirements = array_filter($requirementsArray, function($item) {
                return is_string($item) && !empty(trim($item));
            });
            
            if (empty($filteredRequirements)) {
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Veuillez ajouter au moins une exigence valide pour le poste.']);
            }
            
            // Le modèle JobOffer a un cast 'requirements' => 'array', donc Laravel va automatiquement
            // convertir le tableau en JSON pour la base de données
            $validated['requirements'] = array_values($filteredRequirements);

            // Traitement des critères d'évaluation (optionnel)
            if ($request->has('criteria') && !empty($request->input('criteria'))) {
                $criteriaJson = $request->input('criteria');
                \Log::info('STORE - Critères reçus:', ['data' => $criteriaJson, 'type' => gettype($criteriaJson)]);
                
                if ($criteriaJson !== 'null' && $criteriaJson !== '[]') {
                    // Si c'est déjà un array
                    if (is_array($criteriaJson)) {
                        $criteriaArray = $criteriaJson;
                    } else {
                        // Décoder le JSON si c'est une chaîne
                        $criteriaArray = json_decode($criteriaJson, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            \Log::error('STORE - Erreur JSON critères:', [
                                'data' => $criteriaJson,
                                'error' => json_last_error_msg()
                            ]);
                            // On continue sans les critères en cas d'erreur, ce n'est pas critique
                            $validated['criteria'] = [];
                        } else {
                            $validated['criteria'] = $criteriaArray;
                        }
                    }
                } else {
                    $validated['criteria'] = [];
                }
            } else {
                $validated['criteria'] = [];
            }
            \Log::info('STORE - Critères finaux assignés:', ['final_criteria' => $validated['criteria']]);

            // Les valeurs utilisent maintenant directement les constantes du modèle
            // Plus besoin de mapping car les constantes correspondent aux valeurs attendues

            // Convertir is_featured en boolean
            $validated['is_featured'] = $request->has('is_featured') ? true : false;

                        // Gérer le logo du partenaire
            if ($request->hasFile('partner_logo')) {
                $logoPath = $request->file('partner_logo')->store('job-offers/partner-logos', 'public');
                $validated['partner_logo'] = $logoPath;
            }

            // Gérer le document d'appel d'offre
            if ($request->hasFile('document_appel_offre')) {
                $documentPath = $request->file('document_appel_offre')->store('job-offers/documents', 'public');
                $validated['document_appel_offre'] = $documentPath;
                $validated['document_appel_offre_nom'] = $request->file('document_appel_offre')->getClientOriginalName();
            }

            // Définir le statut final basé sur l'action
            if ($request->has('action')) {
                if ($request->action === 'publish') {
                    $validated['status'] = JobOffer::STATUS_ACTIVE;
                } else {
                    $validated['status'] = JobOffer::STATUS_DRAFT;
                }
            }

            $jobOffer = JobOffer::create($validated);

            return redirect()->route('admin.job-offers.index')
                ->with('success', 'Offre d\'emploi créée avec succès.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('STORE - Erreur de validation globale:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
                'request_data' => $request->except(['partner_logo', 'document_appel_offre', '_token'])
            ]);
            throw $e; // Re-lancer pour affichage normal des erreurs de validation
        } catch (\Exception $e) {
            \Log::error('STORE - Erreur générale lors de la création:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['partner_logo', 'document_appel_offre', '_token'])
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'offre. Détails: ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher une offre d'emploi spécifique
     */
    public function show($slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        $this->authorize('view', $jobOffer);
        
        $jobOffer->load(['applications' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $stats = [
            'total_applications' => $jobOffer->applications->count(),
            'pending' => $jobOffer->applications->where('status', 'pending')->count(),
            'reviewed' => $jobOffer->applications->where('status', 'reviewed')->count(),
            'shortlisted' => $jobOffer->applications->where('status', 'shortlisted')->count(),
            'accepted' => $jobOffer->applications->where('status', 'accepted')->count(),
            'rejected' => $jobOffer->applications->where('status', 'rejected')->count(),
        ];

        return view('admin.job-offers.show', compact('jobOffer', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        $this->authorize('update', $jobOffer);
        return view('admin.job-offers.edit', compact('jobOffer'));
    }

    /**
     * Mettre à jour une offre d'emploi
     */
    public function update(Request $request, $slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        $this->authorize('update', $jobOffer);
        
        try {
            // Log des données reçues pour diagnostic
            \Log::info('UPDATE - Données reçues:', [
                'request_data' => $request->except(['partner_logo', 'document_appel_offre']),
                'files' => [
                    'partner_logo' => $request->hasFile('partner_logo') ? 'présent' : 'absent',
                    'document_appel_offre' => $request->hasFile('document_appel_offre') ? 'présent' : 'absent'
                ]
            ]);

            // Validation avec gestion d'erreurs détaillée
            try {
                $validated = $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'requirements' => 'required|string', // JSON string
                    'benefits' => 'nullable|string',
                    'type' => 'required|in:full-time,part-time,contract,internship,freelance',
                    'location' => 'required|string|max:255',
                    'application_deadline' => 'required|date',
                    'positions_available' => 'required|integer|min:1',
                    'source' => 'required|in:internal,partner',
                    'partner_name' => 'required_if:source,partner|nullable|string|max:255',
                    'partner_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'contact_email' => 'required|email|max:255',
                    'document_appel_offre' => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240', // 10MB max
                    'is_featured' => 'nullable|boolean',
                    'status' => 'required|in:draft,active,paused,closed',
                    'criteria' => 'nullable|string', // JSON string pour les critères
                ]);
                \Log::info('UPDATE - Validation de base réussie');
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('UPDATE - Erreur de validation Laravel:', [
                    'errors' => $e->errors(),
                    'message' => $e->getMessage()
                ]);
                throw $e; // Re-lancer l'exception pour affichage normal
            }

            // Validation personnalisée pour les requirements (méthode UPDATE)
            $requirementsJson = $validated['requirements'];
            
            // Debug : log des données reçues
            \Log::info('UPDATE - Requirements reçus:', [
                'data' => $requirementsJson, 
                'type' => gettype($requirementsJson),
                'length' => strlen($requirementsJson),
                'is_empty' => empty($requirementsJson)
            ]);
            
            // Traitement flexible des requirements
            if (empty($requirementsJson) || $requirementsJson === 'null') {
                \Log::warning('UPDATE - Requirements vides détectés');
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Veuillez ajouter au moins une exigence pour le poste.']);
            }
            
            // Si c'est déjà un array (cas rare mais possible)
            if (is_array($requirementsJson)) {
                $requirementsArray = $requirementsJson;
                \Log::info('UPDATE - Requirements déjà en array');
            } else {
                // Décoder le JSON si c'est une chaîne
                \Log::info('UPDATE - Tentative de décodage JSON:', ['json_string' => $requirementsJson]);
                $requirementsArray = json_decode($requirementsJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('UPDATE - Erreur JSON requirements:', [
                        'data' => $requirementsJson,
                        'error' => json_last_error_msg(),
                        'error_code' => json_last_error()
                    ]);
                    return back()
                        ->withInput()
                        ->withErrors(['requirements' => 'Format des exigences invalide. Erreur: ' . json_last_error_msg()]);
                }
                \Log::info('UPDATE - JSON décodé avec succès:', ['decoded_array' => $requirementsArray]);
            }
            
            // Vérifier que c'est bien un array
            if (!is_array($requirementsArray)) {
                \Log::error('UPDATE - Requirements n\'est pas un array:', ['type' => gettype($requirementsArray), 'data' => $requirementsArray]);
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Les exigences doivent être une liste valide.']);
            }

            \Log::info('UPDATE - Array requirements avant filtrage:', ['count' => count($requirementsArray), 'items' => $requirementsArray]);

            // Filtrer les exigences vides et vérifier qu'il y en a au moins une
            $filteredRequirements = array_filter($requirementsArray, function($item) {
                return is_string($item) && !empty(trim($item));
            });
            
            \Log::info('UPDATE - Array requirements après filtrage:', ['count' => count($filteredRequirements), 'items' => $filteredRequirements]);
            
            if (empty($filteredRequirements)) {
                \Log::warning('UPDATE - Aucune exigence valide après filtrage');
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Veuillez ajouter au moins une exigence valide pour le poste.']);
            }
            
            // Le modèle JobOffer a un cast 'requirements' => 'array', donc Laravel va automatiquement
            // convertir le tableau en JSON pour la base de données
            $validated['requirements'] = array_values($filteredRequirements);
            \Log::info('UPDATE - Requirements finaux assignés:', ['final_requirements' => $validated['requirements']]);

            // Traitement des critères d'évaluation (optionnel) - récupération directe depuis request
            if ($request->has('criteria') && !empty($request->input('criteria'))) {
                $criteriaJson = $request->input('criteria');
                \Log::info('UPDATE - Critères reçus:', ['data' => $criteriaJson, 'type' => gettype($criteriaJson)]);
                
                if ($criteriaJson !== 'null' && $criteriaJson !== '[]') {
                    // Si c'est déjà un array
                    if (is_array($criteriaJson)) {
                        $criteriaArray = $criteriaJson;
                    } else {
                        // Décoder le JSON si c'est une chaîne
                        $criteriaArray = json_decode($criteriaJson, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            \Log::error('UPDATE - Erreur JSON critères:', [
                                'data' => $criteriaJson,
                                'error' => json_last_error_msg()
                            ]);
                            // On continue sans les critères en cas d'erreur, ce n'est pas critique
                            $validated['criteria'] = [];
                        } else {
                            $validated['criteria'] = $criteriaArray;
                        }
                    }
                } else {
                    $validated['criteria'] = [];
                }
            } else {
                $validated['criteria'] = [];
            }
            \Log::info('UPDATE - Critères finaux assignés:', ['final_criteria' => $validated['criteria']]);

            // Les valeurs du formulaire correspondent déjà aux valeurs de la base de données
            // Plus besoin de mapping car les valeurs sont correctes

            \Log::info('UPDATE - Valeurs finales (sans mapping):', [
                'type' => $validated['type'],
                'source' => $validated['source'],
                'application_deadline' => $validated['application_deadline']
            ]);

            // Convertir is_featured en boolean
            $validated['is_featured'] = $request->has('is_featured') ? true : false;
            \Log::info('UPDATE - is_featured défini:', ['is_featured' => $validated['is_featured']]);

            // Traitement du logo partenaire
            \Log::info('UPDATE - Traitement logo partenaire:', [
                'source' => $validated['source'],
                'has_file' => $request->hasFile('partner_logo'),
                'current_logo' => $jobOffer->partner_logo
            ]);

            if ($validated['source'] === 'partner' && $request->hasFile('partner_logo')) {
                try {
                    // Supprimer l'ancien logo s'il existe
                    if ($jobOffer->partner_logo) {
                        Storage::disk('public')->delete($jobOffer->partner_logo);
                        \Log::info('UPDATE - Ancien logo supprimé:', ['path' => $jobOffer->partner_logo]);
                    }
                    
                    $file = $request->file('partner_logo');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('assets/partner-logos', $filename, 'public');
                    $validated['partner_logo'] = $path;
                    \Log::info('UPDATE - Nouveau logo sauvegardé:', ['path' => $path]);
                } catch (\Exception $e) {
                    \Log::error('UPDATE - Erreur upload logo:', ['error' => $e->getMessage()]);
                    return back()
                        ->withInput()
                        ->withErrors(['partner_logo' => 'Erreur lors de l\'upload du logo: ' . $e->getMessage()]);
                }
            } elseif ($validated['source'] !== 'partner') {
                // Si on change de partenaire vers interne, supprimer le logo
                if ($jobOffer->partner_logo) {
                    Storage::disk('public')->delete($jobOffer->partner_logo);
                    \Log::info('UPDATE - Logo supprimé (changement vers interne)');
                }
                $validated['partner_logo'] = null;
                $validated['partner_name'] = null;
            }

            // Traitement du document d'appel d'offre
            \Log::info('UPDATE - Traitement document:', [
                'has_file' => $request->hasFile('document_appel_offre'),
                'current_document' => $jobOffer->document_appel_offre
            ]);

            if ($request->hasFile('document_appel_offre')) {
                try {
                    // Supprimer l'ancien document s'il existe
                    if ($jobOffer->document_appel_offre) {
                        Storage::disk('public')->delete($jobOffer->document_appel_offre);
                        \Log::info('UPDATE - Ancien document supprimé:', ['path' => $jobOffer->document_appel_offre]);
                    }
                    
                    $documentPath = $request->file('document_appel_offre')->store('job-offers/documents', 'public');
                    $validated['document_appel_offre'] = $documentPath;
                    $validated['document_appel_offre_nom'] = $request->file('document_appel_offre')->getClientOriginalName();
                    \Log::info('UPDATE - Nouveau document sauvegardé:', ['path' => $documentPath]);
                } catch (\Exception $e) {
                    \Log::error('UPDATE - Erreur upload document:', ['error' => $e->getMessage()]);
                    return back()
                        ->withInput()
                        ->withErrors(['document_appel_offre' => 'Erreur lors de l\'upload du document: ' . $e->getMessage()]);
                }
            }

            \Log::info('UPDATE - Données finales avant update:', ['validated_data' => array_keys($validated)]);

            // Tentative de mise à jour
            try {
                $jobOffer->update($validated);
                \Log::info('UPDATE - Mise à jour réussie');
            } catch (\Exception $e) {
                \Log::error('UPDATE - Erreur lors de la mise à jour en base:', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'sql_error' => $e instanceof \Illuminate\Database\QueryException ? $e->getSql() : 'N/A'
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()]);
            }

            return redirect()->route('admin.job-offers.show', $jobOffer->slug)
                ->with('success', 'Offre d\'emploi mise à jour avec succès.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('UPDATE - Erreur de validation globale:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
                'request_data' => $request->except(['partner_logo', 'document_appel_offre', '_token'])
            ]);
            throw $e; // Re-lancer pour affichage normal des erreurs de validation
        } catch (\Exception $e) {
            \Log::error('UPDATE - Erreur générale lors de la mise à jour:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['partner_logo', 'document_appel_offre', '_token'])
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de l\'offre. Détails: ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer une offre d'emploi
     */
    public function destroy($slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        $this->authorize('delete', $jobOffer);
        
        // Supprimer les fichiers associés aux candidatures
        foreach ($jobOffer->applications as $application) {
            if ($application->cv_path) {
                Storage::disk('public')->delete($application->cv_path);
            }
            if ($application->portfolio_path) {
                Storage::disk('public')->delete($application->portfolio_path);
            }
        }

        // Supprimer le logo du partenaire s'il existe
        if ($jobOffer->partner_logo) {
            Storage::disk('public')->delete($jobOffer->partner_logo);
        }

        // Supprimer le document d'appel d'offre s'il existe
        if ($jobOffer->document_appel_offre) {
            Storage::disk('public')->delete($jobOffer->document_appel_offre);
        }

        $jobOffer->delete();

        return redirect()->route('admin.job-offers.index')
            ->with('success', 'Offre d\'emploi supprimée avec succès.');
    }

    /**
     * Dupliquer une offre d'emploi
     */
    public function duplicate($slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        
        $newJobOffer = $jobOffer->replicate();
        $newJobOffer->title = $jobOffer->title . ' (Copie)';
        $newJobOffer->slug = null; // Will be auto-generated
        $newJobOffer->status = 'draft';
        $newJobOffer->application_deadline = now()->addMonths(1);
        $newJobOffer->applications_count = 0;
        $newJobOffer->views_count = 0;
        $newJobOffer->save();

        return redirect()->route('admin.job-offers.edit', $newJobOffer->slug)
            ->with('success', 'Offre d\'emploi dupliquée avec succès.');
    }

    /**
     * Changer le statut d'une offre
     */
    public function changeStatus(Request $request, $slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        
        $request->validate([
            'status' => 'required|in:draft,active,paused,closed'
        ]);

        $jobOffer->update(['status' => $request->status]);

        return back()->with('success', 'Statut de l\'offre mis à jour avec succès.');
    }

    /**
     * Marquer une offre comme vedette
     */
    public function toggleFeatured($slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        
        $jobOffer->update(['is_featured' => !$jobOffer->is_featured]);

        $message = $jobOffer->is_featured ? 'Offre marquée comme vedette.' : 'Offre retirée des vedettes.';
        
        return back()->with('success', $message);
    }

    /**
     * Statistiques des offres d'emploi
     */
    public function statistics()
    {
        $stats = [
            'total_offers' => JobOffer::count(),
            'active_offers' => JobOffer::active()->count(),
            'expired_offers' => JobOffer::expired()->count(),
            'total_applications' => JobApplication::count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'recent_applications' => JobApplication::where('created_at', '>=', now()->subDays(7))->count(),
            'internal_offers' => JobOffer::where('source', 'internal')->count(),
            'partner_offers' => JobOffer::where('source', 'partner')->count(),
        ];

        $monthlyApplications = JobApplication::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $popularJobTypes = JobOffer::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->pluck('count', 'type')
            ->toArray();

        return view('admin.job-offers.statistics', compact('stats', 'monthlyApplications', 'popularJobTypes'));
    }

    /**
     * Suggérer des critères basés sur le titre de l'offre
     */
    public function suggestCriteria(Request $request)
    {
        $title = $request->input('title');
        
        if (!$title) {
            return response()->json(['error' => 'Titre requis'], 400);
        }

        $suggestions = \App\Services\JobCriteriaService::getTemplatesByJobTitle($title);
        
        return response()->json($suggestions);
    }

    /**
     * Analyser les candidatures d'une offre pour suggérer des critères
     */
    public function analyzeJob(JobOffer $jobOffer)
    {
        $this->authorize('view', $jobOffer);

        $analysis = \App\Services\JobCriteriaService::analyzeCandidatesAndSuggest($jobOffer);
        
        return response()->json([
            'suggestions' => $analysis['suggestions'] ?? [],
            'message' => $analysis['message'] ?? '',
            'stats' => $analysis['stats'] ?? [],
            'analyzed_count' => $jobOffer->applications->count(),
            'confidence' => 0.85
        ]);
    }
}
