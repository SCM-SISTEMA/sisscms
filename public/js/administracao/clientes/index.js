$(function(){

	$('#clientes').dataTable( {
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

	editar = function( id, tp_pessoa ){
		location.href = sistemaUrl + 'administracao/clientes/cadastro/id/' + id + '/tp_pessoa/' + tp_pessoa;
	}

	visualizar = function( id, tp_pessoa ){
		location.href = sistemaUrl + 'administracao/clientes/visualizar/id/' + id + '/tp_pessoa/' + tp_pessoa;
	}

	$('#novoRegistro').click( function(){
		location.href = sistemaUrl + 'administracao/clientes/cadastro/';
	});

})