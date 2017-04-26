$(function(){
	//limitando observacao
	$('#obs').limit(1000,'#qtd_obs');
	
	$('#btVoltar').click(function(){
		location.href=sistemaUrl+'inscricao/consultar';
	});
	
	$('#btSalvar').click(function(){
		$('#frmSalvarObs').submit();
	});
});