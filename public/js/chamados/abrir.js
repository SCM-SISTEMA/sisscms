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
	
	
	$('#btnSalvar').click(function(){
		formValidation();
	});
	
	$('#a_info_mod_prov').click(function(){
		if( $('#provedor').val() > 0 ){
			$.ajax({
	    		type: "POST",
	    		url: sistemaUrl + "provedor/retorna-observacoes",
	    		data: {provedor: $('#provedor').val()},
	    		dataType: 'json',
	    		success: function( data )
	    		{
	    			$('#conteudoModalInfoProv').html( data.obs );
	    		}
    		});
		}else{
			var msg = 'Por favor, selecione um provedor!';
			$('#mensagem').html( msg );
			$('#mensagem').removeClass( 'hide' );
			setTimeout(function(){ $('#mensagem').addClass( 'hide' ); }, '3000')
			return false;
		}
	});
	
	$('#status').attr('disabled', 'disabled');
	
	$('#provedor').change(function(){
		var select = '';
		$.post( sistemaUrl + "chamados/retorna-clientes/provedor", { provedor: $( this ).val() }, function( data ) {
			for (i = 0; i < data.length; i++) {
				select += '<option value="'+ data[i].id +'">' + data[i].nome + '</option>';
			}
			$("#clientes option[value!='0']").remove();
			$('#clientes').append(select);
			
			
		}, "json");
		$('#clientes').removeAttr('disabled');
		$('#btnRecentes').removeClass('hide');
		
		
	});
	
	$('#a_info_prob').click(function(){
		if( $('#problema').val() == '' ){
			var msg = 'Por favor, selecione um problema!';
			$('#mensagem').html( msg );
			$('#mensagem').removeClass( 'hide' );
			$('#mensagem').focus();
			setTimeout(function(){ $('#mensagem').addClass( 'hide' ); }, '3000')
			return false;
		}else{
			$('#box_info_prob').removeClass('hide');
			$('#info_prob').html( $('#problema option:selected').attr('descricao') );
		}
	});
	
	$('#problema').change(function(){
		$('#a_info_prob').removeClass('hide');
	});
	
	$('#clientes').change(function(){
		if( $(this).val() > 0 ){
			$('#frmAbrirChamado input, checkbox, select').each( function(){
			  if( $(this).attr('disabled') == 'disabled' ){
			    $(this).removeAttr('disabled');
			  }
			});
		}
		
		$('#status').attr('disabled','disabled');
		
		$("[data-mask]").inputmask();
		
		$('#btnSalvar').removeClass('hide');
	});

	$('#provedor').change( function(){
		$( '#btnRecentes' ).show();
		$( '#conteudoChamado' ).show();
	});
	
	$('#btnRecentes').click( function(){
		if( $( '#clientes' ).val() == 0 ){
			var msg = 'Por favor, selecione um clientes!';
			$('#mensagem').html( msg );
			$('#mensagem').removeClass( 'hide' );
			setTimeout(function(){ $('#mensagem').addClass( 'hide' ); }, '3000')
			return false;
		}else{
			$.post( sistemaUrl + "chamados/recentes/clientes/" + $( '#clientes' ).val(), function( data ) {
				$( ".modal-title" ).html( 'Chamados Recentes' );
				$( "#conteudoModal" ).html( data );
			});
				$('#chamadosRecentes').show();
		}
	});
	
	formValidation = function() {
	    "use strict";
	    
	    

	    /*----------- BEGIN validate CODE -------------------------*/
	    $('#frmAbrirChamado').validate({
	        rules: {
	            required: "required"
	        },
	        errorClass: 'help-block col-lg-6',
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
	    		url: sistemaUrl + "chamados/retorna-chamados-abertos",
	    		data: dados,
	    		dataType: 'json',
	    		success: function( data )
	    		{
	    			if( data.success ){
	    				$('#btnSalvar').addClass('hide');
	    				form.submit();
	    			}else{
	    				var msg = 'O chamado <a href="' + sistemaUrl + 'chamados/editar/id_chamado/' + data.id+ '">' + data.protocolo + '</a> do dia ' + data.abertura + ' est&aacute; aberto para este clientes e com <b style="color:red">PRAZO EXPIRADO</b>.';
	    				$( "#tituloModalExistente" ).html( 'Prazo Expirado!' );
	    				$( "#conteudoModalExistente" ).html( msg );
	    				$('#chamadosModalExistente').modal('show');
	    			}
	    		}
	    		});
	    		 
	    		return false;
	    	}
	    });


	}
	
	
})