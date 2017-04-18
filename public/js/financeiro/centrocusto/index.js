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

	$("[data-mask]").inputmask();

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

	$('#co_pessoa').change(function(){
		$('#nu_cep').val($('#co_pessoa option:selected').attr('cep'));
		$('#no_rastreamento').val($('#co_pessoa option:selected').html());
	});

	$('#novoRegistro').click(function(){
		limparForm();
	});

	$('#exibirInativos').click(function(){
		location.href = sistemaUrl+'financeiro/rastreamento/index/inativos/1';
	});

	editar = function( co_centro_custo ){
		limparForm();
		$.ajax({
			url: sistemaUrl+'financeiro/centrocusto/editar',
			type: "POST",
			data: {
				co_centro_custo : co_centro_custo
			},
			dataType: "json",
			success: function(data){
				if( data.co_centro_custo ){
					$('#modalCadastroCentroCusto').modal('show');
					
					$('#co_centro_custo').val( data.co_centro_custo);
					$('#ds_centro_custo').val( data.ds_centro_custo);
					if(data.st_custo_variavel == 'S'){
						$('#st_custo_variavel_s').prop( "checked", true )
					}else{
						$('#st_custo_variavel_s').prop( "checked", false )
						$('#st_custo_variavel_n').prop( "checked", true )

					}

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