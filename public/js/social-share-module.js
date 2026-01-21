/**
 * Module de partage social avancé pour GRN-UCBC
 * Récupère automatiquement les métadonnées et génère les boutons de partage
 * Optimisé pour les pages d'actualités avec fallbacks intelligents
 */

class SocialShareModule {
    constructor() {
        this.metadata = this.extractMetadata();
        this.socialPlatforms = this.initializePlatforms();
    }

    /**
     * Extrait automatiquement les métadonnées de la page
     * Utilise les balises Open Graph ou fallback sur les meta standards
     */
    extractMetadata() {
        // Fonction utilitaire pour récupérer le contenu d'une meta
        const getMeta = (name, property = false) => {
            const selector = property ? `meta[property="${name}"]` : `meta[name="${name}"]`;
            const element = document.querySelector(selector);
            return element ? element.getAttribute('content') : null;
        };

        // URL canonique ou URL actuelle
        const canonicalUrl = document.querySelector('link[rel="canonical"]')?.href || window.location.href;

        // Titre avec fallbacks multiples
        const title = getMeta('og:title', true) ||
            getMeta('twitter:title') ||
            document.title ||
            'Article - GRN UCBC';

        // Description avec fallbacks intelligents
        const description = getMeta('og:description', true) ||
            getMeta('twitter:description') ||
            getMeta('description') ||
            'Découvrez cet article du Programme Gouvernance des Ressources Naturelles - UCBC';

        // Image avec validation
        let image = getMeta('og:image', true) ||
            getMeta('twitter:image') ||
            getMeta('twitter:image:src');

        // Convertir URL relative en URL absolue
        if (image && !image.startsWith('http')) {
            const baseUrl = window.location.origin;
            image = image.startsWith('/') ? baseUrl + image : baseUrl + '/' + image;
        }

        // Image par défaut si aucune trouvée
        if (!image) {
            image = `${window.location.origin}/assets/img/social-share-default.jpg`;
        }

        // Hashtags spécifiques au contexte GRN
        const hashtags = this.extractHashtags();

        // Informations contextuelles pour les actualités
        const author = getMeta('author') || 'GRN UCBC';
        const publishedTime = getMeta('article:published_time', true);
        const section = getMeta('article:section', true) || 'Actualités';

        return {
            url: canonicalUrl,
            title: this.cleanText(title),
            description: this.cleanText(description),
            image: image,
            hashtags: hashtags,
            author: author,
            publishedTime: publishedTime,
            section: section,
            siteName: 'GRN UCBC',
            twitterHandle: '@GRNUCBC'
        };
    }

    /**
     * Extrait les hashtags pertinents selon le contexte
     */
    extractHashtags() {
        const keywords = document.querySelector('meta[name="keywords"]')?.content || '';
        const defaultHashtags = ['GRNUCBC', 'RDC', 'GouvernanceRessourcesNaturelles'];

        // Hashtags basés sur les mots-clés
        const keywordHashtags = keywords.split(',')
            .map(k => k.trim())
            .filter(k => k.length > 2)
            .slice(0, 3)
            .map(k => k.replace(/\s+/g, ''));

        return [...defaultHashtags, ...keywordHashtags].slice(0, 5);
    }

    /**
     * Nettoie le texte pour le partage social
     */
    cleanText(text) {
        return text
            .replace(/&quot;/g, '"')
            .replace(/&amp;/g, '&')
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&#39;/g, "'")
            .trim();
    }

    /**
     * Initialise les plateformes de partage avec leurs configurations
     */
    initializePlatforms() {
        return {
            facebook: {
                name: 'Facebook',
                icon: 'fab fa-facebook-f',
                color: '#1877F2',
                url: (data) => {
                    const params = new URLSearchParams({
                        u: data.url,
                        quote: `${data.title} - ${data.description}`
                    });
                    return `https://www.facebook.com/sharer/sharer.php?${params.toString()}`;
                }
            },
            twitter: {
                name: 'Twitter / X',
                icon: 'fab fa-x-twitter',
                color: '#000000',
                url: (data) => {
                    const text = `${data.title}\n\n${data.description}`;
                    const hashtags = data.hashtags.join(',');
                    const params = new URLSearchParams({
                        text: text,
                        url: data.url,
                        hashtags: hashtags,
                        via: data.twitterHandle.replace('@', '')
                    });
                    return `https://twitter.com/intent/tweet?${params.toString()}`;
                }
            },
            linkedin: {
                name: 'LinkedIn',
                icon: 'fab fa-linkedin-in',
                color: '#0077B5',
                url: (data) => {
                    const params = new URLSearchParams({
                        url: data.url,
                        title: data.title,
                        summary: data.description,
                        source: data.siteName
                    });
                    return `https://www.linkedin.com/sharing/share-offsite/?${params.toString()}`;
                }
            },
            whatsapp: {
                name: 'WhatsApp',
                icon: 'fab fa-whatsapp',
                color: '#25D366',
                url: (data) => {
                    const text = `*${data.title}*\n\n${data.description}\n\n${data.url}`;
                    const params = new URLSearchParams({
                        text: text
                    });
                    return `https://wa.me/?${params.toString()}`;
                }
            },
            telegram: {
                name: 'Telegram',
                icon: 'fab fa-telegram',
                color: '#0088CC',
                url: (data) => {
                    const text = `${data.title}\n\n${data.description}`;
                    const params = new URLSearchParams({
                        url: data.url,
                        text: text
                    });
                    return `https://t.me/share/url?${params.toString()}`;
                }
            },
            email: {
                name: 'Email',
                icon: 'fas fa-envelope',
                color: '#6B7280',
                url: (data) => {
                    const subject = `${data.title} - ${data.siteName}`;
                    const body = `${data.description}\n\nLire l'article complet : ${data.url}\n\n---\nPartagé depuis ${data.siteName}`;
                    const params = new URLSearchParams({
                        subject: subject,
                        body: body
                    });
                    return `mailto:?${params.toString()}`;
                }
            }
        };
    }

    /**
     * Génère le HTML pour un bouton de partage
     */
    generateButton(platform, style = 'default', customClass = '') {
            const config = this.socialPlatforms[platform];
            if (!config) return '';

            const url = config.url(this.metadata);
            const baseClass = 'social-share-btn';

            // Styles prédéfinis - TOUJOURS afficher uniquement les icônes
            const styles = {
                default: `${baseClass} inline-flex items-center justify-center w-10 h-10 rounded-full text-white transition-all duration-200 hover:shadow-lg transform hover:scale-110`,
                minimal: `${baseClass} inline-flex items-center justify-center w-10 h-10 rounded-full text-white transition-all duration-200 hover:shadow-md`,
                hero: `${baseClass} inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-white hover:bg-white/30 transition-all duration-200`,
                outline: `${baseClass} inline-flex items-center justify-center w-10 h-10 rounded-full border-2 font-semibold transition-all duration-200 hover:shadow-md`
            };

            const buttonClass = `${styles[style] || styles.default} ${customClass}`;
            const backgroundColor = style === 'outline' ? 'transparent' : config.color;
            const textColor = style === 'outline' ? config.color : 'white';
            const borderColor = style === 'outline' ? config.color : 'transparent';

            return `
            <a href="${url}" 
               target="_blank" 
               rel="noopener noreferrer"
               class="${buttonClass}"
               style="background-color: ${backgroundColor}; color: ${textColor}; border-color: ${borderColor};"
               data-platform="${platform}"
               data-share-url="${url}"
               title="Partager sur ${config.name}"
               aria-label="Partager cet article sur ${config.name}">
                <i class="${config.icon}" aria-hidden="true"></i>
            </a>
        `;
    }

    /**
     * Génère le conteneur complet des boutons de partage
     */
    generateShareContainer(platforms = ['facebook', 'twitter', 'linkedin', 'whatsapp'], style = 'default', customClass = '') {
        const buttons = platforms
            .filter(platform => this.socialPlatforms[platform])
            .map(platform => this.generateButton(platform, style, customClass))
            .join('\n');

        const containerClass = style === 'hero' ? 'flex items-center space-x-2' : 'flex flex-wrap items-center gap-3';
        
        return `
            <div class="social-share-container ${containerClass}" data-share-style="${style}">
                <span class="text-sm font-medium text-gray-600 mr-2">Partager :</span>
                ${buttons}
            </div>
        `;
    }

    /**
     * Fonction principale pour rendre les boutons dans un conteneur
     */
    renderShareButtons(containerId, options = {}) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.warn(`SocialShare: Container avec l'ID "${containerId}" non trouvé`);
            return;
        }

        const {
            platforms = ['facebook', 'twitter', 'linkedin', 'whatsapp'],
            style = 'default',
            customClass = '',
            showLabel = true
        } = options;

        // Générer le HTML
        let html = '';
        
        if (showLabel && style !== 'hero') {
            html += '<span class="text-sm font-medium text-gray-600 mr-3">Partager :</span>';
        }
        
        const buttons = platforms
            .filter(platform => this.socialPlatforms[platform])
            .map(platform => this.generateButton(platform, style, customClass))
            .join('\n');
        
        html += buttons;

        // Injecter dans le DOM
        container.innerHTML = html;
        container.className = `social-share-container ${this.getContainerClass(style)} ${container.className}`;

        // Ajouter les event listeners
        this.attachEventListeners(container);

        console.log('SocialShare: Boutons générés avec succès', {
            container: containerId,
            platforms: platforms,
            metadata: this.metadata
        });
    }

    /**
     * Retourne la classe CSS appropriée pour le conteneur
     */
    getContainerClass(style) {
        const classes = {
            default: 'flex flex-wrap items-center gap-3',
            minimal: 'flex items-center space-x-2',
            hero: 'flex items-center space-x-2',
            outline: 'flex flex-wrap items-center gap-3'
        };
        return classes[style] || classes.default;
    }

    /**
     * Attache les event listeners pour le tracking et les interactions
     */
    attachEventListeners(container) {
        const buttons = container.querySelectorAll('.social-share-btn');
        
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                const platform = button.dataset.platform;
                const shareUrl = button.dataset.shareUrl;
                
                // Analytics (si Google Analytics est présent)
                if (typeof gtag === 'function') {
                    gtag('event', 'share', {
                        method: platform,
                        content_type: 'article',
                        item_id: this.metadata.url
                    });
                }
                
                // Log pour debug
                console.log('SocialShare: Partage effectué', {
                    platform: platform,
                    url: shareUrl,
                    title: this.metadata.title
                });

                // Ouvrir dans une popup pour certaines plateformes
                if (['facebook', 'twitter', 'linkedin'].includes(platform)) {
                    e.preventDefault();
                    this.openSharePopup(shareUrl, platform);
                }
            });
        });
    }

    /**
     * Ouvre une popup pour le partage
     */
    openSharePopup(url, platform) {
        const width = 600;
        const height = 400;
        const left = (window.screen.width - width) / 2;
        const top = (window.screen.height - height) / 2;
        
        const popup = window.open(
            url,
            `share-${platform}`,
            `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
        );
        
        if (popup) {
            popup.focus();
        }
    }

    /**
     * Copie l'URL dans le presse-papiers
     */
    async copyToClipboard() {
        try {
            await navigator.clipboard.writeText(this.metadata.url);
            
            // Feedback visuel
            this.showCopyFeedback();
            
            console.log('SocialShare: URL copiée dans le presse-papiers');
            return true;
        } catch (err) {
            console.error('SocialShare: Erreur lors de la copie', err);
            return false;
        }
    }

    /**
     * Affiche un feedback visuel après copie
     */
    showCopyFeedback() {
        // Créer une notification temporaire
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.innerHTML = '<i class="fas fa-check mr-2"></i>Lien copié !';
        
        document.body.appendChild(notification);
        
        // Animation d'entrée
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Suppression après 3 secondes
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    /**
     * Ajoute un bouton de copie d'URL
     */
    renderCopyButton(containerId, style = 'default') {
        const container = document.getElementById(containerId);
        if (!container) return;

        const copyButton = document.createElement('button');
        copyButton.className = `social-share-btn ${this.getButtonClass(style)} bg-gray-500 hover:bg-gray-600`;
        copyButton.innerHTML = '<i class="fas fa-link mr-2"></i>Copier le lien';
        copyButton.setAttribute('title', 'Copier le lien de cet article');
        
        copyButton.addEventListener('click', () => {
            this.copyToClipboard();
        });
        
        container.appendChild(copyButton);
    }

    /**
     * Retourne la classe CSS pour un bouton selon le style
     */
    getButtonClass(style) {
        const classes = {
            default: 'inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white transition-all duration-200 hover:shadow-lg transform hover:scale-105',
            minimal: 'inline-flex items-center justify-center w-10 h-10 rounded-full text-white transition-all duration-200 hover:shadow-md',
            hero: 'inline-flex items-center px-3 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-white hover:bg-white/30 transition-all duration-200',
            outline: 'inline-flex items-center px-4 py-2 rounded-lg border-2 font-semibold transition-all duration-200 hover:shadow-md'
        };
        return classes[style] || classes.default;
    }
}

// Instance globale - Vérifier si elle n'existe pas déjà
if (typeof window.socialShare === 'undefined') {
    window.socialShare = new SocialShareModule();
}

/**
 * Fonction globale pour faciliter l'utilisation
 */
window.renderShareButtons = function(containerId, options = {}) {
    window.socialShare.renderShareButtons(containerId, options);
};

/**
 * Fonction pour ajouter un bouton de copie
 */
window.renderCopyButton = function(containerId, style = 'default') {
    window.socialShare.renderCopyButton(containerId, style);
};

/**
 * Initialisation automatique pour les conteneurs avec data-share
 */
document.addEventListener('DOMContentLoaded', function() {
    // Auto-initialisation des conteneurs avec attribut data-share
    const autoContainers = document.querySelectorAll('[data-share]');
    
    autoContainers.forEach(container => {
        const platforms = container.dataset.sharePlatforms ? 
            container.dataset.sharePlatforms.split(',') : 
            ['facebook', 'twitter', 'linkedin', 'whatsapp'];
        
        const style = container.dataset.shareStyle || 'default';
        const showLabel = container.dataset.shareLabel !== 'false';
        
        window.socialShare.renderShareButtons(container.id, {
            platforms: platforms,
            style: style,
            showLabel: showLabel
        });
    });
});

// Export pour utilisation en module
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SocialShareModule;
}