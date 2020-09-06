<?php
require_once("utils.php");


$saldoinicial = 5000;
$saldofinal = $saldoinicial;

// pegar do banco
$pdo = dbConnect();

$sql = 'select * from transaction order by id asc;';
$statement = $pdo->prepare($sql);
// $statement->bindParam(":tipo", $tipo, PDO::PARAM_STR);
// $statement->bindParam(":descricao", $descricao, PDO::PARAM_STR);
// $statement->bindParam(":valor", $valor, PDO::PARAM_STR);

$resultado = $statement->execute();

$movimentos = $statement->fetchAll(PDO::FETCH_ASSOC);

// var_export($movimentos);
// dump($movimentos);

// para cada registro, itero sobre ele
for ($i=0; $i < count($movimentos); $i++) { 
    //acumulando valor para o saldo
    $saldofinal = $saldofinal - $movimentos[$i]['valor'];
    $movimentos[$i]['saldo']=$saldofinal;

    //convertendo a data para uma data legível
    $movimentos[$i]['datahoramovimentoReadable'] = converteData($movimentos[$i]['datahoramovimento']);

    //converter o valor para numero com virgula
    $movimentos[$i]['valorLegivel'] = converteSaldo($movimentos[$i]['valor']);

    //converter o saldo para numero com virgula
    $movimentos[$i]['saldoLegivel'] = converteSaldo($movimentos[$i]['saldo']);

}

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
        <a class="btn" href="<?= path('transaction\create.php') ?>">Novo Movimento</a>
    </nav>
    <section>
        <h3>Movimentos</h3>

        <?php if(isset($_GET['success'])){
            echo "
            <div class='successMessage'>
                $_GET[success]
            </div>
            ";
        } ?>
        

        Saldo Inicial: <?php echo $saldoinicial;?>

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
            <tbody>
                <?php 
                for ($i=0; $i < count($movimentos); $i++) { 
                    $movimento = $movimentos[$i];
                    echo "<tr>
                        <td>$movimento[id]</td>
                        <td>$movimento[descricao]</td>
                        <td>$movimento[tipo]</td>
                        <td>$movimento[valorLegivel]</td>
                        <td>$movimento[saldoLegivel]</td>
                        <td>$movimento[datahoramovimentoReadable]</td>
                    </tr>";
                } ?>
            </tbody>
        </table>
        Saldo Final: <?= $saldofinal ?>
    </section>
    <footer>
        Feito por Diego e Rui
    </footer>
</body>
</html>