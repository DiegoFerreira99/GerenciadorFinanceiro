<?php

class MovimentosCest
{

    /**
     * Testa a criação de um novo usuário.
     */
    public function testIndexMovimentos(\FunctionalTester $functionalTester)
    {
        $usuario = $functionalTester->haveInDatabaseUsuario();
        $movimento = $functionalTester->haveInDatabaseMovimento(['usuario_id' => $usuario['id']]);
        
        $url = 'http://localhost:8000/login/login.php';
        $result = $functionalTester->sendRequest($url, ['nome' => $usuario['nome'], 'senha' => $usuario['senha']], true);
        $functionalTester->assertEquals($result['http_code'], 200);


        $url = 'http://localhost:8000/transaction/index.php';
        $result = $functionalTester->sendRequest($url, []);
        $functionalTester->assertEquals($result['http_code'], 200);


        $movimentoBanco = $functionalTester->getListFromDatabase('movimentos', $movimento);
        var_dump($movimentoBanco);
        $functionalTester->existsInDatabase('movimentos', $movimento);


        $url = 'http://localhost:8000/login/logout.php';
        $result = $functionalTester->sendRequest($url,[]);
        $functionalTester->assertEquals($result['http_code'], 200);
    }

   /* public function testLogin (\FunctionalTester $functionalTester)
    {
        $usuario = $functionalTester->haveInDatabaseUsuario();

        $url = 'http://localhost:8000/login/login.php';
        $result = $functionalTester->sendRequest($url, ['nome' => $usuario['nome'], 'senha' => $usuario['senha']], true);
        $functionalTester->assertEquals($result['http_code'], 200);

        $url = 'http://localhost:8000/transaction/index.php';
        $result = $functionalTester->sendRequest($url, []);
        $functionalTester->assertEquals($result['http_code'], 200);
    }
*/
}
