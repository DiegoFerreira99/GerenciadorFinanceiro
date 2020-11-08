<?php
require_once('../utils.php');
allowOnly('logged');
?>

<!DOCTYPE html>
<html lang="ptbr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Financeiro</title>
    <link rel="stylesheet" href="<?= path('assets/style.css') ?>">
    <script src="<?= path('assets/functions.js') ?>"></script>
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
        
        <form id="formNovoMovimento">
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
            <div id="messageDiv"></div>
        </form>

    </section>

<script>
function transactionStore () {

    //pega os dados do elemento form do html
    let tipo = document.querySelector('input[name="tipo"]:checked')
    if(tipo != null){
        tipo = tipo.value;
    }
    let descricao = document.getElementById("descricao").value;
    let valor = document.getElementById("valor").value;
    let datahoramovimento = document.getElementById("datahoramovimento").value;

    //para cada dado, se não atende às especificações, seta uma mensagem de erro e invalida o form.
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
    form.set('tipo',tipo);
    form.set('descricao',descricao);
    form.set('valor',valor);
    form.set('datahoramovimento',datahoramovimento);

    //dispara request post para o store.php com os dados do form
    fetch('store.php', {
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
                document.getElementById("formNovoMovimento").reset();
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