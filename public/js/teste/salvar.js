function necessidadesEspeciais(obj){
		if($(obj).val() == 1){
			//mostra campo de descricao de necessidade especiais e coloca obrigatoriedade
			$('#div_necessidades_especiais').show();
			$('#ds_necessidade').attr("inputRequerido","true" );
		}else{
			//esconde o descricao de necessidade especiais limpa e retira obrigatoriedade
			$('#div_necessidades_especiais').hide();
			$('#ds_necessidade').val('');
			$('#ds_necessidade').removeAttr("inputRequerido");
		}
}

function parente(obj){
	if($(obj).val() == 1){
		//mostrando campos
		$('.parens_MS').show();
		//colocando obrigatoriedade
		$('#no_parente').attr('inputRequerido2','true');
		$('#setor_parente').attr('inputRequerido2','true');
		$('#fone_parente').attr('inputRequerido2','true');
	}else{
		//ocultando campos
		$('.parens_MS').hide();
		//limpando campos e retirando obrigatoriedade
		$('#no_parente').val('').removeAttr('inputRequerido2');
		$('#setor_parente').val('').removeAttr('inputRequerido2');
		$('#fone_parente').val('').removeAttr('inputRequerido2');
	}
}

//funcao que carrega combo cidade
function carregaCidade(co_cidade_municipio){
	$.ajax({
		  url: sistemaUrl+'inscricao/carregacidade',
		  type: "POST",
		  data: {
			uf :   $('#uf').val()
		  },
		  dataType: "json",
		  success: function(data){
			  if(data.length > 0){
				  $('#cidade option').remove()
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

//funcao que carrega endereco de acordo com o cep
function buscaEndereco(obj){
	var cep = $(obj).val();
	$.ajax({
		  url: sistemaUrl+'inscricao/buscaendereco',
		  type: "POST",
		  data: {
			cep : cep
		  },
		  dataType: "json",
		  success: function(data){
			  if(data){
				  $('#complmento_end').val(data.DS_COMPLEMENTO ? data.DS_COMPLEMENTO : '');
				  $('#bairro').val(data.NO_BAIRRO ? data.NO_BAIRRO : '');
				  $('#endereco').val(data.NO_LOGRADOURO ? data.NO_LOGRADOURO : '');
				  $('#uf').val(data.SG_UF ? data.SG_UF : '');
				  carregaCidade(data.CO_MUNICIPIO_IBGE);
			  }
		  }
		  
		});
}

//funcao que define semestre/ano
function montaSemestreAno(obj){
	var semestre_selecionado = $('#semestre_ano').val();
	var selected = '';
	$('#semestre_ano option').remove();
	switch($(obj).val()){
	case '54':
		//superior
		var semestres = ($('#qtd_semestre').val() == 3 ? 4 : $('#qtd_semestre').val());
		for(var i = 3; i < semestres; i++){
			if(semestre_selecionado == i){
				selected = 'selected';
			}else{
				selected = '';
			}
			$('#semestre_ano').append('<option value="'+i+'" '+selected+'>'+i+'º semestre</option>');
		}
		break;
	
	case '52':
		//medio
		if(semestre_selecionado == 2){
			selected = 'selected';
		}else{
			selected = '';
		}
		$('#semestre_ano').append('<option value="2" '+selected+'> 2º Ano</option>');
		
		var data = $('#dataAtual').val();
		var mes = data.substr(4,2);
		if(mes <=4){
			if(semestre_selecionado == 3){
				selected = 'selected';
			}else{
				selected = '';
			}
			$('#semestre_ano').append('<option value="3" '+selected+'> 3º Ano</option>');
		}
		break;
		
	case '21':
		//tecnico
		var semestres = $('#qtd_semestre').val();
		for(var i = 1; i <= semestres; i++){
			if(semestre_selecionado == i){
				selected = 'selected';
			}else{
				selected = '';
			}
			$('#semestre_ano').append('<option value="'+i+'" '+selected+'>'+i+'º semestre</option>');
		}
		break;
		
	case '20':
		//eja
		$('#semestre_ano').append('<option value="2">2º semestre do 3º segmento</option>');
		break;
		
	}
}

//funcao que carrega o curso de acordo com a escolaridade
function carregaCurso(co_escolaridade){
	var curso = $('#curso').val();
	$('#curso option').remove();
	if(co_escolaridade != '52' && co_escolaridade != '20'){
		$.ajax({
			  url: sistemaUrl+'inscricao/carregacurso',
			  type: "POST",
			  data: {
				co_escolaridade : co_escolaridade
			  },
			  dataType: "json",
			  success: function(data){
				  if(data){
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

//funcao que adciona instiuicao
function add_instiuicao(obj){
	var cod_instituicao = $(obj).parent().find('#id_entidade').val();
	var instituicao = $(obj).parent().find('#entidade').val();
	$('#cod_instituicao').val(cod_instituicao);
	$('#instituicao').val(instituicao);
	$('#dialog-institucao').dialog('close');
} 


//funcao que pesquisa instiuicao 
function pesquisaInstituicao(filtro){
	
	var tabela = '<table cellpadding="0" cellspacing="0" border="0" class="ui-grid-content ui-widget-content" id="paginacao_instituicao">';
		tabela += '<thead>';
		tabela += '<tr align="left">';
		tabela += '<th class="ui-state-default" align="center">INSTITUI&Ccedil;&Atilde;O</th>';
		tabela += '</tr>';
		tabela += '</thead>';
		tabela += '<tbody>';
		
		
		var linha = '<tr><td>A instituição deverá entrar em contato no e-mail <u>marizeth@saude.gov.br</u> solicitando cadastro no MS</td></tr>';
	
	$.ajax({
		  url: sistemaUrl+'inscricao/pesquisainstituicao',
		  type: "POST",
		  data: {
		  instiuicao : filtro
		  },
		  dataType: "json",
		  success: function(data){
			  if(data && data.length > 0){
				  linha = '';
				  for(var i = 0; i<data.length; i++){
					    linha += ' <tr> <td onclick="add_instiuicao($(this))" style="cursor:pointer" title="Selecionar Instiuição"> '+data[i].NO_RAZAO_SOCIAL_ENTIDADE;
						linha += ' <input type="hidden" name="id_entidade" id="id_entidade" value="'+data[i].CO_SEQ_ENTIDADE+'" /> ';
						linha += ' <input type="hidden" name="entidade" id="entidade" value="'+data[i].NO_RAZAO_SOCIAL_ENTIDADE+'" /> ';
						linha += ' </td> </tr> ';
				  }
			  }
			  
			  var rodape = '</tbody></table>';
			  //alert(linha);
			  //return;
			  //alert(linha);
			  $('#div_paginacao_instituicao').html(tabela+linha+rodape);
			  $('#paginacao_instituicao').dataTable( {
			        "bJQueryUI": true,
			        "bLengthChange": true,
			        "bAutoWidth": false,
			        "sPaginationType": "full_numbers",
			        "aoColumns": [
			            {"bSearchable": true, "bVisible": true}
			        ],
			        "aaSorting": [
			            [0, 'asc']
			        ]
			    });
			  
			  $('#div_paginacao_instituicao').show()
		  }
		  
		});
	
	
}

//funcao que adiciona polo
function add_polo(obj){
	var id_polo = $(obj).parent().find('#id_polo').val();
	var polo = $(obj).parent().find('#polo').val();
	$('#id_polo').val(id_polo);
	$('#polo').val(polo);
	$('#dialog-polo').dialog('close');
} 

//funcao que pesquisa polo
function pesquisaPolo(filtro){
	var tabela = '<table cellpadding="0" cellspacing="0" border="0" class="ui-grid-content ui-widget-content" id="paginacao_polo">';
	tabela += '<thead>';
	tabela += '<tr align="left">';
	tabela += '<th class="ui-state-default" align="center">POLO</th>';
	tabela += '</tr>';
	tabela += '</thead>';
	tabela += '<tbody>';
	
	
	var linha = '<tr><td>Nenhum registro encontrado.</td></tr>';
	
	$.ajax({
		url: sistemaUrl+'inscricao/pesquisapolo',
		type: "POST",
		data: {
		polo : filtro
	},
	dataType: "json",
	success: function(data){
		if(data && data.length > 0){
			linha = '';
			for(var i = 0; i<data.length; i++){
				linha += ' <tr> <td onclick="add_polo($(this))" style="cursor:pointer" title="Selecionar Polo"> '+data[i].DS_POLO;
				linha += ' <input type="hidden" name="id_polo" id="id_polo" value="'+data[i].NU_POLO_INSTITUICAO+'" /> ';
				linha += ' <input type="hidden" name="polo" id="polo" value="'+data[i].DS_POLO+'" /> ';
			    linha += ' </td> </tr> ';
			}
		}
		
		var rodape = '</tbody></table>';
		$('#div_paginacao_polo').html(tabela+linha+rodape);
		$('#paginacao_polo').dataTable( {
			"bJQueryUI": true,
			"bLengthChange": true,
			"bAutoWidth": false,
			"sPaginationType": "full_numbers",
			"aoColumns": [
			              {"bSearchable": true, "bVisible": true}
			              ],
			              "aaSorting": [
			                            [0, 'asc']
			                             ]
		});
		
		$('#div_paginacao_polo').show()
	}
	
	});
	
	
}


function is_numeric(pStr){
	var reDigits = /^\d+$/;
	if (reDigits.test(pStr)) {
		return true
	} else if (pStr != null && pStr != "") {
		return false;
	}
}

function validaHora(tempo){
	var array 	= tempo.split(':');
	var hora 	= array[0];
	var min  	= array[1];
	if(hora<0 || hora > 24){
		return false;
	}

	if(hora == 24 && min != 0){
		return false;
	}

	if(min < 0 || min > 59){
		return false;
	}
	return true;
}

//funcao que valida dados pessoais
function validarDadosPessoais(){
	//validacao de campos obrigatórios
	var continua = true;
	//input obrigatorios
	$('input[inputRequerido="true"]').each(function(){
		//retirando espacos em branco da esquerda e da direita
		$(this).val($.trim($(this).val()));
		
		if(!$(this).val()){
			continua = false;
		}
	});

	//input obrigatorios
	$('select[selectRequerido="true"]').each(function(){
		if(!$(this).val() || $(this).val() == 0){
			continua = false;
		}
	});
	
	//validando renda
	if($('#renda').val() == 'R$ 0,00'){
		continua = false;
	}
	
	//validando necessidades especiais
	if(!$('input[name="n_especiais"]').is(':checked')){
		continua = false;
	}
	
	if(!continua){
		alerta('Campos obrigatórios não informados.');
		return false;
	}
	//fim da validacao de campos obrigatorios
	
	
	//validacao de cpf RN006 UC001
	if(!validarCPF($('#nu_cpf').val())){
		alerta('CPF Inválido.',$('#nu_cpf'));
		return false;
	}
	
	//validando data expedicao RN007 UC001
	if(!validarData($('#dt_exp').val(),$('#dt_exp'))){
		return false;
	}
	
	//validando data de nascimento RN007 UC001
	if(!validarData($('#dt_nasc').val(),$('#dt_nasc'))){
		return false;
	}
	
	//validando email UC001
	if(!validarMail($('#email').val())){
		alerta('E-mail Inválido.',$('#email'));
		return false;
	}
	
	//verificando idade RN002 UC001
	var data = $('#dt_nasc').val();
	var array = data.split('/');
	data = array[2]+array[1]+array[0];
	dataAtual = $('#dataAtual').val();
	var diferenca = dataAtual-data;

	if(diferenca < 160000){
	    alerta('Idade menor que 16 anos.',$('#dt_nasc'));
	    return false;
	}
	
	return true;
}

function validarFormacao(){
	//validacao de campos obrigatórios
	var continua = true;
	//input obrigatorios
	$('input[inputRequerido2="true"]').each(function(){
		//retirando espacos em branco da esquerda e da direita
		$(this).val($.trim($(this).val()));
		if(!$(this).val()){
			continua = false;
		}
	});
	
	//select obrigatorios
	$('select[selectRequerido2="true"]').each(function(){
		if(!$(this).val() || $(this).val() == 0){
			continua = false;
		}
	});
	
	//validando radio parentes
	if(!$('input[name="parentes"]').is(':checked')){
		continua = false;
	}
	
	if(!continua){
		alerta('Campos obrigatórios não informados.');
		return false;
	}
	
	//validando período de estudo
	if(!validaHora($('#hora_inicial').val())){
		alerta('Período inválido!', $('#hora_inicial'));
		return false;
	}

	if(!validaHora($('#hora_final').val())){
		alerta('Período inválido!', $('#hora_final'));
		return false;
	}
	
	if( $('#hora_inicial').val() >  $('#hora_final').val()){
	    alerta('Período inválido!');
	    return false;
	}
	
	return true;
}

$(function(){
	//mascara do campo renda familiar
	$('.moeda').priceFormat({
		prefix: 'R$ ',
		centsSeparator: ',',
		thousandsSeparator: '',
		limit:13
	});
	
	//limitando observacao
	$('#observacao').limit(1000,'#qtd_obs');
	
	//cep
	$('#cep').blur(function(){
		buscaEndereco($('#cep'));
	});
	
	//cidade
	$('#uf').change(function(){
		carregaCidade(0);
	});
	
	//carregando combo curso ao selecionar a escolaridade
	$('#escolaridade').change(function(){
		carregaCurso($(this).val());
		montaSemestreAno($(this));
		switch($(this).val()){
		case '54':
			//superior
			//colocando obrigatoriedade no campo
			$('#curso').attr('selectRequerido2','true');
			//mostrando campo
			$('#div_curso').show();
			break;
		
		case '52':
			//medio
			//tirando obrigatoriedade
			$('#curso').removeAttr('selectRequerido2');
			//ocultando campo
			$('#div_curso').hide();
			break;
		
		case '21':
			//tecnico
			//colocando obrigatoriedade no campo
			$('#curso').attr('selectRequerido2','true');
			//mostrando campo
			$('#div_curso').show();
		break;
		
		case '20':
			//eja
			//tirando obrigatoriedade
			$('#curso').removeAttr('selectRequerido2');
			//ocultando campo
			$('#div_curso').hide();
			break;
		}
	});
	
	//carregando semestre/ano
	$('#qtd_semestre').blur(function(){
		if(is_numeric($(this).val())){
			montaSemestreAno($('#escolaridade'));
		}else{
			$(this).val('');
		}
	});
	
	//parentes no MS
	$('input[name="parentes"]').click(function(){
		parente($(this));
	});
	
	//limitando mini-curriculo
	$('#mini_curriculo').limit(1000,'#qtd_mini_curriculo');
	
	$('#btAddInstiuicao').click(function(){
		//trazendo tela de pesquisa instituicao 
		$('#dialog-institucao').load(sistemaUrl+'inscricao/instituicao');
		
		//ocultando div da pagina carregada que mostrará o resultado da pesquisa
		$('#div_paginacao_instituicao').hide();
		
		//abrindo dialog
		$("#dialog-institucao").dialog({
	        title: "PESQUISA INSTIUIÇÃO",
			bgiframe: false,
			resizable: true,
			height:500,
	        width: 850,
			modal: true ,		
			buttons: {			
				'Cancelar': function() {				
					$(this).dialog('close');
				}
			}
		});
	});
	
	
	$('#btAddPolo').click(function(){
		//trazendo tela de pesquisa polo
		$('#dialog-polo').load(sistemaUrl+'inscricao/polo');
		
		//ocultando div da pagina carregada que mostrará o resultado da pesquisa
		$('#div_paginacao_polo').hide()
		
		//abrindo dialog
		$("#dialog-polo").dialog({
	        title: "PESQUISA POLO",
			bgiframe: false,
			resizable: true,
			height:500,
	        width: 850,
			modal: true ,		
			buttons: {			
				'Cancelar': function() {				
					$(this).dialog('close');
				}
			}
		});
	});
});