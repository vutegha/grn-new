@extends('layouts.admin')

@section('title', 'Accès non autorisé')
@section('subtitle', 'Erreur 403 - Permissions insuffisantes')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <!-- Icône d'erreur 403 -->
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100">
                <i class="bi bi-shield-x text-4xl text-red-600"></i>
            </div>
            
            <!-- Code d'erreur -->
            <h1 class="mt-6 text-6xl font-bold text-gray-900">403</h1>
            
            <!-- Message principal -->
            <h2 class="mt-4 text-2xl font-semibold text-gray-700">Accès non autorisé</h2>
            
            <!-- Description détaillée -->
            <div class="mt-4 text-gray-600 space-y-2">
                <p class="text-lg">Vous n'avez pas les permissions nécessaires pour accéder à cette ressource.</p>
                <p class="text-sm">Cette action nécessite des permissions spécifiques qui ne sont pas assignées à votre compte.</p>
            </div>
        </div>

        <!-- Informations sur l'utilisateur -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
            <h3 class="text-sm font-medium text-blue-800 mb-2">
                <i class="bi bi-info-circle mr-1"></i>
                Informations sur votre compte
            </h3>
            <div class="text-sm text-blue-700 space-y-1">
                <p><strong>Utilisateur :</strong> {{ auth()->user()->name ?? 'Non connecté' }}</p>
                <p><strong>Email :</strong> {{ auth()->user()->email ?? 'N/A' }}</p>
                @if(auth()->check() && auth()->user()->roles->count() > 0)
                    <p><strong>Rôle(s) :</strong> {{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>
                @else
                    <p><strong>Rôle :</strong> <span class="text-orange-600">Aucun rôle assigné</span></p>
                @endif
            </div>
        </div>

        <!-- Actions possibles -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <!-- Retour au dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-coral hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Retour au dashboard
                </a>

                <!-- Page précédente -->
                <button onclick="history.back()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Page précédente
                </button>
            </div>

            <!-- Lien vers l'accueil du site -->
            <div class="pt-4 border-t border-gray-200">
                <a href="{{ url('/') }}" 
                   class="text-sm text-gray-500 hover:text-coral transition-colors duration-200">
                    <i class="bi bi-house mr-1"></i>
                    Retour à l'accueil du site
                </a>
            </div>
        </div>

        <!-- Message d'aide -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-yellow-800 mb-2">
                <i class="bi bi-question-circle mr-1"></i>
                Besoin d'aide ?
            </h3>
            <p class="text-sm text-yellow-700">
                Si vous pensez que cette erreur est incorrecte, contactez l'administrateur système ou vérifiez que vous êtes connecté avec le bon compte.
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh de la page après 30 secondes si l'utilisateur vient juste de se connecter
    @if(session('recent_login'))
        setTimeout(() => {
            if (confirm('Voulez-vous essayer de recharger la page ? Vos permissions ont peut-être été mises à jour.')) {
                window.location.reload();
            }
        }, 30000);
    @endif
</script>
@endsection
