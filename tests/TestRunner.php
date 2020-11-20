<?php

require_once 'tests/FunctionalTester.php';

class TestRunner
{

    private FunctionalTester $functionalTester;

    public function __construct()
    {
        $this->functionalTester = new FunctionalTester();
    }

    public function run()
    {
        $this->functionalTester->setPHPSessionCookie();
        $this->functionalTester->prepareDatabase();

        $this->runTest('UsuariosCest','testRegister');
        $this->runTest('UsuariosCest','testLogin');
        $this->runTest('UsuariosCest','testLogout');
        $this->runTest('MovimentosCest','testIndexMovimentos');
    }
    
    /**
     * Dado o nome da função-teste, executa a mesma e limpa o banco logo após.
     */
    public function runTest($className,$testName) {
        require_once "tests/$className.php";
        $objectCest = new $className();
        $this->functionalTester->testName = "$className::$testName";
        $objectCest->$testName($this->functionalTester);
        $this->functionalTester->eraseDatabase();
    }
}