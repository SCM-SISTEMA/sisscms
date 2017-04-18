$(function(){
	if(pesquisa){
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
	}


	$('#dt_vencimento').datepicker();
	$('#dt_inicio_vencimento').datepicker();
	$('#dt_final_vencimento').datepicker();
	$('#dt_inicio_pagamento').datepicker();
	$('#dt_final_pagamento').datepicker();
	$('#ds_valor').priceFormat();
	$('#ds_valor_multa').priceFormat();
	$('#ds_valor_juros').priceFormat();

	$('#co_pessoa').multiselect({
		enableFiltering: true,
		includeSelectAllOption: true,
		maxHeight: 400
	});
	
	$('#co_pessoa_boleto').multiselect({
		enableFiltering: true,
		includeSelectAllOption: true,
		maxHeight: 400
	});

	$('#st_pagamento').change( function(){
		if( $(this).val() == 0){
			$('#dt_inicio_pagamento').val('');
			$('#dt_final_pagamento').val('');
		}
	});

	$('#btnPesquisar').click(function(){
		$('#st_consulta').val('');
	});
	$('#st_pagos').click(function(){
		$('#st_consulta').val('pagos');
		$('#frmPesquisar').submit();
	});
	$('#st_vencendo').click(function(){
		$('#st_consulta').val('vencendo');
		$('#frmPesquisar').submit();
	});
	$('#st_vencidos').click(function(){
		$('#st_consulta').val('vencidos');
		$('#frmPesquisar').submit();
	});
	$('#st_avencer').click(function(){
		$('#st_consulta').val('avencer');
		$('#frmPesquisar').submit();
	});

	$('#btnGerarPdf').click( function(){
		$('#st_gerar_boleto_cliente').val(1);
		$('#frmPesquisar').attr('action',sistemaUrl + 'financeiro/movimentacoes/gerar-pdf' );
		$('#frmPesquisar').submit();
	});

	$('#btnExcluirBoletos').click( function(){
		location.href = sistemaUrl + 'financeiro/movimentacoes/excluir-boletos';
	});

	$('#btnGerarCliente').click( function(){
		$('#st_gerar_pdf').val(1);
		$('#frmPesquisar').attr('action',sistemaUrl + 'financeiro/movimentacoes/gerar-boleto-cliente' );
		$('#frmPesquisar').submit();
	});

	$('#btnLimpar').click( function(){
		limparForm();
	});

	$('#dt_inicio_pagamento').change( function(){
		if( $('input[name=st_pagamento]:checked').val() == 1){
			$('#dt_inicio_pagamento').val('');
		}
	});

	$('#dt_final_pagamento').change( function(){
		if( $('input[name=st_pagamento]:checked').val() == 1){
			$('#dt_final_pagamento').val('');
		}
	});

	$('#frmCategororia').attr('action', sistemaUrl + 'administracao/cnae/salvar' );

	gerarBoleto = function(co_boleto){
		window.open(sistemaUrl + 'financeiro/movimentacoes/gerar-boleto/co_boleto/'+co_boleto);
	}

	paginar = function(page){
		$('#nu_page').val(page);
		$('#frmPesquisar').submit();
	}

	limparForm = function(){
		$(':input', 'form').not(':button, :submit, :reset, :radio').val('').removeAttr('checked').removeAttr('selected');
		$(':input:radio', 'form').removeAttr('checked');
	}

	editar = function( co_cnae ){
		$.ajax({
			url: sistemaUrl+'administracao/cnae/editar',
			type: "POST",
			data: {
				co_cnae : co_cnae
			},
			dataType: "json",
			success: function(data){
				if( data.co_cnae ){
					$('#modalCadastro').modal('show');
					
					$('#co_cnae').val( data.co_cnae);
					$('#ds_cnae').val( data.ds_cnae);
					$('#cod_cnae').val( data.cod_cnae);
					$('#sg_cnae').val( data.sg_cnae);
					
				}
			}
			
		});
	}

	setInterval( function(){
		$("span[class='btn-danger badge']").animate({opacity:'toggle'});
	}, 1000);

	quitarBoleto = function( co_boleto ){
		bootbox.confirm("Deseja quitar este boleto?", function(result) {
			if( result ){
				$.ajax({
					url: sistemaUrl+'financeiro/movimentacoes/quitar-boleto',
					type: "POST",
					data: {
						co_boleto : co_boleto
					},
					dataType: "json",
					success: function(data){
						if( data.msg == 'success' ){
							var image = '<img style="margin-right: 20px;" src="' + sistemaUrl + 'images/accepted.png">';
							alerta(image + 'Boleto quitado com sucesso.', 'index');

						}
					}

				});
			}
		});

	}

	enviarAdvogado = function( co_boleto ){
		bootbox.confirm("Deseja enviar este boleto para o advogado?", function(result) {
			if( result ){
				$.ajax({
					url: sistemaUrl+'financeiro/movimentacoes/enviar-advogado',
					type: "POST",
					data: {
						co_boleto : co_boleto
					},
					dataType: "json",
					success: function(data){
						if( data.msg == 'success' ){
							var image = '<img style="margin-right: 20px;" src="' + sistemaUrl + 'images/accepted.png">';
							alerta(image + 'Boleto enviado para o advogado com sucesso.');
						}
					}

				});
			}
		});

	}

	quitarParcela = function( co_contrato_parcela ){
		bootbox.confirm("Deseja quitar esta parcela?", function(result) {
			if( result ){
				$.ajax({
					url: sistemaUrl+'financeiro/movimentacoes/quitar-parcela',
					type: "POST",
					data: {
						co_contrato_parcela : co_contrato_parcela
					},
					dataType: "json",
					success: function(data){
						if( data.msg == 'success' ){
							var image = '<img style="margin-right: 20px;" src="' + sistemaUrl + 'images/accepted.png">';
							bootbox.alert(image + 'Parcela quitada com sucesso.', function() {
								$( "#boletos" ).load( sistemaUrl + "financeiro/movimentacoes/boletos" );
							});
						}
					}

				});
			}
		});

	}
	
	deletar = function(){
		location.href = sistemaUrl + 'administracao/cnae/deletar/co_cnae/' + $('#co_modal').val();
	}

	$( '#btnSalvar' ).click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadBoleto').validate({
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
					url: sistemaUrl + "financeiro/movimentacoes/salvar-boleto",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							$('#modalCadastro').modal('hide');
							var image = '<img style="margin-right: 20px;" src="' + sistemaUrl + 'images/accepted.png">';
							alerta(image + 'Boleto criado com sucesso.');
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