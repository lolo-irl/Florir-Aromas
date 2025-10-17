<?php

$con = mysqli_connect ("localhost", "root", "", "florir");
$sql = "CREATE TABLE pagamento (
    id_pagamento INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    data_pagamento DATETIME NOT NULL,
    valor FLOAT NOT NULL,
    metodo_pagamento VARCHAR(50),
    status_pagamento VARCHAR(50),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
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