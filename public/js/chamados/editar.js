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
	
	$("[data-mask]").inputmask();
	
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
	
	$('#status').change(function(){
		$('#formChamado').submit();
	});
	
	$('#provedor').change(function(){
		$('#formChamado').submit();
	});
	
	$('#clientes').change(function(){
		if( $(this).val() > 0 ){
			$('#frmAbrirChamado input, checkbox, select').each( function(){
			  if( $(this).attr('disabled') == 'disabled' ){
			    $(this).removeAttr('disabled');
			  }
			});
		}
		$('#status').attr('disabled', 'disabled');
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
	        }
	    });


	}
	
	
})