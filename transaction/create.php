<?php
require_once('../utils.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Financeiro</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <header>
        <h1>Gerenciador Financeiro</h1>
    </header>
    <nav>
        <a class="btn" href="/transaction/create.php">Novo Movimento</a>
    </nav>
    <section>
        <h3>Novo movimento</h3>
        
        <form method="post" action="<?= path('transaction\store.php') ?>">
        Tipo: <input type="text" placeholder="Digite o tipo aqui" name="tipo"> <br><br>
        Descrição: <input type= "text" placeholder="Digite a descrição aqui" name="descricao"> <br><br>
        Valor: <input type="text" placeholder="Digite o valor aqui" name="valor"> <br><br>
        <input type ="submit" value="Enviar">&nbsp;
        <input type ="reset" value = "Apagar">
        </form>

        <?php if(isset($_GET['success'])){
            echo "
            <div class='successMessage'>
                $_GET[success]
            </div>
            ";
        } ?>

        <?php if(isset($_GET['error'])){
            echo "
            <div class='errorMessage'>
                $_GET[error]
            </div>
            ";
        } ?>
    </section>
</body>
</html>