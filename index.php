<?php
require_once "utils.php";
allowUser('guest');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= path('assets/style.css') ?>">
    <script src="<?= path('assets/functions.js') ?>"></script>
</head>
<body>
    <h1>Login</h1>

    <form id="formLogin" action="<?= path('login\login.php') ?>" method="POST">
        <label for="nomeLogin">Nome</label>
        <input type="text" name="nome" id="nomeLogin"><br><br>

        <label for="senhaLogin">Senha</label>
        <input type="password" name="senha" id="senhaLogin"><br><br>
        
        <button class="btn" type="button" onclick="sendLogin()">Entrar</button>
        <div id="messageDiv"></div>
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
<script>
function sendLogin() {
    //pega os dados do elemento form do html
    let nomeLogin = document.getElementById("nomeLogin").value;
    let senhaLogin = document.getElementById("senhaLogin").value;
    // para cada dado, se não atende às especificações, seta uma mensagem de erro e invalida o form.
    let formValido = true;
    let alerta = '';
    if(nomeLogin == null || nomeLogin == ''){
        formValido = false;
        alerta = 'Preencha o Nome!';
    } else if(senhaLogin == null || senhaLogin == ''){
        formValido = false;
        alerta = 'Preencha a Senha!';
    }

    //se o form não for válido, mostra o erro
    if(formValido === false){
        setMessage ('messageDiv', alerta, 'erro');
        return;
    } else {
        //se for válido, garante que o quadro de mensagem não aparecerá agora.
        setMessage ('messageDiv', '', 'hide');
    }

    //crio um objeto form para colocar os dados
    let form = new FormData();
    form.set('nome',nomeLogin);
    form.set('senha',senhaLogin);

    //dispara request post para o login.php com os dados do form
    fetch('login/login.php', {
        method: 'POST',
        headers: new Headers(),
        mode: 'cors',
        cache: 'default',
        body: form
    }).then(function(response) { //quando terminar a request...
        response.json().then(function(dados) { //converte pra json. quando terminar de converter...
            if(response.ok) {
                //se deu bom, eu boto a mensagem de sucesso e apago o conteudo do form html
                setMessage ('messageDiv', dados.body, 'sucesso');
                document.getElementById("formLogin").reset();
                window.location.href = "<?= path('/listamovimentos.php') ?>";
            } else {
                //se deu ruim, eu boto a mensagem de erro, mas deixo o conteudo pro usuario ajustar e mandar de novo.
                setMessage ('messageDiv', dados.body, 'erro');
            }
        });
    }).catch(function (error){
        //se de um bug cabuloso eu mostro no console...
        console.log('erro catch', error);
    });
}
</script>
</body>
</html>