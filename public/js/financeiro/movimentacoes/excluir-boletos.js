$(function(){
	var imageCancel = '<img style="margin-right: 20px;" src="' + sistemaUrl + 'images/cancel.png">';
	var image = '<img style="margin-right: 20px;" src="' + sistemaUrl + 'images/accepted.png">';

	$('#btnExcluir').click( function(){
		if($('#ds_justificativa_exclusao').val() == ''){
			alerta(imageCancel + 'Favor preencher o campo JUSTIFICATIVA.');
		}else{
			$('#btnExcluir').attr('disabled', true);
			$.ajax({
				url: sistemaUrl+'financeiro/movimentacoes/confirmar-exclusao',
				type: "POST",
				data: {
					co_boleto : 				$('#co_boleto').val(),
					ds_justificativa_exclusao : $('#ds_justificativa_exclusao').val()
				},
				dataType: "json",
				success: function(data){
					if( data.msg == 'success' ){
						alerta(image + 'Boleto excluído com sucesso.', 'ok');
						location.href= sistemaUrl+'financeiro/movimentacoes/boletos';
					}
				}
				
			});
		}
	});

	$('#selecionarTodos').change(function(){
		if($(this).is(':checked')){
			$("#table input[type='checkbox']").prop('checked', true);
		}else{
			$("#table input[type='checkbox']").prop('checked', false);
		}
	});


	limparForm = function(){
		$(':input', 'form').not(':button, :submit, :reset, :radio').val('').removeAttr('checked').removeAttr('selected');
		$(':input:radio', 'form').removeAttr('checked');
	}
	
	$('#btnExcluirBoletos').click( function(){
		var arrBoletos = [];
		$("#table input[type='checkbox']:checked").each( function(){
			arrBoletos.push($(this).val());
		});

		$('#co_boleto').val( arrBoletos );
	});

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



})