$(function(){

	$('#tableMovimentacoes').dataTable( {
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

	setInterval( function(){
		$("span[class='btn-danger badge']").animate({opacity:'toggle'});
	}, 1000)


	$('#ds_valor_despesa').priceFormat();
	$('#ds_valor_receitas').priceFormat();
	$('#ds_limite').priceFormat();
	
    $('#dt_movimentacao_despesa').datepicker();
    $('#dt_pagamento_despesa').datepicker();
    $('#dt_vencimento_despesa').datepicker();

	$('#st_movimentacao_despesa_paga').change( function(){
		$('#dt_vencimento_despesa').val('');
		$('.pago').show();
		$('.pagar').hide();
	});
	$('#st_movimentacao_despesa_pagar').change( function(){
		$('#dt_pagamento_despesa').val('');
		$('.pagar').show();
		$('.pago').hide();
	});
	
	$('#st_movimentacao_receitas_paga').change( function(){
		$('#dt_vencimento_receitas').val('');
		$('.pago').show();
		$('.pagar').hide();
	});
	$('#st_movimentacao_receitas_pagar').change( function(){
		$('#dt_pagamento_receitas').val('');
		$('.pagar').show();
		$('.pago').hide();
	});
	
	
	$('#frmCadastro').attr('action', sistemaUrl + 'financeiro/banco/salvar' );
	

	$('#btnSalvarDespesa').click( function(){


	    $("#frmDespesas").validate();


	});
	
	$('#btnSalvarReceitas').click( function(){
		
		
		$("#frmReceitas").validate({

		    rules: {
		        example4: {email:true, required: true},
		        example5: {required: true}
		    },
		    messages: {
		        example5: "Just check the box<h5 class='text-error'>You aren't going to read the EULA</h5>"
		    },
		    tooltip_options: {
		        example4: {trigger:'focus'},
		        example5: {placement:'right',html:true}
		    },

		});
		
		
	});
	
	deletar = function( co_movimentacao ){
		bootbox.confirm("Deseja excluir esta movimentação?", function(result) {
			if( result ){
				$.ajax({
					url: sistemaUrl+'financeiro/movimentacoes/deletar',
					type: "POST",
					data: {
						co_movimentacao : co_movimentacao
					},
					dataType: "json",
					success: function(data){
						if( data.msg == 'success' ){
							var image = '<img style="margin-right: 20px;" src="' + sistemaUrl + 'images/accepted.png">';
							alerta(image + 'Movimentação excluída com sucesso.', sistemaUrl+'financeiro/movimentacoes/index' );
							
						}
					}
					
				});
			}
		}); 
		//;
	}
	
})