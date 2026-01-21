<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Désabonnement - Newsletter GRN-UCBC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --coral: #ee6751;
            --olive: #505c10;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-md">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 text-center">
            <div class="w-16 h-16 bg-gray-400 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 21l-5-5m7.071-7.071L21 3l-5 5"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Désabonnement</h1>
            <p class="text-gray-600">GRN-UCBC </br> Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>

        @if(session('success'))
            <!-- Modal de confirmation de désabonnement -->
            <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-2xl p-8 m-4 max-w-md w-full transform transition-all duration-300 scale-100 opacity-100">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Désabonnement confirmé</h2>
                        <p class="text-green-700 mb-4">Vous avez été désabonné avec succès de la newsletter.</p>
                        <p class="text-sm text-gray-600 mb-6">Vous ne recevrez plus d'emails de notre part.</p>
                        
                        <!-- Barre de progression -->
                        <div class="mb-4">
                            <div class="bg-gray-200 rounded-full h-2 mb-2">
                                <div id="progressBar" class="bg-green-500 h-2 rounded-full transition-all duration-1000 ease-linear" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-500">Redirection automatique dans <span id="countdown">5</span> secondes...</p>
                        </div>
                        
                        <button onclick="redirectNow()" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Aller à l'accueil maintenant
                        </button>
                    </div>
                </div>
            </div>

            <script>
                // Variables pour le compte à rebours
                let countdown = 5;
                let interval;
                
                // Fonction de redirection
                function redirectNow() {
                    clearInterval(interval);
                    window.location.href = '{{ route("site.home") }}';
                }
                
                // Démarrer le compte à rebours
                function startCountdown() {
                    const countdownElement = document.getElementById('countdown');
                    const progressBar = document.getElementById('progressBar');
                    
                    interval = setInterval(() => {
                        countdown--;
                        countdownElement.textContent = countdown;
                        
                        // Mettre à jour la barre de progression
                        const progress = ((5 - countdown) / 5) * 100;
                        progressBar.style.width = progress + '%';
                        
                        if (countdown <= 0) {
                            redirectNow();
                        }
                    }, 1000);
                }
                
                // Démarrer le compte à rebours au chargement
                document.addEventListener('DOMContentLoaded', function() {
                    startCountdown();
                });
            </script>
        @else
            <!-- Formulaire de confirmation -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirmer le désabonnement</h2>
                <p class="text-gray-600 mb-6">
                    Vous êtes sur le point de vous désabonner de toutes nos notifications email. 
                    Cette action supprimera définitivement votre adresse email de notre liste de diffusion.
                </p>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Attention :</strong> Au lieu de vous désabonner complètement, vous pouvez simplement 
                                <a href="{{ route('newsletter.preferences', $newsletter->token) }}" class="underline text-yellow-800 hover:text-yellow-900">
                                    gérer vos préférences
                                </a> 
                                pour choisir quels types de contenus vous souhaitez recevoir.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-gray-800 mb-2">Informations de l'abonné :</h3>
                    <p class="text-sm text-gray-600">Email : {{ $newsletter->email }}</p>
                    @if($newsletter->nom)
                        <p class="text-sm text-gray-600">Nom : {{ $newsletter->nom }}</p>
                    @endif
                    <p class="text-sm text-gray-600">Inscrit le : {{ $newsletter->created_at->format('d/m/Y') }}</p>
                </div>

                <form method="POST" action="{{ route('newsletter.unsubscribe.confirm', $newsletter->token) }}">
                    @csrf
                    
                    <!-- Section raisons de désabonnement -->
                    <div class="mb-6">
                        <h3 class="text-md font-semibold text-gray-800 mb-3">
                            <i class="fas fa-question-circle mr-2 text-blue-600"></i>
                            Pourquoi vous désabonnez-vous ? (optionnel)
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Vos retours nous aident à améliorer notre service. Vous pouvez sélectionner plusieurs raisons.
                        </p>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" 
                                       name="reasons[]" 
                                       value="too_many_emails"
                                       class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-800">📧 Trop d'emails</div>
                                    <div class="text-sm text-gray-500">
                                        Je reçois trop de notifications par email
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" 
                                       name="reasons[]" 
                                       value="not_relevant"
                                       class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-800">📋 Contenu non pertinent</div>
                                    <div class="text-sm text-gray-500">
                                        Le contenu ne correspond pas à mes intérêts
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" 
                                       name="reasons[]" 
                                       value="not_interested"
                                       class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-800">❌ Plus intéressé par nos services</div>
                                    <div class="text-sm text-gray-500">
                                        Je ne suis plus intéressé par vos services
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" 
                                       name="reasons[]" 
                                       value="technical_issues"
                                       class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-800">⚙️ Problème technique</div>
                                    <div class="text-sm text-gray-500">
                                        J'ai rencontré des problèmes techniques
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" 
                                       name="reasons[]" 
                                       value="other"
                                       class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-800">💭 Autres raisons</div>
                                    <div class="text-sm text-gray-500">
                                        Autre motif de désabonnement
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Champ commentaire -->
                        <div class="mt-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                                Commentaire supplémentaire (optionnel)
                            </label>
                            <textarea id="comment" 
                                      name="comment" 
                                      rows="3"
                                      placeholder="Dites-nous en plus sur votre décision..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"></textarea>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <button type="submit" 
                                onclick="return confirm('Êtes-vous vraiment sûr de vouloir vous désabonner ? Cette action est irréversible.')"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                            Confirmer le désabonnement
                        </button>
                        
                        <a href="{{ route('newsletter.preferences', $newsletter->token) }}" 
                           class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                            Gérer mes préférences plutôt
                        </a>
                        
                        <a href="{{ route('site.home') }}" 
                           class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors">
                            Annuler et retourner au site
                        </a>
                    </div>
                </form>
            </div>
        @endif

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm">
            <p>© {{ date('Y') }} Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
            <p>Congo Initiative-Université Chrétienne Bilingue du Congo</p>
            <p class="mt-2">
                <a href="mailto:iri@ucbc.org" class="text-blue-500 hover:text-blue-600">iri@ucbc.org</a>
            </p>
        </div>
    </div>
</body>
</html>
