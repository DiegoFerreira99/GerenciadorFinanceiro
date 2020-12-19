<?php

//passo 1: ter um login

//passo 2: ter uma sessão válida

//passo 3: enviar um form de transaction para o store.php

//passo 4: verificar a resposta do teste

//passo 5: desfazer do banco as alterações feitas

//passo 6: deslogar

require_once 'utils.php';
require_once 'tests/TestRunner.php';

$testRunner = new TestRunner();
$testRunner->run(
    isset($argv[1]) ? $argv[1] : null,
    isset($argv[2]) ? $argv[2] : null
                );
