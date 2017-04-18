/* Função acessar */
function acessar() {
	if($("#ds_email").val() == "") {
		alerta("Por favor informe o e-mail.",$("#ds_email"));
		return false;
	}
	
	if($("#co_senha").val() == "") {
		alerta("Por favor informe a senha.",$("#co_senha"));
		return false;
	}
	document.FormAcesso.submit();
}

$(function(){
	$('#btnNovaSenha').click(function(){
		location.href= sistemaUrl + 'autenticacao/novasenha';
	});
	
	$('#btnSalvar').click( function(){
		$.ajax({
			url: sistemaUrl+'autenticacao/login',
			type: "POST",
			data: {
				ds_usuario : $('#ds_usuario').val(),
				ds_senha : $('#ds_senha').val()
			},
			dataType: "json",
			success: function(data){
				if(data.msg != 'ok'){
					alerta("Esse CNPJ já existe, por este motivo não será possível concluir o cadastro!");
					return false;
				}
			}
			
		});
	});
})