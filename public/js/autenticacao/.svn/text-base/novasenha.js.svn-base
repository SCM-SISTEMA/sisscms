$(function(){
	$("#nu_cpf").mask("999.999.999-99");
});

/* Nova senha */
function novasenha() {
	if($("#nu_cpf").val() == "") {
		alerta("Por favor informe o cpf.",$("#nu_cpf"));
		return false;
	}
	if($("#ds_email").val() == "") {
		alerta("Por favor informe o email.",$("#ds_email"));
		return false;
	}
	
	document.FormUsuario.submit();
}