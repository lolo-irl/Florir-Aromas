document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades do carrinho
    initCarrinho();
    initAnimations();
});

function initCarrinho() {
    // Botões de quantidade
    const botoesDiminuir = document.querySelectorAll('.btn-quantidade.diminuir');
    const botoesAumentar = document.querySelectorAll('.btn-quantidade.aumentar');
    const botoesRemover = document.querySelectorAll('.btn-remover');

    // Diminuir quantidade
    botoesDiminuir.forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            const itemElement = this.closest('.carrinho-item');
            const quantidadeElement = this.nextElementSibling;
            let quantidade = parseInt(quantidadeElement.textContent);
            
            if (quantidade > 1) {
                // Atualizar visualmente
                quantidadeElement.textContent = quantidade - 1;
                atualizarSubtotal(itemElement);
                atualizarResumo();
                
                // Fazer requisição
                fetch(`?diminuir=${itemId}`, { method: 'GET' })
                    .then(() => {
                        mostrarFeedback('Quantidade atualizada!', 'success');
                    })
                    .catch(err => {
                        console.error('Erro:', err);
                        mostrarFeedback('Erro ao atualizar quantidade', 'error');
                        // Reverter visualmente em caso de erro
                        quantidadeElement.textContent = quantidade;
                        atualizarSubtotal(itemElement);
                        atualizarResumo();
                    });
            } else {
                // Se quantidade for 1, remover item
                removerItem(itemId, itemElement);
            }
        });
    });

    // Aumentar quantidade
    botoesAumentar.forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            const itemElement = this.closest('.carrinho-item');
            const quantidadeElement = this.previousElementSibling;
            const quantidade = parseInt(quantidadeElement.textContent);
            
            // Atualizar visualmente
            quantidadeElement.textContent = quantidade + 1;
            atualizarSubtotal(itemElement);
            atualizarResumo();
            
            // Fazer requisição
            fetch(`?aumentar=${itemId}`, { method: 'GET' })
                .then(() => {
                    mostrarFeedback('Quantidade atualizada!', 'success');
                })
                .catch(err => {
                    console.error('Erro:', err);
                    mostrarFeedback('Erro ao atualizar quantidade', 'error');
                    // Reverter visualmente em caso de erro
                    quantidadeElement.textContent = quantidade;
                    atualizarSubtotal(itemElement);
                    atualizarResumo();
                });
        });
    });

    // Remover item
    botoesRemover.forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            const itemElement = this.closest('.carrinho-item');
            removerItem(itemId, itemElement);
        });
    });
}

function removerItem(itemId, itemElement) {
    // Animação de remoção
    itemElement.classList.add('removing');
    
    setTimeout(() => {
        // Fazer requisição para remover
        fetch(`?remover_todos=${itemId}`, { method: 'GET' })
            .then(() => {
                // Recarregar a página para atualizar o contador do header
                window.location.reload();
            })
            .catch(err => {
                console.error('Erro:', err);
                mostrarFeedback('Erro ao remover item', 'error');
                // Reverter animação em caso de erro
                itemElement.classList.remove('removing');
            });
    }, 300);
}

function atualizarSubtotal(itemElement) {
    const precoUnitario = parseFloat(
        itemElement.querySelector('.item-preco').textContent
            .replace('R$ ', '')
            .replace('.', '')
            .replace(',', '.')
    );
    
    const quantidade = parseInt(itemElement.querySelector('.quantidade').textContent);
    const subtotal = precoUnitario * quantidade;
    
    const subtotalElement = itemElement.querySelector('.item-subtotal');
    subtotalElement.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
}

function atualizarResumo() {
    let subtotal = 0;
    const itens = document.querySelectorAll('.carrinho-item');
    
    itens.forEach(item => {
        const itemSubtotal = parseFloat(
            item.querySelector('.item-subtotal').textContent
                .replace('R$ ', '')
                .replace('.', '')
                .replace(',', '.')
        );
        subtotal += itemSubtotal;
    });
    
    const totalElement = document.querySelector('.valor-total');
    if (totalElement) {
        totalElement.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
    }
    
    // Atualizar subtotal no resumo
    const subtotalElement = document.querySelector('.resumo-detalhes .resumo-linha:first-child span:last-child');
    if (subtotalElement) {
        subtotalElement.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
    }
}

function mostrarFeedback(mensagem, tipo) {
    // Criar elemento de feedback
    const feedback = document.createElement('div');
    feedback.className = `feedback ${tipo}`;
    feedback.textContent = mensagem;
    feedback.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${tipo === 'success' ? '#4CAF50' : '#f44336'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(feedback);
    
    // Remover após 3 segundos
    setTimeout(() => {
        feedback.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (feedback.parentNode) {
                feedback.parentNode.removeChild(feedback);
            }
        }, 300);
    }, 3000);
}

function initAnimations() {
    // Animar entrada dos itens
    const itens = document.querySelectorAll('.carrinho-item');
    itens.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = `all 0.5s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
    });
    
    // Adicionar estilos CSS para animações
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .feedback {
            font-weight: 500;
        }
    `;
    document.head.appendChild(style);
}

// Prevenir envio duplo do formulário
const formFinalizar = document.querySelector('.form-finalizar');
if (formFinalizar) {
    formFinalizar.addEventListener('submit', function(e) {
        const btn = this.querySelector('.btn-finalizar-compra');
        btn.disabled = true;
        btn.innerHTML = '🔄 Processando...';
        
        // Permitir recarregar se demorar muito
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '🛒 Finalizar Compra';
        }, 5000);
    });
}   