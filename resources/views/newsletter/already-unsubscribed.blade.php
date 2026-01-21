<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déjà désabonné - Newsletter GRN-UCBC</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-md">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="w-16 h-16 bg-yellow-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Déjà désabonné</h1>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-yellow-700">Vous êtes déjà désabonné(e) de la newsletter GRN-UCBC.</p>
            </div>
            
            <p class="text-gray-600 mb-6">
                Votre adresse email <strong>{{ $newsletter->email }}</strong> ne recevra plus de notifications de notre part.
            </p>
            
            <div class="space-y-3">
                <a href="{{ route('newsletter.resubscribe', $newsletter->token) }}" 
                   class="block w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                    Se réabonner à la newsletter
                </a>
                
                <a href="{{ route('site.home') }}" 
                   class="block w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                    Retour au site
                </a>
            </div>
        </div>
    </div>
</body>
</html>
