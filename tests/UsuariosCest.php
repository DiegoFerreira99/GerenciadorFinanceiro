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

        $functionalTester->login($usuario['nome'], $usuario['senha']);

        $url = 'http://localhost:8000/transaction/index.php';
        $result = $functionalTester->sendRequest($url, []);
        $functionalTester->assertEquals($result['http_code'], 200);

        $functionalTester->logout();

    }

    public function testLogout (\FunctionalTester $functionalTester) {
        
        $usuario = $functionalTester->haveInDatabaseUsuario();

        $functionalTester->login($usuario['nome'], $usuario['senha']);
        
        $functionalTester->logout();

        $url = 'http://localhost:8000/transaction/index.php';
        $result = $functionalTester->sendRequest($url, []);
        $functionalTester->assertEquals($result['http_code'], 401);
        
    }

}
