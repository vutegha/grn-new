/**
 * Gestionnaire d'événements optimisé pour éviter les freezes
 * Implémente throttling, debouncing et cleanup automatique
 */

class EventManager {
    constructor() {
        this.listeners = new Map();
        this.throttleCache = new Map();
        this.debounceCache = new Map();
        this.animationFrameQueue = new Set();

        // Configuration par défaut
        this.defaultThrottleDelay = 16; // ~60fps
        this.defaultDebounceDelay = 300;
        this.maxListeners = 100;

        this.setupCleanup();
    }

    /**
     * Throttle - limite l'exécution à une fois par délai
     */
    throttle(fn, delay = this.defaultThrottleDelay, context = null) {
        const key = fn.toString();

        if (this.throttleCache.has(key)) {
            return this.throttleCache.get(key);
        }

        let isThrottled = false;
        const throttledFn = function(...args) {
            if (isThrottled) return;

            isThrottled = true;
            setTimeout(() => isThrottled = false, delay);

            return fn.apply(context || this, args);
        };

        this.throttleCache.set(key, throttledFn);
        return throttledFn;
    }

    /**
     * Debounce - retarde l'exécution jusqu'à ce que les appels cessent
     */
    debounce(fn, delay = this.defaultDebounceDelay, context = null) {
        const key = fn.toString();

        if (this.debounceCache.has(key)) {
            return this.debounceCache.get(key);
        }

        let timeoutId;
        const debouncedFn = function(...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                fn.apply(context || this, args);
            }, delay);
        };

        this.debounceCache.set(key, debouncedFn);
        return debouncedFn;
    }

    /**
     * RequestAnimationFrame optimisé
     */
    requestFrame(fn, id = null) {
        if (id && this.animationFrameQueue.has(id)) {
            return; // Évite les doublons
        }

        const frameId = requestAnimationFrame(() => {
            if (id) this.animationFrameQueue.delete(id);
            fn();
        });

        if (id) this.animationFrameQueue.add(id);
        return frameId;
    }

    /**
     * Ajouter un event listener optimisé
     */
    addEventListener(element, eventType, handler, options = {}) {
        // Vérification du nombre maximum de listeners
        if (this.listeners.size >= this.maxListeners) {
            console.warn('EventManager: Maximum listeners reached');
            return;
        }

        // Appliquer throttling/debouncing automatiquement selon le type d'événement
        let optimizedHandler = handler;

        if (['scroll', 'resize', 'mousemove', 'touchmove'].includes(eventType)) {
            optimizedHandler = this.throttle(handler, options.throttle || 16);
        } else if (['input', 'keyup', 'search'].includes(eventType)) {
            optimizedHandler = this.debounce(handler, options.debounce || 200);
        }

        // Options par défaut pour les événements passifs
        const eventOptions = {
            passive: ['scroll', 'touchstart', 'touchmove', 'wheel'].includes(eventType),
            ...options
        };

        element.addEventListener(eventType, optimizedHandler, eventOptions);

        // Stocker pour cleanup
        const listenerId = `${element.tagName}-${eventType}-${Date.now()}`;
        this.listeners.set(listenerId, {
            element,
            eventType,
            handler: optimizedHandler,
            options: eventOptions
        });

        return listenerId;
    }

    /**
     * Supprimer un event listener
     */
    removeEventListener(listenerId) {
        const listener = this.listeners.get(listenerId);
        if (listener) {
            listener.element.removeEventListener(
                listener.eventType,
                listener.handler,
                listener.options
            );
            this.listeners.delete(listenerId);
        }
    }

    /**
     * Cleanup automatique lors du changement de page
     */
    setupCleanup() {
        // Cleanup avant déchargement de la page
        window.addEventListener('beforeunload', () => {
            this.cleanup();
        });

        // Cleanup périodique des éléments supprimés du DOM
        if (typeof MutationObserver !== 'undefined') {
            const observer = new MutationObserver(() => {
                this.cleanupDetachedElements();
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }

    /**
     * Nettoyer les listeners d'éléments supprimés du DOM
     */
    cleanupDetachedElements() {
        for (const [id, listener] of this.listeners) {
            if (!document.contains(listener.element)) {
                this.removeEventListener(id);
            }
        }
    }

    /**
     * Cleanup complet
     */
    cleanup() {
        // Supprimer tous les listeners
        for (const [id] of this.listeners) {
            this.removeEventListener(id);
        }

        // Vider les caches
        this.throttleCache.clear();
        this.debounceCache.clear();

        // Annuler les animation frames en attente
        for (const frameId of this.animationFrameQueue) {
            cancelAnimationFrame(frameId);
        }
        this.animationFrameQueue.clear();
    }

    /**
     * Statistiques de performance
     */
    getStats() {
        return {
            activeListeners: this.listeners.size,
            throttleCache: this.throttleCache.size,
            debounceCache: this.debounceCache.size,
            pendingFrames: this.animationFrameQueue.size,
            memoryUsage: this.estimateMemoryUsage()
        };
    }

    estimateMemoryUsage() {
        return {
            listeners: this.listeners.size * 200, // estimation en bytes
            caches: (this.throttleCache.size + this.debounceCache.size) * 100
        };
    }
}

// Instance globale
window.eventManager = new EventManager();

// Méthodes helper globales
window.throttle = (fn, delay) => window.eventManager.throttle(fn, delay);
window.debounce = (fn, delay) => window.eventManager.debounce(fn, delay);

console.log('Event Manager optimisé initialisé');