$(function(){
	
	$("#table").tablecloth({
		theme: "stats",
		bordered: true,
		condensed: true,
		striped: true,
		sortable: true,
		clean: true,
	});
	
	$('#modalCadastro').modal('hide');

	$('#frmServicos').attr('action', sistemaUrl + 'administracao/servicos/salvar' );
	$('#frmTipoContrato').attr('action', sistemaUrl + 'administracao/servicos/salvar-tipo-contrato' );
	
	editar = function( co_servico ){
		$.ajax({
			url: sistemaUrl+'administracao/servicos/editar',
			type: "POST",
			data: {
				co_servico : co_servico
			},
			dataType: "json",
			success: function(data){
				if( data.co_servico ){
					$('#modalCadastro').modal('show');
					$('#co_servico').val( data.co_servico);
					$('#no_servico').val( data.no_servico);
					tinyMCE.get('ds_servico').setContent(data.ds_servico)
				}
			}
			
		});
	}
	
	tinymce.init({
	    selector: "textarea",
	    plugins: [
			" autolink lists link  charmap anchor",
			"searchreplace ",
			"insertdatetime  paste"
	    ],
	    language : "pt_BR",
	    toolbar: ""
	});		
	
	$('#mySubmitButton').click(function() {
	    var content = tinyMCE.activeEditor.getContent(); // get the content
	    $('#ds_clausula').val(content); // put it in the textarea
	    if( $('#ds_clausula').val() == ''){
	    	alerta('Preencha o campo Cláusula.');
	    }
	});

	
	adicionarClausula = function( co_servico ){
		$('#modalClausula').modal('show');
		$('#co_servico_clausula').val( co_servico );
	}
	
	listarClausulas = function( co_servico ){
		$('#modalListaClausula').modal('show');
		$('#modalbodyClausulas').load( sistemaUrl + 'administracao/servicos/listar-clausulas/co_servico/' + co_servico );
	}
	
//	$('#btnSalvar').click( function(){
//		$.ajax({
//			url: sistemaUrl+'autenticacao/login',
//			type: "POST",
//			data: {
//				ds_usuario : $('#ds_usuario').val(),
//				ds_senha : $('#ds_senha').val()
//			},
//			dataType: "json",
//			success: function(data){
//				if(data.msg != 'ok'){
//					alerta("Esse CNPJ já existe, por este motivo não será possível concluir o cadastro!");
//					return false;
//				}
//			}
//			
//		});
//	});
})