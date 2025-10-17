<?php
require_once '../auth/auth_admin.php';

$con = mysqli_connect("localhost", "root", "", "florir");
if (!$con) die("Erro na conexão: " . mysqli_connect_error());

// Confirmar pagamento
if (isset($_GET['confirmar'])) {
    $id_pedido = intval($_GET['confirmar']);
    mysqli_query($con, "UPDATE pedido SET status_pedido='Pago' WHERE id_pedido=$id_pedido");
    mysqli_query($con, "UPDATE pagamento SET status_pagamento='Confirmado', metodo_pagamento='Confirmado' WHERE id_pedido=$id_pedido");
    header("Location: gerenciar.php");
    exit;
}

// Cancelar pedido
if (isset($_GET['cancelar'])) {
    $id_pedido = intval($_GET['cancelar']);

    // Recupera os produtos do pedido (busca na tabela item_carrinho via carrinho relacionado, se houver estrutura)
    $queryItens = "SELECT ic.id_produto, ic.quantidade 
                   FROM item_carrinho ic
                   JOIN carrinho c ON ic.id_carrinho = c.id_carrinho
                   JOIN pedido p ON p.id_cliente = c.id_cliente
                   WHERE p.id_pedido = $id_pedido";
    $resItens = mysqli_query($con, $queryItens);

    while ($item = mysqli_fetch_assoc($resItens)) {
        mysqli_query($con, "UPDATE produto SET estoque = estoque + {$item['quantidade']} WHERE id_produto = {$item['id_produto']}");
    }

    // Atualiza status do pedido e pagamento
    mysqli_query($con, "UPDATE pedido SET status_pedido='Cancelado' WHERE id_pedido=$id_pedido");
    mysqli_query($con, "UPDATE pagamento SET status_pagamento='Cancelado', metodo_pagamento='Cancelado' WHERE id_pedido=$id_pedido");

    header("Location: gerenciar.php");
    exit;
}

// Buscar pedidos
$sql = "SELECT p.id_pedido, c.nome_cliente, p.data_pedido, p.status_pedido, p.valor_total, 
        pa.status_pagamento, pa.metodo_pagamento
        FROM pedido p
        JOIN cliente c ON p.id_cliente = c.id_cliente
        LEFT JOIN pagamento pa ON p.id_pedido = pa.id_pedido
        ORDER BY p.data_pedido DESC";

$res = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Pedidos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styleadm.css">
</head>
<body>
<?php include('../includes/admheader.php'); ?>

<main style="padding: 20px;">
    <h1 style="text-align:center; color:#697963; margin-bottom:30px;">📦 Gerenciar pedidos</h1>

    <div class="top-links">
        <a href="../pages/admpage.php">← Voltar ao Admin</a>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status Pedido</th>
                    <th>Status Pagamento</th>
                    <th>Valor Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = mysqli_fetch_assoc($res)) { ?>
                    <tr>
                        <td><?php echo $pedido['id_pedido']; ?></td>
                        <td><?php echo htmlspecialchars($pedido['nome_cliente']); ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($pedido['data_pedido'])); ?></td>
                        <td><?php echo htmlspecialchars($pedido['status_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['status_pagamento']); ?></td>
                        <td>R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></td>
                        <td>
                            <?php if ($pedido['status_pedido'] == 'Pendente') { ?>
                                <a class="btn confirmar" href="?confirmar=<?php echo $pedido['id_pedido']; ?>" 
                                   onclick="return confirm('Confirmar pagamento deste pedido?')">Confirmar</a>
                                <a class="btn cancelar" href="?cancelar=<?php echo $pedido['id_pedido']; ?>" 
                                   onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">Cancelar</a>
                            <?php } elseif ($pedido['status_pedido'] == 'Pago') { ?>
                                ✅ Pago
                            <?php } else { ?>
                                ❌ Cancelado
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div style="text-align:center;">
            <a href="../pages/admpage.php">← Voltar ao painel</a>
        </div>
    </main>
</body>
</html>
