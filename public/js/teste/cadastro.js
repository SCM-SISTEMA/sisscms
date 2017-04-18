//DADOS PESSOAIS
$(function(){
	
	//necessidades especiais
	$('#div_necessidades_especiais').hide();
	$('input[name="n_especiais"]').click(function(){
		necessidadesEspeciais($(this));
	});
	
	//carregando combo cidade
	carregaCidade(0);
	
	
	$('#validar').click(function(){
		if(validarDadosPessoais()){
			//esconde dados pessoais
			$('#pessoal').hide();
			//mostra formacao
			$('#formacao').show();
		}
	});
});
//FIM DADOS PESSOAIS


//FORMACAO
$(function(){
	//carregando semestre (inicialmente)
	montaSemestreAno($('#escolaridade'));
	
	//ocultando campo curso
	$('#div_curso').hide();

	//ocultando telefone - nome - setor do parente no MS
	$('.parens_MS').hide();
	
	//chamando funcao para validar dados pessoais ao clicar no botao avancar
	$('#validarFormacao').click(function(){
		if(validarFormacao()){
			$('#frmNovoEstagiario').submit();
		}
	});
});
//FIM FORMACAO