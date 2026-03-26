/**
 * GameVault UI Components
 * Notifications, Modals, Theme Toggle
 */

class Toast {
    static show(message, type = 'success', duration = 3000) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.innerHTML = `
            <div class="toast__content">
                <span class="toast__icon">${type === 'success' ? '✅' : '❌'}</span>
                <span class="toast__message">${message}</span>
            </div>
        `;

        container.appendChild(toast);

        // Animation entrée
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });

        // Suppression automatique
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, duration);
    }
}

class Modal {
    static confirm(title, message, onConfirm) {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';

        const modal = document.createElement('div');
        modal.className = 'modal-card';
        modal.innerHTML = `
            <h2 class="modal-title">${title}</h2>
            <p class="modal-text">${message}</p>
            <div class="modal-actions">
                <button class="btn btn--outline modal-cancel">Annuler</button>
                <button class="btn btn--danger modal-confirm">Confirmer</button>
            </div>
        `;

        overlay.appendChild(modal);
        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';

        const close = () => {
            overlay.remove();
            document.body.style.overflow = '';
        };

        modal.querySelector('.modal-cancel').addEventListener('click', close);
        modal.querySelector('.modal-confirm').addEventListener('click', () => {
            onConfirm();
            close();
        });
    }
}

// Theme Toggle Logic
const initTheme = () => {
    const themeBtn = document.getElementById('theme-toggle');
    const html = document.documentElement;

    // Default to dark if not set
    let savedTheme = localStorage.getItem('theme');
    if (!savedTheme) {
        savedTheme = 'dark';
        localStorage.setItem('theme', 'dark');
    }

    html.className = savedTheme;

    themeBtn?.addEventListener('click', () => {
        const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
        html.className = newTheme;
        localStorage.setItem('theme', newTheme);

        Toast.show(`Mode ${newTheme === 'dark' ? 'sombre' : 'clair'} activé !`, 'success', 1500);
    });
};

document.addEventListener('DOMContentLoaded', initTheme);

// Exposer globalement
window.Toast = Toast;
window.Modal = Modal;
