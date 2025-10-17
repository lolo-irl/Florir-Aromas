<?php
session_start();
$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

$erro = '';
$sucesso = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_cliente = mysqli_real_escape_string($con, $_POST['nome_cliente']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validações
    if (empty($nome_cliente) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $erro = "Por favor, preencha todos os campos!";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } else {
        // Verifica se email já existe
        $sql_check = "SELECT * FROM cliente WHERE email='$email'";
        $res_check = mysqli_query($con, $sql_check);

        if (mysqli_num_rows($res_check) > 0) {
            $erro = "Este e-mail já está cadastrado!";
        } else {
            // Cria hash da senha
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere no banco
            $sql = "INSERT INTO cliente (nome_cliente, email, senha) VALUES ('$nome_cliente', '$email', '$hash')";
            if (mysqli_query($con, $sql)) {
                $sucesso = "Cadastro realizado com sucesso!";
                
                // Logar automaticamente
                $_SESSION['tipo'] = "cliente";
                $_SESSION['nome_cliente'] = $nome_cliente;
                $_SESSION['id_cliente'] = mysqli_insert_id($con);
                $_SESSION['email_cliente'] = $email;
                
                // Redirecionar
                header("Location: ../pages/catalogo.php");
                exit;
            } else {
                $erro = "Erro ao cadastrar: " . mysqli_error($con);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Florir Aromas</title>
    <link rel="stylesheet" href="../styles/style_cadastro.css">
</head>
<body class="login-split">
  <div class="login-container">
    <!-- Lado esquerdo (formulário) -->
    <div class="login-form">
      <h2 class="logo">Florir Aromas</h2>
      <p class="welcome">Crie sua conta</p>
      <h1>Cadastro</h1>

      <?php if (!empty($erro)) echo "<p class='erro'>$erro</p>"; ?>
      <?php if (!empty($sucesso)) echo "<p class='sucesso'>$sucesso</p>"; ?>

      <form method="post">
        <label>Nome completo</label>
        <input type="text" name="nome_cliente" placeholder="Seu nome completo" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="seu@email.com" required>

        <label>Senha</label>
        <input type="password" name="senha" placeholder="Sua senha" required>

        <label>Confirmar Senha</label>
        <input type="password" name="confirmar_senha" placeholder="Repita sua senha" required>

        <button type="submit" class="btn-login">Cadastrar</button>
      </form>

      <p class="signup-text">
        Já tem conta? <a href="login.php">Faça login</a>
      </p>
    </div>

    <!-- Lado direito (imagem) -->
    <div class="login-side">
      <img src="../uploads/florir.png" alt="Florir Aromas">
    </div>
  </div>
</body>
</html>