$(function(){
	$("#btnSalvar").click(function(){
		$("#FormUsuario").attr('method','POST');
		$("#FormUsuario").submit();
	});
	
	$("#btnCancelar").click(function(){
		location.href = sistemaUrl + 'usuario/manter';
	});
})