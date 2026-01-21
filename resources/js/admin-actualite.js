/**
 * Admin Actualité - CKEditor avec Vite
 * Version propre et robuste avec destruction automatique
 */

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

let editorInstances = new Map();

/**
 * Initialise CKEditor sur un sélecteur donné
 * @param {string} selector - Sélecteur CSS (défaut: '#contenu')
 */
export function initCKEditor(selector = '#contenu') {
    const element = document.querySelector(selector);

    if (!element) {
        console.warn(`CKEditor: Élément ${selector} non trouvé`);
        return null;
    }

    // Garde contre double initialisation
    if (element.dataset.ckeditorMounted === '1') {
        console.info(`CKEditor: ${selector} déjà initialisé`);
        return editorInstances.get(element);
    }

    // Configuration simple et documentée
    const config = {
        toolbar: [
            'heading', '|',
            'bold', 'italic', 'link', '|',
            'bulletedList', 'numberedList', '|',
            'outdent', 'indent', '|',
            'blockQuote', 'insertTable', '|',
            'undo', 'redo'
        ],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }
            ]
        },
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
        }
    };

    return ClassicEditor
        .create(element, config)
        .then(editor => {
            // Marquer comme monté
            element.dataset.ckeditorMounted = '1';

            // Stocker l'instance pour destruction ultérieure
            editorInstances.set(element, editor);

            console.info(`CKEditor: ${selector} initialisé avec succès`);

            // Mettre à jour le statut si l'élément existe
            updateEditorStatus('ready');

            return editor;
        })
        .catch(error => {
            console.error(`CKEditor: Erreur d'initialisation sur ${selector}:`, error);

            // Activer le fallback en cas d'erreur
            activateTextareaFallback(element);

            return null;
        });
}

/**
 * Détruit proprement une instance CKEditor
 * @param {string} selector - Sélecteur CSS
 */
export function destroyCKEditor(selector = '#contenu') {
    const element = document.querySelector(selector);

    if (!element) return;

    const editor = editorInstances.get(element);
    if (editor) {
        editor.destroy()
            .then(() => {
                element.dataset.ckeditorMounted = '0';
                editorInstances.delete(element);
                console.info(`CKEditor: ${selector} détruit`);
            })
            .catch(error => {
                console.error(`CKEditor: Erreur lors de la destruction:`, error);
            });
    }
}

/**
 * Met à jour le statut visuel de l'éditeur
 * @param {string} status - 'loading', 'ready', 'error', 'fallback'
 */
function updateEditorStatus(status) {
    const statusEl = document.getElementById('editor-status');
    if (!statusEl) return;

    const statusConfig = {
        loading: {
            color: 'bg-yellow-400',
            text: 'Chargement de l\'éditeur...',
            textColor: 'text-gray-500'
        },
        ready: {
            color: 'bg-green-400',
            text: '✅ Éditeur prêt',
            textColor: 'text-green-600'
        },
        error: {
            color: 'bg-red-400',
            text: '❌ Erreur de chargement',
            textColor: 'text-red-600'
        },
        fallback: {
            color: 'bg-orange-400',
            text: '📝 Mode texte simple',
            textColor: 'text-orange-600'
        }
    };

    const config = statusConfig[status] || statusConfig.loading;

    statusEl.innerHTML = `
        <div class="w-2 h-2 rounded-full ${config.color} mr-2${status === 'loading' ? ' animate-pulse' : ''}"></div>
        <span class="${config.textColor} font-medium">${config.text}</span>
    `;
}

/**
 * Active le mode fallback textarea simple
 * @param {HTMLElement} element - L'élément textarea
 */
function activateTextareaFallback(element) {
    element.style.border = '2px solid #f59e0b';
    element.style.backgroundColor = '#fffbeb';
    element.style.fontFamily = 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
    element.style.fontSize = '14px';
    element.style.lineHeight = '1.6';
    element.placeholder = '📝 Mode texte simple - HTML supporté.\n\nExemples :\n<h2>Titre</h2>\n<p>Paragraphe avec <strong>gras</strong> et <em>italique</em></p>\n<ul><li>Liste</li></ul>';

    updateEditorStatus('fallback');

    console.info('CKEditor: Mode fallback activé');
}

/**
 * Gestion de la navigation Turbo/Livewire
 */
function setupNavigationHandlers() {
    // Turbo (si présent)
    if (window.Turbo) {
        document.addEventListener('turbo:before-cache', () => {
            editorInstances.forEach((editor, element) => {
                const selector = element.id ? `#${element.id}` : element.tagName.toLowerCase();
                destroyCKEditor(selector);
            });
        });
    }

    // Livewire (si présent)
    if (window.Livewire) {
        document.addEventListener('livewire:navigating', () => {
            editorInstances.forEach((editor, element) => {
                const selector = element.id ? `#${element.id}` : element.tagName.toLowerCase();
                destroyCKEditor(selector);
            });
        });
    }
}

// Auto-génération du slug (conservé tel quel)
function setupSlugGeneration() {
    const titreInput = document.getElementById('titre');
    const slugInput = document.getElementById('slug');
    const slugPreview = document.getElementById('slug-preview');

    if (titreInput && slugInput && slugPreview) {
        function generateSlug(text) {
            return text
                .toLowerCase()
                .trim()
                .replace(/[àáâãäå]/g, 'a')
                .replace(/[èéêë]/g, 'e')
                .replace(/[ìíîï]/g, 'i')
                .replace(/[òóôõö]/g, 'o')
                .replace(/[ùúûü]/g, 'u')
                .replace(/[ýÿ]/g, 'y')
                .replace(/[ñ]/g, 'n')
                .replace(/[ç]/g, 'c')
                .replace(/[œ]/g, 'oe')
                .replace(/[æ]/g, 'ae')
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        titreInput.addEventListener('input', function() {
            const slug = generateSlug(this.value);
            slugInput.value = slug;
            slugPreview.textContent = slug || 'sera-generee-automatiquement';
        });
    }
}

// Initialisation au chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    setupNavigationHandlers();
    setupSlugGeneration();

    // Initialiser CKEditor si le textarea existe
    const contentTextarea = document.querySelector('#contenu');
    if (contentTextarea) {
        updateEditorStatus('loading');
        initCKEditor('#contenu');
    }
});

// Support Livewire pour ré-initialisation après re-render
if (window.Livewire) {
    document.addEventListener('livewire:load', () => {
        const contentTextarea = document.querySelector('#contenu');
        if (contentTextarea && !contentTextarea.dataset.ckeditorMounted) {
            updateEditorStatus('loading');
            initCKEditor('#contenu');
        }
    });
}

// Exportations par défaut
export default {
    initCKEditor,
    destroyCKEditor
};