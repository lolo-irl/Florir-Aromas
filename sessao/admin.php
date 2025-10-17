<?php
$con = mysqli_connect('localhost', 'root', '', 'florir');


$admin = "admin";
$senha = "1234"; 
$hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (usuario, senha) VALUES ('$admin', '$hash')";

if (mysqli_query($con, $sql)) {
    echo "Admin criado com sucesso!";
} else {
    echo "Erro: " . mysqli_error($con);
}

mysqli_close($con);
?>
