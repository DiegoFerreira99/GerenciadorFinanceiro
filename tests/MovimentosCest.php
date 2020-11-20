<?php

class MovimentosCest
{

    /**
     * Testa a listagem de 3 movimentos pré-inseridos no banco.
     */
    public function testIndexMovimentos(\FunctionalTester $functionalTester)
    {
        //prepara usuario e movimento no banco
        $usuario = $functionalTester->haveInDatabaseUsuario();
        
        $movimentos = [];
        $movimentos[] = $functionalTester->haveInDatabaseMovimento(['usuario_id' => $usuario['id']]);
        $movimentos[] = $functionalTester->haveInDatabaseMovimento(['usuario_id' => $usuario['id']]);
        $movimentos[] = $functionalTester->haveInDatabaseMovimento(['usuario_id' => $usuario['id']]);
        
        //loga com usuario para consultar os movimentos
        $functionalTester->login($usuario['nome'], $usuario['senha']);

        //consulta os movimentos
        $url = 'http://localhost:8000/transaction/index.php';
        $result = $functionalTester->sendRequest($url, []);

        //checa se deu 200 ok
        $functionalTester->assertEquals($result['http_code'], 200);

        //verificar se o que veio na request result é o que está no array de movimentos.
        $functionalTester->assertEquals(count($movimentos), count($result['body']['movimentos']));

        //logout
        $functionalTester->logout();
    }

    /**
     * Testa a criação de um novo movimento.
     */
    public function testInsertMovimentoDespesa(\FunctionalTester $functionalTester)
    {
        //prepara usuario e movimento no banco
        $usuario = $functionalTester->haveInDatabaseUsuario();
        
        //loga com usuario para consultar os movimentos
        $functionalTester->login($usuario['nome'], $usuario['senha']);

        //consulta os movimentos
        $url = 'http://localhost:8000/transaction/store.php';
        $movimentoParaInserir = [
            'descricao' => 'uma despesa',
            'valor' => '500',
            'tipo' => 'Despesa',
            'datahoramovimento' => date('Y-m-d H:i:s'),
        ];
        $result = $functionalTester->sendRequest($url, $movimentoParaInserir);

        //checa se deu 200 ok
        $functionalTester->assertEquals($result['http_code'], 201);

        //exibe o movimento do banco
        $movimentoBanco = $functionalTester->getListFromDatabase('movimentos', []);

        //checa se o movimento está no banco
        $functionalTester->existsInDatabase('movimentos', $movimentoParaInserir);

        //desloga
        $functionalTester->logout();
    }

    public function testInsertMovimentoReceita(\FunctionalTester $functionalTester)
    {
        //prepara usuario e movimento no banco
        $usuario = $functionalTester->haveInDatabaseUsuario();
        
        //loga com usuario para consultar os movimentos
        $functionalTester->login($usuario['nome'], $usuario['senha']);

        //consulta os movimentos
        $url = 'http://localhost:8000/transaction/store.php';
        $movimentoParaInserir = [
            'descricao' => 'uma receita',
            'valor' => '500',
            'tipo' => 'Receita',
            'datahoramovimento' => date('Y-m-d H:i:s'),
        ];
        $result = $functionalTester->sendRequest($url, $movimentoParaInserir);

        //checa se deu 200 ok
        $functionalTester->assertEquals($result['http_code'], 201);

        //exibe o movimento do banco
        $movimentoBanco = $functionalTester->getListFromDatabase('movimentos', []);

        //checa se o movimento está no banco
        $functionalTester->existsInDatabase('movimentos', $movimentoParaInserir);

        //desloga
        $functionalTester->logout();
    }
}
