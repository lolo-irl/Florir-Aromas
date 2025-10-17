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

/* ================== PEGAR id_carrinho DO CLIENTE ================== */
$resCarrinho = mysqli_query($con, "SELECT id_carrinho FROM carrinho WHERE id_cliente=$id_cliente");
if(mysqli_num_rows($resCarrinho) == 0){
    // se não tiver carrinho ainda → cria
    mysqli_query($con, "INSERT INTO carrinho (id_cliente) VALUES ($id_cliente)");
    $id_carrinho = mysqli_insert_id($con);
} else {
    $rowCarrinho = mysqli_fetch_assoc($resCarrinho);
    $id_carrinho = $rowCarrinho['id_carrinho'];
}

/* ================== DIMINUIR QUANTIDADE ================== */
if (isset($_GET['diminuir'])) {
    $id_item = (int)$_GET['diminuir'];
    mysqli_query($con, "UPDATE item_carrinho SET quantidade = quantidade - 1 WHERE id_item=$id_item AND id_carrinho=$id_carrinho");
    mysqli_query($con, "DELETE FROM item_carrinho WHERE id_item=$id_item AND id_carrinho=$id_carrinho AND quantidade <= 0");
    header("Location: carrinho.php");
    exit();
}

/* ================== AUMENTAR QUANTIDADE ================== */
if (isset($_GET['aumentar'])) {
    $id_item = (int)$_GET['aumentar'];
    mysqli_query($con, "UPDATE item_carrinho SET quantidade = quantidade + 1 WHERE id_item=$id_item AND id_carrinho=$id_carrinho");
    header("Location: carrinho.php");
    exit();
}

/* ================== REMOVER ITEM COMPLETO ================== */
if (isset($_GET['remover_todos'])) {
    $id_item = (int)$_GET['remover_todos'];
    mysqli_query($con, "DELETE FROM item_carrinho WHERE id_item=$id_item AND id_carrinho=$id_carrinho");
    header("Location: carrinho.php");
    exit();
}

/* ================== ATUALIZAR VALOR TOTAL DO CARRINHO ================== */
mysqli_query($con, "UPDATE carrinho SET valor_total = (
    SELECT COALESCE(SUM(quantidade * preco_unitario), 0) 
    FROM item_carrinho 
    WHERE id_carrinho = $id_carrinho
) WHERE id_carrinho = $id_carrinho");

/* ================== BUSCAR ITENS DO CARRINHO ================== */
$sql = "SELECT ic.id_item, ic.quantidade, ic.preco_unitario, p.nome_produto, p.imagem_url, p.id_produto
        FROM item_carrinho ic
        JOIN produto p ON ic.id_produto = p.id_produto
        WHERE ic.id_carrinho=$id_carrinho
        ORDER BY ic.id_item DESC";
$res = mysqli_query($con, $sql);

$total = 0;
$produtos = [];
while ($row = mysqli_fetch_assoc($res)) {
    $row['subtotal'] = $row['quantidade'] * $row['preco_unitario'];
    $total += $row['subtotal'];
    $produtos[] = $row;
}

/* ================== CONTAR TOTAL DE ITENS ================== */
$total_carrinho = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT SUM(quantidade) as total 
    FROM item_carrinho 
    WHERE id_carrinho=$id_carrinho
"))['total'] ?? 0;
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrinho - Florir Aromas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../styles/style_carrinho.css">
</head>
<body>

<!-- ===== Cabeçalho ===== -->
<header class="site-header">
    <h1>Florir Aromas</h1>
    <nav>
        <ul>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="sobre.php">Sobre</a></li>
            <li><a href="contato.php">Contato</a></li>
            <li class="carrinho-link">
                <a href="carrinho.php">
                    <span class="carrinho-icon">🛒</span>
                    <span class="carrinho-count">(<?php echo $total_carrinho; ?>)</span>
                </a>
            </li>
            <li class="user-welcome">Olá, <?php echo htmlspecialchars($nome_cliente); ?></li>
        </ul>
    </nav>
</header>

<!-- ===== Submenu ===== -->
<div class="submenu">
    <a href="catalogo.php" class="submenu-link">← Voltar às Compras</a>
    <a href="catalogo.php" class="submenu-link">Óleos</a>
    <a href="catalogo.php" class="submenu-link">Incensos</a>
    <a href="catalogo.php" class="submenu-link">Sachês</a>
</div>

<!-- ===== Conteúdo do Carrinho ===== -->
<main class="carrinho-container">
    <div class="carrinho-header">
        <h1>🛒 Meu Carrinho</h1>
        <p class="itens-total"><?php echo $total_carrinho; ?> ite<?php echo $total_carrinho != 1 ? 'ns' : 'm'; ?> no carrinho</p>
    </div>

    <?php if(!$produtos): ?>
        <div class="carrinho-vazio">
            <div class="vazio-icon">🛒</div>
            <h2>Seu carrinho está vazio</h2>
            <p>Que tal explorar nossos produtos incríveis?</p>
            <a href="catalogo.php" class="btn-voltar-catalogo">Descobrir Produtos</a>
        </div>
    <?php else: ?>
        <div class="carrinho-content">
            <!-- Lista de Produtos -->
            <div class="produtos-lista">
                <?php foreach($produtos as $p): ?>
                <div class="carrinho-item" data-item-id="<?php echo $p['id_item']; ?>">
                    <div class="item-imagem">
                        <?php if($p['imagem_url']): ?>
                            <img src="../produtos/<?php echo $p['imagem_url']; ?>" alt="<?php echo htmlspecialchars($p['nome_produto']); ?>">
                        <?php else: ?>
                            <div class="sem-imagem">📦</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="item-info">
                        <h3><?php echo htmlspecialchars($p['nome_produto']); ?></h3>
                        <p class="item-preco">R$ <?php echo number_format($p['preco_unitario'],2,',','.'); ?></p>
                    </div>
                    
                    <div class="item-controles">
                        <div class="quantidade-controller">
                            <button class="btn-quantidade diminuir" data-id="<?php echo $p['id_item']; ?>">−</button>
                            <span class="quantidade"><?php echo $p['quantidade']; ?></span>
                            <button class="btn-quantidade aumentar" data-id="<?php echo $p['id_item']; ?>">+</button>
                        </div>
                        
                        <div class="item-subtotal">
                            R$ <?php echo number_format($p['subtotal'],2,',','.'); ?>
                        </div>
                        
                        <button class="btn-remover" data-id="<?php echo $p['id_item']; ?>" title="Remover item">
                            🗑️
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Resumo do Pedido -->
            <div class="resumo-pedido">
                <div class="resumo-header">
                    <h3>Resumo do Pedido</h3>
                </div>
                
                <div class="resumo-detalhes">
                    <div class="resumo-linha">
                        <span>Subtotal (<?php echo $total_carrinho; ?> itens):</span>
                        <span>R$ <?php echo number_format($total,2,',','.'); ?></span>
                    </div>
                    
                    <div class="resumo-linha">
                        <span>Frete:</span>
                        <span class="frete-gratis">Grátis</span>
                    </div>
                    <div class="resumo-linha total">
                        <strong>Total:</strong>
                        <strong class="valor-total">R$ <?php echo number_format($total,2,',','.'); ?></strong>
                    </div>
                </div>
                
                <div class="resumo-acoes">
                    <form action="../pedidos/finalizar.php" method="POST" class="form-finalizar">
                        <button type="submit" class="btn-finalizar-compra">
                            🛒 Finalizar Compra
                        </button>
                    </form>
                    
                    <a href="catalogo.php" class="btn-continuar-comprando">
                        + Continuar Comprando
                    </a>
                </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>
<script src="../scripts/script_carrinho.js"></script>
<?php include('../includes/footer.php'); ?>

</body>
</html>