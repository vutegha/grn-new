/**
 * Gestion du modal de téléchargement de rapports avec validation email
 */

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('downloadRapportModal');
    const form = document.getElementById('downloadRapportForm');
    const btnValidate = document.getElementById('btn-validate-download');
    const emailInput = document.getElementById('email-input');
    const rapportIdInput = document.getElementById('rapport-id');
    const actualiteIdInput = document.getElementById('actualite-id');
    const rapportTitleDisplay = document.getElementById('rapport-title-display');
    const errorMessage = document.getElementById('download-error-message');
    const successMessage = document.getElementById('download-success-message');
    const errorText = document.getElementById('error-text');
    const successText = document.getElementById('success-text');
    const btnDownloadText = document.getElementById('btn-download-text');
    const btnDownloadSpinner = document.getElementById('btn-download-spinner');

    // Au clic sur un bouton de téléchargement
    document.querySelectorAll('.btn-download-rapport').forEach(btn => {
        btn.addEventListener('click', function() {
            const rapportId = this.getAttribute('data-rapport-id');
            const rapportTitle = this.getAttribute('data-rapport-title');
            const actualiteId = this.getAttribute('data-actualite-id');

            // Remplir le modal avec les informations
            rapportIdInput.value = rapportId;
            actualiteIdInput.value = actualiteId;
            rapportTitleDisplay.textContent = rapportTitle;

            // Réinitialiser le formulaire
            form.reset();
            emailInput.classList.remove('is-invalid');
            errorMessage.classList.add('d-none');
            successMessage.classList.add('d-none');

            // Ouvrir le modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        });
    });

    // Validation et téléchargement
    btnValidate.addEventListener('click', function() {
        const email = emailInput.value.trim();
        const rapportId = rapportIdInput.value;
        const actualiteId = actualiteIdInput.value;

        // Validation basique
        if (!email) {
            showError('Veuillez saisir votre adresse email.');
            emailInput.classList.add('is-invalid');
            return;
        }

        if (!isValidEmail(email)) {
            showError('Veuillez saisir une adresse email valide.');
            emailInput.classList.add('is-invalid');
            return;
        }

        // Afficher le spinner
        btnDownloadText.textContent = 'Validation...';
        btnDownloadSpinner.classList.remove('d-none');
        btnValidate.disabled = true;
        emailInput.classList.remove('is-invalid');
        errorMessage.classList.add('d-none');

        // Envoyer la requête AJAX
        fetch(`/rapport/${rapportId}/validate-email`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                email: email,
                actualite_id: actualiteId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher le message de succès
                let message = 'Email validé avec succès ! Téléchargement en cours...';
                if (data.newsletter_subscribed) {
                    message += ' Vous avez été inscrit à notre liste de diffusion.';
                }
                showSuccess(message);

                // Déclencher le téléchargement
                setTimeout(() => {
                    window.location.href = data.download_url;
                    
                    // Fermer le modal après un délai
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(modal).hide();
                        resetForm();
                    }, 1500);
                }, 1000);
            } else {
                showError(data.message || 'Une erreur est survenue lors de la validation.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showError('Une erreur est survenue. Veuillez réessayer.');
        })
        .finally(() => {
            btnDownloadText.textContent = 'Télécharger';
            btnDownloadSpinner.classList.add('d-none');
            btnValidate.disabled = false;
        });
    });

    // Validation email au clavier (Enter)
    emailInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            btnValidate.click();
        }
    });

    // Fonctions utilitaires
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function showError(message) {
        errorText.textContent = message;
        errorMessage.classList.remove('d-none');
        successMessage.classList.add('d-none');
    }

    function showSuccess(message) {
        successText.textContent = message;
        successMessage.classList.remove('d-none');
        errorMessage.classList.add('d-none');
    }

    function resetForm() {
        form.reset();
        emailInput.classList.remove('is-invalid');
        errorMessage.classList.add('d-none');
        successMessage.classList.add('d-none');
        btnDownloadText.textContent = 'Télécharger';
        btnDownloadSpinner.classList.add('d-none');
    }
});
