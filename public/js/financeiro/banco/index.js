$(function(){
	
	$('#ds_saldo').priceFormat();
	$('#ds_limite').priceFormat();
	
	$("#table").tablecloth({
		theme: "stats",
		bordered: true,
		condensed: true,
		striped: true,
		sortable: true,
		clean: true,
	});
	
	$('#frmCadastro').attr('action', sistemaUrl + 'financeiro/banco/salvar' );
	
	editar = function( co_banco ){
		$.ajax({
			url: sistemaUrl+'financeiro/banco/editar',
			type: "POST",
			data: {
				co_banco : co_banco
			},
			dataType: "json",
			success: function(data){
				if( data.co_banco ){
					$('#modalCadastro').modal('show');
					$('#co_banco').val( data.co_banco);
					$('#ds_banco').val( data.ds_banco);
					$('#ds_saldo').val( data.ds_saldo);
					$('#ds_limite').val( data.ds_limite);
				}
			}
			
		});
	}
	
	deletar = function(){
		location.href = sistemaUrl + 'financeiro/banco/deletar/co_banco/' + $('#co_modal').val();
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