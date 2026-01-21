@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe - GRN UCBC')

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
    
    .icon-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--iri-primary), var(--iri-accent));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 25px -5px rgba(30, 71, 47, 0.3);
    }
    
    @media (max-width: 640px) {
        .glass-card {
            margin: 1rem;
        }
        
        .form-input {
            padding-top: 16px;
            padding-bottom: 6px;
        }
    }
</style>

<div class="min-h-screen reset-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo et titre -->
        <div class="text-center">
            <div class="logo-container">
                <div class="logo-glow"></div>
                <div class="mx-auto h-20 w-20 flex items-center justify-center relative z-10">
                    <img class="h-16 w-auto filter drop-shadow-lg" src="{{ asset('images/logo.png') }}" alt="GRN-UCBC Logo">
                </div>
            </div>
            
            <div class="icon-circle mb-6">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            
            <h1 class="text-4xl font-bold welcome-text mb-2">
                Mot de passe oublié ?
            </h1>
            <p class="text-lg text-gray-600 font-medium mb-4">
                Centre de Gouvernance des Ressources Naturelles
            </p>
            <p class="text-sm text-gray-500">
                Saisissez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </p>
        </div>

        <!-- Formulaire de réinitialisation -->
        <div class="glass-card rounded-3xl p-8 space-y-6">
            @if (session('status'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <!-- Champ Email -->
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
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

                <!-- Bouton d'envoi -->
                <button type="submit" class="btn-primary w-full relative">
                    <span class="flex items-center justify-center gap-3">
                        <i class="fas fa-paper-plane"></i>
                        Envoyer le lien de réinitialisation
                    </span>
                </button>
            </form>

            <!-- Lien de retour -->
            <div class="text-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 text-sm font-medium text-iri-primary hover:text-iri-accent transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la connexion
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500">
            <p>© {{ date('Y') }} GRN-UCBC. Tous droits réservés.</p>
            <p class="mt-1">
                <a href="#" class="hover:text-iri-primary transition-colors duration-200">Besoin d'aide ?</a>
                •
                <a href="#" class="hover:text-iri-primary transition-colors duration-200">Contactez-nous</a>
            </p>
        </div>
    </div>
</div>
@endsection
