<?php
require_once '../auth/auth_admin.php';
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: ../index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Área Administrativa - Florir Aromas</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles/style_adm.css">
</head>
<body class="admin-body">
  
  <?php include('../includes/admheader.php'); ?>

  <main class="admin-container">
    <!-- Header da Admin -->
    <div class="admin-header">
      <div class="admin-welcome">
        <h1> Área Administrativa</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_adm'] ?? 'Administrador'); ?>!</p>
      </div>
      
          </div>
        </div>
      </div>
    </div>

    <!-- Grid de Funcionalidades -->
    <div class="admin-grid">
      <!-- Gestão de Produtos -->
      <div class="admin-card">
        <div class="card-header">
          <div class="card-icon">🛍️</div>
          <h3>Gestão de Produtos</h3>
        </div>
        <p>Gerencie o catálogo de produtos</p>
        <div class="card-actions">
          <a href="../produtos/list.php" class="btn-admin primary">Ver Produtos</a>
          <a href="../produtos/cria.php" class="btn-admin secondary">+ Novo Produto</a>
        </div>
      </div>

      <!-- Gestão de Pedidos -->
      <div class="admin-card">
        <div class="card-header">
          <div class="card-icon">📦</div>
          <h3>Gestão de Pedidos</h3>
        </div>
        <p>Acompanhe e gerencie pedidos</p>
        <div class="card-actions">
          <a href="../pedidos/gerenciar.php" class="btn-admin primary">Gerenciar Pedidos</a>
        </div>
      </div>

      <!-- Gestão de Clientes -->
      <div class="admin-card">
        <div class="card-header">
          <div class="card-icon">👥</div>
          <h3>Gestão de Clientes</h3>
        </div>
        <p>Administre contas de clientes</p>
        <div class="card-actions">
          <a href="../clientes/list.php" class="btn-admin primary">Ver Clientes</a>
          <a href="../clientes/cria.php" class="btn-admin secondary">+ Novo Cliente</a>
        </div>
      </div>

 
      <!-- Administradores -->
      <div class="admin-card">
        <div class="card-header">
          <div class="card-icon">🔐</div>
          <h3>Administradores</h3>
        </div>
        <p>Gerencie acesso administrativo</p>
        <div class="card-actions">
          <a href="admins.php" class="btn-admin primary">Administradores</a>
          <a href="logs.php" class="btn-admin secondary">Logs</a>
        </div>
      </div>
    </div>       
  </main>
  <script src="../script_adm.js"></script>
  <?php include('../includes/footer.php'); ?>
</body>
</html>