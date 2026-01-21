{{-- Composant de partage social global - peut être utilisé sur toutes les pages --}}
@props([
    'style' => 'default',
    'size' => 'medium',
    'showLabels' => false,
    'platforms' => 'facebook,twitter,linkedin,whatsapp,telegram,email',
    'customTitle' => null,
    'customDescription' => null,
    'customImage' => null,
    'customUrl' => null,
    'position' => 'inline', // inline, fixed-left, fixed-right, floating
    'analytics' => true
])

@php
    // Extraction automatique des métadonnées de la page courante
    $pageTitle = $customTitle ?? (isset($pageTitle) ? $pageTitle : (View::hasSection('title') ? View::getSection('title') : 'Programme Gouvernance des Ressources Naturelles - GRN-UCBC'));
    $pageDescription = $customDescription ?? (isset($pageDescription) ? $pageDescription : (View::hasSection('description') ? View::getSection('description') : 'Programme Gouvernance des Ressources Naturelles de l\'Université Chrétienne Bilingue du Congo'));
    $pageImage = $customImage ?? (isset($pageImage) ? $pageImage : asset('assets/img/logos/iri-logo.png'));
    $pageUrl = $customUrl ?? url()->current();
    
    // Classes CSS selon la position
    $positionClasses = match($position) {
        'fixed-left' => 'social-share-fixed social-share-fixed-left',
        'fixed-right' => 'social-share-fixed social-share-fixed-right',
        'floating' => 'social-share-floating',
        default => 'social-share-inline'
    };
@endphp

{{-- Container principal --}}
<div class="social-share-container {{ $positionClasses }}" 
     data-share="container"
     data-style="{{ $style }}"
     data-size="{{ $size }}"
     data-platforms="{{ $platforms }}"
     data-show-labels="{{ $showLabels ? 'true' : 'false' }}"
     data-analytics="{{ $analytics ? 'true' : 'false' }}"
     data-title="{{ htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') }}"
     data-description="{{ htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') }}"
     data-image="{{ $pageImage }}"
     data-url="{{ $pageUrl }}">
    
    {{-- Titre optionnel --}}
    @if($position === 'inline' && $showLabels)
        <div class="social-share-title">
            <i class="fas fa-share-alt"></i>
            <span>Partager cette page</span>
        </div>
    @endif
    
    {{-- Container des boutons (sera rempli par JavaScript) --}}
    <div class="social-share-buttons" data-share="buttons">
        {{-- Les boutons seront injectés par JavaScript --}}
    </div>
    
    {{-- Message de confirmation (pour le partage par email et copie) --}}
    <div class="social-share-message" data-share="message" style="display: none;">
        <i class="fas fa-check-circle"></i>
        <span></span>
    </div>
</div>

{{-- Styles CSS additionnels pour les positions spéciales --}}
@if($position !== 'inline')
<style>
    .social-share-fixed {
        position: fixed;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 15px 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .social-share-fixed-left {
        left: 20px;
    }
    
    .social-share-fixed-right {
        right: 20px;
    }
    
    .social-share-fixed .social-share-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .social-share-floating {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        background: var(--iri-primary);
        border-radius: 50px;
        padding: 15px 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .social-share-floating:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }
    
    .social-share-floating .social-share-buttons {
        display: flex;
        gap: 12px;
    }
    
    .social-share-loading {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--iri-gray);
        font-size: 14px;
    }
    
    .social-share-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        font-weight: 600;
        color: var(--iri-primary);
        font-size: 16px;
    }
    
    .social-share-message {
        margin-top: 10px;
        padding: 8px 12px;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-radius: 6px;
        color: rgb(34, 197, 94);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    @media (max-width: 768px) {
        .social-share-fixed {
            position: relative;
            top: auto;
            left: auto;
            right: auto;
            transform: none;
            margin: 20px auto;
            max-width: 300px;
        }
        
        .social-share-fixed .social-share-buttons {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .social-share-floating {
            bottom: 20px;
            right: 20px;
            padding: 12px 16px;
        }
    }
</style>
@endif

{{-- Script d'initialisation automatique --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation automatique du composant social share global
    const container = document.querySelector('[data-share="container"]');
    if (container && typeof SocialShareModule !== 'undefined') {
        // Vérifier si le module n'est pas déjà initialisé pour ce container
        if (!container.dataset.initialized) {
            const config = {
                style: container.dataset.style || 'default',
                size: container.dataset.size || 'medium',
                showLabels: container.dataset.showLabels === 'true',
                platforms: container.dataset.platforms ? container.dataset.platforms.split(',') : ['facebook', 'twitter', 'linkedin', 'whatsapp', 'telegram', 'email'],
                analytics: container.dataset.analytics === 'true',
                metadata: {
                    title: container.dataset.title,
                    description: container.dataset.description,
                    image: container.dataset.image,
                    url: container.dataset.url
                }
            };
            
            // Initialiser le module pour ce container
            const shareModule = new SocialShareModule(config);
            shareModule.init(container);
            
            // Marquer comme initialisé
            container.dataset.initialized = 'true';
            
            // Cacher le message de chargement
            const loading = container.querySelector('.social-share-loading');
            if (loading) {
                loading.style.display = 'none';
            }
        }
    }
});
</script>
