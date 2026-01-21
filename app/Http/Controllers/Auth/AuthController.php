<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Afficher la page de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion avec sécurité renforcée
     */
    public function login(Request $request)
    {
        // Validation stricte des données
        $credentials = $request->validate([
            'email' => [
                'required',
                'email:rfc',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'password' => [
                'required',
                'string',
                'max:128',
            ]
        ], [
            'email.regex' => 'Format d\'email invalide.',
        ]);

        // Nettoyage des données
        $credentials['email'] = strtolower(trim($credentials['email']));

        // Protection contre les attaques par force brute
        $key = 'login_attempts_' . $request->ip();
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 5) {
            \Log::warning('Tentative de connexion bloquée - trop d\'essais', [
                'ip' => $request->ip(),
                'email' => $credentials['email'],
                'attempts' => $attempts,
            ]);
            
            return back()->withErrors([
                'email' => 'Trop de tentatives de connexion. Veuillez réessayer dans 15 minutes.',
            ])->onlyInput('email');
        }

        // Vérifier si l'utilisateur existe et est actif
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && !$user->active) {
            cache()->put($key, $attempts + 1, now()->addMinutes(15));
            
            \Log::warning('Tentative de connexion sur compte inactif', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);
            
            return back()->withErrors([
                'email' => 'Votre compte n\'est pas encore activé. Contactez un administrateur.',
            ])->onlyInput('email');
        }

        // Tentative de connexion
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Réinitialiser le compteur de tentatives
            cache()->forget($key);
            
            // Log de connexion réussie
            \Log::info('Connexion réussie', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return redirect()->intended(route('admin.dashboard'))
                           ->with('success', 'Connexion réussie ! Bienvenue ' . Auth::user()->name);
        }

        // Incrémenter le compteur de tentatives
        cache()->put($key, $attempts + 1, now()->addMinutes(15));
        
        // Log de tentative échouée
        \Log::warning('Tentative de connexion échouée', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
            'attempts' => $attempts + 1,
        ]);

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent à aucun compte.',
        ])->onlyInput('email');
    }

    /**
     * Afficher la page d'inscription
     */
    public function showRegisterForm()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    /**
     * Traiter l'inscription avec sécurité renforcée
     */
    public function register(Request $request)
    {
        // Protection CSRF automatique par Laravel middleware
        
        // Validation stricte des données côté serveur
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/u', // Seulement lettres, espaces, traits d'union, apostrophes et points
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns', // Validation DNS
                'max:255',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', // Format email strict
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:128',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', // Mot de passe fort
            ],
        ], [
            'name.regex' => 'Le nom ne peut contenir que des lettres, espaces, traits d\'union et apostrophes.',
            'email.regex' => 'Format d\'email invalide.',
            'email.dns' => 'Le domaine email n\'existe pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.',
        ]);

        // Protection contre les injections - nettoyage des données
        $validated['name'] = trim(strip_tags($validated['name']));
        $validated['email'] = strtolower(trim($validated['email']));

        // Vérification supplémentaire contre les mots de passe communs
        $commonPasswords = [
            'password', '12345678', 'qwerty123', 'admin123', 'password123',
            'motdepasse', 'azerty123', '123456789', 'password1'
        ];
        
        if (in_array(strtolower($validated['password']), $commonPasswords)) {
            return back()->withErrors([
                'password' => 'Ce mot de passe est trop commun. Veuillez en choisir un autre.'
            ])->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            // Création de l'utilisateur avec données sécurisées
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => null, // Email non vérifié par défaut
                'active' => false, // Compte inactif par défaut
            ]);

            // Ne pas assigner de rôle par défaut - sécurité renforcée
            // L'utilisateur n'aura aucune permission au départ
            // Un administrateur devra activer le compte et assigner des rôles

            // Log de sécurité
            \Log::info('Nouvelle inscription utilisateur', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Ne pas connecter automatiquement - sécurité renforcée
            return redirect()->route('login')
                           ->with('success', 'Compte créé avec succès ! Votre compte doit être activé par un administrateur avant de pouvoir vous connecter.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'inscription', [
                'error' => $e->getMessage(),
                'email' => $validated['email'],
                'ip' => $request->ip(),
            ]);

            return back()->withErrors([
                'email' => 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.'
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
                       ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Afficher le formulaire de demande de réinitialisation
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Envoyer l'email de réinitialisation avec sécurité renforcée
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validation stricte
        $request->validate([
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ]
        ], [
            'email.regex' => 'Format d\'email invalide.',
            'email.dns' => 'Le domaine email n\'existe pas.',
        ]);

        // Protection contre le spam de réinitialisation
        $email = strtolower(trim($request->email));
        $key = 'password_reset_' . md5($email);
        
        if (cache()->has($key)) {
            return back()->withErrors([
                'email' => 'Un email de réinitialisation a déjà été envoyé. Veuillez attendre 5 minutes avant de réessayer.'
            ]);
        }

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $email)->first();
        if (!$user) {
            // Ne pas révéler si l'email existe ou non (sécurité)
            return back()->with(['status' => 'Si votre adresse email est enregistrée, vous recevrez un lien de réinitialisation.']);
        }

        // Mettre en cache pour éviter le spam (5 minutes)
        cache()->put($key, true, now()->addMinutes(5));

        try {
            $status = Password::sendResetLink(
                ['email' => $email]
            );

            // Log de sécurité
            \Log::info('Demande de réinitialisation mot de passe', [
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => $status,
            ]);

            return $status === Password::RESET_LINK_SENT
                        ? back()->with(['status' => 'Si votre adresse email est enregistrée, vous recevrez un lien de réinitialisation.'])
                        : back()->withErrors(['email' => 'Une erreur est survenue. Veuillez réessayer.']);
                        
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email réinitialisation', [
                'email' => $email,
                'error' => $e->getMessage(),
                'mail_config' => [
                    'from' => config('mail.from'),
                    'mailer' => config('mail.default')
                ]
            ]);
            
            return back()->withErrors(['email' => 'Erreur lors de l\'envoi de l\'email. Veuillez contacter l\'administrateur.']);
        }
    }

    /**
     * Afficher le formulaire de réinitialisation avec validation du token
     */
    public function showResetForm(Request $request, $token = null)
    {
        // Validation du token
        if (!$token || strlen($token) !== 64) {
            return redirect()->route('password.request')
                           ->withErrors(['token' => 'Token de réinitialisation invalide.']);
        }

        // Validation de l'email
        $email = $request->email;
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'Email invalide.']);
        }

        return view('auth.passwords.reset')->with([
            'token' => $token, 
            'email' => $email
        ]);
    }

    /**
     * Réinitialiser le mot de passe avec sécurité renforcée
     */
    public function reset(Request $request)
    {
        // Validation stricte des données
        $request->validate([
            'token' => [
                'required',
                'string',
                'size:64', // Token Laravel fait 64 caractères
            ],
            'email' => [
                'required',
                'email:rfc',
                'max:255',
                'exists:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:128',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
        ], [
            'email.regex' => 'Format d\'email invalide.',
            'password.regex' => 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.',
        ]);

        // Nettoyage des données
        $email = strtolower(trim($request->email));

        // Vérification contre les mots de passe communs
        $commonPasswords = [
            'password', '12345678', 'qwerty123', 'admin123', 'password123',
            'motdepasse', 'azerty123', '123456789', 'password1'
        ];
        
        if (in_array(strtolower($request->password), $commonPasswords)) {
            return back()->withErrors([
                'password' => 'Ce mot de passe est trop commun. Veuillez en choisir un autre.'
            ])->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            $status = Password::reset(
                [
                    'email' => $email,
                    'password' => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    'token' => $request->token
                ],
                function ($user, $password) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));

                    // Log de sécurité
                    \Log::info('Mot de passe réinitialisé avec succès', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('login')
                               ->with('status', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
            }

            return back()->withErrors(['email' => 'Le lien de réinitialisation est invalide ou a expiré.']);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la réinitialisation du mot de passe', [
                'error' => $e->getMessage(),
                'email' => $email,
                'ip' => $request->ip(),
            ]);

            return back()->withErrors(['email' => 'Une erreur est survenue. Veuillez réessayer.']);
        }
    }
}
