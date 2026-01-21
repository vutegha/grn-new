@extends('layouts.app')

@section('title', 'Inscription - GRN UCBC')

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
    
    .register-bg {
        background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 50%, #f0f9f4 100%);
        position: relative;
    }
    
    .register-bg::before {
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
    
    .register-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(1.1) contrast(1.05);
        transition: transform 0.3s ease;
    }
    
    .register-image:hover {
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

<div class="min-h-screen register-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
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
                    Rejoignez-nous
                </h1>
                <p class="text-lg text-gray-600 font-medium">
                    Centre de Gouvernance des Ressources Naturelles
                </p>
                <p class="mt-4 text-sm text-gray-500">
                    Créez votre compte pour accéder à nos services
                </p>
            </div>

            <!-- Formulaire d'inscription -->
            <div class="glass-card rounded-3xl p-8 space-y-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-6" novalidate id="registerForm">
                    @csrf
                    
                    <!-- Champ Nom -->
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input id="name" 
                               name="name" 
                               type="text" 
                               autocomplete="name" 
                               required 
                               maxlength="255"
                               minlength="2"
                               pattern="[a-zA-ZÀ-ÿ\s\-\'\.]+$"
                               title="Le nom ne peut contenir que des lettres, espaces, traits d'union et apostrophes"
                               class="form-input @error('name') !border-red-400 @enderror" 
                               placeholder=" "
                               value="{{ old('name') }}">
                        <label for="name" class="floating-label">Nom complet</label>
                        @error('name')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

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
                               autocomplete="new-password" 
                               required 
                               minlength="8"
                               maxlength="128"
                               pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                               title="Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial"
                               class="form-input @error('password') !border-red-400 @enderror" 
                               placeholder=" ">
                        <label for="password" class="floating-label">Mot de passe</label>
                        <div class="text-xs text-gray-500 mt-1">
                            Minimum 8 caractères avec majuscule, minuscule, chiffre et caractère spécial
                        </div>
                        @error('password')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Champ Confirmation mot de passe -->
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required 
                               minlength="8"
                               maxlength="128"
                               class="form-input" 
                               placeholder=" ">
                        <label for="password_confirmation" class="floating-label">Confirmer le mot de passe</label>
                    </div>

                    <!-- Bouton d'inscription -->
                    <button type="submit" class="btn-primary w-full relative">
                        <span class="flex items-center justify-center gap-3">
                            <i class="fas fa-user-plus"></i>
                            Créer mon compte
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
                    <a href="{{ route('login') }}" 
                       class="w-full flex items-center justify-center gap-3 px-6 py-3 border-2 border-gray-200 rounded-2xl text-gray-700 font-medium hover:border-iri-primary hover:text-iri-primary transition-all duration-300 group">
                        <i class="fas fa-sign-in-alt group-hover:text-iri-accent transition-colors duration-300"></i>
                        J'ai déjà un compte
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500">
                <p>© {{ date('Y') }} GRN-UCBC. Tous droits réservés.</p>
                <p class="mt-1">
                    En vous inscrivant, vous acceptez nos
                    <a href="#" class="hover:text-iri-primary transition-colors duration-200">Conditions d'utilisation</a>
                    et notre
                    <a href="#" class="hover:text-iri-primary transition-colors duration-200">Politique de confidentialité</a>
                </p>
            </div>
        </div>

        <!-- Colonne droite - Image -->
        <div class="image-container h-full min-h-[600px] lg:min-h-[700px]">
            <div class="image-overlay"></div>
            <img src="http://127.0.0.1:8000/storage/assets/media/J5qMA2xHC2tHx4luBKAOzNdIQrPPzBgsqJyKHqgA.jpg" 
                 alt="Gouvernance des ressources naturelles" 
                 class="register-image" />
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const nameField = document.getElementById('name');
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    
    // Protection contre les injections XSS
    function sanitizeInput(input) {
        return input.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
                   .replace(/<[^>]*>/g, '')
                   .trim();
    }
    
    // Validation en temps réel du nom
    nameField.addEventListener('input', function() {
        const name = sanitizeInput(this.value);
        const nameRegex = /^[a-zA-ZÀ-ÿ\s\-\'\.]+$/;
        
        if (name.length >= 2 && nameRegex.test(name)) {
            this.classList.remove('!border-red-400');
            this.classList.add('!border-green-400');
        } else if (name.length > 0) {
            this.classList.remove('!border-green-400');
            this.classList.add('!border-red-400');
        } else {
            this.classList.remove('!border-red-400', '!border-green-400');
        }
        
        if (this.value !== name) {
            this.value = name;
        }
    });
    
    // Validation en temps réel de l'email
    emailField.addEventListener('input', function() {
        const email = sanitizeInput(this.value.toLowerCase());
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
        
        if (this.value !== email) {
            this.value = email;
        }
    });
    
    // Validation en temps réel du mot de passe
    passwordField.addEventListener('input', function() {
        const password = this.value;
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        
        // Vérifier les mots de passe communs
        const commonPasswords = ['password', '12345678', 'qwerty123', 'admin123', 'password123', 'motdepasse', 'azerty123'];
        
        if (password.length >= 8 && regex.test(password) && !commonPasswords.includes(password.toLowerCase())) {
            this.classList.remove('!border-red-400');
            this.classList.add('!border-green-400');
        } else {
            this.classList.remove('!border-green-400');
            this.classList.add('!border-red-400');
        }
        
        // Vérifier la confirmation
        validatePasswordConfirmation();
    });
    
    // Validation de la confirmation du mot de passe
    function validatePasswordConfirmation() {
        const password = passwordField.value;
        const confirmation = confirmPasswordField.value;
        
        if (confirmation.length > 0) {
            if (confirmation === password && password.length >= 8) {
                confirmPasswordField.classList.remove('!border-red-400');
                confirmPasswordField.classList.add('!border-green-400');
            } else {
                confirmPasswordField.classList.remove('!border-green-400');
                confirmPasswordField.classList.add('!border-red-400');
            }
        } else {
            confirmPasswordField.classList.remove('!border-red-400', '!border-green-400');
        }
    }
    
    confirmPasswordField.addEventListener('input', validatePasswordConfirmation);
    
    // Protection contre le collage malveillant
    [nameField, emailField, passwordField, confirmPasswordField].forEach(field => {
        field.addEventListener('paste', function(e) {
            setTimeout(() => {
                if (this !== passwordField && this !== confirmPasswordField) {
                    this.value = sanitizeInput(this.value);
                }
            }, 10);
        });
        
        // Désactiver le clic droit (optionnel)
        field.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    });
    
    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        const name = sanitizeInput(nameField.value);
        const email = sanitizeInput(emailField.value.toLowerCase());
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;
        
        // Validation nom
        const nameRegex = /^[a-zA-ZÀ-ÿ\s\-\'\.]+$/;
        if (name.length < 2 || !nameRegex.test(name)) {
            e.preventDefault();
            alert('Veuillez entrer un nom valide (au moins 2 caractères, lettres uniquement).');
            nameField.focus();
            return false;
        }
        
        // Validation email
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Veuillez entrer une adresse email valide.');
            emailField.focus();
            return false;
        }
        
        // Validation mot de passe
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordRegex.test(password)) {
            e.preventDefault();
            alert('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.');
            passwordField.focus();
            return false;
        }
        
        // Vérification mots de passe communs
        const commonPasswords = ['password', '12345678', 'qwerty123', 'admin123', 'password123', 'motdepasse', 'azerty123'];
        if (commonPasswords.includes(password.toLowerCase())) {
            e.preventDefault();
            alert('Ce mot de passe est trop commun. Veuillez en choisir un autre.');
            passwordField.focus();
            return false;
        }
        
        // Validation confirmation
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            confirmPasswordField.focus();
            return false;
        }
        
        // Vérification des caractères suspects
        const suspiciousPatterns = [
            /<script/i, /javascript:/i, /on\w+\s*=/i, /\beval\s*\(/i, /\balert\s*\(/i
        ];
        
        const allInputs = name + ' ' + email;
        for (let pattern of suspiciousPatterns) {
            if (pattern.test(allInputs)) {
                e.preventDefault();
                alert('Caractères non autorisés détectés.');
                return false;
            }
        }
        
        // Nettoyer les valeurs finales
        nameField.value = name;
        emailField.value = email;
    });
    
    // Protection contre les soumissions multiples
    let isSubmitting = false;
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        isSubmitting = true;
        
        // Réactiver après 5 secondes en cas d'erreur
        setTimeout(() => {
            isSubmitting = false;
        }, 5000);
    });
});
</script>
@endsection