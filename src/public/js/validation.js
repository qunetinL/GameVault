document.addEventListener('DOMContentLoaded', () => {
    const signupForm = document.getElementById('signup-form');
    if (!signupForm) return;

    const emailInput = signupForm.querySelector('input[type="email"]');
    const passwordInput = signupForm.querySelector('input[type="password"]');
    const confirmInput = signupForm.querySelector('name="confirm_password"');

    // Pour cet exemple, on cherche par ID ou sélecteur générique si name n'est pas dispo
    const passwordConfirm = document.getElementById('register-password-confirm') || signupForm.querySelector('input[name="password_confirm"]');

    const showError = (input, message) => {
        let errorSpan = input.parentNode.querySelector('.error-msg');
        if (!errorSpan) {
            errorSpan = document.createElement('span');
            errorSpan.className = 'error-msg';
            input.parentNode.appendChild(errorSpan);
        }
        errorSpan.textContent = message;
        input.classList.add('input-error');
    };

    const clearError = (input) => {
        const errorSpan = input.parentNode.querySelector('.error-msg');
        if (errorSpan) errorSpan.remove();
        input.classList.remove('input-error');
    };

    // Validation Email
    emailInput?.addEventListener('blur', () => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            showError(emailInput, 'Veuillez entrer une adresse email valide.');
        } else {
            clearError(emailInput);
        }
    });

    // Force du mot de passe
    passwordInput?.addEventListener('input', () => {
        const val = passwordInput.value;
        let strength = 0;
        if (val.length >= 8) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;

        let strengthBar = document.getElementById('password-strength');
        if (!strengthBar) {
            strengthBar = document.createElement('div');
            strengthBar.id = 'password-strength';
            passwordInput.parentNode.appendChild(strengthBar);
        }

        const colors = ['#ef4444', '#f59e0b', '#10b981', '#059669'];
        strengthBar.style.height = '4px';
        strengthBar.style.marginTop = '8px';
        strengthBar.style.borderRadius = '2px';
        strengthBar.style.width = (strength * 25) + '%';
        strengthBar.style.backgroundColor = colors[strength - 1] || '#374151';
        strengthBar.style.transition = 'all 0.3s ease';
    });

    // Confirmation mot de passe
    passwordConfirm?.addEventListener('input', () => {
        if (passwordConfirm.value !== passwordInput.value) {
            showError(passwordConfirm, 'Les mots de passe ne correspondent pas.');
        } else {
            clearError(passwordConfirm);
        }
    });

    signupForm.addEventListener('submit', (e) => {
        const errors = signupForm.querySelectorAll('.input-error');
        if (errors.length > 0) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs avant d\'envoyer le formulaire.');
        }
    });
});
