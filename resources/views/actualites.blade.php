@extends('layouts.iri')

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Actualités', 'url' => null]
    ]" />
@endsection
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 drop-shadow-2xl">
                Actualités
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Suivez les dernières nouvelles et développements du programme GRN-UCBC
            </p>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(optional($actualites)->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($actualites as $item)
                        <article class="group">
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100" style="overflow: visible;">
                                <!-- Article Image -->
                                <div class="relative h-48 overflow-hidden">
                                    @if($item->image && file_exists(public_path('storage/' . $item->image)))
                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                             alt="{{ $item->titre }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <div class="text-center">
                                                <div class="bg-iri-primary/10 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                                    <i class="fas fa-newspaper text-iri-primary text-2xl"></i>
                                                </div>
                                                <p class="text-sm font-medium text-gray-600">
                                                    {{ $item->categorie?->nom ?? $item->service?->nom ?? 'Actualité GRN' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Date Badge -->
                                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        {{ $item->created_at->format('d M Y') }}
                                    </div>

                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3 bg-iri-accent/90 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-newspaper mr-1"></i>
                                        Actualité
                                    </div>

                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-4 left-4 right-4">
                                            <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" 
                                               class="inline-flex items-center justify-center w-full bg-white/20 backdrop-blur-sm text-white font-medium py-2 px-4 rounded-lg border border-white/30 hover:bg-white/30 transition-all duration-200">
                                                <i class="fas fa-eye mr-2"></i>
                                                Lire l'article
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <h3 class="text-base font-semibold text-gray-900 mb-3 group-hover:text-iri-primary transition-colors duration-200 leading-tight">
                                        <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" class="hover:underline">
                                            {{ $item->titre }}
                                        </a>
                                    </h3>

                                    <!-- Summary -->
                                    @if($item->resume)
                                        <div class="mb-4">
                                            <p class="text-gray-600 text-sm line-clamp-3">
                                                {{ Str::limit($item->resume, 200) }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Action Button -->
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" 
                                           class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold text-sm transition-colors duration-200">
                                            Lire plus
                                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-200"></i>
                                        </a>

                                        <!-- Share Button -->
                                        <button onclick="openShareModal('{{ route('site.actualite.show', ['slug' => $item->slug]) }}', '{{ addslashes($item->titre) }}')" 
                                                class="text-gray-400 hover:text-iri-accent transition-colors duration-200" 
                                                title="Partager">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($actualites->hasPages())
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            {{ $actualites->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-iri-primary to-iri-secondary w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-newspaper text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucune actualité disponible</h3>
                        <p class="text-gray-600 mb-6">
                            Les actualités seront publiées ici dès qu'elles seront disponibles.
                        </p>
                        <a href="{{ route('site.home') }}" 
                           class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-home mr-2"></i>
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

<!-- Modal de partage -->
<div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95" id="shareModalContent">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-share-alt text-iri-primary mr-2"></i>
                    Partager l'article
                </h3>
                <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-2" id="shareModalTitle">
                <!-- Le titre sera injecté ici -->
            </p>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <!-- Boutons de partage en ligne -->
            <div class="flex items-center justify-center space-x-4 mb-6">
                <!-- Facebook -->
                <a href="#" id="facebookShare" target="_blank"
                   class="w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center transition-all duration-200 transform hover:scale-110 shadow-lg">
                    <i class="fab fa-facebook-f text-lg"></i>
                </a>
                
                <!-- Twitter -->
                <a href="#" id="twitterShare" target="_blank"
                   class="w-12 h-12 bg-black hover:bg-gray-800 text-white rounded-full flex items-center justify-center transition-all duration-200 transform hover:scale-110 shadow-lg">
                    <i class="fab fa-x-twitter text-lg"></i>
                </a>
                
                <!-- LinkedIn -->
                <a href="#" id="linkedinShare" target="_blank"
                   class="w-12 h-12 bg-blue-700 hover:bg-blue-800 text-white rounded-full flex items-center justify-center transition-all duration-200 transform hover:scale-110 shadow-lg">
                    <i class="fab fa-linkedin-in text-lg"></i>
                </a>
                
                <!-- WhatsApp -->
                <a href="#" id="whatsappShare" target="_blank"
                   class="w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition-all duration-200 transform hover:scale-110 shadow-lg">
                    <i class="fab fa-whatsapp text-lg"></i>
                </a>
                
                <!-- Email -->
                <a href="#" id="emailShare"
                   class="w-12 h-12 bg-gray-600 hover:bg-gray-700 text-white rounded-full flex items-center justify-center transition-all duration-200 transform hover:scale-110 shadow-lg">
                    <i class="fas fa-envelope text-lg"></i>
                </a>
            </div>
            
            <!-- Copier le lien -->
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Ou copiez le lien :</p>
                <div class="flex items-center space-x-2">
                    <input type="text" id="shareUrl" readonly
                           class="flex-1 bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                    <button onclick="copyShareUrl()" 
                            class="bg-iri-primary hover:bg-iri-secondary text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-copy mr-1"></i>
                        Copier
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js Script -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Share functionality -->
<script>
    let currentShareUrl = '';
    let currentShareTitle = '';
    
    // Ouvrir le modal de partage
    function openShareModal(url, title) {
        currentShareUrl = url;
        currentShareTitle = title;
        
        // Mettre à jour le contenu du modal
        document.getElementById('shareModalTitle').textContent = title;
        document.getElementById('shareUrl').value = url;
        
        // Mettre à jour les liens de partage
        updateShareLinks(url, title);
        
        // Afficher le modal avec animation
        const modal = document.getElementById('shareModal');
        const modalContent = document.getElementById('shareModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        
        // Prévenir le scroll du body
        document.body.style.overflow = 'hidden';
    }
    
    // Fermer le modal de partage
    function closeShareModal() {
        const modal = document.getElementById('shareModal');
        const modalContent = document.getElementById('shareModalContent');
        
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 200);
    }
    
    // Mettre à jour les liens de partage
    function updateShareLinks(url, title) {
        const encodedUrl = encodeURIComponent(url);
        const encodedTitle = encodeURIComponent(title);
        
        // Facebook
        document.getElementById('facebookShare').href = 
            `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
        
        // Twitter
        document.getElementById('twitterShare').href = 
            `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}&hashtags=GRNUCBC,RDC`;
        
        // LinkedIn
        document.getElementById('linkedinShare').href = 
            `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
        
        // WhatsApp
        document.getElementById('whatsappShare').href = 
            `https://wa.me/?text=${encodedTitle} ${encodedUrl}`;
        
        // Email
        const emailSubject = encodeURIComponent(`${title} - GRN-UCBC`);
        const emailBody = encodeURIComponent(`Je vous partage cet article intéressant :\n\n${title}\n\n${url}\n\n---\nPartagé depuis le site GRN-UCBC`);
        document.getElementById('emailShare').href = 
            `mailto:?subject=${emailSubject}&body=${emailBody}`;
    }
    
    // Copier l'URL de partage
    function copyShareUrl() {
        const shareUrlInput = document.getElementById('shareUrl');
        shareUrlInput.select();
        shareUrlInput.setSelectionRange(0, 99999);
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(currentShareUrl).then(function() {
                showNotification('Lien copié dans le presse-papiers!', 'success');
            }).catch(function(err) {
                fallbackCopyTextToClipboard(currentShareUrl);
            });
        } else {
            fallbackCopyTextToClipboard(currentShareUrl);
        }
    }
    
    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showNotification('Lien copié dans le presse-papiers!', 'success');
        } catch (err) {
            showNotification('Erreur lors de la copie', 'error');
        }
        
        document.body.removeChild(textArea);
    }
    
    // Fermer le modal en cliquant en dehors
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('shareModal');
        const modalContent = document.getElementById('shareModalContent');
        
        if (event.target === modal && !modalContent.contains(event.target)) {
            closeShareModal();
        }
    });
    
    // Fermer le modal avec la touche Échap
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeShareModal();
        }
    });
    
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notif => notif.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white font-medium transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.style.transform = 'translateX(100%)';
        notification.textContent = message;
        
        // Add to body
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
</script>

<!-- Styles -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-none {
        display: block;
        -webkit-line-clamp: none;
        -webkit-box-orient: initial;
        overflow: visible;
    }
    
    /* Modal animation */
    #shareModal {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    
    #shareModalContent {
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    /* Hover effects pour les boutons de partage */
    #shareModal a:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>
@endsection
