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

	$('#btnSalvarArquivo').click( function(){
		$('#frmCadastroAnuidadeCrea').submit();
	});
	$('#sg_uf').multiselect();

	$('#frmCadastroCrea').attr('action', sistemaUrl + 'administracao/crea/salvar' );
	
	dados = function( co_responsavel_tecnico ){
		location.href = sistemaUrl + 'administracao/crea/dados/co_responsavel_tecnico/' + co_responsavel_tecnico;
	}

	carregaVistos = function( co_responsavel_tecnico ){
		$.ajax({
			type: "POST",
			url: sistemaUrl + "administracao/crea/carregar-vistos",
			data: {co_responsavel_tecnico: co_responsavel_tecnico},
			dataType: 'json',
			success: function( data )
			{
				if( data.msg == 'success' ){
					$('#modalCadastroVisto').modal('hide');
					var html = '';
					for(i=0;i<=data.length();i++){
						html += data.sg_estado + ' - ' + data.no_estado + '<br>';
					}
					$('#listaEstados').html(html);
					$('#message_error').removeClass('hide').show('slide');
					$('#message_error .alert-heading').html(data.message);
					$('#message_error').delay(5000).hide('slide');
				}else{
					$('#message_error').removeClass('hide').show('slide');
					$('#message_error .alert-heading').html(data.message);
					$('#message_error').delay(5000).hide('slide');
				}
			}
		});
	}

	$( '#btnSalvarVisto' ).click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadastroCrea').validate({
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
				$('#modalCadastroVisto').hide();
				$.ajax({
					type: "POST",
					url: sistemaUrl + "administracao/crea/salvar-visto",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							alerta(data.message, function(){
								location.href = sistemaUrl + "administracao/crea/dados";
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