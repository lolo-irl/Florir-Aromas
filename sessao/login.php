<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "florir");
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica admin
    $sql_admin = "SELECT * FROM admin WHERE nome_adm='$email'";
    $res_admin = mysqli_query($con, $sql_admin);

    if (mysqli_num_rows($res_admin) > 0) {
        $admin = mysqli_fetch_assoc($res_admin);
        if (password_verify($senha, $admin['senha'])) {
            $_SESSION['tipo'] = "admin";
            $_SESSION['nome_adm'] = $admin['nome_adm'];
            header("Location: ../pages/admpage.php");
            exit;
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        // Verifica cliente
        $sql_cliente = "SELECT * FROM cliente WHERE email='$email'";
        $res_cliente = mysqli_query($con, $sql_cliente);

        if (mysqli_num_rows($res_cliente) > 0) {
            $cliente = mysqli_fetch_assoc($res_cliente);
            if (password_verify($senha, $cliente['senha'])) {
                $_SESSION['tipo'] = "cliente";
                $_SESSION['nome_cliente'] = $cliente['nome_cliente'];
                $_SESSION['id_cliente'] = $cliente['id_cliente'];
                header("Location: ../pages/catalogo.php");
                exit;
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "Usuário não encontrado!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Florir Aromas</title>
  <link rel="stylesheet" href="../styles/style_login.css">
</head>
<body class="login-split">
  <div class="login-container">
    <!-- Lado esquerdo (formulário) -->
    <div class="login-form">
      <div class="form-header">
        <div class="logo">
          <h2>Florir Aromas</h2>
        </div>
        <h1>Login</h1>
      </div>

      <?php if (!empty($erro)): ?>
        <div class="alert error">
          <span class="alert-icon">⚠️</span>
          <?php echo $erro; ?>
        </div>
      <?php endif; ?>

      <form method="post">
        <div class="form-group">
          <label>Email ou Nome de Usuário</label>
          <input type="text" name="email" placeholder="seu@email.com" required>
        </div>

        <div class="form-group">
          <label>Senha</label>
          <input type="password" name="senha" placeholder="Sua senha" required>
        </div>

        <button type="submit" class="btn-login">Entrar</button>
      </form>

      <div class="login-footer">
        <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
      </div>
    </div>

    <!-- Lado direito (imagem) -->
    <div class="login-side">
      <img src="../uploads/florir.png" alt="Florir Aromas">
    </div>
  </div>
</body>
</html>