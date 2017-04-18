$(function(){
	$('#chamados').dataTable();
	
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
	
	
	$('#slaVencido').click( function(){
		$('#status').val('1');
		$('#data').val(data);
		$('#formChamado').submit();
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