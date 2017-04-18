$(function(){

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

	$('#co_servico_posoutorga').multiselect();

	limpar = function(){
		$(':input', '#frmCadServico').not(':button, :submit, :reset').val(' ');
		tinyMCE.activeEditor.setContent('');
	}

	vincularServico = function(co_plano) {
		$('#modalVicularServicos').modal('show');
		$('#co_plano_servico').val(co_plano);
		$("input[name='data[co_servico][]']").prop('checked',false);

		$.ajax({
			type: "POST",
			url: sistemaUrl + "posoutorga/planos/retornar-servicos-plano",
			data: {co_plano: co_plano},
			dataType: 'json',
			success: function( data )
			{
				if( data ){

					for (i = 0; i < data.length; i++) {
						$("input[name='data[co_servico][]'][value='"+data[i].co_servico+"']").prop('checked', true);
					}
				}
			}
		});

	}

	$( '#btnSalvarPlano' ).click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadPlano').validate({
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
					url: sistemaUrl + "posoutorga/planos/salvar",
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
								location.href = sistemaUrl+'posoutorga/planos/index';
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

	$( '#btnVincularPlanoServico' ).click( function(){

		/*----------- BEGIN validate CODE -------------------------*/
		$('#frmCadPlanoServico').validate({
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
					url: sistemaUrl + "posoutorga/planos/vincular-servico-plano",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							$('#modalVicularServicos').modal('hide');
							$('#btnSalvar').addClass('hide');
							$('#message_success').removeClass('hide').show('slide');
							$('#message_success .alert-heading').html(data.message);
							$('#message_success').delay(2000).hide('slide');
							setTimeout( function(){
								location.href = sistemaUrl+'posoutorga/planos/index';
							}, 2000);

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

	$( '#btnSalvarServico' ).click( function(){

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
				var dados = $( form ).serialize();

				$.ajax({
					type: "POST",
					url: sistemaUrl + "administracao/servicos/salvar",
					data: dados,
					dataType: 'json',
					success: function( data )
					{
						if( data.msg == 'success' ){
							$('#btnSalvar').addClass('hide');
							$('#message_success').removeClass('hide').show('slide');
							$('#message_success .alert-heading').html(data.message);
							$('#message_success').delay(5000).hide('slide');
							$('#modalCadastroServico').modal('hide');
						}else{
							$('#message_error').removeClass('hide').show('slide');
							$('#message_error .alert-heading').html(data.message);
							//$('#message_error').delay(5000).hide('slide');
						}
					}
				});

				return false;
			}
		});


	});
})