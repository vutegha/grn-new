@extends('layouts.app')

@section('title', 'Nouveau mot de passe - GRN UCBC')

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
    
    .reset-bg {
        background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 50%, #f0f9f4 100%);
        position: relative;
    }
    
    .reset-bg::before {
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
    
    .readonly-input {
        background: rgba(245, 245, 245, 0.8) !important;
        color: var(--iri-gray) !important;
        cursor: not-allowed;
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
    
    .icon-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--iri-primary), var(--iri-secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        position: relative;
    }
    
    .icon-circle::before {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
        background: linear-gradient(135deg, var(--iri-accent), var(--iri-gold));
        border-radius: 50%;
        z-index: -1;
        opacity: 0.3;
    }
    
    .icon-circle i {
        font-size: 28px;
        color: white;
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
    
    @media (max-width: 640px) {
        .glass-card {
            margin: 1rem;
            padding: 2rem 1.5rem;
        }
        
        .form-input {
            padding-top: 16px;
            padding-bottom: 6px;
        }
    }
</style>

<div class="min-h-screen reset-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Icône et titre -->
        <div class="text-center">
            <div class="icon-circle">
                <i class="fas fa-key"></i>
            </div>
            <h1 class="text-3xl font-bold welcome-text mb-2">
                Nouveau mot de passe
            </h1>
            <p class="text-lg text-gray-600 font-medium">
                GRN-UCBC
            </p>
            <p class="mt-4 text-sm text-gray-500">
                Créez votre nouveau mot de passe sécurisé
            </p>
        </div>

        <!-- Formulaire -->
        <div class="glass-card rounded-3xl p-8 space-y-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6" novalidate>
                @csrf
                
                <!-- Token caché -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Champ Email (readonly) -->
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           readonly
                           class="form-input readonly-input @error('email') !border-red-400 @enderror" 
                           placeholder=" "
                           value="{{ $email ?? old('email') }}">
                    <label for="email" class="floating-label">Adresse email</label>
                    @error('email')
                        <div class="error-message mt-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Champ Nouveau mot de passe -->
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="new-password" 
                           required 
                           minlength="8"
                           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@@$!%*?&])[A-Za-z\d@@$!%*?&]{8,}$"
                           title="Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial"
                           class="form-input @error('password') !border-red-400 @enderror" 
                           placeholder=" ">
                    <label for="password" class="floating-label">Nouveau mot de passe</label>
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
                           class="form-input" 
                           placeholder=" ">
                    <label for="password_confirmation" class="floating-label">Confirmer le mot de passe</label>
                </div>

                <!-- Bouton de réinitialisation -->
                <button type="submit" class="btn-primary w-full relative">
                    <span class="flex items-center justify-center gap-3">
                        <i class="fas fa-key"></i>
                        Mettre à jour le mot de passe
                    </span>
                </button>

                <!-- Messages d'erreur/succès -->
                @if (session('status'))
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500">
            <p>© {{ date('Y') }} GRN-UCBC. Tous droits réservés.</p>
            <p class="mt-1">
                <a href="{{ route('login') }}" class="hover:text-iri-primary transition-colors duration-200">Retour à la connexion</a>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation côté client renforcée
    const form = document.querySelector('form');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    
    // Validation en temps réel du mot de passe
    passwordField.addEventListener('input', function() {
        const password = this.value;
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@@$!%*?&])[A-Za-z\d@@$!%*?&]{8,}$/;
        
        if (password.length >= 8 && regex.test(password)) {
            this.classList.remove('!border-red-400');
            this.classList.add('!border-green-400');
        } else {
            this.classList.remove('!border-green-400');
            this.classList.add('!border-red-400');
        }
    });
    
    // Validation de la confirmation
    confirmPasswordField.addEventListener('input', function() {
        if (this.value === passwordField.value && this.value.length > 0) {
            this.classList.remove('!border-red-400');
            this.classList.add('!border-green-400');
        } else {
            this.classList.remove('!border-green-400');
            this.classList.add('!border-red-400');
        }
    });
    
    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@@$!%*?&])[A-Za-z\d@@$!%*?&]{8,}$/;
        
        if (!regex.test(password)) {
            e.preventDefault();
            alert('Le mot de passe ne respecte pas les critères de sécurité requis.');
            return false;
        }
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            return false;
        }
    });
    
    // Protection contre le collage de mots de passe faibles
    passwordField.addEventListener('paste', function(e) {
        setTimeout(() => {
            const password = this.value;
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@@$!%*?&])[A-Za-z\d@@$!%*?&]{8,}$/;
            
            if (!regex.test(password)) {
                this.value = '';
                alert('Le mot de passe collé ne respecte pas les critères de sécurité.');
            }
        }, 100);
    });
});
</script>
@endsection