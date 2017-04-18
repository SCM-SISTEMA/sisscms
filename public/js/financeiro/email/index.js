$(function(){

	$('#co_pessoa').multiselect({
		enableFiltering: true,
		includeSelectAllOption: true,
		maxHeight: 400
	});

	$('#btnEnviarEmail').click(function(){
		$(this).addClass('disabled');
		$(':input:radio', '#frmConsultaEmail').removeAttr('checked');
	});

	$('#selecionarTodos').change(function(){
		if($(this).is(':checked')){
			$("#table input[type='checkbox']").prop('checked', true);
		}else{
			$("#table input[type='checkbox']").prop('checked', false);
		}
	});

	$('#btnEnviar').click(function(){
		var emailCco = [];
		$("#table input[type='checkbox']:checked").each( function(){
			emailCco.push($(this).attr('email'));
		});

		$('#ds_emailcco').val(emailCco.join(';'));
	});

	$('#dt_vencimento').datepicker();
	$('#dt_inicio_vencimento').datepicker();
	$('#dt_final_vencimento').datepicker();
	$('#dt_inicio_pagamento').datepicker();
	$('#dt_final_pagamento').datepicker();
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

	retornaDados = function(){
		$.ajax({
			url: sistemaUrl+'financeiro/email/retorna-dados',
			type: "POST",
			data: {
				dt_inicio_vencimento:$('#dt_inicio_vencimento').val(),
				dt_final_vencimento:$('#dt_final_vencimento').val(),
				st_consulta:$("input[name='st_consulta']:checked").val()
			},
			dataType: "json",
			success: function(data){
				if(data.retorno){
					$('#modalCadastro').modal('show');
					$.each(data.retorno, function (index, value) {
						$('#co_pessoa').multiselect('select', [value.co_pessoa_juridica]);
					});
				}
			}
			
		});
	}
	
	deletar = function( co_centro_custo){
		confirma('Deseja realmente excluir este rastreamento?', function(){
			$.ajax({
				type: "POST",
				url: sistemaUrl + "financeiro/centrocusto/deletar",
				data: {co_centro_custo:co_centro_custo},
				dataType: 'json',
				success: function( data )
				{
					if( data.msg == 'success' ){
						alerta(data.message, function(){
							location.href = sistemaUrl+'financeiro/centrocusto/index';
						});

					}else{
						alerta(data.message);
					}
				}
			});
		});
	}
	
	ativar = function( co_rastreamento){
		confirma('Deseja realmente ativar este centro de custo?', function(){
			$.ajax({
				type: "POST",
				url: sistemaUrl + "financeiro/centrocusto/ativar",
				data: {co_rastreamento:co_rastreamento},
				dataType: 'json',
				success: function( data )
				{
					if( data.msg == 'success' ){
						alerta(data.message, function(){
							location.href = sistemaUrl+'financeiro/centrocusto/index';
						});

					}else{
						alerta(data.message);
					}
				}
			});
		});
	}

	importante = function(cod, val){
		$('#co_importante').val(val);
		$('#co_email').val(cod);
		$('#st_importante').val(1);
		$('#frmEdit').submit();
	}

	moverLidos = function(){

		$('#co_lido').val(val);
		$('#co_email').val(cod);
		$('#st_lido').val(1);
		$('#frmEdit').submit();
	}

	limparForm = function(){
		$(':input', 'form').not(':button, :submit, :reset, :radio').val('').removeAttr('checked').removeAttr('selected');
		$(':input:radio', 'form').removeAttr('checked');
	}

	$( '#btnSalvar' ).click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadastroCentroCusto').validate({
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
				$('#modalCadastroCentroCusto').modal('hide');
				var dados = $( form ).serialize();
				$.ajax({
					type: "POST",
					url: sistemaUrl + "financeiro/centrocusto/salvar",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							alerta(data.message, function(){
								location.href = sistemaUrl+'financeiro/centrocusto/index';
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