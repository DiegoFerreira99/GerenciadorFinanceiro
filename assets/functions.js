
/**
 * @var string idElemento - Id do elemento onde se quer inserir a mensagem
 * @var string alerta - mensagem que deseja exibir
 * @var string tipo - tipo de mensagem que altera o estilo do elemento, conforme abaixo:
 * sucesso - classe successMessage - verde
 * erro - classe errorMessage - vermelho
 * hide - deixa sem classes adicionais e sem mensagem.
 */
function setMessage (idElemento, alerta, tipo) {
    let messageDiv = document.getElementById(idElemento);
    messageDiv.classList.remove('successMessage');
    messageDiv.classList.remove('errorMessage');
    messageDiv.innerHTML = alerta;
    if(tipo === 'sucesso') {
        messageDiv.classList.add('successMessage');
    } else if(tipo === 'erro') {
        messageDiv.classList.add('errorMessage');
    } else if (tipo === 'hide'){
        messageDiv.innerHTML = undefined;
    }
}