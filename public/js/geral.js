$(function(){
	$("#btnPausa").click(function(){
		location.href = sistemaUrl + 'usuario/pausa';
	});
	
	function retornaDMY( data ){
		var d = data;
		var dataHora = d.split( " " );

						var hora = dataHora[1];


		       			var data = dataHora[0].split( '-');
		       			var data = data.reverse();
		       			var data = data.join('/');

		return data + ' ' + hora;
	}
});