<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Fornecedores</title>
    <link rel="stylesheet" href="../styleadm.css">
</head>
<body>
    <h1>Fornecedores Registrados</h1>
<?php include('../includes/admheader.php'); ?>
<?php
require_once '../auth/auth_admin.php';
$con = mysqli_connect("localhost", "root", "", "florir");
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Excluir fornecedor
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $del_sql = "DELETE FROM fornecedor WHERE id_fornecedor = $id";
    mysqli_query($con, $del_sql);
    header("Location: list.php");
    exit;
}

// Buscar fornecedores
$sql = "SELECT * FROM fornecedor ORDER BY razao_social ASC";
$res = mysqli_query($con, $sql);
?>


    <div class="top-links">
        <a href="../pages/admpage.php">Voltar ao Admin</a>
        <a href="cria.php">Registrar Fornecedor</a>
    </div>

    <table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Razão Social</th>
        <th>CNPJ</th>
        <th>Email</th>
        <th>Ações</th>
    </tr>
    <?php if ($res && mysqli_num_rows($res) > 0): ?>
        <?php while ($fornecedor = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo $fornecedor['id_fornecedor']; ?></td>
                <td><?php echo ($fornecedor['nome_fornecedor']); ?></td>
                <td><?php echo ($fornecedor['razao_social']); ?></td>
                <td><?php echo ($fornecedor['cnpj']); ?></td>
                <td><?php echo ($fornecedor['email'] ?: '-'); ?></td>
                <td class="actions">
                    <a href="edit.php?id=<?php echo $fornecedor['id_fornecedor']; ?>">Editar</a>
                    <a class="delete" href="list.php?delete=<?php echo $fornecedor['id_fornecedor']; ?>" onclick="return confirm('Deseja realmente excluir este fornecedor?');">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">Nenhum fornecedor registrado.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
