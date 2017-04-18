$(function(){
	$('#tab-alterar_dados').tabs();
	
	//DADOS PESSOAIS
	
	//necessidades especiais
	if($('input[name="n_especiais"]:checked').val() != 1){
		$('#div_necessidades_especiais').hide();
	}
	
	$('input[name="n_especiais"]').click(function(){ necessidadesEspeciais($(this))});
	
	
	//carregando combo cidade
	carregaCidade($('#cidade').val());
	
	//FIM DADOS PESSOAIS
	
});


$(function(){
	//FORMACAO
	
	//curso
	carregaCurso($('#escolaridade').val());
	montaSemestreAno($('#escolaridade'));
	if($('#escolaridade').val() == 54 || $('#escolaridade').val() == 21){
		//superior ou tecnico coloca obrigatoriedade no campo
		$('#curso').attr('selectRequerido2','true');
	}else{
		//eja ou medio esconde campo
		$('#div_curso').hide();
	}
	
	//parentes no MS
	if($('input[name="parentes"]:checked').val() != 1){
		$('.parens_MS').hide();
		//limpando campos e retirando obrigatoriedade
		$('#no_parente').val('').removeAttr('inputRequerido2');
		$('#setor_parente').val('').removeAttr('inputRequerido2');
		$('#fone_parente').val('').removeAttr('inputRequerido2');
	}else{
		//mostrando campos
		$('.parens_MS').show();
		//colocando obrigatoriedade
		$('#no_parente').attr('inputRequerido2','true');
		$('#setor_parente').attr('inputRequerido2','true');
		$('#fone_parente').attr('inputRequerido2','true');
	}
	
	//FIM FORMACAO
	
	//ACOES DOS BOTOES
	
	//botao alterar dados
	$('#btAlterarDados').click(function(){
		if(!validarDadosPessoais()){
			$( "#tab-alterar_dados" ).tabs({ selected: 'pessoal' });
			return false;
		}
		
		if(!validarFormacao()){
			$( "#tab-alterar_dados" ).tabs({ selected: 'formacao' });
			return false
		}
		
		$('#alterarDados').attr('action',sistemaUrl+'inscricao/salvar');
		$('#alterarDados').submit();
	});
	
	//botao visualizar mini-curriculo
	$('#btMiniCurriculo').click(function(){
		$('#alterarDados').append('<input type="hidden" name="gerarMiniCurriculo" value="1" />');
		$('#alterarDados').submit();
	});
	
	//botao sair
	$('#btSair').click(function(){
		location.href=sistemaUrl+"autenticacao/logout";
	});
	
	
	
});