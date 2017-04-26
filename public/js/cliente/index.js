/* Função acessar */
function acessar() {
	if($("#ds_email").val() == "") {
		alerta("Por favor informe o e-mail.",$("#ds_email"));
		return false;
	}
	
	if($("#co_senha").val() == "") {
		alerta("Por favor informe a senha.",$("#co_senha"));
		return false;
	}
	document.FormAcesso.submit();
}

$(function(){
	
	$('#abrirChamado').click(function(){
		location.href = sistemaUrl + 'chamados/abrir';
	});
	
	$('#categoria').change(function(){
		$('#data').val('');
		$('#formChamado').submit();
	});
	
	$('#status').change(function(){
		$('#data').val('');
		$('#formChamado').submit();
	});
	
	$('#provedor').change(function(){
		$('#data').val('');
		$('#formChamado').submit();
	});
	
	$("#table").tablecloth({
		theme: "stats",
		bordered: true,
		condensed: true,
		striped: true,
		sortable: true,
		clean: true,
	});
	
	editar = function( id_chamado ){
		location.href = sistemaUrl + 'chamados/editar/id_chamado/' + id_chamado;
	}
	
	$('#slaVencido').click( function(){
		$('#status').val('1');
		$('#data').val(data);
		$('#formChamado').submit();
	});
	
	abrirChamado = function( id_cliente, id_provedor ){
		location.href = sistemaUrl + 'chamados/abrir/provedor/'+ id_provedor +'/clientes/' + id_cliente;
	}
	
	$('#novoRegistro').click( function(){
		$('#modalCadastro').modal('show');
	});
	
//	$('#btnSalvar').click( function(){
//		$.ajax({
//			url: sistemaUrl+'autenticacao/login',
//			type: "POST",
//			data: {
//				ds_usuario : $('#ds_usuario').val(),
//				ds_senha : $('#ds_senha').val()
//			},
//			dataType: "json",
//			success: function(data){
//				if(data.msg != 'ok'){
//					alerta("Esse CNPJ já existe, por este motivo não será possível concluir o cadastro!");
//					return false;
//				}
//			}
//			
//		});
//	});
})