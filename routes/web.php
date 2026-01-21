<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActualiteController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\AuteurController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\RapportController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProjetController;
use App\Http\Controllers\Admin\EvenementController;
use App\Http\Controllers\Admin\EmailTestController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Site\SiteControllerTest;
use App\Http\Controllers\NewsletterController as PublicNewsletterController;
use App\Http\Controllers\Admin\JobOfferController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\RapportDownloadController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PartenaireController;
use App\Http\Controllers\Admin\SocialLinkController;

// ====================================================================
// ROUTES D'AUTHENTIFICATION (STANDARDS LARAVEL)
// ====================================================================

// Routes guest (authentification) - URLs standard Laravel
Route::middleware(['guest'])->group(function () {
    // Connexion
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Inscription
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    // Réinitialisation de mot de passe
    Route::get('/password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');
});

// Route de déconnexion (utilisateurs authentifiés) - Reste dans l'espace admin
Route::middleware(['auth'])->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// ====================================================================
// ROUTES FRONTEND (PUBLIQUES - SANS AUTHENTIFICATION)
// ====================================================================

// Routes principales du site public
Route::get('/', [SiteController::class, 'index'])->name('site.home');
Route::get('/galerie', [SiteController::class, 'galerie'])->name('site.galerie');
Route::get('/media/download/{id}', [SiteController::class, 'downloadMedia'])->name('site.media.download');

// Services
Route::get('/service', [SiteController::class, 'services'])->name('site.services');
Route::get('/service/{slug}', [SiteController::class, 'serviceshow'])->name('site.service.show');
Route::get('/service/{slug}/projets', [SiteController::class, 'serviceProjects'])->name('site.service.projets');
Route::get('/service/{slug}/actualites', [SiteController::class, 'serviceActualites'])->name('site.service.actualites');

// Projets
Route::get('/projets', [SiteController::class, 'projets'])->name('site.projets');
Route::get('/projet/{slug}', [SiteController::class, 'projetShow'])->name('site.projet.show');

// Publications
Route::get('/publications', [SiteController::class, 'publications'])->name('site.publications');
Route::get('/publications/{slug}', [SiteController::class, 'publicationShow'])->name('publication.show');
Route::get('/convert-image/{publication}', [SiteController::class, 'convertirImageUnique'])->name('publications.convert.single');

// Actualités
Route::get('/actualites', [SiteController::class, 'actualites'])->name('site.actualites');
Route::get('/actualite/{slug}', [SiteController::class, 'actualiteShow'])->name('site.actualite.show');
Route::get('/actualite-id/{id}', [SiteController::class, 'actualiteShowById'])->name('site.actualite.id');

// Rapports (Routes publiques)
Route::get('/rapports', [SiteController::class, 'rapports'])->name('site.rapports');
Route::get('/rapport/{slug}', [SiteController::class, 'rapportShow'])->name('site.rapport.show');

// Événements
Route::get('/evenements', [SiteController::class, 'evenements'])->name('site.evenements');
Route::get('/evenement/{slug}', [SiteController::class, 'evenementShow'])->name('site.evenement.show');

// À propos
Route::get('/about', [SiteController::class, 'about'])->name('site.about');
Route::get('/a-propos', fn() => redirect()->route('site.about'));

// Contact
Route::get('/contact', [SiteController::class, 'contact'])->name('site.contact');
Route::post('/contact', [SiteController::class, 'storeContact'])->name('site.contact.store');

// Travaillez avec nous et emploi (redirections)
Route::get('/work-with-us', [SiteController::class, 'workWithUs'])->name('site.work-with-us');
Route::get('/travaillez-avec-nous', [SiteController::class, 'workWithUs']);
Route::get('/travailler avec nous', fn() => redirect()->route('site.work-with-us'));
Route::get('/travaillez avec nous', fn() => redirect()->route('site.work-with-us'));
Route::get('/travailler-avec-nous', fn() => redirect()->route('site.work-with-us'));
Route::get('/emploi', fn() => redirect()->route('site.work-with-us'));
Route::get('/emplois', fn() => redirect()->route('site.work-with-us'));
Route::get('/carriere', fn() => redirect()->route('site.work-with-us'));
Route::get('/carrieres', fn() => redirect()->route('site.work-with-us'));

// Candidatures d'emploi
Route::get('/jobs/{job}/apply', [SiteController::class, 'showJobApplication'])->name('site.job.apply');
Route::post('/jobs/{job}/apply', [SiteController::class, 'submitJobApplication'])->name('site.job.apply.submit');
Route::get('/jobs/{job}/download', [SiteController::class, 'downloadJobDocument'])->name('site.job.download');

// Autres pages
Route::get('/partenariats', [SiteController::class, 'partenariats'])->name('site.partenariats');
Route::get('/recherche', [SiteController::class, 'search'])->name('site.search');
Route::get('/convert', [SiteController::class, 'convertirImage'])->name('site.convert');

// Plan du site
Route::get('/plan-du-site', function () {
    return view('sitemap');
})->name('site.sitemap');

// Newsletter publique
Route::post('/newsletter-subscribe', [SiteController::class, 'subscribeNewsletter'])->name('site.newsletter.subscribe');

// Route de test temporaire pour debug newsletter
Route::get('/test-newsletter', function() {
    return view('test-newsletter');
});

// Route de test simple pour diagnostic
Route::get('/test-simple', function() {
    return '<h1>Test Simple OK</h1><p>Cette page fonctionne !</p>';
})->name('test.simple');

// Route de test pour audit des actualités 2x4 (sans DB)
Route::get('/test-actualites-audit', function() {
    return view('test-actualites-simple');
})->name('test.actualites.audit');

// Route de test complète avec BD (nécessite MySQL)
Route::get('/test-actualites-complete', function() {
    return view('test-actualites-audit');
})->name('test.actualites.complete');

// Route d'audit CSS pour diagnostiquer les conflits
Route::get('/audit-css-conflits', function() {
    return view('audit-css-conflits');
})->name('audit.css.conflits');

// Route de test pour vérifier la correction - Vue simplifiée
Route::get('/test-correction-actualites', function() {
    return view('test-correction-grille');
})->name('test.correction.actualites');

// Test grille finale avec CSS override
Route::get('/test-grille-finale', function() {
    return view('test-grille-finale');
})->name('test.grille.finale');

// Test styles titre page principale
Route::get('/test-styles-titre', function() {
    return view('test-styles-titre');
})->name('test.styles.titre');

Route::post('/test-newsletter-submit', function(Illuminate\Http\Request $request) {
    try {
        \Log::info('Test newsletter form submission', [
            'data' => $request->all(),
            'headers' => $request->headers->all()
        ]);
        
        // Test simple d'insertion sans validation
        $token = bin2hex(random_bytes(32));
        $inserted = \DB::table('newsletters')->insert([
            'email' => $request->email,
            'nom' => 'Test User',
            'token' => $token,
            'actif' => 1,
            'preferences' => json_encode(['actualites' => true, 'publications' => true]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        if ($inserted) {
            // Test d'envoi d'email
            try {
                $newsletter = \DB::table('newsletters')->where('email', $request->email)->first();
                if ($newsletter) {
                    // Créer un modèle Newsletter pour le service
                    $newsletterModel = new \App\Models\Newsletter();
                    $newsletterModel->fill([
                        'id' => $newsletter->id,
                        'email' => $newsletter->email,
                        'nom' => $newsletter->nom,
                        'token' => $newsletter->token,
                        'actif' => $newsletter->actif,
                        'preferences' => json_decode($newsletter->preferences, true)
                    ]);
                    $newsletterModel->exists = true;
                    
                    $newsletterService = app(\App\Services\NewsletterService::class);
                    $emailSent = $newsletterService->sendWelcomeEmail($newsletterModel);
                    
                    if ($emailSent) {
                        return back()->with('success', 'Test réussi ! Email enregistré ET email de bienvenue envoyé.');
                    } else {
                        return back()->with('success', 'Email enregistré mais échec envoi email de bienvenue. Vérifiez config mail.');
                    }
                }
            } catch (\Exception $emailError) {
                \Log::error('Test email sending failed', ['error' => $emailError->getMessage()]);
                return back()->with('success', 'Email enregistré mais erreur envoi: ' . $emailError->getMessage());
            }
            
            return back()->with('success', 'Test réussi ! Email enregistré.');
        } else {
            return back()->with('error', 'Échec de l\'insertion.');
        }
        
    } catch (\Exception $e) {
        \Log::error('Test newsletter error', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur: ' . $e->getMessage());
    }
});

// Test avec validation allégée
Route::post('/test-newsletter-light', function(Illuminate\Http\Request $request) {
    try {
        // Validation simple
        $request->validate([
            'email' => 'required|email|max:255',
        ]);
        
        \Log::info('Test newsletter light validation passed', ['email' => $request->email]);
        
        // Appeler le contrôleur original mais bypass certaines validations
        $controller = new \App\Http\Controllers\Site\SiteController();
        
        // Créer une requête modifiée
        $modifiedRequest = new \Illuminate\Http\Request();
        $modifiedRequest->merge([
            'email' => $request->email,
            'nom' => 'Test User Light',
            'preferences' => ['actualites', 'publications'],
            'redirect_url' => url()->current(),
            'start_time' => time()
        ]);
        $modifiedRequest->setMethod('POST');
        
        // Test direct du contrôleur (bypass FormRequest)
        return $controller->subscribeNewsletter($modifiedRequest);
        
    } catch (\Exception $e) {
        \Log::error('Test newsletter light error', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur validation light: ' . $e->getMessage());
    }
});

// Test avec copie exacte de la logique du contrôleur SANS FormRequest
Route::post('/test-newsletter-manual', function(Illuminate\Http\Request $request) {
    try {
        \Log::info('🔍 Manual newsletter test started', ['data' => $request->all()]);
        
        // Validation manuelle simple
        $request->validate([
            'email' => 'required|email|max:255',
        ]);
        
        $ip = $request->ip();
        $validatedData = [
            'email' => $request->email,
            'nom' => $request->nom ?: 'Test User Manual',
        ];
        
        \Log::info('Manual test - data validated', $validatedData);
        
        // Préférences par défaut
        $preferences = ['actualites' => true, 'publications' => true];
        
        \Log::info('Manual test - starting transaction');
        
        // Transaction
        \DB::beginTransaction();
        
        try {
            // Vérifier si email existe
            $existing = \DB::table('newsletters')
                          ->where('email', $validatedData['email'])
                          ->lockForUpdate()
                          ->first();
            
            \Log::info('Manual test - checked existing', ['existing' => $existing ? 'found' : 'not found']);
            
            if ($existing) {
                if ($existing->actif) {
                    \DB::rollback();
                    \Log::info('Manual test - email already active');
                    return back()->with('info', 'Cette adresse email est déjà inscrite à notre newsletter.');
                } else {
                    // Réactiver
                    \DB::table('newsletters')
                        ->where('email', $validatedData['email'])
                        ->update([
                            'actif' => 1,
                            'nom' => $validatedData['nom'],
                            'preferences' => json_encode($preferences),
                            'updated_at' => now(),
                        ]);
                    
                    \DB::commit();
                    \Log::info('Manual test - reactivated successfully');
                    return back()->with('success', 'Newsletter réactivée avec succès !');
                }
            }

            // Nouvelle inscription
            $token = bin2hex(random_bytes(32));
            $inserted = \DB::table('newsletters')->insert([
                'email' => $validatedData['email'],
                'nom' => $validatedData['nom'],
                'token' => $token,
                'actif' => 1,
                'preferences' => json_encode($preferences),
                'emails_sent_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($inserted) {
                \DB::commit();
                \Log::info('Manual test - inserted successfully');
                
                // Test envoi email
                try {
                    $newsletter = \DB::table('newsletters')->where('email', $validatedData['email'])->first();
                    if ($newsletter) {
                        $newsletterModel = new \App\Models\Newsletter();
                        $newsletterModel->fill([
                            'id' => $newsletter->id,
                            'email' => $newsletter->email,
                            'nom' => $newsletter->nom,
                            'token' => $newsletter->token,
                            'actif' => $newsletter->actif,
                            'preferences' => json_decode($newsletter->preferences, true)
                        ]);
                        $newsletterModel->exists = true;
                        
                        $newsletterService = app(\App\Services\NewsletterService::class);
                        $emailSent = $newsletterService->sendWelcomeEmail($newsletterModel);
                        
                        if ($emailSent) {
                            \Log::info('Manual test - email sent successfully');
                            return back()->with('success', 'Inscription réussie ! Email de bienvenue envoyé.');
                        } else {
                            \Log::warning('Manual test - email failed');
                            return back()->with('success', 'Inscription réussie ! (Email de bienvenue échoué)');
                        }
                    }
                } catch (\Exception $emailError) {
                    \Log::error('Manual test - email error', ['error' => $emailError->getMessage()]);
                    return back()->with('success', 'Inscription réussie ! (Erreur email: ' . $emailError->getMessage() . ')');
                }
                
                return back()->with('success', 'Inscription réussie !');
            } else {
                \DB::rollback();
                \Log::error('Manual test - insert failed');
                return back()->with('error', 'Erreur lors de l\'enregistrement.');
            }
            
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
        
    } catch (\Exception $e) {
        \Log::error('Manual test - global error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Erreur: ' . $e->getMessage());
    }
});

// Test de configuration email
Route::post('/test-email-config', function(Illuminate\Http\Request $request) {
    try {
        $testEmail = $request->test_email;
        
        // Test simple d'envoi d'email
        \Mail::raw('Ceci est un email de test de configuration.', function ($message) use ($testEmail) {
            $message->to($testEmail)
                   ->subject('Test Configuration Email - Newsletter Debug');
        });
        
        \Log::info('Test email sent successfully', ['to' => $testEmail]);
        return back()->with('success', 'Email de test envoyé avec succès à ' . $testEmail);
        
    } catch (\Exception $e) {
        \Log::error('Test email config failed', [
            'error' => $e->getMessage(),
            'to' => $request->test_email
        ]);
        return back()->with('error', 'Erreur config email: ' . $e->getMessage());
    }
});

// Test avec contrôleur dédié (sans FormRequest)
Route::post('/test-newsletter-controller', [SiteControllerTest::class, 'subscribeNewsletterTest']);

// Test pour vérifier si la route originale est accessible
Route::post('/test-route-access', function(Illuminate\Http\Request $request) {
    \Log::info('🚨 Route test accessed', ['data' => $request->all()]);
    
    try {
        // Appeler directement la route originale mais avec des logs
        $response = app()->handle(
            Illuminate\Http\Request::create('/newsletter-subscribe', 'POST', $request->all(), [], [], $request->server->all())
        );
        
        \Log::info('🚨 Route response', ['status' => $response->getStatusCode()]);
        
        return back()->with('success', 'Route accessible - Status: ' . $response->getStatusCode());
        
    } catch (\Exception $e) {
        \Log::error('🚨 Route access error', [
            'error' => $e->getMessage(),
            'class' => get_class($e),
            'line' => $e->getLine(),
            'file' => basename($e->getFile())
        ]);
        return back()->with('error', 'Erreur route: ' . $e->getMessage());
    }
});

// Test pour déboguer le FormRequest spécifiquement
Route::post('/test-formrequest-debug', function(Illuminate\Http\Request $request) {
    \Log::info('🐛 FormRequest debug - Raw data', ['all' => $request->all()]);
    
    try {
        // Créer une instance du FormRequest et tester la validation
        $formRequest = new \App\Http\Requests\NewsletterSubscriptionRequest();
        
        // Simuler la requête
        $formRequest->setContainer(app());
        $formRequest->replace($request->all());
        $formRequest->setMethod('POST');
        
        // Tester l'autorisation
        $authorized = $formRequest->authorize();
        \Log::info('🐛 FormRequest authorization', ['authorized' => $authorized]);
        
        if (!$authorized) {
            return back()->with('error', 'FormRequest - Authorization failed');
        }
        
        // Tester les règles
        $rules = $formRequest->rules();
        \Log::info('🐛 FormRequest rules', ['rules' => $rules]);
        
        // Tester la validation manuelle
        $validator = \Validator::make($request->all(), $rules, $formRequest->messages());
        
        if ($validator->fails()) {
            \Log::info('🐛 FormRequest validation failed', ['errors' => $validator->errors()->toArray()]);
            return back()->with('error', 'Validation échouée: ' . implode(', ', $validator->errors()->all()));
        }
        
        \Log::info('🐛 FormRequest validation passed', ['validated' => $validator->validated()]);
        return back()->with('success', 'FormRequest validation réussie ! Le problème est ailleurs.');
        
    } catch (\Exception $e) {
        \Log::error('🐛 FormRequest debug error', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Erreur FormRequest debug: ' . $e->getMessage());
    }
});

// Routes Newsletter publiques détaillées
Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::get('/subscribe', [PublicNewsletterController::class, 'showSubscribeForm'])->name('subscribe');
    Route::post('/subscribe', [PublicNewsletterController::class, 'subscribe'])->name('subscribe.post');
    Route::get('/preferences/{token}', [PublicNewsletterController::class, 'preferences'])->name('preferences');
    Route::put('/preferences/{token}', [PublicNewsletterController::class, 'updatePreferences'])->name('preferences.update');
    Route::get('/unsubscribe/{token}', [PublicNewsletterController::class, 'unsubscribe'])->name('unsubscribe');
    Route::post('/unsubscribe/{token}', [PublicNewsletterController::class, 'confirmUnsubscribe'])->name('unsubscribe.confirm');
    Route::get('/resubscribe/{token}', [PublicNewsletterController::class, 'resubscribe'])->name('resubscribe');
});

// ====================================================================
// ROUTES ADMIN (PROTÉGÉES PAR AUTHENTIFICATION)
// ====================================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Test d'email (admin seulement)
    Route::middleware(['can:viewAny,App\Models\User'])->prefix('email-test')->name('email-test.')->group(function () {
        Route::get('/', [EmailTestController::class, 'index'])->name('index');
        Route::post('/send', [EmailTestController::class, 'send'])->name('send');
        Route::get('/config', [EmailTestController::class, 'testConfig'])->name('config');
        Route::post('/connection', [EmailTestController::class, 'testConnection'])->name('connection');
        Route::get('/password-reset', [EmailTestController::class, 'testPasswordReset'])->name('password-reset');
    });
    
    // Gestion des utilisateurs (admin seulement)
    Route::middleware(['can:manage_users'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/{user}/permissions', [UserController::class, 'managePermissions'])->name('manage-permissions');
        Route::put('/{user}/permissions', [UserController::class, 'updatePermissions'])->name('update-permissions');
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{user}/update-status-roles', [UserController::class, 'updateStatusAndRoles'])->name('update-status-roles');
    });

    // ================================
    // GESTION DES ACTUALITÉS
    // ================================
    Route::prefix('actualite')->name('actualite.')->group(function () {
        Route::get('/', [ActualiteController::class, 'index'])->name('index');
        Route::get('/create', [ActualiteController::class, 'create'])->name('create');
        Route::post('/', [ActualiteController::class, 'store'])->name('store');
        Route::post('/upload-image', [ActualiteController::class, 'uploadImage'])->name('upload-image');
        Route::get('/test-ckeditor', function() { return view('admin.test-ckeditor'); })->name('test-ckeditor');
        Route::get('/test-simple-ckeditor', function() { return view('admin.test-simple-ckeditor'); })->name('test-simple-ckeditor');
        Route::get('/diagnostic-tinymce', function() { return view('admin.actualite.create_diagnostic'); })->name('diagnostic-tinymce');
        Route::get('/pending-moderation', [ActualiteController::class, 'pendingModeration'])->name('pending');
        Route::get('/view/{actualite}', [ActualiteController::class, 'show'])->name('show');
        Route::get('/{actualite}/edit', [ActualiteController::class, 'edit'])->name('edit');
        Route::put('/{actualite}', [ActualiteController::class, 'update'])->name('update');
        Route::delete('/{actualite}', [ActualiteController::class, 'destroy'])->name('destroy');
        Route::put('/{actualite}/updatefeatures', [ActualiteController::class, 'updateFeatures'])->name('updatefeatures');
        Route::post('/{actualite}/toggle-une', [ActualiteController::class, 'toggleUne'])->name('toggle-une');
        Route::post('/{actualite}/publish', [ActualiteController::class, 'publish'])->name('publish');
        Route::post('/{actualite}/unpublish', [ActualiteController::class, 'unpublish'])->name('unpublish');
        Route::patch('/{actualite}/moderate', [ActualiteController::class, 'moderate'])->name('moderate');
        Route::post('/{actualite}/attach-rapports', [ActualiteController::class, 'attachRapports'])->name('attach-rapports');
        Route::delete('/{actualite}/detach-rapport/{rapport}', [ActualiteController::class, 'detachRapport'])->name('detach-rapport');
    });

    // ================================
    // GESTION DES PUBLICATIONS
    // ================================
    Route::prefix('publication')->name('publication.')->group(function () {
        Route::get('/', [PublicationController::class, 'index'])->name('index');
        Route::get('/create', [PublicationController::class, 'create'])->name('create');
        Route::post('/', [PublicationController::class, 'store'])->name('store');
        Route::get('/{publication}', [PublicationController::class, 'show'])->name('show');
        Route::get('/{publication}/edit', [PublicationController::class, 'edit'])->name('edit');
        Route::put('/{publication}', [PublicationController::class, 'update'])->name('update');
        Route::delete('/{publication}', [PublicationController::class, 'destroy'])->name('destroy');
        Route::put('/{publication}/updatefeatures', [PublicationController::class, 'updateFeatures'])->name('updatefeatures');
        Route::post('/{publication}/toggle-une', [PublicationController::class, 'toggleUne'])->name('toggle-une');
        
        // Routes de modération (protégées)
        Route::middleware(['can_moderate'])->group(function () {
            Route::post('/{publication}/publish', [PublicationController::class, 'publish'])->name('publish');
            Route::post('/{publication}/unpublish', [PublicationController::class, 'unpublish'])->name('unpublish');
            Route::get('/pending-moderation', [PublicationController::class, 'pendingModeration'])->name('pending');
        });
    });

    // ================================
    // GESTION DES AUTEURS
    // ================================
    
    // Routes AJAX pour auteurs (pour les modals de publication) - AVANT les routes génériques
    Route::prefix('auteurs')->name('auteurs.')->group(function () {
        Route::get('/search', [AuteurController::class, 'search'])->name('search');
        Route::post('/', [AuteurController::class, 'store'])->name('store-ajax');
    });

    // Routes CRUD classiques pour auteurs
    Route::prefix('auteur')->name('auteur.')->group(function () {
        Route::get('/', [AuteurController::class, 'index'])->name('index');
        Route::get('/create', [AuteurController::class, 'create'])->name('create');
        Route::post('/', [AuteurController::class, 'store'])->name('store');
        Route::get('/{auteur}/edit', [AuteurController::class, 'edit'])->name('edit');
        Route::put('/{auteur}', [AuteurController::class, 'update'])->name('update');
        Route::delete('/{auteur}', [AuteurController::class, 'destroy'])->name('destroy');
        Route::get('/{auteur}/show', [AuteurController::class, 'show'])->name('show');
    });

    // ================================
    // GESTION DES SERVICES
    // ================================
    Route::prefix('service')->name('service.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::get('/{service}/show', [ServiceController::class, 'show'])->name('show');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
        
        // Routes de modération
        Route::post('/{service}/publish', [ServiceController::class, 'publish'])->name('publish');
        Route::post('/{service}/unpublish', [ServiceController::class, 'unpublish'])->name('unpublish');
        Route::post('/{service}/toggle-menu', [ServiceController::class, 'toggleMenu'])->name('toggle-menu');
        Route::get('/pending-moderation', [ServiceController::class, 'pendingModeration'])->name('pending');
    });

    // ================================
    // GESTION DES PARTENAIRES
    // ================================
    Route::prefix('partenaires')->name('partenaires.')->group(function () {
        Route::get('/', [PartenaireController::class, 'index'])->name('index');
        Route::get('/create', [PartenaireController::class, 'create'])->name('create');
        Route::post('/', [PartenaireController::class, 'store'])->name('store');
        Route::get('/{partenaire}', [PartenaireController::class, 'show'])->name('show');
        Route::get('/{partenaire}/edit', [PartenaireController::class, 'edit'])->name('edit');
        Route::put('/{partenaire}', [PartenaireController::class, 'update'])->name('update');
        Route::delete('/{partenaire}', [PartenaireController::class, 'destroy'])->name('destroy');
        Route::patch('/{partenaire}/toggle-publication', [PartenaireController::class, 'togglePublication'])->name('toggle-publication');
    });

    // ================================
    // GESTION DES LIENS SOCIAUX
    // ================================
    Route::prefix('social-links')->name('social-links.')->group(function () {
        Route::get('/', [SocialLinkController::class, 'index'])->name('index');
        Route::get('/create', [SocialLinkController::class, 'create'])->name('create');
        Route::post('/', [SocialLinkController::class, 'store'])->name('store');
        Route::get('/{socialLink}', [SocialLinkController::class, 'show'])->name('show');
        Route::get('/{socialLink}/edit', [SocialLinkController::class, 'edit'])->name('edit');
        Route::put('/{socialLink}', [SocialLinkController::class, 'update'])->name('update');
        Route::delete('/{socialLink}', [SocialLinkController::class, 'destroy'])->name('destroy');
        Route::patch('/{socialLink}/toggle-active', [SocialLinkController::class, 'toggleActive'])->name('toggle-active');
    });

    // ================================
    // GESTION DES NEWSLETTERS
    // ================================
    Route::prefix('newsletter')->name('newsletter.')->group(function () {
        Route::get('/', [NewsletterController::class, 'index'])->name('index');
        Route::get('/create', [NewsletterController::class, 'create'])->name('create');
        Route::post('/', [NewsletterController::class, 'store'])->name('store');
        Route::get('/{newsletter}/edit', [NewsletterController::class, 'edit'])->name('edit');
        Route::put('/{newsletter}', [NewsletterController::class, 'update'])->name('update');
        Route::delete('/{newsletter}', [NewsletterController::class, 'destroy'])->name('destroy');
        Route::get('/export', [NewsletterController::class, 'export'])->name('export');
        Route::get('/{newsletter}', [NewsletterController::class, 'show'])->name('show');
        Route::patch('/{newsletter}/toggle', [NewsletterController::class, 'toggle'])->name('toggle');
    });

    // ================================
    // ROUTES API POUR LES MODALS (AJAX)
    // ================================
    
    // API pour recherche d'auteurs (modal publication)
    Route::prefix('api/auteurs')->name('api.auteurs.')->group(function () {
        Route::get('/search', [AuteurController::class, 'searchApi'])->name('search');
        Route::post('/', [AuteurController::class, 'storeApi'])->name('store');
    });
    
    // ================================
    // GESTION DES CATÉGORIES
    // ================================
    
    // Routes AJAX pour catégories (pour les modals de publication) - AVANT les routes génériques
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::post('/', [CategorieController::class, 'store'])->name('store-ajax');
    });

    // Routes CRUD classiques pour catégories
    Route::prefix('categorie')->name('categorie.')->group(function () {
        Route::get('/', [CategorieController::class, 'index'])->name('index');
        Route::get('/create', [CategorieController::class, 'create'])->name('create');
        Route::post('/', [CategorieController::class, 'store'])->name('store');
        Route::get('/{categorie}', [CategorieController::class, 'show'])->name('show');
        Route::get('/{categorie}/edit', [CategorieController::class, 'edit'])->name('edit');
        Route::put('/{categorie}', [CategorieController::class, 'update'])->name('update');
        Route::delete('/{categorie}', [CategorieController::class, 'destroy'])->name('destroy');
    });

    // ================================
    // GESTION DES RAPPORTS
    // ================================
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::get('/search', [RapportController::class, 'search'])->name('search');
        Route::get('/category/{categorieId?}', [RapportController::class, 'byCategory'])->name('category');
        Route::get('/create', [RapportController::class, 'create'])->name('create');
        Route::post('/', [RapportController::class, 'store'])->name('store');
        Route::post('/store-multiple', [RapportController::class, 'storeMultiple'])->name('store-multiple');
        Route::get('/{rapport}', [RapportController::class, 'show'])->name('show');
        Route::get('/{rapport}/download', [RapportController::class, 'download'])->name('download');
        Route::get('/{rapport}/preview', [RapportController::class, 'preview'])->name('preview');
        Route::get('/{rapport}/edit', [RapportController::class, 'edit'])->name('edit');
        Route::put('/{rapport}', [RapportController::class, 'update'])->name('update');
        Route::delete('/{rapport}', [RapportController::class, 'destroy'])->name('destroy');
        
        // Routes pour les actions rapides
        Route::post('/delete-multiple', [RapportController::class, 'deleteMultiple'])->name('delete-multiple');
        Route::get('/export', [RapportController::class, 'export'])->name('export');
        Route::get('/download-zip', [RapportController::class, 'downloadZip'])->name('download-zip');
        
        // Routes de modération (conservées pour compatibilité)
        Route::post('/{rapport}/publish', [RapportController::class, 'publish'])->name('publish');
        Route::post('/{rapport}/unpublish', [RapportController::class, 'unpublish'])->name('unpublish');
        Route::get('/pending-moderation', [RapportController::class, 'pendingModeration'])->name('pending');
    });

    // ================================
    // GESTION DES PROJETS
    // ================================
    Route::prefix('projets')->name('projets.')->group(function () {
        Route::get('/', [ProjetController::class, 'index'])->name('index');
        Route::post('/search', [ProjetController::class, 'search'])->name('search');
        Route::get('/create', [ProjetController::class, 'create'])->name('create');
        Route::post('/', [ProjetController::class, 'store'])->name('store');
        Route::get('/{projet}', [ProjetController::class, 'show'])->name('show');
        Route::get('/{projet}/edit', [ProjetController::class, 'edit'])->name('edit');
        Route::put('/{projet}', [ProjetController::class, 'update'])->name('update');
        Route::delete('/{projet}', [ProjetController::class, 'destroy'])->name('destroy');
        
        // Routes de modération
        Route::post('/{projet}/publish', [ProjetController::class, 'publish'])->name('publish');
        Route::post('/{projet}/unpublish', [ProjetController::class, 'unpublish'])->name('unpublish');
        Route::get('/pending-moderation', [ProjetController::class, 'pendingModeration'])->name('pending');
        
        // Routes pour les rapports associés
        Route::post('/{projet}/attach-rapports', [ProjetController::class, 'attachRapports'])->name('attach-rapports');
        Route::delete('/{projet}/detach-rapport/{rapport}', [ProjetController::class, 'detachRapport'])->name('detach-rapport');
    });

    // ================================
    // GESTION DES ÉVÉNEMENTS
    // ================================
    Route::resource('evenements', EvenementController::class);
    Route::patch('/evenements/{evenement}/toggle-featured', [EvenementController::class, 'toggleFeatured'])->name('evenements.toggle-featured');
    Route::patch('/evenements/{evenement}/toggle-published', [EvenementController::class, 'togglePublished'])->name('evenements.toggle-published');

    // ================================
    // GESTION DES CONTACTS
    // ================================
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('index');
        Route::get('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('show');
        Route::patch('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'update'])->name('update');
        Route::delete('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('destroy');
    });

    // ================================
    // CONFIGURATION DES EMAILS
    // ================================
    Route::prefix('email-settings')->name('email-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\EmailSettingController::class, 'index'])->name('index');
        Route::put('/{emailSetting}', [\App\Http\Controllers\Admin\EmailSettingController::class, 'update'])->name('update');
        Route::post('/{emailSetting}/add-email', [\App\Http\Controllers\Admin\EmailSettingController::class, 'addEmail'])->name('add-email');
        Route::delete('/{emailSetting}/remove-email', [\App\Http\Controllers\Admin\EmailSettingController::class, 'removeEmail'])->name('remove-email');
        Route::post('/test-email', [\App\Http\Controllers\Admin\EmailSettingController::class, 'testEmail'])->name('test-email');
    });

    // ================================
    // CONFIGURATION DES INFORMATIONS DE CONTACT
    // ================================
    Route::prefix('contact-info')->name('contact-info.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ContactInfoController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ContactInfoController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ContactInfoController::class, 'store'])->name('store');
        Route::get('/{contactInfo}/edit', [\App\Http\Controllers\Admin\ContactInfoController::class, 'edit'])->name('edit');
        Route::put('/{contactInfo}', [\App\Http\Controllers\Admin\ContactInfoController::class, 'update'])->name('update');
        Route::delete('/{contactInfo}', [\App\Http\Controllers\Admin\ContactInfoController::class, 'destroy'])->name('destroy');
        Route::post('/{contactInfo}/toggle-active', [\App\Http\Controllers\Admin\ContactInfoController::class, 'toggleActive'])->name('toggle-active');
    });

    // ================================
    // GESTION DES OFFRES D'EMPLOI
    // ================================
    Route::resource('job-offers', JobOfferController::class);
    Route::post('/job-offers/{jobOffer}/duplicate', [JobOfferController::class, 'duplicate'])->name('job-offers.duplicate');
    Route::post('/job-offers/{jobOffer}/change-status', [JobOfferController::class, 'changeStatus'])->name('job-offers.change-status');
    Route::post('/job-offers/{jobOffer}/toggle-featured', [JobOfferController::class, 'toggleFeatured'])->name('job-offers.toggle-featured');
    Route::get('/job-offers-statistics', [JobOfferController::class, 'statistics'])->name('job-offers.statistics');
    
    // Routes pour les critères d'évaluation
    Route::post('/job-offers/suggest-criteria', [JobOfferController::class, 'suggestCriteria'])->name('job-offers.suggest-criteria');
    Route::post('/job-offers/{jobOffer}/analyze', [JobOfferController::class, 'analyzeJob'])->name('job-offers.analyze');
    
    // ================================
    // GESTION DES CANDIDATURES
    // ================================
    Route::prefix('job-applications')->name('job-applications.')->group(function () {
        Route::get('/', [JobApplicationController::class, 'index'])->name('index');
        Route::get('/{application}', [JobApplicationController::class, 'show'])->name('show');
        Route::patch('/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('update-status');
        Route::get('/{application}/download-cv', [JobApplicationController::class, 'downloadCV'])->name('download-cv');
        Route::get('/{application}/download-portfolio', [JobApplicationController::class, 'downloadPortfolio'])->name('download-portfolio');
        Route::delete('/{application}', [JobApplicationController::class, 'destroy'])->name('destroy');
        Route::get('/export', [JobApplicationController::class, 'export'])->name('export');
        Route::post('/bulk-review', [JobApplicationController::class, 'bulkReview'])->name('bulk-review');
        Route::get('/statistics', [JobApplicationController::class, 'statistics'])->name('statistics');
    });

    // ================================
    // GESTION DES MÉDIAS (dans le groupe admin)
    // ================================
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::get('/create', [MediaController::class, 'create'])->name('create');
        Route::post('/', [MediaController::class, 'store'])->name('store');
        
        // For CKEditor media integration - DOIT ÊTRE AVANT LES ROUTES AVEC PARAMÈTRES
        Route::get('/list', [MediaController::class, 'list'])->name('list');
        Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
        Route::get('/api', [MediaController::class, 'apiList'])->name('api');
        
        Route::get('/{media}', [MediaController::class, 'show'])->name('show');
        Route::get('/{media}/edit', [MediaController::class, 'edit'])->name('edit');
        Route::put('/{media}', [MediaController::class, 'update'])->name('update');
        Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
        
        // Actions de modération
        Route::post('/{media}/approve', [MediaController::class, 'approve'])->name('approve');
        Route::post('/{media}/reject', [MediaController::class, 'reject'])->name('reject');
        Route::post('/{media}/publish', [MediaController::class, 'publish'])->name('publish');
        Route::post('/{media}/unpublish', [MediaController::class, 'unpublish'])->name('unpublish');
        
        // Actions utilitaires
        Route::get('/{media}/download', [MediaController::class, 'download'])->name('download');
        Route::get('/{media}/copy-link', [MediaController::class, 'copyLink'])->name('copyLink');

    });
});

// ====================================================================
// ROUTES PUBLIQUES DE TÉLÉCHARGEMENT DE RAPPORTS (HORS AUTHENTIFICATION)
// ====================================================================
Route::post('/rapport/{id}/validate-email', [RapportDownloadController::class, 'validateEmail'])->name('rapport.validate-email');
Route::get('/rapport/{id}/download', [RapportDownloadController::class, 'download'])->name('rapport.download');

// ====================================================================
// ROUTES POUR LES IMAGES PLACEHOLDER ET API UTILITIES
// ====================================================================

// Routes publiques pour les images placeholder (sans authentification)
Route::prefix('api/placeholder')->name('api.placeholder.')->group(function () {
    Route::get('/actualite-image', [App\Http\Controllers\Api\PlaceholderImageController::class, 'generateActualiteImage'])
        ->name('actualite.image');
    
    Route::get('/generic-image', [App\Http\Controllers\Api\PlaceholderImageController::class, 'generateGenericImage'])
        ->name('generic.image');
});

// Alias pour compatibilité avec le modèle Actualite
Route::get('/actualite/placeholder-image', [App\Http\Controllers\Api\PlaceholderImageController::class, 'generateActualiteImage'])
    ->name('actualite.placeholder.image');