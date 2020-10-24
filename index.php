<?php
require_once "utils.php";
allowUser('guest');
?>

<!DOCTYPE html>
<html lang="ptbr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <h1>Login</h1>

    <form action="<?= path('login\login.php') ?>" method="POST">
        <label for="nomeLogin">Nome</label>
        <input type="text" name="nome" id="nomeLogin"><br><br>

        <label for="senhaLogin">Senha</label>
        <input type="password" name="senha" id="senhaLogin"><br><br>
        
        <input type="submit" value="Entrar">
    </form>

<hr>

    <h2>Cadastro</h2>
    <form action="<?= path('login\register.php') ?>" method="POST">
        <label for="nomeRegister">Nome</label>
        <input type="text" name="nome" id="nomeRegister"><br><br>

        <label for="senhaRegister">Senha</label>
        <input type="password" name="senha" id="senha1Register"><br><br>

        <label for="repitaSenhaRegister">Repita a senha</label>
        <input type="password" name="repitaSenha" id="repitaSenhaRegister"><br><br>

        <input type="submit" value="Criar conta">

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
    </form>
</body>
</html>