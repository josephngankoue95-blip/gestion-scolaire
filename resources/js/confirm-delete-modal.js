// resources/js/confirm-delete-modal.js

export function initConfirmDeleteModals() {
    // Injecte la modale une seule fois dans le DOM
    if (!document.getElementById('global-delete-modal')) {
        const modalHTML = `
        <div id="global-delete-modal" class="gdm-overlay" style="display:none;">
            <div class="gdm-box">
                <div class="gdm-icon">
                    <i data-lucide="alert-triangle"></i>
                </div>
                <h3 class="gdm-title">Confirmer la suppression</h3>
                <p class="gdm-text" id="gdm-message">Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.</p>
                <div class="gdm-actions">
                    <button type="button" class="gdm-btn gdm-btn-cancel" id="gdm-cancel">Annuler</button>
                    <button type="button" class="gdm-btn gdm-btn-confirm" id="gdm-confirm">Supprimer</button>
                </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    const overlay   = document.getElementById('global-delete-modal');
    const messageEl = document.getElementById('gdm-message');
    const btnCancel = document.getElementById('gdm-cancel');
    const btnConfirm= document.getElementById('gdm-confirm');

    let formToSubmit = null;

    function openModal(form, customMessage) {
        formToSubmit = form;
        messageEl.textContent = customMessage || 'Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.';
        overlay.style.display = 'flex';
        requestAnimationFrame(() => overlay.classList.add('gdm-open'));
    }

    function closeModal() {
        overlay.classList.remove('gdm-open');
        setTimeout(() => { overlay.style.display = 'none'; formToSubmit = null; }, 200);
    }

    btnCancel.addEventListener('click', closeModal);
    overlay.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && overlay.classList.contains('gdm-open')) closeModal(); });

    btnConfirm.addEventListener('click', () => {
        if (formToSubmit) formToSubmit.submit();
    });

    // Intercepte tous les formulaires marqués data-confirm-delete
    document.querySelectorAll('form[data-confirm-delete]').forEach(form => {
        // Retire tout onsubmit natif conflictuel
        form.removeAttribute('onsubmit');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            openModal(form, form.dataset.confirmMessage);
        });
    });

    if (window.lucide) lucide.createIcons();
}

document.addEventListener('DOMContentLoaded', initConfirmDeleteModals);