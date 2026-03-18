/**
 * GameVault — main.js
 */

document.addEventListener('DOMContentLoaded', () => {

    /* ── Menu hamburger mobile ───────────────────────────── */
    const btn       = document.getElementById('hamburger-btn');
    const nav       = document.getElementById('mobile-nav');
    const iconMenu  = document.getElementById('icon-menu');
    const iconClose = document.getElementById('icon-close');

    if (btn && nav) {
        btn.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', String(isOpen));
            iconMenu.style.display  = isOpen ? 'none'  : '';
            iconClose.style.display = isOpen ? ''      : 'none';
        });

        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !nav.contains(e.target)) {
                nav.classList.remove('is-open');
                btn.setAttribute('aria-expanded', 'false');
                iconMenu.style.display  = '';
                iconClose.style.display = 'none';
            }
        });
    }

    /* ── Animations d'entrée (IntersectionObserver) ──────── */
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity   = '1';
                entry.target.style.transform = 'none';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -32px 0px' });

    document.querySelectorAll('.game-card, .session-card').forEach((el, i) => {
        el.style.opacity    = '0';
        el.style.transform  = 'translateY(16px)';
        el.style.transition = `opacity 350ms ease ${i * 60}ms, transform 350ms ease ${i * 60}ms`;
        observer.observe(el);
    });

});
