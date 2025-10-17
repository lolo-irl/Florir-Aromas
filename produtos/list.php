<?php
require_once '../auth/auth_admin.php';
$con = mysqli_connect("localhost", "root", "", "florir");
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM produto WHERE id_produto = $id");
    header("Location: list.php");
    exit;
}

$sql = "SELECT p.*, c.nome_categoria 
        FROM produto p
        LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
        ORDER BY c.nome_categoria ASC";
$res = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styleadm.css">
</head>
<body>
<!-- ===== HEADER ===== -->
<?php include('../includes/admheader.php'); ?>

<main style="padding: 20px;">
    <h1 style="text-align:center; color:#697963; margin-bottom:30px;">📦 Produtos Registrados</h1>

    <div class="top-links">
        <a href="../pages/admpage.php">← Voltar ao Admin</a>
        <a href="cria.php">➕ Registrar Produto</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Categoria</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($res && mysqli_num_rows($res) > 0): ?>
                <?php while ($produto = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?php echo $produto['id_produto']; ?></td>
                        <td><?php echo $produto['nome_categoria'] ?: "Sem categoria"; ?></td>
                        <td><?php echo htmlspecialchars($produto['nome_produto']); ?></td>
                        <td>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></td>
                        <td><?php echo $produto['estoque']; ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $produto['id_produto']; ?>">✏️ Editar</a>
                            <a class="delete" href="list.php?delete=<?php echo $produto['id_produto']; ?>" onclick="return confirm('Deseja realmente excluir <?php echo htmlspecialchars($produto['nome_produto']); ?>?')">🗑️ Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#666; padding:40px;">📝 Nenhum produto registrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
