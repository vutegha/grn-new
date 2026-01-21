// Newsletter Enhanced Functionality

document.addEventListener('DOMContentLoaded', function() {
    
    // Animation des éléments au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);

    // Observer tous les éléments qui ont besoin d'animation
    const animatedElements = document.querySelectorAll('.newsletter-card, .preference-card, .stats-number');
    animatedElements.forEach(el => observer.observe(el));

    // Validation du formulaire en temps réel
    const emailInput = document.getElementById('email');
    const form = document.querySelector('form[action*="newsletter.subscribe"]');
    const submitBtn = form?.querySelector('button[type="submit"]');

    if (emailInput) {
        emailInput.addEventListener('input', function() {
            validateEmail(this);
        });

        emailInput.addEventListener('blur', function() {
            validateEmail(this);
        });
    }

    function validateEmail(input) {
        const email = input.value.trim();
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        
        if (email && !isValid) {
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-300', 'border-iri-primary');
            showEmailError('Veuillez entrer une adresse email valide');
        } else if (email && isValid) {
            input.classList.add('border-green-500');
            input.classList.remove('border-red-500', 'border-gray-300');
            hideEmailError();
        } else {
            input.classList.add('border-gray-300');
            input.classList.remove('border-red-500', 'border-green-500');
            hideEmailError();
        }
    }

    function showEmailError(message) {
        let errorDiv = document.getElementById('email-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'email-error';
            errorDiv.className = 'text-red-500 text-xs mt-1 flex items-center';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i><span></span>`;
            emailInput.parentNode.appendChild(errorDiv);
        }
        errorDiv.querySelector('span').textContent = message;
        errorDiv.style.display = 'flex';
    }

    function hideEmailError() {
        const errorDiv = document.getElementById('email-error');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }

    // Animation des statistiques
    function animateNumbers() {
        const statsNumbers = document.querySelectorAll('.stats-number');
        
        statsNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent.replace(/,/g, ''));
            const duration = 2000; // 2 secondes
            const increment = finalValue / (duration / 16); // 60fps
            let currentValue = 0;
            
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(timer);
                }
                stat.textContent = Math.floor(currentValue).toLocaleString();
            }, 16);
        });
    }

    // Déclencher l'animation des statistiques quand la section devient visible
    const statsSection = document.querySelector('.bg-gradient-to-br.from-iri-primary');
    if (statsSection) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateNumbers();
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        statsObserver.observe(statsSection);
    }

    // Gestion des préférences avec feedback visuel
    const preferenceCheckboxes = document.querySelectorAll('input[name="preferences[]"]');
    preferenceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            const icon = label.querySelector('i');
            
            if (this.checked) {
                label.classList.add('bg-iri-primary/5', 'border-iri-primary');
                label.classList.remove('border-gray-200');
                if (icon) {
                    icon.classList.add('text-iri-primary');
                    icon.classList.remove('text-gray-400');
                }
                
                // Animation de confirmation
                this.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            } else {
                label.classList.remove('bg-iri-primary/5', 'border-iri-primary');
                label.classList.add('border-gray-200');
                if (icon) {
                    icon.classList.remove('text-iri-primary');
                    icon.classList.add('text-gray-400');
                }
            }
        });
    });

    // Feedback pour le consentement
    const consentCheckbox = document.querySelector('input[name="consent"]');
    if (consentCheckbox) {
        consentCheckbox.addEventListener('change', function() {
            if (submitBtn) {
                if (this.checked) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitBtn.classList.add('hover:shadow-lg', 'transform', 'hover:scale-105');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    submitBtn.classList.remove('hover:shadow-lg', 'transform', 'hover:scale-105');
                }
            }
        });
    }

    // Animation de soumission du formulaire
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return;
            }

            // Animation du bouton de soumission
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Inscription en cours...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');
        });
    }

    function validateForm() {
        let isValid = true;

        // Validation de l'email
        if (emailInput) {
            const email = emailInput.value.trim();
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                validateEmail(emailInput);
                isValid = false;
            }
        }

        // Validation du consentement
        if (consentCheckbox && !consentCheckbox.checked) {
            isValid = false;
            const consentLabel = consentCheckbox.closest('label');
            consentLabel.classList.add('bg-red-50', 'border-red-300');
            setTimeout(() => {
                consentLabel.classList.remove('bg-red-50', 'border-red-300');
            }, 2000);
        }

        return isValid;
    }

    // Tooltip pour les boutons d'aide
    const helpButtons = document.querySelectorAll('[data-tooltip]');
    helpButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.classList.add('tooltip');
        });
    });

    // Auto-resize pour les champs de texte si nécessaire
    const textInputs = document.querySelectorAll('input[type="text"], input[type="email"]');
    textInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.add('newsletter-input');
        });
    });

    // Smooth scroll pour les ancres internes
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Gestion du thème sombre
    function handleThemeChange() {
        const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const cards = document.querySelectorAll('.newsletter-card, .preference-card');
        
        cards.forEach(card => {
            if (isDark) {
                card.classList.add('dark-theme');
            } else {
                card.classList.remove('dark-theme');
            }
        });
    }

    // Écouter les changements de thème
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', handleThemeChange);
    handleThemeChange();

    // Message de bienvenue discret
    setTimeout(() => {
        if (!sessionStorage.getItem('newsletter-visited')) {
            console.log('🔔 Bienvenue sur notre page d\'abonnement à la newsletter !');
            sessionStorage.setItem('newsletter-visited', 'true');
        }
    }, 1000);

});

// Fonction utilitaire pour copier l'URL de la page
function copyPageUrl() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        // Feedback visuel de confirmation
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'URL copiée !';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 2000);
    });
}

// Fonction pour partager la page
function shareNewsletter() {
    if (navigator.share) {
        navigator.share({
            title: 'Newsletter GRN-UCBC',
            text: 'Rejoignez la newsletter du Centre de Gouvernance des Ressources Naturelles',
            url: window.location.href
        });
    } else {
        copyPageUrl();
    }
}
