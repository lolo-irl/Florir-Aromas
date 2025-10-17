<?php
$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

$query = "SELECT * FROM produto LIMIT 6"; // Limita para 6 produtos na LP
$result = mysqli_query($con, $query);
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Florir - Essências Naturais</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/style_index.css">
</head>
<body>
  <!-- Header Estilo Catálogo -->
  <header class="site-header">
    <h1>Florir Aromas</h1>
    <nav>
      <ul>
        <li><a href="#produtos">Produtos</a></li>
        <li><a href="#beneficios">Benefícios</a></li>
        <li><a href="sessao/login.php" class="login-btn">Login</a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h2>Equilíbrio Natural</h2>
      <p>Transforme sua vida com óleos essenciais puros e produtos naturais da mais alta qualidade.</p>
      <a href="sessao/login.php" class="cta-button">Explorar Produtos</a>
    </div>
  </section>

  <!-- Produtos em Destaque -->
  <section id="produtos" class="featured-products">
    <h2 class="section-title">Produtos em Destaque</h2>
    
    <div class="catalogo-grid">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="produto-card">
          <div class="produto-imagem">
            <?php if($row['imagem_url']): ?>
              <img src="produtos/<?php echo $row['imagem_url']; ?>" alt="<?php echo htmlspecialchars($row['nome_produto']); ?>" loading="lazy">
            <?php else: ?>
              <div class="sem-imagem">Sem imagem</div>
            <?php endif; ?>
            <div class="produto-overlay">
              <a href="sessao/login.php" class="btn-add-carrinho">Ver Produto</a>
            </div>
          </div>
          <div class="produto-info">
            <h3><?php echo htmlspecialchars($row['nome_produto']); ?></h3>
            <p class="produto-descricao"><?php echo htmlspecialchars($row['descricao']); ?></p>
            <div class="produto-preco">R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></div>
            <a href="sessao/login.php" class="btn-comprar">Adicionar ao Carrinho</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    
    <div class="view-all-container">
      <a href="sessao/login.php" class="view-all-btn">Ver Todos os Produtos</a>
    </div>
  </section>

  <!-- Benefícios -->
  <section id="beneficios" class="benefits">
    <h2 class="section-title">Por que Escolher a Florir?</h2>
    <div class="benefits-container">
      <div class="benefit-item">
        <div class="benefit-icon">🌿</div>
        <h3>100% Natural</h3>
        <p>Produtos puros sem aditivos químicos ou conservantes artificiais</p>
      </div>
      <div class="benefit-item">
        <div class="benefit-icon">💚</div>
        <h3>Sustentável</h3>
        <p>Embalagens ecológicas e processos de produção responsáveis</p>
      </div>
      <div class="benefit-item">
        <div class="benefit-icon">⭐</div>
        <h3>Alta Qualidade</h3>
        <p>Seleção rigorosa dos melhores ingredientes naturais</p>
      </div>
    </div>
  </section>

  <!-- CTA Final -->
  <section class="final-cta">
    <div class="cta-content">
      <h2>Pronto para Transformar seu Ambiente?</h2>
      <p>Junte-se a milhares de clientes satisfeitos e descubra o poder dos aromas naturais</p>
      <a href="sessao/login.php" class="cta-button large">Começar Agora</a>
    </div>
  </section>

  <footer>
    <p>© 2025 Florir Aromas. Todos os direitos reservados.</p>
  </footer>

  <script src="script_index.js"></script>
</body>
</html>