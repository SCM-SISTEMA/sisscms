$(function(){

	$("[data-mask]").inputmask();

	$('#co_pessoa').change(function(){
		$('#nu_cep').val($('#co_pessoa option:selected').attr('cep'));
		$('#no_rastreamento').val($('#co_pessoa option:selected').html());
	});

	$('#dt_inicio').datepicker();
	$('#dt_final').datepicker();

	$('#novoRegistro, #btnLimpar').click(function(){
		limparForm();
	});

	paginar = function(page){
		$('#nu_page').val(page);
		$('#frmPesquisar').submit();
	}

	$('#exibirInativos').click(function(){
		location.href = sistemaUrl+'financeiro/rastreamento/index/inativos/1';
	});

	rastrear = function( objeto ){

		$.ajax({
			url: sistemaUrl+'financeiro/rastreamento/rastrear-objeto/',
			type: "POST",
			data: {
				objeto : objeto
			},
			dataType: "json",
			success: function(data){
				if( data ){
					$('#contreudoRastreio').html(data.retorno);
					$('#contreudoRastreio table').attr('class', 'table table-striped table-bordered');
					$('#nuRastreio').html(objeto);
					$('#modalRastreamento').modal('show');
				}
			}

		});
	}

	editar = function( co_rastreamento ){
		limparForm();
		$.ajax({
			url: sistemaUrl+'financeiro/rastreamento/editar',
			type: "POST",
			data: {
				co_rastreamento : co_rastreamento
			},
			dataType: "json",
			success: function(data){
				if( data.co_rastreamento ){
					$('#modalCadastroRastreamento').modal('show');
					
					$('#co_rastreamento').val( data.co_rastreamento);
					$('#co_pessoa').val( data.co_pessoa_juridica);
					$('#no_rastreamento').val( data.no_rastreamento);
					$('#nu_cep').val( data.nu_cep);
					$('#nu_rastreamento').val( data.nu_rastreamento);
					$('#ds_identificacao_conteudo').val( data.ds_identificacao_conteudo);
				}
			}
			
		});
	}
	
	deletar = function( co_rastreamento){
		confirma('Deseja realmente excluir este rastreamento?', function(){
			$.ajax({
				type: "POST",
				url: sistemaUrl + "financeiro/rastreamento/deletar",
				data: {co_rastreamento:co_rastreamento},
				dataType: 'json',
				success: function( data )
				{
					if( data.msg == 'success' ){
						alerta(data.message, function(){
							location.href = sistemaUrl+'financeiro/rastreamento/index';
						});

					}else{
						alerta(data.message);
					}
				}
			});
		});
	}
	
	ativar = function( co_rastreamento){
		confirma('Deseja realmente ativar este rastreamento?', function(){
			$.ajax({
				type: "POST",
				url: sistemaUrl + "financeiro/rastreamento/ativar",
				data: {co_rastreamento:co_rastreamento},
				dataType: 'json',
				success: function( data )
				{
					if( data.msg == 'success' ){
						alerta(data.message, function(){
							location.href = sistemaUrl+'financeiro/rastreamento/index';
						});

					}else{
						alerta(data.message);
					}
				}
			});
		});
	}

	limparForm = function(){
		$(':input', 'form').not(':button, :submit, :reset, :radio').val('').removeAttr('checked').removeAttr('selected');
		$(':input:radio', 'form').removeAttr('checked');
	}

	$( '#btnSalvar' ).click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadastroRastreamento').validate({
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

				$('#modalCadastroRastreamento').modal('hide');

				$.ajax({
					type: "POST",
					url: sistemaUrl + "financeiro/rastreamento/salvar",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							alerta(data.message, function(){
								location.href = sistemaUrl+'financeiro/rastreamento/index';
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