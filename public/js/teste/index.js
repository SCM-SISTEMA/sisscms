/* Função acessar */
function acessar() {
	if($("#nu_cpf").val() == "") {
		alerta("Por favor informe o CPF.",$("#nu_cpf"));
		return false;
	}
	
	if($("#co_senha").val() == "") {
		alerta("Por favor informe a senha.",$("#co_senha"));
		return false;
	}
	//validacao de cpf RN006
	if(!validarCPF($('#nu_cpf').val())){
		alerta('CPF Inválido.',$('#nu_cpf'));
		return false;
	}
	
	$('#FormAcesso').attr('action',sistemaUrl+'autenticacao');
	document.FormAcesso.submit();
}