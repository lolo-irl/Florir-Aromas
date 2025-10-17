<?php include('../includes/admheader.php'); ?>
<?php
require_once '../auth/auth_admin.php';
$con = mysqli_connect("localhost", "root", "", "florir");
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if (!isset($_GET['id'])) {
    die("ID do fornecedor não informado.");
}

$id = intval($_GET['id']);

// Buscar fornecedor
$sql = "SELECT * FROM fornecedor WHERE id_fornecedor=$id";
$res = mysqli_query($con, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    die("Fornecedor não encontrado.");
}
$fornecedor = mysqli_fetch_assoc($res);

// Atualizar fornecedor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_fornecedor = mysqli_real_escape_string($con, $_POST['nome_fornecedor']);
    $razao_social = mysqli_real_escape_string($con, $_POST['razao_social']);
    $cnpj = mysqli_real_escape_string($con, $_POST['cnpj']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $sql_update = "UPDATE fornecedor SET 
        nome_fornecedor='$nome_fornecedor',
        razao_social='$razao_social',
        cnpj='$cnpj',
        email='$email'
        WHERE id_fornecedor=$id";

    if (mysqli_query($con, $sql_update)) {
        header("Location: list.php");
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Editar Fornecedor</title>
    <link rel="stylesheet" href="../styleadm.css">
</head>
<body>
    <h1>Editar Fornecedor</h1>
    <form method="post">
    <label>Nome:</label><br>
    <input type="text" name="nome_fornecedor" value="<?php echo htmlspecialchars($fornecedor['nome_fornecedor']); ?>" required><br>

        <label>Razão Social:</label><br>
        <input type="text" name="razao_social" value="<?php echo htmlspecialchars($fornecedor['razao_social']); ?>" required><br>

        <label>CNPJ:</label><br>
        <input type="text" name="cnpj" value="<?php echo htmlspecialchars($fornecedor['cnpj']); ?>" required><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($fornecedor['email']); ?>"><br><br>

        <button type="submit">Atualizar</button>
    </form>
    <br>
    <a href="list.php_
