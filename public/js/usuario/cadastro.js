$(function(){
	$("#btnSalvar").click(function(){
		if($("#nu_cpf").val() == "" || $("#no_usuario").val() == "" || $("#co_perfil").val() == "" || $("#ds_email").val() == "") {
			alerta("Campos obrigatórios não preenchidos!");
			return false;
		}
		
		if(!$('input[name="st_registro_ativo"]').is(':checked')){
			alerta("Campos obrigatórios não preenchidos !");
			return false;
		}
		
		$("#FormUsuario").attr('method','POST');
		$("#FormUsuario").submit();
	});
	
	$("#btnVoltar").click(function(){
		location.href = sistemaUrl + 'usuario/manter';
	});
})