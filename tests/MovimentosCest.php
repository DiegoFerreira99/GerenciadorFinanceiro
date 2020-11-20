<?php

class MovimentosCest
{

    /**
     * Testa a criação de um novo usuário.
     */
    public function testIndexMovimentos(\FunctionalTester $functionalTester)
    {
        //prepara usuario e movimento no banco
        $usuario = $functionalTester->haveInDatabaseUsuario();
        $movimento = $functionalTester->haveInDatabaseMovimento(['usuario_id' => $usuario['id']]);
        
        //loga com usuario para consultar os movimentos
        $url = 'http://localhost:8000/login/login.php';
        $result = $functionalTester->sendRequest($url, ['nome' => $usuario['nome'], 'senha' => $usuario['senha']], true);
        $functionalTester->assertEquals($result['http_code'], 200);

        //consulta os movimentos
        $url = 'http://localhost:8000/transaction/index.php';
        $result = $functionalTester->sendRequest($url, []);

        //checa se deu 200 ok
        $functionalTester->assertEquals($result['http_code'], 200);

        //exibe o movimento do banco
        $movimentoBanco = $functionalTester->getListFromDatabase('movimentos', $movimento);

        //checa se o movimento está no banco
        $functionalTester->existsInDatabase('movimentos', $movimento);

        //desloga
        $url = 'http://localhost:8000/login/logout.php';
        $result = $functionalTester->sendRequest($url,[]);
        $functionalTester->assertEquals($result['http_code'], 200);
    }
}
