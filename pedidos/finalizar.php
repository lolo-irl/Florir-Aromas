<?php
session_start();
if (!isset($_SESSION['id_cliente'])) {
    header('Location: ../sessao/login.php');
    exit();
}

$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

$id_cliente = $_SESSION['id_cliente'];
$nome_cliente = $_SESSION['nome_cliente'] ?? "Cliente";

// pega carrinho
$resCarrinho = mysqli_query($con, "SELECT * FROM carrinho WHERE id_cliente=$id_cliente");
$carrinho = mysqli_fetch_assoc($resCarrinho);

if (!$carrinho || $carrinho['valor_total'] <= 0) {
    die("Carrinho vazio ou inválido.");
}

$valor_total = $carrinho['valor_total'];

// cria pedido
mysqli_query($con, "INSERT INTO pedido (id_cliente, data_pedido, status_pedido, valor_total) 
    VALUES ($id_cliente, NOW(), 'Pendente', $valor_total)");
$id_pedido = mysqli_insert_id($con);

// cria pagamento
mysqli_query($con, "INSERT INTO pagamento (id_pedido, data_pagamento, valor, metodo_pagamento, status_pagamento) 
    VALUES ($id_pedido, NOW(), $valor_total, 'Aguardando', 'Pendente')");

// limpa carrinho (remove itens)
mysqli_query($con, "DELETE FROM item_carrinho WHERE id_carrinho=".$carrinho['id_carrinho']);

// Atualiza o valor total do carrinho para 0
mysqli_query($con, "UPDATE carrinho SET valor_total = 0 WHERE id_carrinho=".$carrinho['id_carrinho']);
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pagamento - Florir Aromas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../styles/style_pagamento.css">
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <h1>Florir Aromas</h1>
        <nav>
            <ul>
                <li><a href="../pages/catalogo.php">Catálogo</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li class="user-welcome">Olá, <?php echo htmlspecialchars($nome_cliente); ?></li>
            </ul>
        </nav>
    </header>

    <div class="pagamento-container">
        <div class="pagamento-content">
            <!-- Cabeçalho de Confirmação -->
            <div class="confirmacao-header">
                <div class="success-icon">✓</div>
                <h1>Pedido Confirmado!</h1>
                <p class="pedido-numero">Nº do Pedido: <strong>#<?php echo str_pad($id_pedido, 6, '0', STR_PAD_LEFT); ?></strong></p>
            </div>

            <!-- Resumo do Pedido -->
            <div class="resumo-pedido">
                <h2>Resumo do Pedido</h2>
                <div class="resumo-detalhes">
                    <div class="resumo-item">
                        <span>Valor Total:</span>
                        <strong>R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></strong>
                    </div>
                    <div class="resumo-item">
                        <span>Status:</span>
                        <span class="status-pendente">Pagamento Pendente</span>
                    </div>
                    <div class="resumo-item">
                        <span>Data:</span>
                        <span><?php echo date('d/m/Y H:i'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Opções de Pagamento -->
            <div class="opcoes-pagamento">
                <h2>Escolha como pagar</h2>
                
                <!-- PIX -->
                <div class="metodo-pagamento active" data-metodo="pix">
                    <div class="metodo-header">
                        <div class="metodo-radio">
                            <input type="radio" id="pix" name="metodo-pagamento" checked>
                            <label for="pix">PIX</label>
                        </div>
                        <div class="metodo-icon">🏷️</div>
                    </div>
                    <div class="metodo-content">
                        <p class="metodo-descricao">Pagamento instantâneo e seguro</p>
                        <div class="pix-detalhes">
                            <div class="pix-qrcode">
                                <img src="../imagens/qrcode_exemplo.png" alt="QR Code PIX" class="qrcode-img">
                                <p class="qrcode-text">Escaneie o QR Code acima</p>
                            </div>
                            <div class="pix-chave">
                                <p><strong>Chave PIX (CPF/CNPJ):</strong></p>
                                <div class="chave-container">
                                    <span id="chave-pix">12.345.678/0001-90</span>
                                    <button class="btn-copiar" data-chave="12.345.678/0001-90">
                                        📋 Copiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagamento na Entrega -->
                <div class="metodo-pagamento" data-metodo="entrega">
                    <div class="metodo-header">
                        <div class="metodo-radio">
                            <input type="radio" id="entrega" name="metodo-pagamento">
                            <label for="entrega">Pagamento na Entrega</label>
                        </div>
                        <div class="metodo-icon">🚚</div>
                    </div>
                    <div class="metodo-content">
                        <p class="metodo-descricao">Pague quando receber seu pedido</p>
                        <div class="entrega-detalhes">
                            <div class="opcao-entrega">
                                <h4>💵 Dinheiro</h4>
                                <p>Tenha o troco preparado</p>
                            </div>
                            <div class="opcao-entrega">
                                <h4>📱 PIX na Entrega</h4>
                                <p>Escaneie o QR code do entregador</p>
                            </div>
                            <div class="opcao-entrega">
                                <h4>💳 Cartão na Máquina</h4>
                                <p>Cartão de crédito ou débito</p>
                            </div>
                        </div>
                    </div>
                </div>

              

            <!-- Ações -->
            <div class="acoes-pagamento">
                <div class="timer-pix">
                    <div class="timer-icon">⏰</div>
                    <p>PIX copia e cola expira em: <strong id="timer">30:00</strong></p>
                </div>
                <div class="botoes-acao">
                    <a href="../pages/catalogo.php" class="btn-voltar">← Continuar Comprando</a>
                    <button class="btn-confirmar" id="btn-confirmar-pagamento">
                        Confirmar Pagamento
                    </button>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="info-adicional">
                <h3>📦 Informações da Entrega</h3>
                <p>Seu pedido será preparado assim que confirmarmos o pagamento.</p>
                <p>Dúvidas? <a href="../pages/contato.php">Fale conosco</a></p>
            </div>
        </div>
    </div>
      <script src="../scripts/script_pagamento.js"></script>
</body>
</html>