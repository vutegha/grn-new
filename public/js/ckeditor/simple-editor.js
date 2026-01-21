/**
 * CKEditor 5 - Build minimaliste et fonctionnel
 * Alternative au CDN pour éviter les problèmes de connectivité
 * Version: Custom build pour IRI
 */

// Configuration d'un éditeur basique mais fonctionnel
window.SimpleEditor = {
    create: function(element, config = {}) {
        return new Promise((resolve, reject) => {
            try {
                // Vérifier que l'élément existe
                if (!element) {
                    throw new Error('Element not found');
                }

                // Créer l'interface d'édition
                const editorContainer = document.createElement('div');
                editorContainer.className = 'simple-editor-container';
                editorContainer.style.cssText = `
                    border: 2px solid #e5e7eb;
                    border-radius: 12px;
                    background: white;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                `;

                // Toolbar
                const toolbar = document.createElement('div');
                toolbar.className = 'simple-editor-toolbar';
                toolbar.style.cssText = `
                    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                    border-bottom: 2px solid #e2e8f0;
                    padding: 12px;
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                `;

                // Boutons de la toolbar
                const buttons = [
                    { name: 'bold', label: 'B', title: 'Gras', cmd: 'bold' },
                    { name: 'italic', label: 'I', title: 'Italique', cmd: 'italic' },
                    { name: 'underline', label: 'U', title: 'Souligné', cmd: 'underline' },
                    { name: 'separator', label: '|' },
                    { name: 'heading1', label: 'H1', title: 'Titre 1', cmd: 'formatBlock', value: 'h1' },
                    { name: 'heading2', label: 'H2', title: 'Titre 2', cmd: 'formatBlock', value: 'h2' },
                    { name: 'heading3', label: 'H3', title: 'Titre 3', cmd: 'formatBlock', value: 'h3' },
                    { name: 'separator', label: '|' },
                    { name: 'insertOrderedList', label: '1.', title: 'Liste numérotée', cmd: 'insertOrderedList' },
                    { name: 'insertUnorderedList', label: '•', title: 'Liste à puces', cmd: 'insertUnorderedList' },
                    { name: 'separator', label: '|' },
                    { name: 'createLink', label: '🔗', title: 'Insérer un lien', cmd: 'createLink' },
                    { name: 'separator', label: '|' },
                    { name: 'undo', label: '↶', title: 'Annuler', cmd: 'undo' },
                    { name: 'redo', label: '↷', title: 'Rétablir', cmd: 'redo' }
                ];

                buttons.forEach(btn => {
                    if (btn.name === 'separator') {
                        const sep = document.createElement('span');
                        sep.textContent = btn.label;
                        sep.style.cssText = 'color: #cbd5e1; margin: 0 4px;';
                        toolbar.appendChild(sep);
                    } else {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = btn.label;
                        button.title = btn.title;
                        button.style.cssText = `
                            background: white;
                            border: 1px solid #d1d5db;
                            border-radius: 6px;
                            padding: 6px 10px;
                            font-weight: bold;
                            cursor: pointer;
                            transition: all 0.2s ease;
                            font-size: 14px;
                        `;

                        button.addEventListener('mouseover', () => {
                            button.style.background = '#1e472f';
                            button.style.color = 'white';
                            button.style.transform = 'translateY(-1px)';
                        });

                        button.addEventListener('mouseout', () => {
                            button.style.background = 'white';
                            button.style.color = 'black';
                            button.style.transform = 'translateY(0)';
                        });

                        button.addEventListener('click', () => {
                            if (btn.cmd === 'createLink') {
                                const url = prompt('Entrez l\'URL du lien:');
                                if (url) {
                                    document.execCommand(btn.cmd, false, url);
                                }
                            } else if (btn.value) {
                                document.execCommand(btn.cmd, false, btn.value);
                            } else {
                                document.execCommand(btn.cmd, false, null);
                            }
                            editableDiv.focus();
                        });

                        toolbar.appendChild(button);
                    }
                });

                // Zone d'édition
                const editableDiv = document.createElement('div');
                editableDiv.contentEditable = true;
                editableDiv.className = 'simple-editor-content';
                editableDiv.style.cssText = `
                    min-height: 500px;
                    padding: 24px;
                    font-size: 16px;
                    line-height: 1.7;
                    font-family: system-ui, -apple-system, sans-serif;
                    color: #374151;
                    outline: none;
                    background: white;
                `;

                // Contenu initial
                editableDiv.innerHTML = element.value || '<p>Commencez à taper votre contenu...</p>';

                // Assemblage
                editorContainer.appendChild(toolbar);
                editorContainer.appendChild(editableDiv);

                // Remplacer le textarea
                element.style.display = 'none';
                element.parentNode.insertBefore(editorContainer, element);

                // API de l'éditeur
                const editor = {
                    element: editableDiv,
                    container: editorContainer,
                    getData: function() {
                        return editableDiv.innerHTML;
                    },
                    setData: function(data) {
                        editableDiv.innerHTML = data;
                        this.updateTextarea();
                    },
                    updateTextarea: function() {
                        element.value = this.getData();
                    },
                    ui: {
                        view: {
                            editable: {
                                element: editableDiv
                            }
                        }
                    },
                    model: {
                        document: {
                            on: function(event, callback) {
                                if (event === 'change:data') {
                                    editableDiv.addEventListener('input', callback);
                                    editableDiv.addEventListener('keyup', callback);
                                    editableDiv.addEventListener('paste', callback);
                                }
                            }
                        }
                    },
                    plugins: {
                        get: function(pluginName) {
                            // Mock pour la compatibilité
                            return {
                                on: function() {}
                            };
                        }
                    }
                };

                // Synchronisation avec le textarea
                editableDiv.addEventListener('input', () => {
                    editor.updateTextarea();
                });

                editableDiv.addEventListener('keyup', () => {
                    editor.updateTextarea();
                });

                editableDiv.addEventListener('paste', () => {
                    setTimeout(() => editor.updateTextarea(), 100);
                });

                // Focus automatique
                setTimeout(() => {
                    editableDiv.focus();
                    // Placer le curseur à la fin
                    const range = document.createRange();
                    const selection = window.getSelection();
                    range.selectNodeContents(editableDiv);
                    range.collapse(false);
                    selection.removeAllRanges();
                    selection.addRange(range);
                }, 100);

                resolve(editor);

            } catch (error) {
                reject(error);
            }
        });
    }
};

console.log('✅ SimpleEditor chargé - Alternative locale à CKEditor');