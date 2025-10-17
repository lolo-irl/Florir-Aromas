// script_login_simple.js - Versão mínima sem validação de tamanho
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar senha
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('senha');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🔒';
        });
    }

    // Remover quaisquer restrições de senha
    if (passwordInput) {
        passwordInput.removeAttribute('minlength');
        passwordInput.removeAttribute('maxlength');
        passwordInput.removeAttribute('pattern');
    }

    // Loading no submit
    const loginForm = document.querySelector('.login-form-content');
    const btnLogin = document.querySelector('.btn-login');
    
    if (loginForm && btnLogin) {
        loginForm.addEventListener('submit', function() {
            btnLogin.classList.add('loading');
            btnLogin.disabled = true;
        });
    }
});