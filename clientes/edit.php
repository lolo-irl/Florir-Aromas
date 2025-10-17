<?php 
include('../includes/admheader.php'); 
require_once '../auth/auth_admin.php';

$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Verificar se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list_clientes.php');
    exit;
}

$id_cliente = intval($_GET['id']);

// Buscar dados do cliente
$sql_cliente = "SELECT * FROM cliente WHERE id_cliente = $id_cliente";
$result_cliente = mysqli_query($con, $sql_cliente);

if (!$result_cliente || mysqli_num_rows($result_cliente) == 0) {
    header('Location: list_clientes.php');
    exit;
}

$cliente = mysqli_fetch_assoc($result_cliente);

$mensagem_sucesso = '';
$mensagem_erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_cliente = mysqli_real_escape_string($con, $_POST['nome_cliente']);
    $cpf = mysqli_real_escape_string($con, $_POST['CPF']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $telefone = mysqli_real_escape_string($con, $_POST['telefone']);
    $bairro = mysqli_real_escape_string($con, $_POST['bairro']);
    $rua = mysqli_real_escape_string($con, $_POST['rua']);
    $numero = mysqli_real_escape_string($con, $_POST['numero']);
    $cep = mysqli_real_escape_string($con, $_POST['cep']);

    // Verificar se email já existe (excluindo o próprio cliente)
    $check_email = mysqli_query($con, "SELECT id_cliente FROM cliente WHERE email = '$email' AND id_cliente != $id_cliente");
    if (mysqli_num_rows($check_email) > 0) {
        $mensagem_erro = "❌ Este email já está cadastrado por outro cliente.";
    } else {
        $sql = "UPDATE cliente SET 
                nome_cliente = '$nome_cliente',
                CPF = '$cpf',
                email = '$email',
                telefone = '$telefone',
                bairro = '$bairro',
                rua = '$rua',
                numero = '$numero',
                cep = '$cep'
                WHERE id_cliente = $id_cliente";

        if (mysqli_query($con, $sql)) {
            $mensagem_sucesso = "✅ Cliente atualizado com sucesso!";
            // Atualizar dados do cliente
            $cliente = array_merge($cliente, [
                'nome_cliente' => $nome_cliente,
                'CPF' => $cpf,
                'email' => $email,
                'telefone' => $telefone,
                'bairro' => $bairro,
                'rua' => $rua,
                'numero' => $numero,
                'cep' => $cep
            ]);
        } else {
            $mensagem_erro = "❌ Erro ao atualizar o cliente: " . mysqli_error($con);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - Admin | Florir Aromas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styleadm.css">
</head>
<body>

<main>
    <div class="form-container">
        <h1 class="form-title">✏️ Editar Cliente</h1>

        <?php if($mensagem_sucesso): ?>
            <div class="message message-success"><?php echo $mensagem_sucesso; ?></div>
        <?php elseif($mensagem_erro): ?>
            <div class="message message-error"><?php echo $mensagem_erro; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label class="form-label">Nome Completo *</label>
                <input type="text" class="form-input" name="nome_cliente" 
                       value="<?php echo htmlspecialchars($cliente['nome_cliente']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">CPF *</label>
                <input type="text" class="form-input" name="CPF" 
                       value="<?php echo htmlspecialchars($cliente['CPF']); ?>" required>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-input" name="email" 
                           value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Telefone</label>
                    <input type="tel" class="form-input" name="telefone" 
                           value="<?php echo htmlspecialchars($cliente['telefone']); ?>">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Bairro</label>
                    <input type="text" class="form-input" name="bairro" 
                           value="<?php echo htmlspecialchars($cliente['bairro']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Rua</label>
                    <input type="text" class="form-input" name="rua" 
                           value="<?php echo htmlspecialchars($cliente['rua']); ?>">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Número</label>
                    <input type="number" class="form-input" name="numero" 
                           value="<?php echo htmlspecialchars($cliente['numero']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">CEP</label>
                    <input type="text" class="form-input" name="cep" 
                           value="<?php echo htmlspecialchars($cliente['cep']); ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    💾 Atualizar Cliente
                </button>
                <a href="list_clientes.php" class="btn-cancel">
                    ← Voltar à Lista
                </a>
            </div>
        </form>
    </div>
</main>

</body>
</html>
