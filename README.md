# GerenciadorFinanceiro
## tarefas

## bugs
nenhum.

## funcoes gerais da aplicação
- [x] login
- [x] logout
- [x] register
- [x] password hash
- [x] lista de movimentos
- [x] separação de movimentos por usuário
- [ ] N- converter para framework
- [ ] N- testes unitarios
- [ ] N- testes front end (e2e/selenium/cypress)
- [x] allow user para back-end api

## tester
- [x] functionalTester login
- [x] functionalTester logout
- [ ] TestRunner->run() aceitar argumentos classe e metodo

## testes funcionais
- [x] login
- [x] register
- [x] index de movimentos
- [ ] get de movimento
- [x] criação de movimentos (despesa)
- [ ] criação de movimentos (receita)

## testes funcionais negativos
- [ ] não faz login com nome errado
- [ ] não faz login com senha errada
- [ ] não faz register sem nome
- [ ] não faz register sem senha
- [ ] não faz register sem senha repetida
- [ ] não faz register sem senha igual
- [ ] não cria movimentos se não tem descricao
- [ ] não cria movimentos se não tem valor
- [ ] não cria movimentos se não tem data
- [ ] não cria movimentos se não tem tipo

## testes funcionais permissões
- [ ] não pode fazer login
- [ ] não pode se registrar
- [ ] não pode listar movimentos
- [ ] não pode criar movimento

## Permissões
- [ ] tela de criação de transaciton (somente auth)

## Na criação
- [x] data do movimento
- [x] Tipo do movimento eg. despesa/receita
- [x] descrição - descritivo do que foi comprado/vendido
- [x] valor - valor da movimentação

- [ ] regra de data 1: se a data estiver para o futuro, não é considerada efetuada
- [ ] regra de data 2: se a data for hoje ou no passado, é considerada efetuada

- [ ] Parcelado pessoal - se o movimento deve ter seu valor dividido em X vezes e criado, pelos proximos y meses. (ex. compra de notebook em 10x de 300 reais), Deve ser informado a data padrão do pagamento de parcela.

- [ ] Parcelado cartão (na tela de cartão) - se o movimento deve ter seu valor dividido em X vezes e criado, pelos proximos y meses. (ex. compra de notebook em 10x de 300 reais). deve ser informado o cartão usado no parcelamento. esse movimento tem data de ocorrencia e data de vencimento que é a mesma data do cartão (Ex. dia 9 vence meu cartão);

- [ ] recorrência - se o movimento deve ser criado com mesmo valor (sem ser efetuado) todos os meses (ex. netflix, spotify). Deve inserir o dia padrão do movimento, e deve ter uma data fim obrigatória. Deve disparar aviso quando a recorrencia estiver à 3 meses ou menos do fim, afim de provocar recadastramento da recorrencia.

- [ ] categoria - alimentação, eletronicos, farmacia, higiene, médico, contas de casa, etc; Obs: Pode ser usado para criar um gráfico de pizza por exemplo

- [ ] conta - conta na qual foi feita a transação (itau, dinheiro vivo, nubank, inter, etc.) Obs: Vai ajudar a definir a composição do saldo

- [ ] origem do movimento: data na qual foi feita emissão de nota fiscal, quando aplicado. deve ser igual a data padrão quando não for aplicável. desenvolver somente quando ver que vai ser util;

## Informação da conta
- [ ] Saldo - saldo resultante da conta (somente exibição) antes de efetuar a criação

## outras funcionalidades
- [ ] Criar transferência entre contas

## operações crud básico
- [ ] update
- [ ] delete
