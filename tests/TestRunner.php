<?php

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

        $this->runTest('testRegister');
        $this->runTest('testLogin');
    }
    
    /**
     * Dado o nome da função-teste, executa a mesma e limpa o banco logo após.
     */
    public function runTest($testName) {
        $usuariosCest = new UsuariosCest();
        $usuariosCest->$testName($this->functionalTester);
        $this->functionalTester->eraseDatabase();
    }
}