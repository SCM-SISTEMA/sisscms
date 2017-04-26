$(function(){
	$('#dt_inicio').datepicker({
	    format: 'dd/mm/yyyy',                
	    language: 'pt-BR'
	});
	$('#dt_fim').datepicker({
		format: 'dd/mm/yyyy',                
		language: 'pt-BR'
	});
	
	$('#dt_consulta_inicio').inputmask('99:99');
	$('#dt_consulta_fim').inputmask('99:99');
	
	$('#btnGerar').click( function(){
		
		var data_inicial = new Date( $('#dt_inicio').val() );
		var data_final = new Date( $('#dt_fim').val() );

		if( data_inicial > data_final ){
		  alert('A data inicial deve ser maior que a data final');
		  return false;
		}
		
		if( $('#tipo-chamados').is(':checked') || $('#tipo-provedor').is(':checked') || $('#tipo-atendente').is(':checked') || $('#tipo-usuario').is(':checked') || $('#tipo-geral').is(':checked') ){
			
			if( $('#tipo-provedor').is(':checked') ){
				if( $('#provedor').val() == '' ){
					alert('Selecione um provedor!');
					return false;
				}
			}
			
			if( $('#tipo-atendente').is(':checked') ){
				if( $('#usuario').val() == '' ){
					alert('Selecione um Atendente!');
					return false;
				}
			}
			
			if( $('#tipo-usuario').is(':checked') ){
				if( $('#usuario').val() == '' ){
					alert('Selecione um Usuário!');
					return false;
				}
			}
			
			if( $('#tipo-geral').is(':checked') ){
				if( $('#dt_inicio').val() == '' && $('#dt_fim').val() == '' ){
					alert('Selecione um período!');
					return false;
				}
			}
			
			$('#formRelatorio').submit();
		}else{
			alert('Você deve selecionar um tipo!');
			return false;
		}
		
		if( $('#chamados').is(':checked') ){
			var startTime = $('#dt_consulta_inicio').val();
			var endTime = $('#dt_consulta_fim').val();
			var regExp = /(\d{1,2})\:(\d{1,2})\:(\d{1,2})/;
			if(parseInt(endTime .replace(regExp, "$1$2$3")) < parseInt(startTime .replace(regExp, "$1$2$3"))){
				alert("A hora inicial deve ser menor que a hora final");
				return false;
			}
		}
	});
	
	
	
})