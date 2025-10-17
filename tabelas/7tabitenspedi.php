<?php

$con = mysqli_connect ("localhost", "root", "", "florir");
$sql = "CREATE TABLE itempedido (
    id_item_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario FLOAT NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido),
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