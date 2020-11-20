<?php

class FunctionalTester
{

    private $testName;

    public function __set($name, $value)
    {
        $this->$name = $value;
        return $this;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function setPHPSessionCookie () {
        $url = 'http://localhost:8000/';
        $result = $this->sendRequest($url, [], 'get', true);
    }

    public function prepareDatabase(){
        $this->eraseDatabase();
    }

    /**
     * @param string $url
     * @param mixed $body
     * Dispara uma requisição via curl retornando os dados e o código http. Caso o resultado seja JSON, também converte e retorna os dados.
     */
    public function sendRequest ($url, $body, $method = 'get', $storeReceivedCookies = false) {
        
        $encodedFields = http_build_query($body);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $encodedFields);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HEADER, true);

        if($method == 'post') {
            curl_setopt($ch,CURLOPT_POST, true);
        }

        if($storeReceivedCookies){
            if(file_exists('tmp/cookiejar.txt')){
                unlink("tmp/cookiejar.txt");
            }
            curl_setopt($ch, CURLOPT_COOKIEJAR, 'tmp/cookiejar.txt');
        } else {
            curl_setopt($ch, CURLOPT_COOKIEFILE, 'tmp/cookiejar.txt');
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
    public function eraseDatabase () {
        $this->truncateTable('movimentos');
        $this->truncateTable('usuarios');
    }

    /**
     * @param string $tableName nome da tabela da qual se deseja apagar todos os registros.
     * Função que apaga todos os registros da tabela.
     */
    public function truncateTable ($tableName) {
        $pdo = dbConnect();
        $sql = "DELETE from $tableName;";
        $result = $pdo->exec($sql);
    }

    public function haveInDatabase($tableName, $data) {
        $pdo = dbConnect();
        $columns = '';
        $values = '';
        $i = 0;
        foreach ($data as $campo => $valor) {
            if($i > 0) {
                $columns .= ", ";
                $values .= ", ";
            }
            $columns .= "$campo";
            $values .= ":$campo";
            $i++;
        }

        $sql = "INSERT INTO $tableName ($columns) values ($values);";
        $statement = $pdo->prepare($sql);

        foreach ($data as $campo => $valor) {
            $statement->bindValue(":$campo", $valor, PDO::PARAM_STR);
        }

        $result = $statement->execute();

        return $data;
    }

    /**
     * @param string $table nome da tabela.
     * @param mixed $data array chave-valor de coluna-valor para se verificar no banco.
     * Verifica se, na $table informada, existe um ou mais registros com as condições de coluna-valor em $data.
     * Caso negativo, exibe mensagem de erro.
     */
    public function existsInDatabase ($table, $data)
    {
        $result = $this->getListFromDatabase($table,$data);

        if(count($result) < 1){
            echo "\n x $this->testName";
            echo "\nValor \n".var_export($data,true)."\n não existe no banco de dados.\n";
        }
    }

    public function getListFromDatabase($table, $data)
    {
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
            if(is_float($valor)){
                $valor = number_format($valor, 2, '.' , '');
            }
            $statement->bindValue(":$campo", $valor, PDO::PARAM_STR);
        }

        $result = $statement->execute();
        $list = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $list;
    }

    /**
     * @param string $value1
     * @param string $value2
     * Verifica se value1 é igual a value2. Caso negativo, exibe mensagem de erro.
     */
    public function assertEquals ($value1 , $value2) {
        if($value1 !== $value2){
            echo "\n x $this->testName";
            echo "\n Falha ao verificar que \n".var_export($value1,true)."\n é igual a \n".var_export($value2,true).".\n\n";
        }
    }

    public function haveInDatabaseUsuario()
    {
        $usuario = [
            'nome' => 'nomeTeste',
            'senha' => '123456'
        ];

        // $usuario = haveInDatabase('usuarios', $data); //não se pode fazer assim pois o register.php também criptografa a senha
        $url = 'http://localhost:8000/login/register.php';
        $result = $this->sendRequest($url, ['nome' => $usuario['nome'], 'senha' => $usuario['senha'], 'repitaSenha' => $usuario['senha']]);


        //fazer uma consulta para pegar o ID do usuario
        $usuarios = $this->getListFromDatabase('usuarios', ['nome' => 'nomeTeste']);
        $usuario['id'] = $usuarios[0]['id'];
        
        return $usuario;
    }


    public function haveInDatabaseMovimento($data)
    {
        $movimento = [
            'tipo' => 'despesa',
            'descricao' => 'minha despesa',
            'valor' => 1000.00,
            'datahoramovimento' => date('Y-m-d H:i:s'),
            'usuario_id' => $data['usuario_id']
        ];

        $movimento = $this->haveInDatabase('movimentos', $movimento);
        return $movimento;
    }

}