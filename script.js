/*
 * ARCHÉO-IT - Script principal 
 */
window.scriptLoaded = true;

document.addEventListener('DOMContentLoaded', function() {
    // =============================================
    // 1. GESTION DU MENU RESPONSIVE
    // =============================================
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');
            this.setAttribute('aria-expanded', mainNav.classList.contains('active'));
        });
    }

    // =============================================
    // 2. AMÉLIORATION DES FORMULAIRES
    // =============================================
    document.querySelectorAll('form').forEach(form => {
        // Validation basique
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = this.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('error');
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });

        // Reset des erreurs lors de la saisie
        form.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    this.classList.remove('error');
                }
            });
        });
    });

    // =============================================
    // 3. GALLERIE D'IMAGES AUTOMATIQUE
    // (Pour chantiers.php et actualités)
    // =============================================
    function initImageGallery() {
        document.querySelectorAll('.gallery img, .chantier img, .actualite img').forEach(img => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                const overlay = document.createElement('div');
                overlay.className = 'image-overlay';
                overlay.innerHTML = `
                    <div class="overlay-content">
                        <img src="${this.src}" alt="${this.alt}">
                        <button class="close-overlay" aria-label="Fermer">&times;</button>
                    </div>
                `;
                document.body.appendChild(overlay);
                
                // Fermeture
                overlay.querySelector('.close-overlay').addEventListener('click', () => {
                    document.body.removeChild(overlay);
                });
            });
        });
    }
    initImageGallery();

    // =============================================
    // 4. SYSTÈME DE MESSAGES FLASH
    // =============================================
    const flashMessage = document.querySelector('.flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.opacity = '0';
            setTimeout(() => flashMessage.remove(), 500);
        }, 5000);
        
        flashMessage.querySelector('.close-flash').addEventListener('click', () => {
            flashMessage.remove();
        });
    }

    // =============================================
    // 5. ACCESSIBILITÉ
    // =============================================
    // Contraste élevé
    document.getElementById('high-contrast')?.addEventListener('click', function() {
        document.body.classList.toggle('high-contrast');
        localStorage.setItem('highContrast', document.body.classList.contains('high-contrast'));
    });

    // Restaurer les préférences
    if (localStorage.getItem('highContrast') === 'true') {
        document.body.classList.add('high-contrast');
    }

    // =============================================
    // 6. ANIMATIONS DE DÉFILEMENT
    // =============================================
    function animateOnScroll() {
        document.querySelectorAll('.animate').forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight * 0.75) {
                el.classList.add('animated');
            }
        });
    }
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll();

    // =============================================
    // 7. BOUTON "RETOUR EN HAUT"
    // =============================================
    const backToTop = document.createElement('button');
    backToTop.className = 'back-to-top';
    backToTop.innerHTML = '↑';
    backToTop.setAttribute('aria-label', 'Retour en haut');
    document.body.appendChild(backToTop);

    window.addEventListener('scroll', () => {
        backToTop.style.display = (window.pageYOffset > 300) ? 'block' : 'none';
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // =============================================
    // 8. DÉTECTION DE LA PAGE ACTIVE
    // =============================================
    function highlightCurrentPage() {
        const currentPage = location.pathname.split('/').pop() || 'index.php';
        document.querySelectorAll(nav a[href="${currentPage}"]).forEach(link => {
            link.classList.add('current-page');
        });
    }
    highlightCurrentPage();
});

// =============================================
// FONCTIONS UTILITAIRES GLOBALES
// =============================================

/**
 * Formate une date au format français
 */
function formatFrenchDate(dateString) {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}

/**
 * Vérifie si un élément est visible à l'écran
 */
function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}