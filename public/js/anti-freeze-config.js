/**
 * Configuration recommandée pour éviter les freezes
 * À ajouter dans votre layout admin après les scripts d'optimisation
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // 1. CONFIGURATION GLOBALE ANTI-FREEZE
    const CONFIG = {
        // Limites d'événements
        maxScrollListeners: 5,
        maxMouseListeners: 3,
        maxEventListeners: 50,

        // Throttling par défaut
        scrollThrottle: 16, // ~60fps
        mouseThrottle: 16, // ~60fps
        resizeThrottle: 100, // 10fps
        inputDebounce: 300, // 300ms

        // Seuils de performance
        maxFrameTime: 16.67, // 60fps
        maxMemoryMB: 100,

        // Features
        passiveEvents: true,
        autoCleanup: true,
        performanceMonitoring: true
    };

    // 2. APPLIQUER LA CONFIGURATION
    if (window.eventManager) {
        // Configurer l'Event Manager
        window.eventManager.defaultThrottleDelay = CONFIG.scrollThrottle;
        window.eventManager.defaultDebounceDelay = CONFIG.inputDebounce;
        window.eventManager.maxListeners = CONFIG.maxEventListeners;

        console.log('✅ Event Manager configuré avec les paramètres anti-freeze');
    }

    if (window.performanceMonitor) {
        // Démarrer le monitoring avec alertes
        window.performanceMonitor.start({
            showDebugPanel: window.location.hostname.includes('localhost'),
            onWarning: (message, metrics) => {
                console.warn(`⚠️ Performance Warning: ${message}`, metrics);
            },
            onAlert: (message, metrics) => {
                console.error(`🚨 Performance Alert: ${message}`, metrics);

                // Actions automatiques en cas d'alerte
                if (metrics.frameDrops > 10) {
                    // Désactiver les animations non critiques
                    document.querySelectorAll('.animate-pulse, .animate-bounce').forEach(el => {
                        el.classList.remove('animate-pulse', 'animate-bounce');
                    });
                }

                if (metrics.memoryUsage > CONFIG.maxMemoryMB) {
                    // Forcer le garbage collection si possible
                    if (window.gc) {
                        window.gc();
                    }

                    // Nettoyer les listeners détachés
                    window.eventManager.cleanupDetachedElements();
                }
            }
        });

        console.log('✅ Performance Monitor démarré avec alertes automatiques');
    }

    // 3. OPTIMISATIONS CSS CRITIQUES
    const criticalCSS = `
        /* Optimisations anti-freeze */
        * {
            /* Accélération matérielle pour les éléments critiques */
        }
        
        .scroll-container {
            /* Optimisation du scroll */
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            will-change: scroll-position;
        }
        
        .animate-optimized {
            /* Animations optimisées GPU */
            will-change: transform, opacity;
            transform: translateZ(0);
        }
        
        /* Réduire la complexité des sélectors */
        [class*="transition-"] {
            transition-duration: 0.15s;
        }
        
        /* Éviter les repaint coûteux */
        img, video {
            image-rendering: optimizeSpeed;
        }
    `;

    // Injecter le CSS critique
    const style = document.createElement('style');
    style.textContent = criticalCSS;
    document.head.appendChild(style);

    // 4. OPTIMISATIONS DOM
    // Lazy loading pour les images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // 5. NETTOYAGE AUTOMATIQUE
    if (CONFIG.autoCleanup) {
        // Cleanup périodique des listeners orphelins
        setInterval(() => {
            if (window.eventManager) {
                window.eventManager.cleanupDetachedElements();
            }
        }, 30000); // Toutes les 30 secondes

        // Cleanup avant déchargement
        window.addEventListener('beforeunload', () => {
            if (window.eventManager) {
                window.eventManager.cleanup();
            }
            if (window.performanceMonitor) {
                window.performanceMonitor.stop();
            }
        });
    }

    // 6. THROTTLING GLOBAL DES ÉVÉNEMENTS CRITIQUES
    const originalAddEventListener = EventTarget.prototype.addEventListener;
    EventTarget.prototype.addEventListener = function(type, listener, options) {
        let optimizedListener = listener;

        // Appliquer automatiquement le throttling sur les événements critiques
        if (['scroll', 'resize', 'mousemove', 'touchmove'].includes(type)) {
            if (window.eventManager && window.eventManager.throttle) {
                optimizedListener = window.eventManager.throttle(listener, CONFIG.scrollThrottle);
            }

            // Forcer les options passives
            if (CONFIG.passiveEvents && !options) {
                options = { passive: true };
            } else if (CONFIG.passiveEvents && typeof options === 'boolean') {
                options = { capture: options, passive: true };
            } else if (CONFIG.passiveEvents && typeof options === 'object') {
                options.passive = true;
            }
        }

        return originalAddEventListener.call(this, type, optimizedListener, options);
    };

    console.log('✅ Configuration anti-freeze appliquée globalement');

    // 7. RAPPORT INITIAL
    setTimeout(() => {
        if (window.performanceMonitor) {
            const report = window.performanceMonitor.getReport();
            console.log('📊 Rapport de performance initial:', report);
        }
    }, 2000);
});