<?php

//passo 1: ter um login

//passo 2: ter uma sessão válida

//passo 3: enviar um form de transaction para o store.php

//passo 4: verificar a resposta do teste

//passo 5: desfazer do banco as alterações feitas

//passo 6: deslogar

require_once 'utils.php';
require_once 'tests/tests.php';

setPHPSessionCookie();
runTest('testRegister');
runTest('testLogin');

function setPHPSessionCookie () {
    $url = 'http://localhost:8000/';
    $result = sendRequest($url, [], 'get', true);
}


/**
 * @param string $url
 * @param mixed $body
 * Dispara uma requisição via curl retornando os dados e o código http. Caso o resultado seja JSON, também converte e retorna os dados.
 */
function sendRequest ($url, $body, $method = 'get', $acceptCookies = false) {
    
    $encodedFields = http_build_query($body);
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $encodedFields);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HEADER, true);

    if($acceptCookies){
        if(file_exists('cookiejar.txt')){
            unlink("cookiejar.txt");
        }
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookiejar.txt');
    } else {
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookiejar.txt');
    }
    
    if($method == 'post') {
        curl_setopt($ch,CURLOPT_POST, true);
    }
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    if(strpos($info['content_type'], 'application/json') !== false){
        $data = json_decode($result, true);
        $result = isset($data['body']) ? $data['body'] : $data;
    }
    return ['http_code' => $info['http_code'], 'body' => $result];
}

/**
 * Rotina para apagar as tabelas do banco de dados após os testes
 */
function eraseDatabase () {
    truncateTable('movimentos');
    truncateTable('usuarios');
}

/**
 * @param string $tableName nome da tabela da qual se deseja apagar todos os registros.
 * Função que apaga todos os registros da tabela.
 */
function truncateTable ($tableName) {
    $pdo = dbConnect();
    $sql = "DELETE from $tableName;";
    $result = $pdo->exec($sql);
}

/**
 * Dado o nome da função-teste, executa a mesma e limpa o banco logo após.
 */
function runTest($testName) {
    $testName();
    eraseDatabase();
}


/**
 * @param string $table nome da tabela.
 * @param mixed $data array chave-valor de coluna-valor para se verificar no banco.
 * Verifica se, na $table informada, existe um ou mais registros com as condições de coluna-valor em $data.
 * Caso negativo, exibe mensagem de erro.
 */
function existsInDatabase ($table, $data) {
    $pdo = dbConnect();

    $where = ' WHERE';
    $i = 0;
    foreach ($data as $campo => $valor) {
        if($i > 0) {
            $where .= " and";
        }
        $where .= " $campo = :$campo";
        $i++;
    }
    $where .= ";";

    $sql = "SELECT * FROM $table $where";
    $statement = $pdo->prepare($sql);

    foreach ($data as $campo => $valor) {
        $statement->bindParam(":$campo", $valor, PDO::PARAM_STR);
    }

    $result = $statement->execute();
    $list = $statement->fetchAll(PDO::FETCH_ASSOC);

    if(count($list) < 1){
        echo "\n x Erro no teste! Valor ".var_export($data)." não existe no banco de dados.\n";
    }
}

/**
 * @param string $value1
 * @param string $value2
 * Verifica se value1 é igual a value2. Caso negativo, exibe mensagem de erro.
 */
function assertEquals ($value1 , $value2) {
    if($value1 !== $value2){
        echo "\n x Erro no teste! Falha ao verificar que ".var_export($value1,true)." é igual a ".var_export($value2,true).".\n\n";
    }
}
