<?php

/**
 * Testa a criação de um novo usuário.
 */
function testRegister()
{
    $nome = 'nomeTeste';
    $senha = '123456';
    $repitaSenha = '123456';
    $url = 'http://localhost:8000/login/register.php';
    
    $result = sendRequest($url, ['nome' => $nome, 'senha' => $senha, 'repitaSenha' => $repitaSenha]);

    assertEquals($result['http_code'], 200);
    existsInDatabase('usuarios', ['nome' => $nome]);
}

function testLogin()
{

    $nome = 'nomeTeste';
    $senha = '123456';
    $repitaSenha = '123456';
    $url = 'http://localhost:8000/login/register.php';
    $result = sendRequest($url, ['nome' => $nome, 'senha' => $senha, 'repitaSenha' => $repitaSenha]);

    $nome = 'nomeTeste';
    $senha = '123456';
    $url = 'http://localhost:8000/login/login.php';
    $result = sendRequest($url, ['nome' => $nome, 'senha' => $senha], true);
    assertEquals($result['http_code'], 200);
    $url = 'http://localhost:8000/transaction/index.php';
    $result = sendRequest($url, []);
    assertEquals($result['http_code'], 200);
}
