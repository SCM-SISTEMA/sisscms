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
		menubar : false,
		plugins: [
			"link image charmap preview anchor",
			"searchreplace visualblocks",
			"insertdatetime media wordcount "
		],
		theme_advanced_path : false,
		language : "pt_BR",
		toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	});

	$('#novoRegistro').click(function(){
		limparForm();
	});
	$('#modalCadastro').modal('hide');
	$('#modalClausula').modal('hide');
	
	$('#frmServicos').attr('action', sistemaUrl + 'administracao/servicos/salvar' );
	$('#frmTipoContrato').attr('action', sistemaUrl + 'administracao/servicos/salvar-tipo-contrato' );
	
	editar = function( co_servico ){
		$.ajax({
			url: sistemaUrl+'administracao/servicos/editar',
			type: "POST",
			data: {
				co_servico : co_servico
			},
			dataType: "json",
			success: function(data){
				if( data.co_servico ){
					$('#modalCadastro').modal('show');
					$('#co_servico').val( data.co_servico);
					$('#no_servico').val( data.no_servico);
					$('#sg_servico').val( data.sg_servico);
					$('#co_modelo_contrato_servico').val( data.co_modelo_contrato_servico);
					$('input[type=radio][value='+data.tp_servico+']').attr('checked', 'checked');
					tinyMCE.get('ds_servico').setContent(data.ds_servico);
					tinyMCE.get('ds_modelo').setContent(data.ds_modelo);
				}
			}
			
		});
	}

	limparForm = function(){
		$(':input', 'form').not(':button, :submit, :reset, :radio').val('').removeAttr('checked').removeAttr('selected');
		$(':input:radio', 'form').removeAttr('checked');
		tinyMCE.activeEditor.setContent('');
		$('#frmCadServico span').remove();
		$('#frmCadServico div').removeClass('has-success');
		$('#frmCadServico div').removeClass('has-error');
	}

	$('#btnSalvarServico').click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadServico').validate({
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

				$.ajax({
					url: sistemaUrl+'administracao/servicos/salvar',
					type: "POST",
					data: {
						ds_servico: 					tinyMCE.get('ds_servico').getContent(),
						ds_modelo: 						tinyMCE.get('ds_modelo').getContent(),
						co_servico: 					$('#co_servico').val(),
						co_modelo_contrato_servico: 	$('#co_modelo_contrato_servico').val(),
						sg_servico: 					$('#sg_servico').val(),
						no_servico: 					$('#no_servico').val(),
						tp_servico: 					$(':input:radio:checked', 'form').val()
					},
					dataType: "json",
					success: function(data){
						limparForm();
						if(data.msg != 'success'){
							$('#modalCadastro').modal('hide');
							var image = '<img style="margin-right: 20px;" src="' + sistemaUrl + 'images/accepted.png">';
							alerta(image + data.message);
						}else{
							alerta(data.message, function(){
								location.href = sistemaUrl + 'administracao/servicos/';
							});
						}
					}

				});

				return false;
			}
		});

	});
})