<?php

require_once "utils.php";

//preparar os dados


if($resultado){
    //se der tudo certo, manda de volta para a lista de movimentos
    $success = 'Inserido com sucesso!';
    redirect("index.php?success=$success");

} else {
    //se não, volta pro formulario dizendo o erro
    $erro = $statement->errorInfo();
    $erro = $erro[2];
    // dump($erro);
    redirect("transaction/create.php?error=$erro");
}

?>