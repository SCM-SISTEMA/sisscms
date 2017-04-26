$(function(){

	$('#table').dataTable( {
		"bSort": false,
		"oLanguage": {
			"oPaginate": {
				"sLast": "Ultima",
				"sNext": "Próxima",
				"sPrevious": "Anterior",
				"sEmptyTable": "Nenhum registro encontrado!",
				"sInfo": "Got a total of _TOTAL_ entries to show (_START_ to _END_)"
			}
		}
	} );

	$('#novoRegistro').click(function(){
		limparForm();
	});

	editar = function( co_responsavel_tecnico ){
		$.ajax({
			url: sistemaUrl+'administracao/crea/editar',
			type: "POST",
			data: {
				co_responsavel_tecnico : co_responsavel_tecnico
			},
			dataType: "json",
			success: function(data){
				if( data.co_responsavel_tecnico ){
					$('#modalCadastroResponsavelTecnico').modal('show');
					
					$('#co_responsavel_tecnico').val( data.co_responsavel_tecnico);
					$('#co_area_tecnica').val( data.co_area_tecnica);
					$("input[name='tp_responsavel_tecnico'][value='"+ data.tp_responsavel_tecnico +"']").prop({checked: true});
					$('#no_resp_tec').val( data.no_responsavel_tecnico);
					$('#nu_registro_crea').val( data.nu_registro_crea);
					$('#nu_tel_resp_tec').val( data.nu_tel_resp_tec);
					$('#nu_tel_resp_tec').val( data.nu_telefone);
					$('#ds_email_resp_tec').val( data.ds_email);
					if(data.st_recebimento_direto == 'S') {
						$('#st_recebimento_direto').attr('checked', 'checked');
					}else{
						$('#st_recebimento_direto').removeAttr('checked');
					}
					$('#nu_whatsapp').val( data.nu_whatsapp);
					$('#sg_cnae').val( data.sg_cnae);
					
				}
			}
			
		});
	}
	
	deletar = function(){
		location.href = sistemaUrl + 'administracao/crea/deletar/co_responsavel_tecnico/' + $('#co_modal').val();
	}
	
	dados = function( co_responsavel_tecnico ){
		location.href = sistemaUrl + 'administracao/crea/dados/co_responsavel_tecnico/' + co_responsavel_tecnico;
	}

	limparForm = function(){
		$(':input', 'form').not(':button, :submit, :reset, :radio').val('').removeAttr('checked').removeAttr('selected');
		$(':input:radio', 'form').removeAttr('checked');
	}

	$( '#btnSalvar' ).click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadastroResponsavelTecnico').validate({
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

				$('#modalCadastroResponsavelTecnico').modal('hide');

				$.ajax({
					type: "POST",
					url: sistemaUrl + "administracao/crea/salvar",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							alerta(data.message, function(){
								location.href = sistemaUrl+'administracao/crea/index';
							});

						}else{
							alerta(data.message);
						}
					}
				});

				return false;
			}
		});


	});


})