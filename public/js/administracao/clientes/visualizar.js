$(function(){
	if ( $( '#tp_pessoa' ).val() != 1 ){
		$('.pj').hide();
		$('.pf').show();
	}else{
	  $('.pf').hide();
	  $('.pj').show();
	}

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
			alert('Por favor, preencha o campo Nome');
			$( '#no_socio' ).focus();
			return false;
		}
		
		if( $( '#ds_skypemsn' ).val() == '' ){
			alert('Por favor, preencha o campo Skype/MSN');
			$( '#ds_skypemsn' ).focus();
			return false;
		}
		
		if( $( '#ds_profissao_socio' ).val() == '' ){
			alert('Por favor, preencha o campo Profissão');
			$( '#ds_profissao_socio' ).focus();
			return false;
		}

		if( $( '#ds_cargo_socio' ).val() == '' ){
			alert('Por favor, preencha o campo Cargo');
			$( '#ds_cargo_socio' ).focus();
			return false;
		}
		
		if( $( '#nu_whatsapp_socio' ).val() == '' ){
			alert('Preencha o campo Telefone');
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
			var number = $("input[name='data[pj][socio][nome][]']").length;
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
		}else{
			alerta('Por favor, selecionar um Responsável Técnico');
		}
		
	}

	
	$( '#btnSalvar' ).click( function(){
		
		if ( $( '#tp_pessoa_pj' ).is(':checked') ){
			if( $('#hdnSocio :input').length == 0 ){
				alert('Por favor, cadastre ao menos um sócio!');
				return false;
			}
		}

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
		$.ajax({
			url: sistemaUrl+'administracao/clientes/buscar-cep',
			type: "POST",
			data: {
				nu_cep : $( this ).val()
			},
			dataType: "json",
			success: function(data){
				if(data.msg == 'ok'){
		          $('#ds_endereco').val(data.logradouro);
		          $('#no_bairro').val(data.bairro);
		          $('#sg_uf').val(data.uf);
		          $('#no_cidade').val(data.localidade);
				}
			}
			
		});
	});


})