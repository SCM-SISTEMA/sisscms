//funcao que carrega combo cidade
function carregaCidade(co_cidade_municipio){
	$('#cidade option').remove();
	if($('#uf').val() == 'Selecione' || !$('#uf').val()){
		 $('#cidade').append('<option>Selecione a UF</option>');
		 return false;
	}
	
	$.ajax({
		  url: sistemaUrl+'inscricao/carregacidade',
		  type: "POST",
		  data: {
			uf :   $('#uf').val()
		  },
		  dataType: "json",
		  success: function(data){
			  if(data.length > 0){
				  $('#cidade').append('<option>Selecione</option>');
				  var option = '';
				  var selected = '';
				  for(var i = 1; i<data.length; i++){
					  if(co_cidade_municipio == data[i].CO_MUNICIPIO_IBGE || co_cidade_municipio == data[i].CO_SEQ_CIDADE){
						  selected = 'selected';
					  }else{
						  selected = '';
					  }
					  
					  option = '<option value="'+data[i].CO_SEQ_CIDADE+'" '+selected+'>'+data[i].NO_CIDADE+'</option>';
					  $('#cidade').append(option);
				  }
			  }
		  }
		  
		});
}




//funcao que carrega o curso de acordo com a escolaridade
function carregaCurso(co_escolaridade){
	var curso = $('#curso').val();
	$('#curso option').remove();
	if(co_escolaridade != '52' && co_escolaridade != '20' && co_escolaridade != 'Selecione' && co_escolaridade){
		$.ajax({
			  url: sistemaUrl+'inscricao/carregacurso',
			  type: "POST",
			  data: {
				co_escolaridade : co_escolaridade
			  },
			  dataType: "json",
			  success: function(data){
				  if(data){
					  $('#curso').append('<option>Selecione</option>');
					  var selected = '';
					  for(var i = 0; i<data.length; i++){
						  if(data[i].CO_HABILITACAO_PROFISSIONAL == curso){
							  selected = 'selected'
						  }else{
							  selected = '';
						  }
						  var option = '<option value="'+data[i].CO_HABILITACAO_PROFISSIONAL+'" '+selected+' >'+data[i].DS_HABILITACAO_PROFISSIONAL+'</option>';
						  $('#curso').append(option);
					  }
				  }
			  }
			  
			});
	}
}


//funcao que limpa o filtro
function limparFiltro(){
	$('#escolaridade').val('');
	$('#uf').val('');
	$('#curso option').remove();
	$('#cidade option').remove();
	$('#cidade').append('<option>Selecione a UF<option>');
	$('#hora_inicial').val('');
	$('#hora_final').val('');
	$('#mini_curriculo').val('');
	$('#semestre_inicial').val('');
	$('#semestre_final').val('');
	$('#renda_inicial').val('');
	$('#renda_final').val('');
	$('input[name="n_especiais"]').removeAttr('checked');
}

function is_numeric(pStr){
	var reDigits = /^\d+$/;
	if (reDigits.test(pStr)) {
		return true
	} else if (pStr != null && pStr != "") {
		return false;
	}
}

//para que o formulario seja submetido temos que ter pelo menos um filtro preenchido
function validaCampos(){
	var submete = false;
	
	if($('#semestre_inicial').val() && is_numeric($('#semestre_inicial').val()) ){
		return true;
	}

	if($('#semestre_final').val() && is_numeric($('#semestre_final').val()) ){
		return true;
	}
	
	$('input[inputRequerido="true"]').each(function(){
		if($(this).val() &&  $(this).val() != 'R$ 0,00'){
			submete = true;
		}
	});
	
	if(submete){
		return true;
	}


	$('select[selectRequerido="true"]').each(function(){
		if($.trim($(this).val()) && $(this).val() != 'Selecione' && $(this).val() != 'Selecione a UF'){
			submete = true;
		}
	});
	
	if(submete){
		return true;
	}
	
	if($('input[name="n_especiais"]').is(':checked')){
		return true;
	}
	
	alerta('Pelo menos 1 filtro deve ser preenchido.');
	return false;
	
}

//funcao que submete o formulario via AJAX
function submeteFormAjax(){
	$('#resultadoPesquisa').html('');
	$.ajax({
		  url: sistemaUrl+'inscricao/resultadopesquisa',
		  type: "POST",
		  data: $('#frmPesquisarInscricao').serializeArray(),
		  beforeSend: function(){
				$('#carregando').show();
		  },
		  success: function(data){
			  $('#carregando').hide();
			  $('#resultadoPesquisa').html(data);
		  }
		});
}

$(function(){
	//mascara do campo renda
	$('.moeda').priceFormat({
		prefix: 'R$ ',
		centsSeparator: ',',
		thousandsSeparator: '',
		limit:13
	});
	
	$('#uf').change(function(){
		carregaCidade(0);
	});
	
	$('#escolaridade').change(function(){
		carregaCurso($(this).val());
	});
	
	$('#btLimpar').click(function(){
		limparFiltro();
	});
	
	$('#btSair').click(function(){
		location.href = sistemaUrl;
	});
	
	$('#btPesquisarPolo').click(function(){
		if(validaCampos()){
			//submete
			submeteFormAjax();
		}
	});
	
});