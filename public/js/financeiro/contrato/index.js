$(function(){
	
	
	$('#ds_valor').priceFormat();
	
	$('#co_pessoa').change( function(){
		$('#tp_pessoa').val( $( "#co_pessoa option:selected" ).attr('tp_pessoa') );
	});
	
	$('#frmContrato').attr('action', sistemaUrl + 'administracao/contrato/salvar' );
	$('#frmFormaPagamento').attr('action', sistemaUrl + 'administracao/contrato/salvar-forma-pagamento' );
	
	gerarContrato = function( co_contrato ){
		location.href = sistemaUrl + 'financeiro/contrato/dados-contrato/co_contrato/' + base64_encode( co_contrato );
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