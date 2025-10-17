<?php
session_start();
$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Verificar se veio o ID do produto
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: catalogo.php');
    exit();
}

$id_produto = (int)$_GET['id'];

// Buscar informações do produto
$sql_produto = "SELECT * FROM produto WHERE id_produto = $id_produto";
$result_produto = mysqli_query($con, $sql_produto);

if (!$result_produto || mysqli_num_rows($result_produto) == 0) {
    header('Location: catalogo.php');
    exit();
}

$produto = mysqli_fetch_assoc($result_produto);

// ============= LOGOUT =============
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Verificar se usuário está logado
$usuario_logado = isset($_SESSION['id_cliente']);
$id_cliente = $_SESSION['id_cliente'] ?? null;
$nome_cliente = $_SESSION['nome_cliente'] ?? "Usuário";

// Contar itens no carrinho (se estiver logado)
$total_carrinho = 0;
if ($usuario_logado) {
    $total_carrinho = mysqli_fetch_assoc(mysqli_query($con, "
        SELECT SUM(ic.quantidade) as total 
        FROM item_carrinho ic
        JOIN carrinho c ON ic.id_carrinho = c.id_carrinho
        WHERE c.id_cliente=$id_cliente
    "))['total'] ?? 0;
}

// Adicionar ao carrinho (se estiver logado)
if ($usuario_logado && isset($_GET['add_carrinho'])) {
    // Verificar se já tem carrinho
    $resCarrinho = mysqli_query($con, "SELECT id_carrinho FROM carrinho WHERE id_cliente=$id_cliente");
    if(mysqli_num_rows($resCarrinho) == 0){
        mysqli_query($con, "INSERT INTO carrinho (id_cliente) VALUES ($id_cliente)");
        $id_carrinho = mysqli_insert_id($con);
    } else {
        $rowCarrinho = mysqli_fetch_assoc($resCarrinho);
        $id_carrinho = $rowCarrinho['id_carrinho'];
    }

    // Verificar se produto já está no carrinho
    $check = mysqli_query($con, "SELECT * FROM item_carrinho WHERE id_carrinho=$id_carrinho AND id_produto=$id_produto");
    if(mysqli_num_rows($check) > 0){
        mysqli_query($con, "UPDATE item_carrinho SET quantidade = quantidade + 1 WHERE id_carrinho=$id_carrinho AND id_produto=$id_produto");
    } else {
        mysqli_query($con, "INSERT INTO item_carrinho (id_carrinho, id_produto, quantidade, preco_unitario) VALUES ($id_carrinho, $id_produto, 1, {$produto['preco']})");
    }
    
    header("Location: produto.php?id=$id_produto&added=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome_produto']); ?> - Florir Aromas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style_produto.css">
</head>
<body>

<!-- ===== Cabeçalho ===== -->
<header class="site-header">
    <h1>Florir Aromas</h1>
    <nav>
        <ul>
            <li><a href="catalogo.php">Início</a></li>
            <li><a href="sobre.php">Sobre</a></li>
            <li><a href="contato.php">Contato</a></li>
            <?php if ($usuario_logado): ?>
                <li class="carrinho-link">
                    <a href="carrinho.php">
                        <span class="carrinho-icon">🛒</span>
                        <span class="carrinho-count">(<?php echo $total_carrinho; ?>)</span>
                    </a>
                </li>
                <li class="user-menu">
                    <div class="user-dropdown">
                        <button class="user-welcome">
                            <span>Olá, <?php echo htmlspecialchars(explode(' ', $nome_cliente)[0]); ?></span>
                            <span>▼</span>
                        </button>
                        <div class="dropdown-content">
                            <a href="minha-conta.php" class="dropdown-item">
                                <span class="dropdown-icon">👤</span>
                                Minha Conta
                            </a>
                            <a href="meus-pedidos.php" class="dropdown-item">
                                <span class="dropdown-icon">📦</span>
                                Meus Pedidos
                            </a>
                            <a href="?logout=true" class="dropdown-item logout" onclick="return confirm('Tem certeza que deseja sair?')">
                                <span class="dropdown-icon">🚪</span>
                                Sair
                            </a>
                        </div>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="../sessao/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- ===== Submenu ===== -->
<div class="submenu">
    <a href="catalogo.php" class="submenu-link active">Todos</a>
    <a href="catalogo.php" class="submenu-link">Óleos</a>
    <a href="catalogo.php" class="submenu-link">Incensos</a>
    <a href="catalogo.php" class="submenu-link">Sachês</a>
    <a href="catalogo.php" class="submenu-link">Blends</a>
</div>

<main class="produto-container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="../index.php">Início</a> > 
        <a href="catalogo.php">Catálogo</a> > 
        <span><?php echo htmlspecialchars($produto['nome_produto']); ?></span>
    </nav>

    <div class="produto-content">
        <!-- Galeria de Imagens -->
        <div class="produto-galeria">
            <div class="imagem-principal">
                <?php if($produto['imagem_url']): ?>
                    <img src="../produtos/<?php echo $produto['imagem_url']; ?>" alt="<?php echo htmlspecialchars($produto['nome_produto']); ?>">
                <?php else: ?>
                    <div class="sem-imagem">📦 Sem imagem</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informações do Produto -->
        <div class="produto-info">
            <h1><?php echo htmlspecialchars($produto['nome_produto']); ?></h1>
            
            <div class="produto-preco">
                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
            </div>

            <div class="produto-descricao">
                <h3>Descrição</h3>
                <p><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
            </div>

            <?php if (isset($produto['ingredientes']) && !empty($produto['ingredientes'])): ?>
            <div class="produto-ingredientes">
                <h3>Ingredientes</h3>
                <p><?php echo nl2br(htmlspecialchars($produto['ingredientes'])); ?></p>
            </div>
            <?php endif; ?>

            <?php if (isset($produto['modo_uso']) && !empty($produto['modo_uso'])): ?>
            <div class="produto-modo-uso">
                <h3>Modo de Usar</h3>
                <p><?php echo nl2br(htmlspecialchars($produto['modo_uso'])); ?></p>
            </div>
            <?php endif; ?>

            <!-- Ações -->
            <div class="produto-actions">
                <?php if ($usuario_logado): ?>
                    <?php if (isset($_GET['added'])): ?>
                        <div class="success-message">✅ Produto adicionado ao carrinho!</div>
                    <?php endif; ?>
                    <a href="?id=<?php echo $id_produto; ?>&add_carrinho=1" class="btn-comprar">
                        🛒 Adicionar ao Carrinho
                    </a>
                <?php else: ?>
                    <a href="../sessao/login.php" class="btn-comprar">
                        🔐 Faça Login para Comprar
                    </a>
                <?php endif; ?>
                
                <a href="catalogo.php" class="btn-voltar">← Voltar ao Catálogo</a>
            </div>
    </div>

    <!-- Produtos Relacionados (opcional) -->
    <section class="produtos-relacionados">
        <h2>Você também pode gostar</h2>
        <div class="relacionados-grid">
            <!-- Aqui você pode adicionar produtos relacionados -->
            <p>Em breve: produtos relacionados...</p>
        </div>
    </section>
</main>

<?php include('../includes/footer.php'); ?>
</body>
</html>