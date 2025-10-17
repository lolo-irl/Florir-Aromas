document.addEventListener('DOMContentLoaded', function() {
    // Timer PIX
    let timeLeft = 30 * 60; // 30 minutos em segundos
    const timerElement = document.getElementById('timer');
    
    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft > 0) {
            timeLeft--;
            setTimeout(updateTimer, 1000);
        } else {
            timerElement.textContent = 'Expirado!';
            timerElement.style.color = 'var(--error)';
        }
    }
    
    updateTimer();

    // Trocar método de pagamento
    const metodosPagamento = document.querySelectorAll('.metodo-pagamento');
    const radioInputs = document.querySelectorAll('input[name="metodo-pagamento"]');
    
    radioInputs.forEach((radio, index) => {
        radio.addEventListener('change', function() {
            // Remove active de todos
            metodosPagamento.forEach(metodo => {
                metodo.classList.remove('active');
            });
            
            // Adiciona active no selecionado
            if (this.checked) {
                metodosPagamento[index].classList.add('active');
            }
        });
    });

    // Copiar chave PIX
    const btnCopiar = document.querySelector('.btn-copiar');
    if (btnCopiar) {
        btnCopiar.addEventListener('click', function() {
            const chave = this.getAttribute('data-chave');
            
            navigator.clipboard.writeText(chave).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '✓ Copiado!';
                this.classList.add('copied');
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Erro ao copiar: ', err);
                alert('Não foi possível copiar a chave. Tente novamente.');
            });
        });
    }

    // Formatação do cartão
    const inputCartao = document.querySelector('input[placeholder="0000 0000 0000 0000"]');
    if (inputCartao) {
        inputCartao.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = value.substring(0, 19);
        });
    }

    const inputValidade = document.querySelector('input[placeholder="MM/AA"]');
    if (inputValidade) {
        inputValidade.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value.substring(0, 5);
        });
    }

    const inputCVV = document.querySelector('input[placeholder="000"]');
    if (inputCVV) {
        inputCVV.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 3);
        });
    }

    // Confirmar pagamento
    const btnConfirmar = document.getElementById('btn-confirmar-pagamento');
    if (btnConfirmar) {
        btnConfirmar.addEventListener('click', function() {
            const metodoSelecionado = document.querySelector('input[name="metodo-pagamento"]:checked').id;
            
            let mensagem = '';
            switch(metodoSelecionado) {
                case 'pix':
                    mensagem = 'Pagamento via PIX confirmado! Verifique seu e-mail para mais detalhes.';
                    break;
                case 'entrega':
                    mensagem = 'Pedido confirmado! Você pagará na entrega.';
                    break;
                case 'cartao':
                    mensagem = 'Pagamento via cartão processado com sucesso!';
                    break;
            }
            
            // Simular processamento
            this.innerHTML = 'Processando...';
            this.disabled = true;
            
            setTimeout(() => {
                alert(mensagem);
                window.location.href = 'catalogo.php';
            }, 2000);
        });
    }

    // Animações de entrada
    const elements = document.querySelectorAll('.pagamento-content > *');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = `all 0.6s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
    });
});