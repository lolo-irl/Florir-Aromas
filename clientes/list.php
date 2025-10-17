<?php
require_once '../auth/auth_admin.php';
$con = mysqli_connect("localhost", "root", "", "florir");
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $del_sql = "DELETE FROM cliente WHERE id_cliente = $id";
    mysqli_query($con, $del_sql);
    header("Location: list.php");
    exit;
}

$sql = "SELECT * FROM cliente ORDER BY nome_cliente ASC";
$res = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styleadm.css">
</head>
<body>
<?php include('../includes/admheader.php'); ?>
    
    <main style="padding: 20px;">
        <h1 style="color: #697963; text-align: center; margin-bottom: 30px;">👥 Clientes Registrados</h1>

        <div class="top-links">
            <a href="../pages/admpage.php">← Voltar ao Admin</a>
            <a href="cria.php">➕ Registrar Cliente</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Bairro</th>
                    <th>Rua</th>
                    <th>Número</th>
                    <th>CEP</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res && mysqli_num_rows($res) > 0): ?>
                    <?php while ($cliente = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><?php echo $cliente['id_cliente']; ?></td>
                            <td><strong><?php echo htmlspecialchars($cliente['nome_cliente']); ?></strong></td>
                            <td><?php echo htmlspecialchars($cliente['CPF']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['bairro']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['rua']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['numero']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['cep']); ?></td>
                            <td class="actions">
                                <a href="edit.php?id=<?php echo $cliente['id_cliente']; ?>">✏️ Editar</a>
                                <a class="delete" href="list.php?delete=<?php echo $cliente['id_cliente']; ?>" 
                                   onclick="return confirm('Tem certeza que deseja excluir <?php echo htmlspecialchars($cliente['nome_cliente']); ?>?')">
                                   🗑️ Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 40px; color: #666;">
                            📝 Nenhum cliente registrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>