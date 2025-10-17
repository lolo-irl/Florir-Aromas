<?php

$con = mysqli_connect ("localhost", "root", "", "florir");
$sql = "CREATE TABLE produto (
    id_produto INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco FLOAT NOT NULL,
    estoque INT NOT NULL,
    imagem_url VARCHAR(255),
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
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