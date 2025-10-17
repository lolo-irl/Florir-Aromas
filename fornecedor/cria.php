<?php include('../includes/admheader.php'); ?>
<?php
require_once '../auth/auth_admin.php';
$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_fornecedor = mysqli_real_escape_string($con, $_POST['nome_fornecedor']);
    $razao_social    = mysqli_real_escape_string($con, $_POST['razao_social']);
    $cnpj            = mysqli_real_escape_string($con, $_POST['cnpj']);
    $email           = mysqli_real_escape_string($con, $_POST['email']);

    $sql = "INSERT INTO fornecedor (nome_fornecedor, razao_social, cnpj, email)
            VALUES ('$nome_fornecedor', '$razao_social', '$cnpj', '$email')";

    if (mysqli_query($con, $sql)) {
        echo "Fornecedor cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o fornecedor: " . mysqli_error($con);
    }
}
?>
<head>
    <link rel="stylesheet" href="../styleadm.css">
</head>
<h2>Cadastro de Fornecedor</h2>
<form method="post">
    <input type="text" name="nome_fornecedor" placeholder="Nome" required><br>
    <input type="text" name="razao_social" placeholder="Razão Social" required><br>
    <input type="text" name="cnpj" placeholder="CNPJ" required><br>
    <input type="email" name="email" placeholder="Email"><br><br>

    <button type="submit">Cadastrar Fornecedor</button><br>
    <a href="list.php">Voltar ao relatório</a>
</form>
