document.addEventListener('DOMContentLoaded', function() {
    // Elementos
    const formCadastro = document.getElementById('formCadastro');
    const btnCadastrar = document.getElementById('btnCadastrar');
    const toggleButtons = document.querySelectorAll('.toggle-password');
    const senhaInput = document.getElementById('senha');
    const confirmarSenhaInput = document.getElementById('confirmar_senha');

    // Mostrar/ocultar senha
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const input = document.getElementById(target);
            
            if (input) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁️' : '🔒';
            }
        });
    });

    // Remover restrições de senha
    if (senhaInput) {
        senhaInput.removeAttribute('minlength');
        senhaInput.removeAttribute('maxlength');
    }
    
    if (confirmarSenhaInput) {
        confirmarSenhaInput.removeAttribute('minlength');
        confirmarSenhaInput.removeAttribute('maxlength');
    }

    // Validação em tempo real
    if (senhaInput && confirmarSenhaInput) {
        confirmarSenhaInput.addEventListener('input', function() {
            validatePasswordMatch();
        });
        
        senhaInput.addEventListener('input', function() {
            validatePasswordMatch();
        });
    }

    // Submit do formulário
    if (formCadastro) {
        formCadastro.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            } else {
                showLoading();
            }
        });
    }

    // Funções de validação
    function validatePasswordMatch() {
        if (senhaInput.value && confirmarSenhaInput.value) {
            const match = senhaInput.value === confirmarSenhaInput.value;
            
            if (confirmarSenhaInput.value !== '') {
                confirmarSenhaInput.style.borderColor = match ? 'var(--success)' : 'var(--error)';
            }
            
            return match;
        }
        return true;
    }

    function validateForm() {
        // Verificar se os campos obrigatórios estão preenchidos
        const requiredInputs = formCadastro.querySelectorAll('input[required]');
        let isValid = true;

        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                showMessage('Por favor, preencha todos os campos obrigatórios', 'error');
                input.focus();
                isValid = false;
                return;
            }
        });

        if (!isValid) return false;

        // Verificar termos
        const termos = formCadastro.querySelector('input[name="termos"]');
        if (!termos.checked) {
            showMessage('Você precisa aceitar os Termos de Uso para continuar', 'error');
            termos.focus();
            return false;
        }

        // Verificar senhas
        if (!validatePasswordMatch()) {
            showMessage('As senhas não coincidem', 'error');
            confirmarSenhaInput.focus();
            return false;
        }

        return true;
    }

    function showLoading() {
        btnCadastrar.classList.add('loading');
        btnCadastrar.disabled = true;
    }

    function showMessage(message, type) {
        // Remover mensagens existentes
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        // Criar nova mensagem
        const alert = document.createElement('div');
        alert.className = `alert ${type}`;
        alert.innerHTML = `
            <span class="alert-icon">${type === 'error' ? '⚠️' : 'ℹ️'}</span>
            ${message}
        `;

        // Estilos
        const styles = {
            error: { 
                background: 'rgba(244, 67, 54, 0.1)', 
                color: 'var(--error)', 
                border: '1px solid rgba(244, 67, 54, 0.2)' 
            },
            info: { 
                background: 'rgba(33, 150, 243, 0.1)', 
                color: '#2196F3', 
                border: '1px solid rgba(33, 150, 243, 0.2)' 
            }
        };

        Object.assign(alert.style, {
            padding: '1rem',
            borderRadius: '12px',
            marginBottom: '1.5rem',
            display: 'flex',
            alignItems: 'center',
            gap: '0.5rem',
            animation: 'shake 0.5s ease',
            ...styles[type]
        });

        // Inserir no DOM
        const formHeader = document.querySelector('.form-header');
        formHeader.parentNode.insertBefore(alert, formHeader.nextSibling);

        // Remover após 5 segundos
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    }

    // Animações de entrada
    const formElements = document.querySelectorAll('.cadastro-form > *');
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = `all 0.6s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
    });

    // Adicionar CSS para animações
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeOut {
            to { opacity: 0; transform: translateY(-10px); }
        }
    `;
    document.head.appendChild(style);
});

// Prevenir reenvio do formulário
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}