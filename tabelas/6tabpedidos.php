<?php

$con = mysqli_connect ("localhost", "root", "", "florir");
$sql = "CREATE TABLE pedido (
    id_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_cliente INT NOT NULL,
    data_pedido DATETIME NOT NULL,
    status_pedido VARCHAR(50),
    valor_total FLOAT,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
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