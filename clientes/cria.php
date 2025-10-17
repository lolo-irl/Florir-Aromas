<?php 
include('../includes/admheader.php'); 
require_once '../auth/auth_admin.php';

$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

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

    // Gerar senha padrão (pode ser alterada depois)
    $senha = password_hash('123456', PASSWORD_DEFAULT);

    // Verificar se email já existe
    $check_email = mysqli_query($con, "SELECT id_cliente FROM cliente WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $mensagem_erro = "❌ Este email já está cadastrado no sistema.";
    } else {
        $sql = "INSERT INTO cliente (nome_cliente, CPF, email, telefone, bairro, rua, numero, cep, senha)
                VALUES ('$nome_cliente', '$cpf', '$email', '$telefone', '$bairro', '$rua', '$numero', '$cep', '$senha')";

        if (mysqli_query($con, $sql)) {
            $mensagem_sucesso = "✅ Cliente cadastrado com sucesso!";
            $_POST = array(); // Limpar formulário
        } else {
            $mensagem_erro = "❌ Erro ao cadastrar o cliente: " . mysqli_error($con);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente - Admin | Florir Aromas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styleadm.css">
</head>
<body>
<?php include('../includes/admheader.php'); ?>

<main>
    <div class="form-container">
        <h1 class="form-title">👥 Cadastrar Cliente</h1>

        <?php if($mensagem_sucesso): ?>
            <div class="message message-success"><?php echo $mensagem_sucesso; ?></div>
        <?php elseif($mensagem_erro): ?>
            <div class="message message-error"><?php echo $mensagem_erro; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label class="form-label">Nome Completo *</label>
                <input type="text" class="form-input" name="nome_cliente" 
                       value="<?php echo htmlspecialchars($_POST['nome_cliente'] ?? ''); ?>" 
                       placeholder="Ex: Maria Silva Santos" required>
            </div>

            <div class="form-group">
                <label class="form-label">CPF *</label>
                <input type="text" class="form-input" name="CPF" 
                       value="<?php echo htmlspecialchars($_POST['CPF'] ?? ''); ?>" 
                       placeholder="000.000.000-00" required>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-input" name="email" 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                           placeholder="exemplo@email.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Telefone</label>
                    <input type="tel" class="form-input" name="telefone" 
                           value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>" 
                           placeholder="(11) 99999-9999">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Bairro</label>
                    <input type="text" class="form-input" name="bairro" 
                           value="<?php echo htmlspecialchars($_POST['bairro'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Rua</label>
                    <input type="text" class="form-input" name="rua" 
                           value="<?php echo htmlspecialchars($_POST['rua'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Número</label>
                    <input type="number" class="form-input" name="numero" 
                           value="<?php echo htmlspecialchars($_POST['numero'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">CEP</label>
                    <input type="text" class="form-input" name="cep" 
                           value="<?php echo htmlspecialchars($_POST['cep'] ?? ''); ?>" 
                           placeholder="00000-000">
                </div>
            </div>

            <div class="form-group">
                <div class="message message-success" style="background: rgba(105, 121, 99, 0.1); color: var(--primary);">
                    <strong>📝 Informação:</strong> A senha padrão será "123456". O cliente poderá alterá-la posteriormente.
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    💾 Cadastrar Cliente
                </button>
                <a href="list.php" class="btn-cancel">
                    ← Voltar à Lista
                </a>
            </div>
        </form>
    </div>
</main>

</body>
</html>
