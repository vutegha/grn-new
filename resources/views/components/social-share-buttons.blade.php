{{--
    Composant de boutons de partage social optimisé pour GRN-UCBC
    Utilise le SocialShareModule JavaScript pour une meilleure performance
    Compatible avec les métadonnées Open Graph de showactualite.blade.php
    
    @param string $url - URL à partager (optionnel, utilise l'URL actuelle par défaut)
    @param string $title - Titre à partager (optionnel, extrait des meta)
    @param string $description - Description à partager (optionnel, extrait des meta)  
    @param string $image - Image à partager (optionnel, extrait des meta)
    @param string $style - Style des boutons: default|minimal|hero|outline
    @param array|string $platforms - Plateformes à afficher: facebook,twitter,linkedin,whatsapp,telegram,email
    @param bool $showLabel - Afficher le label "Partager :"
    @param bool $includeCopy - Inclure un bouton de copie d'URL
    @param string $class - Classes CSS supplémentaires
--}}

@props([
    'url' => null,
    'title' => null, 
    'description' => null,
    'image' => null,
    'style' => 'default',
    'platforms' => ['facebook', 'twitter', 'linkedin', 'whatsapp'],
    'showLabel' => true,
    'includeCopy' => false,
    'class' => ''
])

@php
    // Générer un ID unique pour ce composant
    $shareId = 'share-' . uniqid();
    
    // Convertir platforms en string si c'est un array
    $platformsString = is_array($platforms) ? implode(',', $platforms) : $platforms;
    
    // Injecter les métadonnées personnalisées si fournies
    $customMeta = [];
    if ($url) $customMeta['url'] = $url;
    if ($title) $customMeta['title'] = $title;
    if ($description) $customMeta['description'] = $description;
    if ($image) $customMeta['image'] = $image;
@endphp

{{-- Conteneur principal avec chargement progressif --}}
<div id="{{ $shareId }}" 
     class="social-share-wrapper {{ $class }}"
     data-share="true"
     data-share-platforms="{{ $platformsString }}"
     data-share-style="{{ $style }}"
     data-share-label="{{ $showLabel ? 'true' : 'false' }}"
     data-include-copy="{{ $includeCopy ? 'true' : 'false' }}">
    
    {{-- Les boutons seront injectés par JavaScript --}}
</div>

{{-- Injection des métadonnées personnalisées dans le JavaScript --}}
@if(!empty($customMeta))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Attendre que le module soit initialisé
            const waitForModule = setInterval(() => {
                if (window.socialShare && window.socialShare.metadata) {
                    // Injecter les métadonnées personnalisées
                    const customMeta = @json($customMeta);
                    Object.assign(window.socialShare.metadata, customMeta);
                    clearInterval(waitForModule);
                }
            }, 50);
        });
    </script>
@endif

{{-- Gestion du bouton de copie si demandé --}}
@if($includeCopy)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter le bouton de copie après l'initialisation
            setTimeout(() => {
                if (window.renderCopyButton) {
                    renderCopyButton('{{ $shareId }}', '{{ $style }}');
                }
            }, 200);
        });
    </script>
@endif

{{-- Chargement du module de partage social optimisé --}}
@once
    @push('scripts')
    <script src="{{ asset('js/social-share-module.js') }}" defer></script>
    @endpush
@endonce

{{-- Styles CSS personnalisés pour les animations et responsive --}}
@once
    @push('styles')
    <style>
        /* Styles pour les boutons de partage social optimisés */
        .social-share-wrapper {
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        
        .social-share-container {
            opacity: 0;
            animation: fadeInUp 0.5s ease-out 0.1s forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Masquer le placeholder de chargement quand les vrais boutons apparaissent */
        .social-share-container ~ div[id$="-loading"] {
            display: none;
        }
        
        /* Améliorer les animations des boutons */
        .social-share-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .social-share-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: width 0.6s, height 0.6s, top 0.6s, left 0.6s;
            transform: translate(-50%, -50%);
            z-index: 0;
        }
        
        .social-share-btn:hover::before {
            width: 200px;
            height: 200px;
        }
        
        .social-share-btn > * {
            position: relative;
            z-index: 1;
        }
        
        /* Styles responsifs améliorés */
        @media (max-width: 640px) {
            .social-share-container {
                gap: 0.5rem;
            }
            
            .social-share-container span {
                font-size: 0.75rem;
            }
            
            /* Mode compact sur mobile */
            .social-share-container[data-share-style="default"] .social-share-btn span,
            .social-share-container[data-share-style="outline"] .social-share-btn span {
                display: none;
            }
            
            .social-share-container[data-share-style="default"] .social-share-btn i,
            .social-share-container[data-share-style="outline"] .social-share-btn i {
                margin-right: 0 !important;
            }
        }
        
        /* Style hero spécifique avec backdrop blur amélioré */
        .social-share-container[data-share-style="hero"] .social-share-btn {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Style outline avec couleurs personnalisées */
        .social-share-container[data-share-style="outline"] .social-share-btn:hover {
            transform: translateY(-2px) scale(1.05);
        }
        
        /* Animation pour les notifications */
        .copy-notification,
        .share-notification {
            animation: slideInRight 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Amélioration du placeholder de chargement */
        .social-share-wrapper div[id$="-loading"] {
            transition: opacity 0.3s ease;
        }
        
        .social-share-wrapper div[id$="-loading"] div {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            background-size: 200px 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% {
                background-position: -200px 0;
            }
            100% {
                background-position: calc(200px + 100%) 0;
            }
        }
    </style>
    @endpush
@endonce

{{-- Script d'initialisation et tracking pour GRN-UCBC --}}
@once
    @push('scripts')
    <script>
        // Configuration et tracking spécifique pour GRN-UCBC
        document.addEventListener('DOMContentLoaded', function() {
            // Tracking des partages sociaux
            document.addEventListener('click', function(e) {
                if (e.target.closest('.social-share-btn')) {
                    const button = e.target.closest('.social-share-btn');
                    const platform = button.dataset.platform;
                    
                    // Google Analytics 4 si disponible
                    if (typeof gtag === 'function') {
                        gtag('event', 'share', {
                            'event_category': 'social',
                            'event_label': platform,
                            'custom_map': {
                                'custom_parameter_1': 'actualite_share',
                                'custom_parameter_2': window.location.pathname
                            }
                        });
                    }
                    
                    // Facebook Pixel si disponible
                    if (typeof fbq === 'function') {
                        fbq('track', 'Share', {
                            content_name: document.title,
                            content_category: 'actualite'
                        });
                    }
                    
                    // Log pour suivi interne
                    console.log('🔗 GRN Social Share:', {
                        platform: platform,
                        page: window.location.pathname,
                        title: document.title,
                        timestamp: new Date().toISOString()
                    });
                }
            });
            
            // Améliorer l'accessibilité
            document.querySelectorAll('.social-share-btn').forEach(btn => {
                btn.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });
            
            console.log('✅ Système de partage social GRN-UCBC initialisé');
        });
    </script>
    @endpush
@endonce
