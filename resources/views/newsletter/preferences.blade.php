<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des préférences - Newsletter GRN-UCBC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-blue-dark) 0%, #1e40af 100%);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-md">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-green-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 17h-7a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Préférences Newsletter</h1>
            <p class="text-gray-600">GRN-UCBC - Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>

        <!-- Messages de feedback -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Informations abonné -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations de l'abonné</h2>
            <div class="space-y-2">
                <p><span class="font-medium">Email :</span> {{ $newsletter->email }}</p>
                @if($newsletter->nom)
                    <p><span class="font-medium">Nom :</span> {{ $newsletter->nom }}</p>
                @endif
                <p><span class="font-medium">Inscrit le :</span> {{ $newsletter->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Formulaire de préférences unifié -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Vos préférences de notification</h2>
            <p class="text-gray-600 mb-6 text-sm">Personnalisez votre nom et choisissez les types de contenus pour lesquels vous souhaitez recevoir des notifications par email.</p>
            
            <form method="POST" action="{{ route('newsletter.preferences.update', $newsletter->token) }}">
                @csrf
                @method('PUT')
                
                <!-- Champ nom personnalisé -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Votre nom (optionnel)
                    </label>
                    <input type="text" 
                           id="nom" 
                           name="nom" 
                           value="{{ old('nom', $newsletter->nom) }}"
                           placeholder="Ex: Jean Dupont"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <p class="text-xs text-gray-500 mt-1">
                        Ce nom sera utilisé dans tous nos emails pour personnaliser nos communications.
                    </p>
                </div>
                
                <!-- Préférences de contenu -->
                <div class="space-y-4">
                    <h3 class="text-md font-semibold text-gray-800 mb-3">
                        <i class="fas fa-bell mr-2 text-blue-600"></i>
                        Types de notifications
                    </h3>
                    
                    <!-- Actualités -->
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" 
                               name="preferences[actualites]" 
                               value="1"
                               {{ $newsletter->hasPreference('actualites') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-800">Actualités</div>
                            <div class="text-sm text-gray-500">
                                Recevez les notifications pour les actualités importantes de l'institut
                            </div>
                        </div>
                        <div class="text-2xl">📰</div>
                    </label>

                    <!-- Publications -->
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" 
                               name="preferences[publications]" 
                               value="1"
                               {{ $newsletter->hasPreference('publications') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-800">Publications de recherche</div>
                            <div class="text-sm text-gray-500">
                                Recevez les notifications pour les nouvelles publications académiques et scientifiques
                            </div>
                        </div>
                        <div class="text-2xl">📚</div>
                    </label>

                    <!-- Rapports -->
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" 
                               name="preferences[rapports]" 
                               value="1"
                               {{ $newsletter->hasPreference('rapports') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-800">Rapports d'activité</div>
                            <div class="text-sm text-gray-500">
                                Recevez les notifications pour nos rapports d'activité et analyses approfondies
                            </div>
                        </div>
                        <div class="text-2xl">📊</div>
                    </label>

                    <!-- Événements -->
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" 
                               name="preferences[evenements]" 
                               value="1"
                               {{ $newsletter->hasPreference('evenements') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-800">Événements</div>
                            <div class="text-sm text-gray-500">
                                Recevez les notifications pour nos conférences, séminaires et formations
                            </div>
                        </div>
                        <div class="text-2xl">🎓</div>
                    </label>

                    <!-- Projets -->
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" 
                               name="preferences[projets]" 
                               value="1"
                               {{ $newsletter->hasPreference('projets') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-800">Projets</div>
                            <div class="text-sm text-gray-500">
                                Recevez les notifications pour nos nouveaux projets et leurs avancements
                            </div>
                        </div>
                        <div class="text-2xl">🚀</div>
                    </label>
                </div>

                <div class="mt-6 space-y-3">
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer mes informations et préférences
                    </button>
                    
                    <a href="{{ route('site.home') }}" 
                       class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors">
                        Retour au site
                    </a>
                </div>
            </form>
        </div>

        <!-- Zone de désabonnement -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-red-800 mb-2">Se désabonner complètement</h3>
            <p class="text-red-700 text-sm mb-4">
                Si vous ne souhaitez plus recevoir aucune notification de notre part, vous pouvez vous désabonner complètement.
            </p>
            <a href="{{ route('newsletter.unsubscribe', $newsletter->token) }}" 
               onclick="return confirm('Êtes-vous sûr de vouloir vous désabonner de toutes nos notifications ?')"
               class="inline-block bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                Me désabonner
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-8">
            <p>© {{ date('Y') }} Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
            <p>GRN-UCBC</p>
        </div>
    </div>
</body>
</html>
