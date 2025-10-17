<?php

$con = mysqli_connect ("localhost", "root", "", "florir");
$sql = "CREATE TABLE entrada (
    id_entrada INT PRIMARY KEY AUTO_INCREMENT,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    data_movimentacao DATETIME NOT NULL,
    descricao TEXT,
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto)
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