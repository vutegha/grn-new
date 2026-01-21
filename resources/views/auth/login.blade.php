@extends('layouts.app')

@section('title', 'Connexion - GRN UCBC')

@section('content')
<style>
    :root {
        --iri-primary: #1e472f;
        --iri-secondary: #2d5a3f;
        --iri-accent: #d2691e;
        --iri-light: #f0f9f4;
        --iri-gold: #b8860b;
        --iri-gray: #64748b;
        --iri-dark: #1a1a1a;
    }
    
    .login-bg {
        background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 50%, #f0f9f4 100%);
        position: relative;
    }
    
    .login-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="%231e472f" opacity="0.1"/><circle cx="80" cy="40" r="3" fill="%23d2691e" opacity="0.1"/><circle cx="40" cy="70" r="2" fill="%232d5a3f" opacity="0.1"/><circle cx="70" cy="15" r="1.5" fill="%23b8860b" opacity="0.1"/><circle cx="15" cy="60" r="2.5" fill="%231e472f" opacity="0.1"/><circle cx="90" cy="80" r="2" fill="%23d2691e" opacity="0.1"/></svg>') repeat;
        z-index: 0;
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .input-group {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--iri-gray);
        z-index: 10;
    }
    
    .floating-label {
        position: absolute;
        left: 48px;
        top: 16px;
        color: var(--iri-gray);
        font-size: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        z-index: 5;
    }
    
    .form-input {
        padding-left: 48px;
        padding-right: 16px;
        padding-top: 20px;
        padding-bottom: 8px;
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        font-size: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
    }
    
    .form-input:focus,
    .form-input:not(:placeholder-shown) {
        border-color: var(--iri-primary);
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 3px rgba(30, 71, 47, 0.1);
    }
    
    .form-input:focus + .floating-label,
    .form-input:not(:placeholder-shown) + .floating-label {
        top: 4px;
        font-size: 12px;
        color: var(--iri-primary);
        font-weight: 600;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--iri-primary) 0%, var(--iri-secondary) 100%);
        border: none;
        border-radius: 16px;
        padding: 16px 32px;
        font-weight: 600;
        font-size: 16px;
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s;
    }
    
    .btn-primary:hover::before {
        left: 100%;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -12px rgba(30, 71, 47, 0.4);
    }
    
    .social-btn {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .social-btn:hover {
        border-color: var(--iri-primary);
        background: rgba(255, 255, 255, 1);
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .divider {
        position: relative;
        text-align: center;
        margin: 32px 0;
    }
    
    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--iri-light), transparent);
    }
    
    .divider span {
        background: rgba(255, 255, 255, 0.95);
        padding: 0 20px;
        color: var(--iri-gray);
        font-size: 14px;
        font-weight: 500;
    }
    
    .logo-container {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .logo-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(30, 71, 47, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse 3s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
        50% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
    }
    
    .welcome-text {
        background: linear-gradient(135deg, var(--iri-primary), var(--iri-accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
    }
    
    .error-message {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border: 1px solid #fca5a5;
        color: #dc2626;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .success-message {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #86efac;
        color: #16a34a;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .checkbox-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .custom-checkbox {
        width: 18px;
        height: 18px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .custom-checkbox:checked {
        background: var(--iri-primary);
        border-color: var(--iri-primary);
    }
    
    .custom-checkbox:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
        font-weight: bold;
    }
    
    .image-container {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
    }
    
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(30, 71, 47, 0.1) 0%, rgba(210, 105, 30, 0.1) 100%);
        z-index: 1;
    }
    
    .login-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(1.1) contrast(1.05);
        transition: transform 0.3s ease;
    }
    
    .login-image:hover {
        transform: scale(1.02);
    }
    
    .two-column-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        max-width: 1200px;
        width: 100%;
        align-items: center;
    }
    
    @media (max-width: 1024px) {
        .two-column-layout {
            grid-template-columns: 1fr;
            gap: 2rem;
            max-width: 500px;
        }
        
        .image-container {
            order: -1;
            height: 300px;
        }
    }
    
    @media (max-width: 640px) {
        .glass-card {
            margin: 1rem;
        }
        
        .form-input {
            padding-top: 16px;
            padding-bottom: 6px;
        }
        
        .image-container {
            height: 250px;
        }
        
        .two-column-layout {
            gap: 1.5rem;
        }
    }
</style>

<div class="min-h-screen login-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
    <div class="two-column-layout relative z-10">
        <!-- Colonne gauche - Formulaire -->
        <div class="space-y-8">
            <!-- Logo et titre -->
            <div class="text-center">
                <div class="logo-container">
                    <div class="logo-glow"></div>
                    <div class="mx-auto h-20 w-20 flex items-center justify-center relative z-10">
                        <img class="h-16 w-auto filter drop-shadow-lg" src="{{ asset('images/logo.png') }}" alt="GRN-UCBC Logo">
                    </div>
                </div>
                <h1 class="text-4xl font-bold welcome-text mb-2">
                    Bienvenue
                </h1>
                <p class="text-lg text-gray-600 font-medium">
                    Centre de Gouvernance des Ressources Naturelles
                </p>
                <p class="mt-4 text-sm text-gray-500">
                    Connectez-vous pour accéder à votre espace personnel
                </p>
            </div>

            <!-- Formulaire de connexion -->
            <div class="glass-card rounded-3xl p-8 space-y-6">
                <form method="POST" action="{{ route('login') }}" class="space-y-6" novalidate id="loginForm">
                    @csrf
                    
                    <!-- Champ Email -->
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               maxlength="255"
                               pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                               title="Veuillez entrer une adresse email valide"
                               class="form-input @error('email') !border-red-400 @enderror" 
                               placeholder=" "
                               value="{{ old('email') }}">
                        <label for="email" class="floating-label">Adresse email</label>
                        @error('email')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Champ Mot de passe -->
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="current-password" 
                               required 
                               maxlength="128"
                               minlength="8"
                               class="form-input @error('password') !border-red-400 @enderror" 
                               placeholder=" ">
                        <label for="password" class="floating-label">Mot de passe</label>
                        @error('password')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between">
                        <div class="checkbox-container">
                            <input id="remember-me" 
                                   name="remember" 
                                   type="checkbox" 
                                   class="custom-checkbox">
                            <label for="remember-me" class="text-sm text-gray-700 font-medium cursor-pointer">
                                Se souvenir de moi
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" 
                               class="font-medium text-iri-primary hover:text-iri-accent transition-colors duration-200">
                                Mot de passe oublié ?
                            </a>
                        </div>
                    </div>

                    <!-- Bouton de connexion -->
                    <button type="submit" class="btn-primary w-full relative">
                        <span class="flex items-center justify-center gap-3">
                            <i class="fas fa-sign-in-alt"></i>
                            Se connecter
                        </span>
                    </button>

                    <!-- Messages d'erreur/succès -->
                    @if (session('error'))
                        <div class="error-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                </form>

                <!-- Divider -->
                <div class="divider">
                    <span>Ou</span>
                </div>

                <!-- Actions alternatives -->
                <div class="space-y-4">
                    <a href="{{ route('register') }}" 
                       class="w-full flex items-center justify-center gap-3 px-6 py-3 border-2 border-gray-200 rounded-2xl text-gray-700 font-medium hover:border-iri-primary hover:text-iri-primary transition-all duration-300 group">
                        <i class="fas fa-user-plus group-hover:text-iri-accent transition-colors duration-300"></i>
                        Créer un nouveau compte
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500">
                <p>© {{ date('Y') }} GRN-UCBC. Tous droits réservés.</p>
                <p class="mt-1">
                    <a href="#" class="hover:text-iri-primary transition-colors duration-200">Politique de confidentialité</a>
                    •
                    <a href="#" class="hover:text-iri-primary transition-colors duration-200">Conditions d'utilisation</a>
                </p>
            </div>
        </div>

        <!-- Colonne droite - Image -->
        <div class="image-container h-full min-h-[600px] lg:min-h-[700px]">
            <div class="image-overlay"></div>
            <img src="http://127.0.0.1:8000/storage/assets/media/J5qMA2xHC2tHx4luBKAOzNdIQrPPzBgsqJyKHqgA.jpg" 
                 alt="Gouvernance des ressources naturelles" 
                 class="login-image" />
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    
    // Protection contre les injections XSS dans les champs
    function sanitizeInput(input) {
        return input.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
                   .replace(/<[^>]*>/g, '')
                   .trim();
    }
    
    // Validation en temps réel de l'email
    emailField.addEventListener('input', function() {
        const email = sanitizeInput(this.value);
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        if (email.length > 0 && emailRegex.test(email)) {
            this.classList.remove('!border-red-400');
            this.classList.add('!border-green-400');
        } else if (email.length > 0) {
            this.classList.remove('!border-green-400');
            this.classList.add('!border-red-400');
        } else {
            this.classList.remove('!border-red-400', '!border-green-400');
        }
        
        // Nettoyer la valeur
        if (this.value !== email) {
            this.value = email;
        }
    });
    
    // Protection contre le collage malveillant
    emailField.addEventListener('paste', function(e) {
        setTimeout(() => {
            this.value = sanitizeInput(this.value);
        }, 10);
    });
    
    passwordField.addEventListener('paste', function(e) {
        setTimeout(() => {
            this.value = sanitizeInput(this.value);
        }, 10);
    });
    
    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        const email = sanitizeInput(emailField.value);
        const password = sanitizeInput(passwordField.value);
        
        // Validation email
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Veuillez entrer une adresse email valide.');
            emailField.focus();
            return false;
        }
        
        // Validation mot de passe
        if (password.length < 8 || password.length > 128) {
            e.preventDefault();
            alert('Le mot de passe doit contenir entre 8 et 128 caractères.');
            passwordField.focus();
            return false;
        }
        
        // Vérification des caractères suspects
        const suspiciousPatterns = [
            /<script/i,
            /javascript:/i,
            /on\w+\s*=/i,
            /\beval\s*\(/i,
            /\balert\s*\(/i
        ];
        
        const allInputs = email + ' ' + password;
        for (let pattern of suspiciousPatterns) {
            if (pattern.test(allInputs)) {
                e.preventDefault();
                alert('Caractères non autorisés détectés.');
                return false;
            }
        }
        
        // Nettoyer les valeurs finales
        emailField.value = email;
        passwordField.value = password;
    });
    
    // Protection contre les attaques de timing
    let submitCount = 0;
    let lastSubmit = 0;
    
    form.addEventListener('submit', function(e) {
        const now = Date.now();
        if (now - lastSubmit < 2000) { // Minimum 2 secondes entre les soumissions
            e.preventDefault();
            alert('Veuillez patienter avant de soumettre à nouveau.');
            return false;
        }
        
        submitCount++;
        lastSubmit = now;
        
        if (submitCount > 5) {
            e.preventDefault();
            alert('Trop de tentatives. Veuillez actualiser la page.');
            return false;
        }
    });
    
    // Désactiver le clic droit sur les champs sensibles (optionnel)
    [emailField, passwordField].forEach(field => {
        field.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    });
});
</script>
@endsection
