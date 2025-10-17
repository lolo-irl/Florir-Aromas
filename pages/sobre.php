<?php
session_start(); 

$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Aqui você precisa garantir que na hora do login o id_cliente seja salvo na sessão
// Exemplo: $_SESSION['id_cliente'] = $dados['id_cliente'];

$id_cliente = $_SESSION['id_cliente'] ?? null;

if (!$id_cliente) {
    die("Usuário não logado corretamente. id_cliente não encontrado na sessão.");
}

// Contar itens no carrinho
$total_carrinho = mysqli_fetch_assoc(mysqli_query(
    $con,
    "SELECT SUM(ic.quantidade) AS total
     FROM item_carrinho ic
     INNER JOIN carrinho c ON ic.id_carrinho = c.id_carrinho
     WHERE c.id_cliente = $id_cliente"
))['total'] ?? 0;

?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Catálogo</title>
<link rel="stylesheet" href="../style.css"> <!-- link do CSS -->
</head>
<body>

<!-- ===== Cabeçalho ===== -->
<header>
    <h1>Florir Aromas</h1>
    <nav>
        <ul>
            <li><a href="catalogo.php">Início</a></li>
            <li><a href="sobre.php">Sobre</a></li>
            <li><a href="contato.php">Contato</a></li>
            <li><a href="carrinho.php">🛒 Carrinho (<?php echo $total_carrinho; ?>)</a></li>
        </ul>
    </nav>
</header>

<!-- ===== Submenu ===== -->
<div class="submenu">
    <a href="#">Óleos</a>
    <a href="#">Incensos</a>
    <a href="#">Sachês</a>
    <a href="#">Blends</a>
</div>

<!-- ===== Catálogo ===== -->
<h1>Sobre</h1>

<!-- ===== Rodapé ===== -->
<footer>
  <a href="../log/logout.php">Sair</a>
</footer>

</body>
</html>