$(function(){
	if ( $( '#tp_pessoa_pf' ).is(':checked') ){
		$('.pj').hide();
		$('.pf').show();
	}
	if ( $( '#tp_pessoa_pj' ).is(':checked') ){
	  $('.pf').hide();
	  $('.pj').show();
	}
	$( "input[name='endCorresp']" ).change( function(){
		if($(this).is(':checked') && $(this).val() == 'N'){
			$('.ds_endereco_correspondecia').show();
		}else{
			$('.ds_endereco_correspondecia').hide();
		}

	});

	$("[data-mask]").inputmask();
	
	$( "input[name*='tp_pessoa']" ).change( function(){
		if ( $( '#tp_pessoa_pf' ).is(':checked') ){
			$('.pj').hide();
			$('.pj').find('input:text').val(''); 
			$('.pf').show();
		}
		if ( $( '#tp_pessoa_pj' ).is(':checked') ){
		  $('.pf').hide();
		  $('.pf').find('input:text').val(''); 
		  $('.pj').show();
		}
		
		
	});

	$('#nu_cnpj').focusout(function () {
		verificaExistenciaCliente($(this).val());
	} );

	$('#novoSocio').click( function(){
		$('#modalCadastro').modal('show');
		$( '#modalCadastro input').each( function(){
			$(this).val('');
		});
	});
	
	$('#novoResponsavelTecnico').click( function(){
		$('#modalCadastroResponsavelTecnico').modal('show');
	});
	
	$('#btnAdicionarResponsavelTecnico').click( function(){
		mostrarResponsavelTecnico();
	});
	
	$( '#btnSalvarSocio' ).click( function(){
		
		if( $( '#no_Socio' ).val() == '' ){
			alerta('Por favor, preencha o campo Nome');
			$( '#no_socio' ).focus();
			return false;
		}
		
		if( $( '#ds_skypemsn' ).val() == '' ){
			alerta('Por favor, preencha o campo Skype/MSN');
			$( '#ds_skypemsn' ).focus();
			return false;
		}
		
		if( $( '#ds_profissao_socio' ).val() == '' ){
			alerta('Por favor, preencha o campo Profissão');
			$( '#ds_profissao_socio' ).focus();
			return false;
		}

		if( $( '#ds_cargo_socio' ).val() == '' ){
			alerta('Por favor, preencha o campo Cargo');
			$( '#ds_cargo_socio' ).focus();
			return false;
		}
		
		if( $( '#nu_whatsapp_socio' ).val() == '' ){
			alerta('Preencha o campo Telefone');
			$( '#nu_whatsapp_socio' ).focus();
			return false;
		}
		
		mostrarSocio();
		addInputSocio();
		$('#modalCadastro').modal('hide');
	});
	
	removerSocio = function( number ){
		$( '#socio_'+number ).remove();
		$("input[socio='"+number+"']").remove();
	}
	
	mostrarSocio = function(){
			var number = $('#tbodySocio tr').length;
		    var html ='<tr id="socio_'+number+'">';
		    html +='	<td>'+$("#no_socio").val()+'</td>';
		    html +='<td>'+$("#ds_profissao_socio").val()+'</td>';
		    html +='<td>'+$("#ds_cargo_socio").val()+'</td>';
		    html +='<td>'+$("#nu_telefone_socio").val()+'</td>';
		    html +='<td>'+$("#nu_whatsapp_socio").val()+'</td>';
		    html +='<td><a style="cursor:pointer" onclick="removerSocio(\''+number+'\')"><i class="glyphicon glyphicon-trash"></i></a></td>';
		    html +='</tr>';
		    
		    $( '#tbodySocio' ).append(html);
		    
	}
	
	addInputSocio = function(){
		var number = $("#hdnSocio :input[name*='nome']").length;
		
		var html = '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][nome]" value="'+$("#no_socio").val()+'">';
		html +=    '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][profissao]" value="'+$("#ds_profissao_socio").val()+'">';
		html +=    '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][cargo]" value="'+$("#ds_cargo_socio").val()+'">';
		html +=    '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][telefone]" value="'+$("#nu_telefone_socio").val()+'">';
		html +=    '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][nu_whatsapp]" value="'+$("#nu_whatsapp_socio").val()+'">';
		$( '#hdnSocio' ).append(html);
	}
	
	
	mostrarResponsavelTecnico = function(){
		var number = $("input[name='co_responsavel_tecnico']:checked").length;
		if( number > 0 ){
			var html ='<tr>';
			html +='	<input type="hidden" name="data[pj][co_responsavel_tecnico]" value="'+$("input[name='co_responsavel_tecnico']:checked").val()+'"></td>';
			html +='	<td>'+$("input[name='co_responsavel_tecnico']:checked").attr('noresponsaveltecnico')+'</td>';
			html +='	<td>'+$("input[name='co_responsavel_tecnico']:checked").attr('crea')+'</td>';
			html +='<td><a style="cursor:pointer" onclick="$( \'#tbodyResponsavelTecnico\' ).html(\'\')"><i class="glyphicon glyphicon-trash"></i></a></td>';
			html +='</tr>';
			$( '#tbodyResponsavelTecnico' ).html(html);
			$('#modalCadastroResponsavelTecnico').modal('hide');
		}

		if ( $( '#tp_pessoa_pj' ).is(':checked') ){
			if( $('#tbodyResponsavelTecnico tr').length == 0 ){
				alerta('Por favor, selecionar um Responsável Técnico');
				return false;
			}
		}

	}

	
	$( '#btnSalvar' ).click( function(){
		
		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadCliente').validate({
			rules: {
				required: "required"
			},
			errorClass: 'help-block col-lg-12',
			errorElement: 'span',
			highlight: function (element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight: function (element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
			},
			submitHandler: function( form ){
				var dados = $( form ).serialize();

				$.ajax({
					type: "POST",
					url: sistemaUrl + "administracao/clientes/salvar",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							$('#btnSalvar').addClass('hide');
							$('#message_success').removeClass('hide').show('slide');
							$('#message_success .alert-heading').html(data.message);
							$('#message_success').delay(5000).hide('slide');
							setTimeout( function(){
								location.href = sistemaUrl+'administracao/clientes/index';
							}, 5000);
						}else{
							$('#message_error').removeClass('hide').show('slide');
							$('#message_error .alert-heading').html(data.message);
							$('#message_error').delay(5000).hide('slide');
						}
					}
				});

				return false;
			}
		});
		
	             
	});
	
	$( '#nu_cep' ).focusout( function(){
		retornaCep($( this ).val());
	});



	retornaCep = function(nu_cep){
		$.ajax({
			url: sistemaUrl+'administracao/clientes/buscar-cep',
			type: "POST",
			data: {
				nu_cep : nu_cep
			},
			dataType: "json",
			success: function(data){
				$('#ds_endereco').val(data.logradouro);
				$('#no_bairro').val(data.bairro);
				$("#sg_uf option[sg='"+data.uf+"']").attr('selected', 'selected');
				$('#no_cidade').val(data.localidade);
			}

		});
	}

	limparForm = function(){
		$(':input', 'form').not(':button, :submit, :reset, :radio').val('').removeAttr('checked').removeAttr('selected');
		$(':input:radio', 'form').removeAttr('checked');
	}

	verificaExistenciaCliente = function(nu_cnpj){
		if(nu_cnpj != '' && $('#co_pessoa_juridica').val() == ''){
			var retorno = true;
			$.ajax({
				url: sistemaUrl + 'administracao/clientes/verifica-existencia-cliente',
				type: "POST",
				data: {
					nu_cnpj: nu_cnpj
				},
				dataType: "json",
				success: function (data) {
					if (data.msg == 'error') {
						alerta(data.message);
						limparForm();
					}else{
						retornaDadosReceita(nu_cnpj);
					}


				}

			});
		}else{
			retornaDadosReceita(nu_cnpj);
		}

	}

	retornaDadosReceita = function(nu_cnpj){
		$.ajax({
			type: "POST",
			url: sistemaUrl + "administracao/clientes/buscar-cnpj",
			data: {nu_cnpj: nu_cnpj},
			dataType: 'json',
			success: function( data )
			{
				var htmlPrimaria = '<fieldset><legend>Atividade Primária</legend>';
				for(i=0;i<data.atividade_principal.length;i++){
					htmlPrimaria += '<input type="hidden" name="data[pj][nu_cnae_primario][]" value="'+data.atividade_principal[i].code+'|'+data.atividade_principal[i].text+'">';
					htmlPrimaria += data.atividade_principal[i].code + ' - ';
					htmlPrimaria += data.atividade_principal[i].text;
				}
				htmlPrimaria += '</fieldset><div class="clear pj">&nbsp;</div>';

				var htmlSecundaria = '<fieldset><legend>Atividades Secundárias</legend>';
				for(i=0;i<data.atividades_secundarias.length;i++){
					htmlSecundaria += '<input type="hidden" name="data[pj][nu_cnae_secundario][]" value="'+data.atividades_secundarias[i].code+'|'+data.atividades_secundarias[i].text+'">';
					htmlSecundaria += data.atividades_secundarias[i].code + ' - ';
					htmlSecundaria += data.atividades_secundarias[i].text + '<br>';
				}
				htmlSecundaria += '</fieldset>';

				$('#no_razao_social').val(data.nome);
				$('#no_fantasia').val(data.fantasia);

				$('#nu_cep').val(data.cep);
				$('#ds_endereco').val(data.logradouro);
				$('#no_bairro').val(data.bairro);
				$("#sg_uf option[sg='"+data.uf+"']").attr('selected', 'selected');
				$('#no_cidade').val(data.municipio);
				$('#nu_endereco').val(data.numero);
				$('#ds_complemento').val(data.complemento);
				$('#nu_telefone_1').val(data.telefone.replace(' ', ''));

				$('#atividade_primaria').html(htmlPrimaria);
				$('#atividade_secundaria').html(htmlSecundaria);
			}
		});
	}


})