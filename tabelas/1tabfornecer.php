<?php

$con = mysqli_connect ("localhost", "root", "", "florir");
$sql = "CREATE TABLE fornecedor (
    id_fornecedor INT PRIMARY KEY AUTO_INCREMENT,
    razao_social VARCHAR(255) NOT NULL,
    cnpj VARCHAR(255) NOT NULL,
    email VARCHAR(255)
);";
$exe = mysqli_query ($con, $sql);
    if ($exe) {
        echo "Tabela criada com sucesso!";
    }
    else {
        echo "Erro ao criar tabela!";
    }
$end = mysqli_close($con);


?>