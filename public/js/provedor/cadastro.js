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
	
	$("[data-mask]").inputmask();
	
	//iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });
	
	
	$('#btnSalvar').click(function(){
		formValidation();
	});
	
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
	    $('#frmCadastroProvedor').validate({
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
	    		form.submit();
	    	}
	    });


	}
	
	
})