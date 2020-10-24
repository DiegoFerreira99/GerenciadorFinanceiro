<?php
require_once('../utils.php');
?>

<!DOCTYPE html>
<html lang="ptbr">
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
        
        <form id="formNovoMovimento" method="post" action="<?= path('transaction\store.php') ?>">
            Tipo: <input id="despesa" type="radio" name="tipo" value="Despesa"> 
            <label for="despesa">Despesa<label>
            <input id="receita" type="radio" name="tipo" value="Receita">
            <label for="receita">Receita<label>
            <br><br>
            Descrição: <input type="text" id="descricao" placeholder="Digite a descrição aqui" name="descricao"> <br><br>
            Valor: <input type="text" id="valor" placeholder="Digite o valor aqui" name="valor"> <br><br>
            Data: <input type="date" id="datahoramovimento" placeholder="Coloque a data aqui" name="datahoramovimento"><br><br> 
        
            <a class="btn" href="<?= path('index.php') ?>">Voltar</a>
            <input class="btn" type="reset" value="Apagar">
            <button class="btn" type="button" onclick="transactionStore()">Enviar</button>&nbsp;
        </form>

        <div id="messageDiv"></div>

        <div id="result"></div>
    </section>

<script>
function transactionStore () {
    let tipo = document.querySelector('input[name="tipo"]:checked')
    if(tipo != null){
        tipo = tipo.value;
    }
    let descricao = document.getElementById("descricao").value;
    let valor = document.getElementById("valor").value;
    let datahoramovimento = document.getElementById("datahoramovimento").value;

    let formValido = true;
    let alerta = '';

    if(tipo == null){
        formValido = false;
        alerta = 'Selecione um tipo de movimento (despesa ou receita)!';
    } else if(descricao == null || descricao == ''){
        formValido = false;
        alerta = 'Escreva uma descrição para o seu movimento!';
    } else if(valor ==  null || valor <= 0 ){
        formValido = false;
        alerta = "Digite um valor válido para o seu movimento!";
    } else if(datahoramovimento == null || datahoramovimento == ''){
        formValido = false;
        alerta = "Coloque uma data para o seu movimento!";
    }
    
    if(formValido === false){
        setMessage ('messageDiv', alerta, 'erro');
        return;
    } else {
        setMessage ('messageDiv', '', 'hide');
    }

    let form = new FormData();
    form.set('tipo',tipo);
    form.set('descricao',descricao);
    form.set('valor',valor);
    form.set('datahoramovimento',datahoramovimento);

    fetch('store.php', {
        method: 'POST',
        headers: new Headers(),
        mode: 'cors',
        cache: 'default',
        body: form
    }).then(function(response) {
        response.json().then(function(dados) {
            if(response.ok) {
                setMessage ('messageDiv', dados.body, 'sucesso');
                document.getElementById("formNovoMovimento").reset();
            } else {
                setMessage ('messageDiv', dados.body, 'erro');
            }
        });
    }).catch(function (error){
        console.log('erro catch', error);
    });
}

function setMessage (idElemento, alerta, tipo) {
    let messageDiv = document.getElementById(idElemento);
    messageDiv.classList.remove('successMessage');
    messageDiv.classList.remove('errorMessage');
    messageDiv.innerHTML = alerta;
    if(tipo === 'sucesso') {
        messageDiv.classList.add('successMessage');
    } else if(tipo === 'erro') {
        messageDiv.classList.add('errorMessage');
    } else if (tipo === 'hide'){
        messageDiv.innerHTML = undefined;
    }
}
</script>
</body>
</html>