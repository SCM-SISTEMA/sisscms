$(function(){
	$("#nu_cpf").mask("999.999.999-99");
});

/* Salvar formulário */
function salvar() {
	if($("#nu_cpf").val() == "") {
		alerta("Por favor informe o cpf.",$("#nu_cpf"));
		return false;
	}
	if($("#no_usuario").val() == "") {
		alerta("Por favor informe o nome.",$("#no_usuario"));
		return false;
	}
	if($("#ds_email").val() == "") {
		alerta("Por favor informe o email.",$("#ds_email"));
		return false;
	}
	
	document.FormUsuario.submit();
}