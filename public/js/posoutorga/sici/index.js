$(function(){
	//mascara do campo renda
	$('.moeda').priceFormat({
		prefix: '',
		centsSeparator: ',',
		thousandsSeparator: '',
		limit:13
	});

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
	$('#cidades').dataTable( {
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

	gerarArquivo = function(co_cliente, tp_pessoa) {
		location.href = sistemaUrl + 'posoutorga/sici/gerar-arquivo/id_pessoa/' + co_cliente + '/tp_pessoa/'+ tp_pessoa;
	}

	vincularMunicipios = function( id ){
		$('#modalMunicipios').modal('show');

		$('#cidades_wrapper div[class=col-xs-6]').attr('class', 'col-xs-12').prepend('<div class="clear">&nbsp;</div>');
		$('#cidades input').prop({checked: false});

		$('#btnVincular').click( function(){

			var html = '';
			j=0;
			$('#cidades input:checked').each( function(){
				var alfabeto = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o'];
				switch(id){
					case 'qaipl4sm':
						html += '<div class="control-group">';
						html += '	<label class="col-lg-2 control-label" for="ds_plano">Municipio: <span style="color:red">*</span></label>';
						html += '	<div class="col-lg-10">';
						html += '		<input type="text" required="required" class="input-xxlarge" name="'+id+'['+j+'][co_municipio]" value="'+$(this).val()+'">';
						html += '	</div>';
						html += '</div>';
						html += '<div class="clear">&nbsp;</div>';
						var i = 0;
						for( i=0;i<alfabeto.length; i++){
							html += '<div class="control-group">';
							html += '	<label class="col-lg-2 control-label" for="ds_plano">'+ alfabeto[i].toUpperCase()+': <span style="color:red">*</span></label>';
							html += '	<div class="col-lg-10">';
							html += '		<input type="text" class="col-lg-2 moeda" name="'+id+'['+j+'][item]['+alfabeto[i]+'][total]" placeholder="Total">';
							html += '		<input type="text" class="col-lg-2 moeda" name="'+id+'['+j+'][item]['+alfabeto[i]+'][faixa15]" placeholder="Faixa 15">';
							html += '		<input type="text" class="col-lg-2 moeda" name="'+id+'['+j+'][item]['+alfabeto[i]+'][faixa16]" placeholder="Faixa 16">';
							html += '		<input type="text" class="col-lg-2 moeda" name="'+id+'['+j+'][item]['+alfabeto[i]+'][faixa17]" placeholder="Faixa 17">';
							html += '		<input type="text" class="col-lg-2 moeda" name="'+id+'['+j+'][item]['+alfabeto[i]+'][faixa18]" placeholder="Faixa 18">';
							html += '		<input type="text" class="col-lg-2 moeda" name="'+id+'['+j+'][item]['+alfabeto[i]+'][faixa19]" placeholder="Faixa 19">';
							html += '<div class="clear">&nbsp;</div>';
							html += '	</div>';
							html += '</div>';
						}
						html += '&nbsp;<hr class="clear">';
						break;
					case 'ipl3':
						html += '<div class="control-group">';
						html += '	<label class="col-lg-2 control-label" for="ds_plano">Municipio: <span style="color:red">*</span></label>';
						html += '	<div class="col-lg-10">';
						html += '		<input type="text" required="required" class="input-xxlarge" name="'+id+'['+j+'][co_municipio]" value="'+$(this).val()+'">';
						html += '	</div>';
						html += '</div>';
						html += '<div class="control-group">';
						html += '	<label class="col-lg-2 control-label" for="ds_plano">F: <span style="color:red">*</span></label>';
						html += '	<div class="col-lg-10">';
						html += '		<input type="text" class="input-xxlarge moeda" name="'+id+'['+j+'][item][f][a]">';
						html += '	</div>';
						html += '</div>';
						html += '<div class="control-group">';
						html += '	<label class="col-lg-2 control-label" for="ds_plano">J: <span style="color:red">*</span></label>';
						html += '	<div class="col-lg-10">';
						html += '		<input type="text" class="input-xxlarge moeda" name="'+id+'['+j+'][item][j][a]">';
						html += '	</div>';
						html += '</div>';
						html += '&nbsp;<hr class="clear">';
						break;
				}
				j++;
			});
			$('#'+id).html(html);

			//mascara do campo renda
			$('.moeda').priceFormat({
				centsSeparator: ',',
				prefix: '',
				thousandsSeparator: '',
				limit:13
			});

			$('#modalMunicipios').modal('hide');
		});
	}


	$( '#btnGerarArquivo' ).click( function(){

		if ( $( '#tp_pessoa_pj' ).is(':checked') ){
			if( $('#hdnSocio :input').length == 0 ){
				alert('Por favor, cadastre ao menos um sócio!');
				return false;
			}
		}

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmGerarArquivo').validate({
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
					url: sistemaUrl + "posoutorga/sici/criar-arquivo-xml",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							$('#btnSalvar').addClass('hide');
							$('#message_success').removeClass('hide').show('slide');
							$('#message_success .alert-heading').html(data.message);
							$('#message_success').delay(5000).hide('slide');
							setTimeout( function(){
								location.href = sistemaUrl+'administracao/clientes/index';
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