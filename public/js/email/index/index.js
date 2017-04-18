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

	$('#marcarTodos').change(function(){
		$('input:checkbox').prop('checked', !$(this).prop('checked'));
		$('input:checkbox').prop("checked", $(this).prop("checked"));
	});

	$('#co_pessoa').change(function(){
		$('#nu_cep').val($('#co_pessoa option:selected').attr('cep'));
		$('#no_rastreamento').val($('#co_pessoa option:selected').html());
	});

	$('#exibirInativos').click(function(){
		location.href = sistemaUrl+'financeiro/rastreamento/index/inativos/1';
	});

	limparForm = function(){
		$(':input', 'form').not(':button, :submit, :reset, :radio').val('').removeAttr('checked').removeAttr('selected');
		$(':input:radio', 'form').removeAttr('checked');
	}

})