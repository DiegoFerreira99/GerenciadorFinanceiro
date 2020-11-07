<?php
require_once("../utils.php");
allowOnly('logged');

?>

<!DOCTYPE html>
<html lang="ptbr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Financeiro</title>
    <link rel="stylesheet" href="/assets/style.css">
    <script>
    
    function onload () {
        loadMovimentos();
    }

    function logout () {
        fetch('<?= path('login/logout.php'); ?>', {
            method: 'GET',
            headers: new Headers(),
            mode: 'cors',
            cache: 'default'
        }).then(function(response) { //quando terminar a request...
            response.json().then(function(dados) { //converte pra json. quando terminar de converter...
                if(response.ok) {
                    //se deu bom, eu boto a mensagem de sucesso e apago o conteudo do form html
                    window.location.href = "<?= path('index.php') ?>";
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

    function loadMovimentos () {
        //pega os dados do elemento form do html

        //dispara request post para o login.php com os dados do form
        fetch('<?= path('transaction/index.php'); ?>', {
            method: 'GET',
            headers: new Headers(),
            mode: 'cors',
            cache: 'default'
        }).then(function(response) { //quando terminar a request...
            response.json().then(function(dados) { //converte pra json. quando terminar de converter...
                if(response.ok) {
                    //se deu bom, eu boto a mensagem de sucesso e apago o conteudo do form html
                    showMovimentos(dados['body']);
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

    function showMovimentos (dados) {
        let tbody = document.getElementById("tbodyTabelaMovimentos");

        let linhas = '';
        for (let index = 0; index < dados.movimentos.length; index++) {
            const movimento = dados.movimentos[index];

            let string = `<tr>
                <td>${movimento.id}</td>
                <td>${movimento.descricao}</td>
                <td>${movimento.tipo}</td>
                <td>${movimento.valorLegivel}</td>
                <td>${movimento.saldoLegivel}</td>
                <td>${movimento.datahoramovimentoReadable}</td>
            </tr>`;
            linhas = `${linhas}${string}`;
        }
        tbody.innerHTML = linhas;
    }
    </script>
</head>
<body onload="onload()">
    <header>
        <h1>Gerenciador Financeiro</h1>
    </header>
    <nav>
        <button onclick="logout()">Logout</button>
        <br>
        <a class="btn" href="<?= path('transaction\create.php') ?>">Novo Movimento</a>
    </nav>
    <section>
        <h3>Movimentos</h3>

        <?php 
        // if(isset($_GET['success'])){
        //     echo "
        //     <div class='successMessage'>
        //         $_GET[success]
        //     </div>
        //     ";
        // }
        ?>
        Saldo Inicial: <?php #echo $saldoinicial;?>
        <div id="messageDiv"></div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Descricao</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Saldo</th>
                    <th>Data e Hora</th>
                </tr>
            </thead>
            <tbody id="tbodyTabelaMovimentos">
            </tbody>
        </table>
        Saldo Final: 
    </section>
    <footer>
        Feito por Diego e Rui
    </footer>
</body>
</html>