// Versão mais simples e robusta
document.addEventListener('DOMContentLoaded', function() {
    // Animação de entrada dos produtos
    const animateProducts = () => {
        const products = document.querySelectorAll('.produto-card');
        products.forEach((product, index) => {
            setTimeout(() => {
                product.classList.add('visible');
            }, index * 100);
        });
    };

    // Esperar um pouco antes de animar
    setTimeout(animateProducts, 300);

    // Efeito nos botões de compra
    const buyButtons = document.querySelectorAll('.btn-comprar, .btn-add-carrinho');
    buyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const originalText = this.innerHTML;
            this.innerHTML = '✓ Adicionado!';
            this.style.background = '#4CAF50';
            
            setTimeout(() => {
                window.location.href = this.getAttribute('href');
            }, 600);
        });
    });

    // Filtros simples
    const filterLinks = document.querySelectorAll('.submenu-link');
    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            filterLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Loading de imagens
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease';
    });
});

// Menu dropdown do usuário
function initUserMenu() {
    const userDropdown = document.querySelector('.user-dropdown');
    const dropdownContent = document.querySelector('.dropdown-content');
    
    if (userDropdown && dropdownContent) {
        // Fechar dropdown ao clicar fora
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target)) {
                dropdownContent.style.display = 'none';
            }
        });
        
        // Alternar dropdown ao clicar no botão
        const userWelcome = document.querySelector('.user-welcome');
        userWelcome.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = dropdownContent.style.display === 'block';
            dropdownContent.style.display = isVisible ? 'none' : 'block';
        });
        
        // Confirmar logout
        const logoutLink = document.querySelector('.dropdown-item.logout');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                if (!confirm('Tem certeza que deseja sair?')) {
                    e.preventDefault();
                }
            });
        }
    }
}

// Adicionar ao DOMContentLoaded existente
document.addEventListener('DOMContentLoaded', function() {
    // ... código existente ...
    
    initUserMenu();
});

// Ou se preferir uma versão mais simples:
document.addEventListener('DOMContentLoaded', function() {
    // Menu do usuário simples
    const userWelcome = document.querySelector('.user-welcome');
    const dropdownContent = document.querySelector('.dropdown-content');
    
    if (userWelcome && dropdownContent) {
        userWelcome.addEventListener('click', function() {
            const isVisible = dropdownContent.style.display === 'block';
            dropdownContent.style.display = isVisible ? 'none' : 'block';
        });
        
        // Fechar ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-dropdown')) {
                dropdownContent.style.display = 'none';
            }
        });
        
        // Confirmar logout
        const logoutLink = document.querySelector('.logout');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                if (!confirm('Tem certeza que deseja sair?')) {
                    e.preventDefault();
                }
            });
        }
    }
});