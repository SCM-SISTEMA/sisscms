$(function(){
	$(".telefone").mask("9999-9999");
	$(".ddd_telefone").mask("(99)9999-9999");
	$(".oficio").mask("999/9999");
	$(".ddd").mask("99");
	$(".data").mask("99/99/9999");
	$(".cpf").mask("999.999.999-99");
	$(".cep").mask("99999-999");
	$(".cnpj").mask("99.999.999/9999-99");
	$(".hora").mask("99:99");
	$(".cnae").mask("99.99-9/99");
	$(".maiuscula").css("text-transform", "uppercase");
	$(".minuscula").css("text-transform", "lowercase");
	$(".pmaiuscula").css("text-transform", "capitalize");
	
});