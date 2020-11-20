<?php

class UsuariosCest
{

    /**
     * Testa a criação de um novo usuário.
     */
    public function testRegister(\FunctionalTester $functionalTester)
    {
        $nome = 'nomeTeste';
        $senha = '123456';
        $repitaSenha = '123456';
        $url = 'http://localhost:8000/login/register.php';
        
        $result = $functionalTester->sendRequest($url, ['nome' => $nome, 'senha' => $senha, 'repitaSenha' => $repitaSenha]);

        $functionalTester->assertEquals($result['http_code'], 200);
        $functionalTester->existsInDatabase('usuarios', ['nome' => $nome]);
    }

    public function testLogin (\FunctionalTester $functionalTester)
    {
        $usuario = $functionalTester->haveInDatabaseUsuario();

        $url = 'http://localhost:8000/login/login.php';
        $result = $functionalTester->sendRequest($url, ['nome' => $usuario['nome'], 'senha' => $usuario['senha']], true);
        $functionalTester->assertEquals($result['http_code'], 200);

        $url = 'http://localhost:8000/transaction/index.php';
        $result = $functionalTester->sendRequest($url, []);
        $functionalTester->assertEquals($result['http_code'], 200);

        $url = 'http://localhost:8000/login/logout.php';
        $result = $functionalTester->sendRequest($url,[]);
        $functionalTester->assertEquals($result['http_code'], 200);

    }

    public function testLogout (\FunctionalTester $functionalTester) {
        
        $usuario = $functionalTester->haveInDatabaseUsuario();

        $url = 'http://localhost:8000/login/login.php';
        $result = $functionalTester->sendRequest($url, ['nome' => $usuario['nome'], 'senha' => $usuario['senha']], true);
        $functionalTester->assertEquals($result['http_code'], 200);

        $url = 'http://localhost:8000/login/logout.php';
        $result = $functionalTester->sendRequest($url,[]);
        $functionalTester->assertEquals($result['http_code'], 200);

        $url = 'http://localhost:8000/transaction/index.php';
        $result = $functionalTester->sendRequest($url, []);
        $functionalTester->assertEquals($result['http_code'], 401);
        
    }

}
