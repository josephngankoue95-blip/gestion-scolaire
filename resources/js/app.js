import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * =====================================================
 * APP.JS — Gestion Scolaire
 * Toutes les fonctions d'initialisation de l'application,
 * regroupées et exécutées au chargement du DOM.
 * =====================================================
 */

/* ---------------------------------------------------
   1. SIDEBAR (back-office) — toggle mobile
--------------------------------------------------- */
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggle = document.getElementById('sidebar-toggle');
    if (!sidebar || !toggle) return;

    const open = () => { sidebar.classList.add('is-open'); overlay?.classList.add('is-visible'); };
    const close = () => { sidebar.classList.remove('is-open'); overlay?.classList.remove('is-visible'); };

    toggle.addEventListener('click', () => {
        sidebar.classList.contains('is-open') ? close() : open();
    });
    overlay?.addEventListener('click', close);
}

/* ---------------------------------------------------
   2. MENU MOBILE (site vitrine)
--------------------------------------------------- */
function initMobileMenu() {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('menu-overlay');

    if (!toggle || !menu) return;

    toggle.addEventListener('click', () => {
        menu.classList.toggle('is-open');
        overlay?.classList.toggle('active');
    });

    overlay?.addEventListener('click', () => {
        menu.classList.remove('is-open');
        overlay.classList.remove('active');
    });
}

/* ---------------------------------------------------
   3. SAISIE DE NOTES — désactive le champ note si "absent" cochée
--------------------------------------------------- */
function initSaisieNotes() {
    document.querySelectorAll('.absent-checkbox').forEach((checkbox) => {
        const row = checkbox.closest('tr');
        const noteInput = row?.querySelector('.note-input');
        if (!noteInput) return;

        const sync = () => {
            noteInput.disabled = checkbox.checked;
            if (checkbox.checked) noteInput.value = '';
        };

        checkbox.addEventListener('change', sync);
        sync(); // état initial
    });
}

/* ---------------------------------------------------
   4. APPEL / ABSENCES — active type + motif si "présent" décoché
--------------------------------------------------- */
function initAppel() {
    document.querySelectorAll('.present-checkbox').forEach((checkbox) => {
        const row = checkbox.closest('tr');
        const typeSelect = row.querySelector('.type-select');
        const motifInput = row.querySelector('.motif-input');

        checkbox.addEventListener('change', () => {
            const absent = !checkbox.checked;
            typeSelect.disabled = !absent;
            motifInput.disabled = !absent;
        });
    });
}

document.addEventListener('DOMContentLoaded', initAppel);

    // Bouton "Tout marquer présent / absent" (si présent dans la vue)
    document.getElementById('marquer-tous-presents')?.addEventListener('click', () => {
        document.querySelectorAll('.present-checkbox').forEach((cb) => {
            cb.checked = true;
            cb.dispatchEvent(new Event('change'));
        });
    });

    document.getElementById('marquer-tous-absents')?.addEventListener('click', () => {
        document.querySelectorAll('.present-checkbox').forEach((cb) => {
            cb.checked = false;
            cb.dispatchEvent(new Event('change'));
        });
    });

/* ---------------------------------------------------
   5. CONFIRMATION DE SUPPRESSION GENERIQUE
   (remplace les onsubmit="return confirm(...)" inline si besoin)
--------------------------------------------------- */
function initConfirmDelete() {
    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            const message = form.getAttribute('data-confirm') || 'Êtes-vous sûr ?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

/* ---------------------------------------------------
   6. FERMETURE AUTOMATIQUE DES ALERTES FLASH
--------------------------------------------------- */
function initAutoDismissAlerts() {
    document.querySelectorAll('.alert-success, .alert-error, .alert-warning, .alert-info').forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
}

/* ---------------------------------------------------
   7. APERCU PHOTO AVANT UPLOAD (élève, enseignant, candidature)
--------------------------------------------------- */
function initPhotoPreview() {
    document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
        const previewId = input.getAttribute('data-preview');
        const previewEl = document.getElementById(previewId);
        if (!previewEl) return;

        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (event) => {
                previewEl.src = event.target.result;
                previewEl.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    });
}

/* ---------------------------------------------------
   8. RECHERCHE DYNAMIQUE AVEC DEBOUNCE (filtres listes)
   Utilisation : <input data-search-debounce>
--------------------------------------------------- */
function initSearchDebounce() {
    document.querySelectorAll('[data-search-debounce]').forEach((input) => {
        let timer = null;
        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                input.closest('form')?.submit();
            }, 600);
        });
    });
}

/* ---------------------------------------------------
   9. CALCUL EN DIRECT DE LA NOTE SUR 20 (saisie de notes)
   Affiche un aperçu "x/20" à côté du champ pendant la saisie
--------------------------------------------------- */
function initNotePreview() {
    document.querySelectorAll('.note-input[data-bareme]').forEach((input) => {
        const bareme = parseFloat(input.getAttribute('data-bareme')) || 20;
        const previewEl = input.parentElement.querySelector('.note-preview');
        if (!previewEl) return;

        const update = () => {
            const valeur = parseFloat(input.value);
            if (isNaN(valeur)) {
                previewEl.textContent = '';
                return;
            }
            const sur20 = ((valeur / bareme) * 20).toFixed(2);
            previewEl.textContent = `≈ ${sur20}/20`;
        };

        input.addEventListener('input', update);
        update();
    });
}

/* ---------------------------------------------------
   10. SELECT DEPENDANT (ex: Section -> Classes filtrées)
   Utilisation : <select data-filter-target="#classe_id" data-filter-url="/api/classes">
--------------------------------------------------- */
function initDependentSelects() {
    document.querySelectorAll('[data-filter-target]').forEach((source) => {
        const targetSelector = source.getAttribute('data-filter-target');
        const target = document.querySelector(targetSelector);
        if (!target) return;

        source.addEventListener('change', async () => {
            const url = source.getAttribute('data-filter-url');
            if (!url) return;

            target.innerHTML = '<option>Chargement...</option>';
            try {
                const response = await fetch(`${url}?parent_id=${source.value}`);
                const data = await response.json();
                target.innerHTML = '<option value="">-- Choisir --</option>';
                data.forEach((item) => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.nom;
                    target.appendChild(option);
                });
            } catch (error) {
                console.error('Erreur de chargement des options:', error);
                target.innerHTML = '<option value="">Erreur de chargement</option>';
            }
        });
    });
}

/* ---------------------------------------------------
   11. ICONES LUCIDE — re-render après changement dynamique du DOM
--------------------------------------------------- */
function refreshIcons() {
    if (window.lucide) {
        window.lucide.createIcons();
    }
}

/* ---------------------------------------------------
   12. TOGGLE MOT DE PASSE (afficher/masquer sur le login)
--------------------------------------------------- */
function initPasswordToggle() {
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        const targetSelector = button.getAttribute('data-toggle-password');
        const input = document.querySelector(targetSelector);
        if (!input) return;

        button.addEventListener('click', () => {
            input.type = input.type === 'password' ? 'text' : 'password';
            button.classList.toggle('text-primary-600');
        });
    });
}

/* ---------------------------------------------------
   13. SCROLL FLUIDE VERS LES ANCRES (site vitrine)
--------------------------------------------------- */
function initSmoothAnchors() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (e) => {
            const targetId = anchor.getAttribute('href');
            if (targetId.length <= 1) return;
            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

function filtrerNiveaux() {
    const sectionId  = document.getElementById('select_section_classe').value;
    const selectNiv  = document.getElementById('select_niveau_classe');
    const options    = selectNiv.querySelectorAll('option');

    selectNiv.innerHTML = '<option value="">-- Choisir un niveau --</option>';
    options.forEach(opt => {
        if (opt.dataset.section === sectionId || !opt.dataset.section) {
            selectNiv.appendChild(opt.cloneNode(true));
        }
    });
}

document.getElementById('select_section_classe').addEventListener('change', filtrerNiveaux);
filtrerNiveaux(); // état initial

/* ---------------------------------------------------
   INITIALISATION GLOBALE
--------------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initMobileMenu();
    initSaisieNotes();
    initAppel();
    initConfirmDelete();
    initAutoDismissAlerts();
    initPhotoPreview();
    initSearchDebounce();
    initNotePreview();
    initDependentSelects();
    initPasswordToggle();
    initSmoothAnchors();
    refreshIcons();
});



/* ---------------------------------------------------
   EXPORT (si besoin d'appeler refreshIcons() après un fetch/ajax)
--------------------------------------------------- */
window.AppLycee = {
    refreshIcons,
    initSaisieNotes,
    initAppel,
    initPhotoPreview,
    initNotePreview,
};