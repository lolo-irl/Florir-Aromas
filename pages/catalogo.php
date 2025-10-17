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

// ============= LOGOUT =============
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Garante que id_cliente foi salvo na sessão no login
$id_cliente = $_SESSION['id_cliente'] ?? null;
$nome_cliente = $_SESSION['nome_cliente'] ?? "Usuário";

if (!$id_cliente) {
    header("Location: ../sessao/login.php");
    exit();
}

/* ============= ADICIONAR PRODUTO AO CARRINHO ============= */
if(isset($_GET['add'])){
    $id_produto = (int)$_GET['add'];

    // 1. Verifica se cliente já tem carrinho
    $resCarrinho = mysqli_query($con, "SELECT id_carrinho FROM carrinho WHERE id_cliente=$id_cliente");
    if(mysqli_num_rows($resCarrinho) == 0){
        // cria carrinho
        mysqli_query($con, "INSERT INTO carrinho (id_cliente) VALUES ($id_cliente)");
        $id_carrinho = mysqli_insert_id($con);
    } else {
        $rowCarrinho = mysqli_fetch_assoc($resCarrinho);
        $id_carrinho = $rowCarrinho['id_carrinho'];
    }

    // 2. Verifica se produto já está no carrinho
    $check = mysqli_query($con, "SELECT * FROM item_carrinho WHERE id_carrinho=$id_carrinho AND id_produto=$id_produto");
    if(mysqli_num_rows($check) > 0){
        // Já existe → aumenta quantidade
        mysqli_query($con, "UPDATE item_carrinho SET quantidade = quantidade + 1 WHERE id_carrinho=$id_carrinho AND id_produto=$id_produto");
    } else {
        // Novo item → pega preço do produto
        $preco = mysqli_fetch_assoc(mysqli_query($con, "SELECT preco FROM produto WHERE id_produto=$id_produto"))['preco'];
        mysqli_query($con, "INSERT INTO item_carrinho (id_carrinho, id_produto, quantidade, preco_unitario) VALUES ($id_carrinho, $id_produto, 1, $preco)");
    }

    header("Location: catalogo.php");
    exit();
}

/* ============= BUSCAR PRODUTOS ============= */
$res = mysqli_query($con, "SELECT * FROM produto ORDER BY nome_produto ASC");

/* ============= CONTAR ITENS NO CARRINHO ============= */
$total_carrinho = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT SUM(ic.quantidade) as total 
    FROM item_carrinho ic
    JOIN carrinho c ON ic.id_carrinho = c.id_carrinho
    WHERE c.id_cliente=$id_cliente
"))['total'] ?? 0;

?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catálogo - Florir Aromas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../styles/style_catalogo.css">
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
            <li class="carrinho-link">
                <a href="carrinho.php">
                    <span class="carrinho-icon">🛒</span>
                    <span class="carrinho-count">(<?php echo $total_carrinho; ?>)</span>
                </a>
            </li>
            <li class="user-menu">
                <div class="user-dropdown">
                    <button class="user-welcome">
                        <span>👋 Olá, <?php echo htmlspecialchars(explode(' ', $nome_cliente)[0]); ?></span>
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
        </ul>
    </nav>
</header>

<!-- ===== Submenu ===== -->
<div class="submenu">
    <a href="#" class="submenu-link active">Todos</a>
    <a href="#" class="submenu-link">Óleos</a>
    <a href="#" class="submenu-link">Incensos</a>
    <a href="#" class="submenu-link">Sachês</a>
    <a href="#" class="submenu-link">Blends</a>
</div>

<!-- ===== Hero Section ===== -->
<section class="hero-catalogo">
    <div class="hero-content">
        <h2>Descubra Nossas Essências</h2>
        <p>Aromas puros que transformam seu ambiente e bem-estar</p>
    </div>
</section>

<!-- ===== Catálogo ===== -->
<main class="catalogo-container">
    <h2 class="section-title">Nossos Produtos</h2>
    
    <div class="produtos-grid">
    <?php while($p = mysqli_fetch_assoc($res)): ?>
        <div class="produto-card">
            <div class="produto-imagem">
                <?php if($p['imagem_url']): ?>
                    <!-- LINK NA IMAGEM -->
                    <a href="produto.php?id=<?php echo $p['id_produto']; ?>">
                        <img src="../produtos/<?php echo $p['imagem_url']; ?>" alt="<?php echo htmlspecialchars($p['nome_produto']); ?>" loading="lazy">
                    </a>
                <?php else: ?>
                    <!-- LINK NO "SEM IMAGEM" -->
                    <a href="produto.php?id=<?php echo $p['id_produto']; ?>">
                        <div class="sem-imagem">Sem imagem</div>
                    </a>
                <?php endif; ?>
                <div class="produto-overlay">
                    <!-- BOTÃO VER PRODUTO (ao invés de adicionar) -->
                    <a href="produto.php?id=<?php echo $p['id_produto']; ?>" class="btn-ver-produto">Ver Produto</a>
                </div>
            </div>
            <div class="produto-info">
                <!-- LINK NO NOME E DESCRIÇÃO -->
                <a href="produto.php?id=<?php echo $p['id_produto']; ?>" style="text-decoration: none; color: inherit;">
                    <h3><?php echo htmlspecialchars($p['nome_produto']); ?></h3>
                    <p class="produto-descricao"><?php echo htmlspecialchars($p['descricao']); ?></p>
                </a>
                <div class="produto-preco">R$ <?php echo number_format((float)$p['preco'],2,',','.'); ?></div>
                <!-- BOTÃO ADICIONAR AO CARRINHO (TEXTO BRANCO) -->
                <a href="?add=<?php echo $p['id_produto']; ?>" class="btn-comprar">Adicionar ao Carrinho</a>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</main>

<?php include('../includes/footer.php'); ?>
</body>
</html>