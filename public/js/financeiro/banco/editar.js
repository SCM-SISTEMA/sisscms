$(function(){
	
	if ( $( '#tp_pessoa_pf' ).is(':checked') ){
		$('.pj').hide();
		$('.pf').show();
	}
	if ( $( '#tp_pessoa_pj' ).is(':checked') ){
	  $('.pf').hide();
	  $('.pj').show();
	}
	
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
		
		if( $( '#nu_telefone_socio' ).val() == '' ){
			alerta('Preencha o campo Telefone');
			$( '#nu_telefone_socio' ).focus();
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
		    html +='<td>'+$("#ds_skypemsn_socio").val()+'</td>';
		    html +='<td><a style="cursor:pointer" onclick="removerSocio(\''+number+'\')"><i class="icon-trash"></i></a></td>';
		    html +='</tr>';
		    
		    $( '#tbodySocio' ).append(html);
		    
	}
	
	addInputSocio = function(){
		var number = $("#hdnSocio :input[name*='nome']").length;
		
		var html = '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][nome]" value="'+$("#no_socio").val()+'">';
		html +=    '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][profissao]" value="'+$("#ds_profissao_socio").val()+'">';
		html +=    '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][cargo]" value="'+$("#ds_cargo_socio").val()+'">';
		html +=    '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][telefone]" value="'+$("#nu_telefone_socio").val()+'">';
		html +=    '<input socio="'+number+'" type="hidden" name="data[pj][socio]['+number+'][skypemsn]" value="'+$("#ds_skypemsn_socio").val()+'">';
		$( '#hdnSocio' ).append(html);
	}
	
	$( '#btnSalvar' ).click( function(){
		
		if ( $( '#tp_pessoa_pj' ).is(':checked') ){
			if( $('#hdnSocio :input').length == 0 ){
				alerta('Por favor, cadastre ao menos um sócio!');
				return false;
			}
		}
		$('#frmCadCliente').validate();
		
	             
	});
	
	$( '#nu_cep' ).change( function(){
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
		          
		          $('#sg_uf option').each( function(){
		        	  if( $(this).attr('sg') == data.uf){
		        		  $(this).prop('selected', true);
		        	  }
		        	});
		          $('#no_cidade').val(data.localidade);
				}
			}
			
		});
	});
})