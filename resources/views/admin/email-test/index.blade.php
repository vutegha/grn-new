@extends('layouts.admin')

@section('title', 'Test de Configuration Email')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- En-tête -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
            <h1 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-envelope-open-text mr-3"></i>
                Test de Configuration Email
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                Testez et vérifiez la configuration de votre serveur de messagerie
            </p>
        </div>
    </div>

    <!-- Configuration actuelle -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-cog mr-2 text-gray-600"></i>
                Configuration Actuelle
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">Serveur SMTP :</span>
                        <span class="text-gray-600">{{ $config['host'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">Port :</span>
                        <span class="text-gray-600">{{ $config['port'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">Encryption :</span>
                        <span class="text-gray-600 uppercase">{{ $config['encryption'] }}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">Utilisateur :</span>
                        <span class="text-gray-600">{{ $config['username'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">Expéditeur :</span>
                        <span class="text-gray-600">{{ $config['from_address'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">Nom :</span>
                        <span class="text-gray-600">{{ $config['from_name'] }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Test de connexion -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <button onclick="testConnection()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plug mr-2"></i>
                    Tester la Connexion SMTP
                </button>
                <div id="connectionResult" class="mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Formulaire de test d'email -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-paper-plane mr-2 text-gray-600"></i>
                Envoyer un Email de Test
            </h2>
        </div>
        <div class="p-6">
            <form id="emailTestForm" class="space-y-6">
                @csrf
                
                <!-- Destinataire -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-at mr-1"></i>Adresse de destination
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="exemple@domaine.com"
                           required>
                </div>

                <!-- Sujet -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1"></i>Sujet
                    </label>
                    <input type="text" 
                           id="subject" 
                           name="subject" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           value="Test de Configuration Email - GRN-UCBC"
                           required>
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1"></i>Message
                    </label>
                    <textarea id="message" 
                              name="message" 
                              rows="6" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                              placeholder="Votre message de test..."
                              required>Bonjour,

Ceci est un email de test envoyé depuis l'interface d'administration de GRN-UCBC.

Configuration testée :
- Serveur SMTP : {{ $config['host'] }}
- Port : {{ $config['port'] }}
- Encryption : {{ $config['encryption'] }}
- Expéditeur : {{ $config['from_address'] }}

Si vous recevez cet email, la configuration fonctionne correctement !

Date/Heure : {{ now()->format('d/m/Y H:i:s') }}

Cordialement,
L'équipe GRN-UCBC</textarea>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour au Dashboard
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Envoyer l'Email de Test
                    </button>
                </div>
            </form>
            
            <!-- Résultat -->
            <div id="emailResult" class="mt-6"></div>
        </div>
    </div>

    <!-- Guide de dépannage -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-question-circle mr-2 text-gray-600"></i>
                Guide de Dépannage
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="font-medium text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Problèmes Courants
                    </h3>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Vérifiez que le serveur SMTP {{ $config['host'] }} est accessible</li>
                        <li>• Assurez-vous que le port {{ $config['port'] }} est ouvert sur votre réseau</li>
                        <li>• Vérifiez les identifiants de connexion (nom d'utilisateur et mot de passe)</li>
                        <li>• Confirmez que l'encryption {{ strtoupper($config['encryption']) }} est supportée</li>
                    </ul>
                </div>
                
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="font-medium text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        Configuration Recommandée
                    </h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Port 465 avec SSL pour une sécurité maximale</li>
                        <li>• Authentification SMTP activée</li>
                        <li>• Timeout de connexion de 30 secondes minimum</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Test de connexion SMTP
function testConnection() {
    const resultDiv = document.getElementById('connectionResult');
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Test en cours...';
    
    fetch('{{ route("admin.email-test.connection") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-green-800">Connexion Réussie</h4>
                            <p class="text-sm text-green-600">${data.message}</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-500 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-red-800">Erreur de Connexion</h4>
                            <p class="text-sm text-red-600">${data.message}</p>
                        </div>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        resultDiv.innerHTML = `
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-red-500 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-red-800">Erreur</h4>
                        <p class="text-sm text-red-600">Erreur de connexion : ${error.message}</p>
                    </div>
                </div>
            </div>
        `;
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Envoi d'email de test
document.getElementById('emailTestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const resultDiv = document.getElementById('emailResult');
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
    
    fetch('{{ route("admin.email-test.send") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-green-800">Email Envoyé</h4>
                            <p class="text-sm text-green-600">${data.message}</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-500 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-red-800">Erreur d'Envoi</h4>
                            <p class="text-sm text-red-600">${data.message}</p>
                        </div>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        resultDiv.innerHTML = `
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-red-500 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-red-800">Erreur</h4>
                        <p class="text-sm text-red-600">Erreur de connexion : ${error.message}</p>
                    </div>
                </div>
            </div>
        `;
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});
</script>
@endsection
