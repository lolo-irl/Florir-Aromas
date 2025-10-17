<?php
session_start();
$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_cliente = $_POST['nome_cliente'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    
    $sql_check = "SELECT * FROM cliente WHERE email='$email'";
    $res_check = mysqli_query($con, $sql_check);

    if (mysqli_num_rows($res_check) > 0) {
        echo "Este e-mail já está cadastrado!";
    } else {
        
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        
        $sql = "INSERT INTO cliente (nome_cliente, email, senha) VALUES ('$nome_cliente', '$email', '$hash')";
        if (mysqli_query($con, $sql)) {
            $_SESSION['tipo'] = "cliente";
            $_SESSION['usuario'] = $nome_cliente;
            header("Location: ../index.php");
            exit;
        } else {
            echo "Erro ao cadastrar: " . mysqli_error($con);
        }
    }
}
?>
<head>
    <link rel="stylesheet" href="../style.css">
</head>
<h2>Cadastro de Cliente</h2>
<form method="post">
    <input type="text" name="nome_cliente" placeholder="Nome completo" required><br>
    <input type="email" name="email" placeholder="E-mail" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <button type="submit">Cadastrar</button>
</form>
