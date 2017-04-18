$(function(){
	
	$('#btnImprimir').click( function(){
	    $('#conteudo').printElement();		
	});	
	
	$('#dados').click( function(){
		$('.nav li').removeClass();
		$(this).addClass('active');
	});
	$('#parcelas').click( function(){
		$('.nav li').removeClass();
		$(this).addClass('active');
	});
	
	gerarBoleto = function( co_contrato_parcela ){
		window.open( sistemaUrl + 'financeiro/contrato/gerar-boleto/co_contrato_parcela/' + base64_encode( co_contrato_parcela ) );
	}
})