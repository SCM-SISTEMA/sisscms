$(function(){
	$("#nu_cpf").mask("999.999.999-99");
	
	$("#btnPesquisar").click(function(){
		if($("#nu_cpf").val() == "" && $("#no_usuario").val() == "" && $("#co_perfil").val() == "" && $("#ds_email").val() == "") {
			alerta("Por favor, informe um filtro de pesquisa!");
			return false;
		}
		
		pesquisaUsuario();
	});
	
	$("#btnNovo").click(function(){
		location.href = sistemaUrl + 'usuario/cadastro';
	});
})

function pesquisaUsuario() {
	$("#pesquisaUsuario").html('');
	
	$.ajax({
		type: "POST",
		dataType: "json",
		cache: false,
		url: sistemaUrl + "usuario/pesquisausuario",
		async: true,
		data: $("#FormUsuario").serialize(),
		beforeSend: function() {
			$("#loadingUsuario").show();
		},
		success: function(data){
			$("#loadingUsuario").hide();

			if(data.length > 0) {
				var resultado = '';
				resultado += '<div class="ui-grid ui-widget ui-widget-content ui-corner-all ui-helper-clearfix">';
				resultado += '	<table cellpadding="0" cellspacing="0" border="0" class="ui-grid-content ui-widget-content" id="paginacao">';
				resultado += '		<thead>';
				resultado += '			<tr align="left">';
				resultado += '				<th class="ui-state-default">Usuário</th>';
				resultado += '				<th class="ui-state-default">CPF</th>';
				resultado += '				<th width="70px" class="ui-state-default">Opções</th>';
				resultado += '			</tr>';
				resultado += '		</thead>';
				resultado += '		<tbody>';		
				for(var i in data) {
				resultado += '			<tr>';
				resultado += '				<td>' + data[i].NO_USUARIO + '</td>';
				resultado += '				<td>' + data[i].NU_CPF + '</td>';
				resultado += '				<td>';
				resultado += '					<div id="icons">';
				resultado += '						<a href="#" class="ui-state-default ui-corner-all" title="Editar" onclick="javascript: editar(\''+ data[i].NU_CPF + '\');"><span class="ui-icon ui-icon-pencil"></span></a>';
				resultado += '						<a href="#" class="ui-state-default ui-corner-all" title="Excluir" onclick="javascript: excluir(\'' + data[i].NU_CPF + '\');"><span class="ui-icon ui-icon-closethick"></span></a>';
				resultado += '					</div>';
				resultado += '				</td>';
				resultado += '			</tr>';
				}
				resultado += '		</tbody>';
				resultado += '	</table>';
				resultado += '</div>';
				
				$("#pesquisaUsuario").html(resultado);
				
				var oTable;
				oTable = $('#paginacao').dataTable( {
			        "bJQueryUI": true,
			        "bLengthChange": true,
			        "bAutoWidth": false,
			        "sPaginationType": "full_numbers",
			        "aoColumns": [
			            {"bSearchable": true, "bVisible": true},
			            {"bSearchable": false, "bVisible": true,"bSortable": false},
			            {"bSearchable": false, "bVisible": true}
			        ],
			        "aaSorting": [
			            [0, 'asc']
			        ]
			    });
			} else {
				alerta("Nenhum registro encontrado!");
			}
		}
	});
}

function editar(nu_cpf) {
	$("#nu_cpf").val(nu_cpf);
	$("#FormUsuario").attr('action',sistemaUrl + 'usuario/cadastro');
	$("#FormUsuario").attr('method','POST');
	$("#FormUsuario").submit();
}

function excluir(nu_cpf) {
	if(confirm("Deseja excluir este registro?")) {
		$.ajax({
			type: "POST",
			dataType: "json",
			cache: false,
			url: sistemaUrl + "usuario/excluir",
			async: true,
			data: {nu_cpf: nu_cpf},
			beforeSend: function() {},
			success: function(data){
				if(data){
					alerta(data.msg_certo);
				}
				pesquisaUsuario();
			}
		});
	}
}