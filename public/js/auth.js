/* Authentication JavaScript */

document.addEventListener('DOMContentLoaded', function() {
    initAuthForms();
    initPasswordToggle();
    initPasswordStrength();
});

/**
 * Initialize authentication forms
 */
function initAuthForms() {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');

    if (registerForm) {
        registerForm.addEventListener('submit', handleRegisterSubmit);
    }

    if (loginForm) {
        loginForm.addEventListener('submit', handleLoginSubmit);
    }
}

/**
 * Handle registration form submission
 */
function handleRegisterSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const btn = document.getElementById('registerBtn');
    const formData = new FormData(form);

    // Clear previous errors
    clearFormErrors(form);

    // Validate form
    if (!validateRegisterForm(form)) {
        return;
    }

    // Disable button and show loading
    btn.disabled = true;
    btn.classList.add('btn-loading');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span style="opacity: 0;">Processing...</span>';

    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Registration successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/';
            }, 1500);
        } else {
            if (data.errors) {
                displayFormErrors(form, data.errors);
            } else {
                showAlert(data.message || 'Registration failed. Please try again.', 'danger');
            }
            btn.disabled = false;
            btn.classList.remove('btn-loading');
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred. Please try again.', 'danger');
        btn.disabled = false;
        btn.classList.remove('btn-loading');
        btn.innerHTML = originalText;
    });
}

/**
 * Handle login form submission
 */
function handleLoginSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const btn = document.getElementById('loginBtn');
    const formData = new FormData(form);

    // Clear previous errors
    clearFormErrors(form);

    // Validate form
    if (!validateLoginForm(form)) {
        return;
    }

    // Disable button and show loading
    btn.disabled = true;
    btn.classList.add('btn-loading');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span style="opacity: 0;">Logging in...</span>';

    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Login successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/';
            }, 1500);
        } else {
            if (data.errors) {
                displayFormErrors(form, data.errors);
            } else {
                showAlert(data.message || 'Invalid credentials. Please try again.', 'danger');
            }
            btn.disabled = false;
            btn.classList.remove('btn-loading');
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred. Please try again.', 'danger');
        btn.disabled = false;
        btn.classList.remove('btn-loading');
        btn.innerHTML = originalText;
    });
}

/**
 * Validate registration form
 */
function validateRegisterForm(form) {
    let isValid = true;

    const email = form.querySelector('#email');
    const password = form.querySelector('#password');
    const passwordConfirmation = form.querySelector('#password_confirmation');

    // Validate email
    if (!email.value) {
        showFieldError(email, 'Email is required.');
        isValid = false;
    } else if (!isValidEmail(email.value)) {
        showFieldError(email, 'Please enter a valid email address.');
        isValid = false;
    }

    // Validate password
    if (!password.value) {
        showFieldError(password, 'Password is required.');
        isValid = false;
    } else if (password.value.length < 8) {
        showFieldError(password, 'Password must be at least 8 characters.');
        isValid = false;
    }

    // Validate password confirmation
    if (!passwordConfirmation.value) {
        showFieldError(passwordConfirmation, 'Please confirm your password.');
        isValid = false;
    } else if (password.value !== passwordConfirmation.value) {
        showFieldError(passwordConfirmation, 'Passwords do not match.');
        isValid = false;
    }

    return isValid;
}

/**
 * Validate login form
 */
function validateLoginForm(form) {
    let isValid = true;

    const email = form.querySelector('#email');
    const password = form.querySelector('#password');

    // Validate email
    if (!email.value) {
        showFieldError(email, 'Email is required.');
        isValid = false;
    } else if (!isValidEmail(email.value)) {
        showFieldError(email, 'Please enter a valid email address.');
        isValid = false;
    }

    // Validate password
    if (!password.value) {
        showFieldError(password, 'Password is required.');
        isValid = false;
    }

    return isValid;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    const feedback = field.parentElement.querySelector('.invalid-feedback') ||
                     field.closest('.mb-3').querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = message;
        feedback.classList.add('d-block');
    }
}

/**
 * Clear form errors
 */
function clearFormErrors(form) {
    const inputs = form.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.classList.remove('is-invalid', 'is-valid');
    });

    const feedbacks = form.querySelectorAll('.invalid-feedback');
    feedbacks.forEach(feedback => {
        feedback.textContent = '';
        feedback.classList.remove('d-block');
    });
}

/**
 * Display form errors from server
 */
function displayFormErrors(form, errors) {
    Object.keys(errors).forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field) {
            showFieldError(field, errors[fieldName][0]);
        }
    });
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Show alert message
 */
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} position-fixed`;
    alertDiv.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        animation: slideInRight 0.3s ease;
    `;
    alertDiv.textContent = message;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.style.transition = 'opacity 0.3s ease';
        alertDiv.style.opacity = '0';
        setTimeout(() => alertDiv.remove(), 300);
    }, 3000);
}

/**
 * Initialize password toggle
 */
function initPasswordToggle() {
    const toggleButtons = document.querySelectorAll('.password-toggle');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
}

/**
 * Initialize password strength indicator
 */
function initPasswordStrength() {
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('passwordStrength');

    if (passwordInput && strengthIndicator) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);

            strengthIndicator.classList.remove('weak', 'medium', 'strong');

            if (password.length > 0) {
                strengthIndicator.classList.add('show');

                if (strength < 40) {
                    strengthIndicator.classList.add('weak');
                } else if (strength < 70) {
                    strengthIndicator.classList.add('medium');
                } else {
                    strengthIndicator.classList.add('strong');
                }
            } else {
                strengthIndicator.classList.remove('show');
            }
        });
    }
}

/**
 * Calculate password strength
 */
function calculatePasswordStrength(password) {
    let strength = 0;

    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 15;
    if (/[a-z]/.test(password)) strength += 15;
    if (/[A-Z]/.test(password)) strength += 15;
    if (/[0-9]/.test(password)) strength += 15;
    if (/[^a-zA-Z0-9]/.test(password)) strength += 15;

    return strength;
}

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
