/**
 * Monitor de Performance pour détecter les freezes
 * Surveille les événements et la performance en temps réel
 */

class PerformanceMonitor {
    constructor() {
        this.metrics = {
            eventCount: 0,
            scrollEvents: 0,
            mousemoveEvents: 0,
            resizeEvents: 0,
            frameDrops: 0,
            avgFrameTime: 0,
            memoryUsage: 0,
            lastFrameTime: performance.now()
        };

        this.frameTimeHistory = [];
        this.maxHistoryLength = 60; // 1 seconde à 60fps
        this.warningThreshold = 16.67; // 60fps = 16.67ms par frame
        this.alertThreshold = 33.33; // 30fps = 33.33ms par frame

        this.isMonitoring = false;
        this.warningCallback = null;
        this.alertCallback = null;

        this.setupEventCounters();
        this.setupFrameMonitoring();
        this.setupMemoryMonitoring();
    }

    /**
     * Commencer le monitoring
     */
    start(options = {}) {
        this.isMonitoring = true;
        this.warningCallback = options.onWarning || this.defaultWarningHandler;
        this.alertCallback = options.onAlert || this.defaultAlertHandler;

        console.log('🔍 Performance Monitor démarré');

        // Créer un panel de debug si demandé
        if (options.showDebugPanel) {
            this.createDebugPanel();
        }

        return this;
    }

    /**
     * Arrêter le monitoring
     */
    stop() {
        this.isMonitoring = false;
        this.removeDebugPanel();
        console.log('🔍 Performance Monitor arrêté');
    }

    /**
     * Surveiller les événements problématiques
     */
    setupEventCounters() {
        const originalAddEventListener = EventTarget.prototype.addEventListener;
        const monitor = this;

        EventTarget.prototype.addEventListener = function(type, listener, options) {
            if (monitor.isMonitoring) {
                monitor.metrics.eventCount++;

                // Compter les événements problématiques
                switch (type) {
                    case 'scroll':
                        monitor.metrics.scrollEvents++;
                        break;
                    case 'mousemove':
                        monitor.metrics.mousemoveEvents++;
                        break;
                    case 'resize':
                        monitor.metrics.resizeEvents++;
                        break;
                }

                // Wrapper pour mesurer le temps d'exécution
                const wrappedListener = function(event) {
                    const start = performance.now();
                    const result = listener.call(this, event);
                    const duration = performance.now() - start;

                    // Alerte si un handler prend trop de temps
                    if (duration > 10) {
                        monitor.logSlowHandler(type, duration);
                    }

                    return result;
                };

                return originalAddEventListener.call(this, type, wrappedListener, options);
            }

            return originalAddEventListener.call(this, type, listener, options);
        };
    }

    /**
     * Surveiller les frame rates
     */
    setupFrameMonitoring() {
        const monitor = () => {
            if (!this.isMonitoring) return;

            const now = performance.now();
            const frameTime = now - this.metrics.lastFrameTime;

            this.frameTimeHistory.push(frameTime);
            if (this.frameTimeHistory.length > this.maxHistoryLength) {
                this.frameTimeHistory.shift();
            }

            // Calculer la moyenne
            this.metrics.avgFrameTime = this.frameTimeHistory.reduce((a, b) => a + b, 0) / this.frameTimeHistory.length;

            // Détecter les frame drops
            if (frameTime > this.alertThreshold) {
                this.metrics.frameDrops++;
                this.handleAlert(`Frame drop détecté: ${frameTime.toFixed(1)}ms`);
            } else if (frameTime > this.warningThreshold) {
                this.handleWarning(`Frame lent: ${frameTime.toFixed(1)}ms`);
            }

            this.metrics.lastFrameTime = now;

            // Mettre à jour le panel de debug
            this.updateDebugPanel();

            requestAnimationFrame(monitor);
        };

        requestAnimationFrame(monitor);
    }

    /**
     * Surveiller l'utilisation mémoire
     */
    setupMemoryMonitoring() {
        if (!performance.memory) return;

        setInterval(() => {
            if (!this.isMonitoring) return;

            this.metrics.memoryUsage = performance.memory.usedJSHeapSize / 1024 / 1024; // MB

            // Alerte si utilisation mémoire excessive
            if (this.metrics.memoryUsage > 100) {
                this.handleAlert(`Utilisation mémoire élevée: ${this.metrics.memoryUsage.toFixed(1)}MB`);
            }
        }, 5000);
    }

    /**
     * Logger les handlers lents
     */
    logSlowHandler(eventType, duration) {
        console.warn(`⚠️ Handler lent détecté: ${eventType} (${duration.toFixed(1)}ms)`);
    }

    /**
     * Gestion des warnings
     */
    handleWarning(message) {
        if (this.warningCallback) {
            this.warningCallback(message, this.metrics);
        }
    }

    /**
     * Gestion des alertes
     */
    handleAlert(message) {
        if (this.alertCallback) {
            this.alertCallback(message, this.metrics);
        }
    }

    /**
     * Handler par défaut pour les warnings
     */
    defaultWarningHandler(message, metrics) {
        console.warn(`⚠️ Performance Warning: ${message}`, metrics);
    }

    /**
     * Handler par défaut pour les alertes
     */
    defaultAlertHandler(message, metrics) {
        console.error(`🚨 Performance Alert: ${message}`, metrics);
    }

    /**
     * Créer un panel de debug
     */
    createDebugPanel() {
        if (document.getElementById('perf-monitor-panel')) return;

        const panel = document.createElement('div');
        panel.id = 'perf-monitor-panel';
        panel.style.cssText = `
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            z-index: 10000;
            min-width: 200px;
        `;

        document.body.appendChild(panel);
        this.debugPanel = panel;
    }

    /**
     * Mettre à jour le panel de debug
     */
    updateDebugPanel() {
            if (!this.debugPanel) return;

            const fps = Math.round(1000 / this.metrics.avgFrameTime);
            const fpsColor = fps >= 55 ? '#4CAF50' : fps >= 30 ? '#FF9800' : '#F44336';

            this.debugPanel.innerHTML = `
            <div style="color: ${fpsColor}; font-weight: bold;">FPS: ${fps}</div>
            <div>Frame Time: ${this.metrics.avgFrameTime.toFixed(1)}ms</div>
            <div>Frame Drops: ${this.metrics.frameDrops}</div>
            <div>Events: ${this.metrics.eventCount}</div>
            <div>- Scroll: ${this.metrics.scrollEvents}</div>
            <div>- Mousemove: ${this.metrics.mousemoveEvents}</div>
            <div>- Resize: ${this.metrics.resizeEvents}</div>
            ${performance.memory ? `<div>Memory: ${this.metrics.memoryUsage.toFixed(1)}MB</div>` : ''}
        `;
    }

    /**
     * Supprimer le panel de debug
     */
    removeDebugPanel() {
        if (this.debugPanel) {
            this.debugPanel.remove();
            this.debugPanel = null;
        }
    }

    /**
     * Obtenir un rapport détaillé
     */
    getReport() {
        return {
            ...this.metrics,
            fps: Math.round(1000 / this.metrics.avgFrameTime),
            frameTimeHistory: [...this.frameTimeHistory],
            timestamp: new Date().toISOString()
        };
    }

    /**
     * Réinitialiser les métriques
     */
    reset() {
        this.metrics = {
            eventCount: 0,
            scrollEvents: 0,
            mousemoveEvents: 0,
            resizeEvents: 0,
            frameDrops: 0,
            avgFrameTime: 0,
            memoryUsage: 0,
            lastFrameTime: performance.now()
        };
        this.frameTimeHistory = [];
    }
}

// Instance globale
window.performanceMonitor = new PerformanceMonitor();

// Auto-start en développement
if (window.location.hostname === 'localhost' || window.location.hostname.includes('127.0.0.1')) {
    window.performanceMonitor.start({
        showDebugPanel: true,
        onAlert: (message, metrics) => {
            console.error(`🚨 PERFORMANCE ALERT: ${message}`, metrics);
            // Optionnel: envoyer à un service de monitoring
        }
    });
}