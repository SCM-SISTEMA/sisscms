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
	
	editar = function( co_pessoa, tp_pessoa ){
		location.href = sistemaUrl + 'administracao/clientes/editar/' + base64_encode('co_pessoa') + '/' + base64_encode(co_pessoa) + '/' + base64_encode('tp_pessoa') + '/' + base64_encode(tp_pessoa);
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
		location.href = sistemaUrl + 'administracao/clientes/cadastro/';
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