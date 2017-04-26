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
	
	$('#frmCategororia').attr('action', sistemaUrl + 'administracao/cnae/salvar' );
	
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
	
	deletar = function(){
		location.href = sistemaUrl + 'administracao/cnae/deletar/co_cnae/' + $('#co_modal').val();
	}

	$( '#btnSalvar' ).click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadCnae').validate({
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
					url: sistemaUrl + "administracao/cnae/salvar",
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