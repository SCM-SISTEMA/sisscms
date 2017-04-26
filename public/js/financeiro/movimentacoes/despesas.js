$(function(){

	$('#dt_vencimento').datepicker();
	$('#dt_inicio_vencimento').datepicker();
	$('#dt_final_vencimento').datepicker();
	$('#dt_inicio_pagamento').datepicker();
	$('#dt_final_pagamento').datepicker();
	$('#ds_valor').priceFormat();

	$('#dt_despesa').datepicker();
	$('#dt_pagamento').datepicker();
	$('#dt_vencimento').datepicker();

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

	$('#st_despesa_duplica').change( function(){
		if($(this).is(':checked')){
			$('.qtd_despesa').show();
		}else{
			$('.qtd_despesa').hide();
			$('#nu_qtd_despesa').val(0);
		}
	});

	paginar = function(page){
		$('#nu_page').val(page);
		$('#frmPesquisar').submit();
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

	quitar = function( co_movimentacao ){
		bootbox.confirm("Deseja quitar esta movimentação?", function(result) {
			if( result ){
				$.ajax({
					url: sistemaUrl+'financeiro/movimentacoes/quitar',
					type: "POST",
					data: {
						co_movimentacao : co_movimentacao
					},
					dataType: "json",
					success: function(data){
						if( data.msg == 'success' ){
							var image = '<img style="margin-right: 20px;" src="' + sistemaUrl + 'images/accepted.png">';
							alerta(image + 'Movimentação quitada com sucesso.', 'index');
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
		$('#frmCadDespesa').validate({
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
					url: sistemaUrl + "financeiro/movimentacoes/salvar-despesa",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							$('#modalCadastro').modal('hide');
							$('#message_success').removeClass('hide').show('slide');
							$('#message_success .alert-heading').html(data.message);
							$('#message_success').delay(5000).hide('slide');
							setTimeout( function(){
								location.href = sistemaUrl+'administracao/cnae/index';
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
})