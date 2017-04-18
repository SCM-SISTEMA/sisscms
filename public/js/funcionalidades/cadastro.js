$(function(){
	$('#listagemFuncionalidades').dataTable();
	
	$('#btnSalvar').click(function(){
		formValidation();
	});
	
	formValidation = function() {
	    "use strict";
	    
	    

	    /*----------- BEGIN validate CODE -------------------------*/
	    $('#frmCadastro').validate({
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
	    		$.ajax({
	    			url: sistemaUrl+'funcionalidades/salvar',
	    			type: "POST",
	    			data: $('#frmCadastro').serialize(),
	    			dataType: "json",
	    			success: function(data){
	    				alert(data.msg );
	    				location.href = sistemaUrl+'funcionalidades/cadastro';
	    			}
	    		});
	    		
	    	}
	    });


	}
	
	
})