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

	tinyMCE.init({
		selector: "textarea",
		menubar : "view",
		plugins: [
			"link image charmap preview anchor",
			"searchreplace visualblocks",
			"insertdatetime media wordcount "
		],
		theme_advanced_path : false,
		language : "pt_BR",
		toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	});

	$('#co_servico').multiselect();

	$('#co_servico').change(function(){
		if($(this).val() == 4){
			$('#servicos').show();
		}else{
			$('#servicos').hide();
			//$('#co_servico').multiselect('clearSelection')
		}
	});

	$('#co_pessoa').change(function(){
		$('#tp_pessoa').val( $('#co_pessoa option:selected').attr('tp_pessoa'));
	});

	$('#ds_valor').priceFormat();


	$('#co_pessoa').change( function(){
		$('#tp_pessoa').val( $( "#co_pessoa option:selected" ).attr('tp_pessoa') );
	});
	
	$('#frmContrato').attr('action', sistemaUrl + 'administracao/contrato/salvar' );
	$('#frmFormaPagamento').attr('action', sistemaUrl + 'administracao/contrato/salvar-forma-pagamento' );
	
	editar = function( co_cnae ){
		$.ajax({
			url: sistemaUrl+'administracao/contrato/editar',
			type: "POST",
			data: {
				co_contrato : co_contrato
			},
			dataType: "json",
			success: function(data){
				if( data.co_contrato ){
					$('#modalCadastro').modal('show');
					
					$('#co_cnae').val( data.co_contrato);
					$('#ds_cnae').val( data.ds_cnae);
					$('#cod_cnae').val( data.cod_cnae);
					$('#sg_cnae').val( data.sg_cnae);
					
				}
			}
			
		});
	}
	
	deletar = function(){
		location.href = sistemaUrl + 'administracao/contrato/deletar/co_contrato/' + $('#co_modal').val();
	}
	
	gerarContrato = function( co_contrato ){
		location.href = sistemaUrl + 'administracao/contrato/gerar-contrato/'+ base64_encode("co_contrato")+'/' + base64_encode( co_contrato );
	}

	checklist = function( co_contrato ){
		location.href = sistemaUrl + 'administracao/acompanhamento/index/co_contrato/' + base64_encode( co_contrato );
	}

	$('#btnGravarContrato').click( function(){

		$('#ds_contrato').val(tinyMCE.get('ds_modelo').getContent());

		var dados = $('#frmGravarContrato').serialize();

		$.ajax({
			url: sistemaUrl+'administracao/contrato/salvar',
			type: "POST",
			data: dados,
			dataType: "json",
			success: function(data){
				$('#modalCadastro').modal('hide');
				if(data.msg == 'success'){
					alerta(data.message, function(){
						location.href = sistemaUrl + 'administracao/contrato/';
					});
				}else{
					alerta(data.message);
				}
			}

		});

	});

	$('#btnGerarContrato').click( function(){

		$('#frmCadContrato').prop('action', sistemaUrl + 'administracao/contrato/gerar');

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadContrato').validate({
			rules: {
				required: "required"
			},
			errorClass: 'help-block col-lg-10',
			errorElement: 'span',
			highlight: function (element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight: function (element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
			},
			submitHandler: function( form ){
				form.submit();
			}
		});

	});

	$('#btnSalvarFormaPagamento').click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmFormaPagamento').validate({
			rules: {
				required: "required"
			},
			errorClass: 'help-block col-lg-10',
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
					url: sistemaUrl+'administracao/contrato/salvar-forma-pagamento',
					type: "POST",
					data: dados,
					dataType: "json",
					success: function(data){
						$('#modalFormaPagamento').modal('hide');
						if(data.msg != 'success'){
							$('#message_error').removeClass('hide').show('slide');
							$('#message_error .alert-heading').html(data.message);
							$('#message_error').delay(5000).hide('slide');
						}else{
							$('#message_success').removeClass('hide').show('slide');
							$('#message_success .alert-heading').html(data.message);
							$('#message_success').delay(5000).hide('slide');
						}
					}

				});

				return false;
			}
		});

	});

})