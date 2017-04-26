$(function(){
	
	$("#table").tablecloth({
		theme: "stats",
		bordered: true,
		condensed: true,
		striped: true,
		sortable: true,
		clean: true,
	});
	
	$('#frmCadastro').attr('action', sistemaUrl + 'administracao/status/salvar' );
	
	editar = function( co_status ){
		$.ajax({
			url: sistemaUrl+'administracao/status/editar',
			type: "POST",
			data: {
				co_status : co_status
			},
			dataType: "json",
			success: function(data){
				if( data.co_status ){
					$('#modalCadastro').modal('show');
					
					$('#co_status').val( data.co_status);
					$('#ds_status').val( data.ds_status);
					
				}
			}
			
		});
	}
	
	deletar = function(){
		location.href = sistemaUrl + 'administracao/status/deletar/co_status/' + $('#co_modal').val();
	}
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