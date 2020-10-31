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
        <div id="messageDivLogin"></div>
    </form>


    <hr>

    <h2>Cadastro</h2>
    <form id="formRegister" action="<?= path('login\register.php') ?>" method="POST">
        <label for="nomeRegister">Nome</label>
        <input type="text" name="nome" id="nomeRegister"><br><br>

        <label for="senhaRegister">Senha</label>
        <input type="password" name="senha" id="senhaRegister"><br><br>

        <label for="repitaSenhaRegister">Repita a senha</label>
        <input type="password" name="repitaSenha" id="repitaSenhaRegister"><br><br>

        <button class="btn" type="button" onclick="sendRegister()">Criar Conta</button>
        <div id="messageDivRegister"></div>
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
        setMessage ('messageDivLogin', alerta, 'erro');
        return;
    } else {
        //se for válido, garante que o quadro de mensagem não aparecerá agora.
        setMessage ('messageDivLogin', '', 'hide');
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
                setMessage ('messageDivLogin', dados.body, 'sucesso');
                document.getElementById("formLogin").reset();
                window.location.href = "<?= path('/listamovimentos.php') ?>";
            } else {
                //se deu ruim, eu boto a mensagem de erro, mas deixo o conteudo pro usuario ajustar e mandar de novo.
                setMessage ('messageDivLogin', dados.body, 'erro');
            }
        });
    }).catch(function (error){
        //se de um bug cabuloso eu mostro no console...
        console.log('erro catch', error);
    });
}

function sendRegister() {
    //pega os dados do elemento form do html
    let nomeRegister = document.getElementById("nomeRegister").value;
    let senhaRegister = document.getElementById("senhaRegister").value;
    let repitaSenhaRegister = document.getElementById("repitaSenhaRegister").value;
    // para cada dado, se não atende às especificações, seta uma mensagem de erro e invalida o form.
    let formValido = true;
    let alerta = '';
    if(nomeRegister == null || nomeRegister == ''){
        formValido = false;
        alerta = 'Preencha o Nome!';
    } else if(senhaRegister == null || senhaRegister == ''){
        formValido = false;
        alerta = 'Preencha a Senha!';
    } else if (repitaSenhaRegister !== senhaRegister){
        formValido = false;
        alerta = 'Senhas diferentes!'
    }

    //se o form não for válido, mostra o erro
    if(formValido === false){
        setMessage ('messageDivRegister', alerta, 'erro');
        return;
    } else {
        //se for válido, garante que o quadro de mensagem não aparecerá agora.
        setMessage ('messageDivRegister', '', 'hide');
    }

    //crio um objeto form para colocar os dados
    let form = new FormData();
    form.set('nome',nomeRegister);
    form.set('senha',senhaRegister);
    form.set('repitaSenha', repitaSenhaRegister);

    //dispara request post para o login.php com os dados do form
    fetch('login/register.php', {
        method: 'POST',
        headers: new Headers(),
        mode: 'cors',
        cache: 'default',
        body: form
    }).then(function(response) { //quando terminar a request...
        response.json().then(function(dados) { //converte pra json. quando terminar de converter...
            if(response.ok) {
                //se deu bom, eu boto a mensagem de sucesso e apago o conteudo do form html
                setMessage ('messageDivRegister', dados.body, 'sucesso');
                document.getElementById("formRegister").reset();
            } else {
                //se deu ruim, eu boto a mensagem de erro, mas deixo o conteudo pro usuario ajustar e mandar de novo.
                setMessage ('messageDivRegister', dados.body, 'erro');
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