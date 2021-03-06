<?php

require_once 'tests/FunctionalTester.php';

class TestRunner
{

    private FunctionalTester $functionalTester;

    public function __construct()
    {
        $this->functionalTester = new FunctionalTester();
    }

    public function run($class, $method)
    {
        $this->functionalTester->setPHPSessionCookie();
        $this->functionalTester->prepareDatabase();
        
        if($class == null && $method == null){
            $this->runTest('UsuariosCest','testRegister');
            $this->runTest('UsuariosCest','testLogin');
            $this->runTest('UsuariosCest','testLogout');
            $this->runTest('MovimentosCest','testIndexMovimentos');
            $this->runTest('MovimentosCest','testInsertMovimentoDespesa');
            $this->runTest('MovimentosCest','testInsertMovimentoReceita');
        }elseif($class == null || $method == null){ 
            echo "Quantidade de argumentos incorreto";                  
        }else{
            $this->runTest($class, $method);
        }


    }
    
    /**
     * Dado o nome da função-teste, executa a mesma e limpa o banco logo após.
     */
    public function runTest($className,$testName) {
        require_once "tests/$className.php";
        $objectCest = new $className();
        $this->functionalTester->testName = "$className::$testName";
        $objectCest->$testName($this->functionalTester);
        $this->functionalTester->showEndNotice();
        $this->functionalTester->eraseDatabase();
    }
}