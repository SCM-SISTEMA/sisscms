/* Função personalizada de mensagens */
function alerta(mensagem,acao) {
	if( acao ){
		bootbox.alert(mensagem, acao);
	}else{
		bootbox.alert(mensagem);
	}
}


/*
 * Resposta positiva À funcao confirma()
 */
function confirmado(arg1){	
	location.href = sistemaUrl + arg1;
}

/* Função personalizada de mensagens de confirmação*/
function confirma(mensagem, acao) {	
	bootbox.confirm(mensagem, acao);
}


/* Preenchimento de somente caracteres numéricos
m�todo de chamada: onkeyup="somente_numero(this);"
 */ 
function somente_numero(campo){   
	var digits="0123456789";
	var campo_temp;
    for (var i=0; i <= campo.value.length; i++) {
        campo_temp = campo.value.substring(i,i+1);
        if (digits.indexOf(campo_temp) == -1) {
            campo.value = campo.value.substring(0,i);   
        }   
    }   
}



function somente_texto(e) {
	$(".maiuscula").css("text-transform", "uppercase");
	var k;
	document.all ? k = e.keyCode : k = e.which;
	//alert(k);
	return (
			(k > 64 && k < 91) 
			|| (k > 96 && k < 123) 
			|| (k >= 224) 
			|| k == 8 
			|| k == 32 
			|| k == 193 
			|| k == 194
			|| k == 195
			|| k == 199
			|| k == 201 
			|| k == 202			
			|| k == 205 
			|| k == 206 
			|| k == 211
			|| k == 213
			|| k == 212
			|| k == 218
			|| k == 219
			|| k == 227			
		   );
}



/* Validação de data */
function validacaoData(obj) {
	tmpresult = true;
	var arr = obj.value.split('/');
	var curDate = new Date();
	
	if (obj.value == '') {
		tmpresult = true;
	} else {
		if (arr.length < 3) {
			tmpresult = false;
		} else {                                        
			//tmpresult = (arr[1] <= 12) && (arr[0] <= 31) && (arr[2] <= curDate.getFullYear());
			tmpresult = (arr[1] <= 12) && (arr[0] <= 31);
		}
	}
	if (!tmpresult) {
		alerta('Data Inválida.',obj);
		obj.value = '';
		return false
	}
}

/* Validação de data */
function validarData(data,obj) {

    var dia = data.substr(0,2);
    var mes = data.substr(3,2);
    var ano = data.substr(6,4);

	var date = new Date();
	var blnRet = false;
	var blnDia;
	var blnMes;
	var blnAno;

	date.setFullYear(ano, mes -1, dia);

	blnDia = (date.getDate()     == dia);
	blnMes = (date.getMonth()    == mes -1);
	blnAno = (date.getFullYear() == ano);

	if (blnDia && blnMes && blnAno)
	
	blnRet = true;
	
	if (!blnRet) {
		alerta('Data Inválida.',obj);
		obj.value = '';
		return false;
	}
	return true;
}

function stripos ( f_haystack, f_needle, f_offset ){
    // Finds position of first occurrence of a string within another, case insensitive  
    // 
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/stripos    // +     original by: Martijn Wieringa
    // +      revised by: Onno Marsman
    // *         example 1: stripos('ABC', 'a');
    // *         returns 1: 0
    var haystack = (f_haystack+'').toLowerCase();    var needle = (f_needle+'').toLowerCase();
    var index = 0;
 
    if ((index = haystack.indexOf(needle, f_offset)) !== -1) {
        return index;    }
    return false;
}

function is_numeric (mixed_var) {
    // Returns true if value is a number or a numeric string  
    // 
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/is_numeric    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: David
    // +   improved by: taith
    // +   bugfixed by: Tim de Koning
    // +   bugfixed by: WebDevHobo (http://webdevhobo.blogspot.com/)    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: is_numeric(186.31);
    // *     returns 1: true
    // *     example 2: is_numeric('Kevin van Zonneveld');
    // *     returns 2: false    // *     example 3: is_numeric('+186.31e2');
    // *     returns 3: true
    // *     example 4: is_numeric('');
    // *     returns 4: false
    // *     example 4: is_numeric([]);    // *     returns 4: false
    return (typeof(mixed_var) === 'number' || typeof(mixed_var) === 'string') && mixed_var !== '' && !isNaN(mixed_var);
}

function strpos (haystack, needle, offset) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Onno Marsman    
    // +   bugfixed by: Daniel Esteban
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: strpos('Kevin van Zonneveld', 'e', 5);
    // *     returns 1: 14

    var i = (haystack+'').indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
}

function strcasecmp (f_string1, f_string2){
    // http://kevin.vanzonneveld.net
    // +     original by: Martijn Wieringa
    // +     bugfixed by: Onno Marsman
    // *         example 1: strcasecmp('Hello', 'hello');
    // *         returns 1: 0

    var string1 = (f_string1+'').toLowerCase();
    var string2 = (f_string2+'').toLowerCase();

    if (string1 > string2) {
      return 1;
    }
    else if (string1 == string2) {
      return 0;
    }

    return -1;
}

function explode (delimiter, string, limit) {
    // http://kevin.vanzonneveld.net
    // +     original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: kenneth
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: d3x
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    // *     example 2: explode('=', 'a=bc=d', 2);
    // *     returns 2: ['a', 'bc=d']
 
    var emptyArray = { 0: '' };
    
    // third argument is not required
    if ( arguments.length < 2 ||
        typeof arguments[0] == 'undefined' ||
        typeof arguments[1] == 'undefined' ) {
        return null;
    }
 
    if ( delimiter === '' ||
        delimiter === false ||
        delimiter === null ) {
        return false;
    }
 
    if ( typeof delimiter == 'function' ||
        typeof delimiter == 'object' ||
        typeof string == 'function' ||
        typeof string == 'object' ) {
        return emptyArray;
    }
 
    if ( delimiter === true ) {
        delimiter = '1';
    }
    
    if (!limit) {
        return string.toString().split(delimiter.toString());
    } else {
        // support for limit argument
        var splitted = string.toString().split(delimiter.toString());
        var partA = splitted.splice(0, limit - 1);
        var partB = splitted.join(delimiter.toString());
        partA.push(partB);
        return partA;
    }
}

function retiraCaracter(string, caracter) {
    var i = 0;
    var fim = '';
    while (i < string.length) {
        if (string.charAt(i) == caracter) {
            fim += string.substr(0, i);
            string = string.substr(i+1, string.length - (i+1));
            i = 0;
        }
        else {
            i++;
        }
    }
    return fim + string;
}

function validarMail(campo){
	var er = new RegExp(/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/);
	if(typeof(campo) == "string"){
		if(er.test(campo)){
			return true;
		}
	}else if(typeof(campo) == "object"){
		if(er.test(campo.value)){
			return true;
		}
	}
	return false;
}

function verificarEmail(obj) {
	if(!validarMail(obj)) {
		alerta('Por favor informe um email válido.',obj);
	}
}

function validarCPF(campo){
   var cpf = campo;
   var filtro = /^\d{3}.\d{3}.\d{3}-\d{2}$/i;
   if(!filtro.test(cpf)){
	 return false;
   }

   cpf = retiraCaracter(cpf, ".");
   cpf = retiraCaracter(cpf, "-");

   if(cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" ||
	  cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" ||
	  cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" ||
	  cpf == "88888888888" || cpf == "99999999999"){
	  return false;
   }

   soma = 0;
   for(i = 0; i < 9; i++)
   	 soma += parseInt(cpf.charAt(i)) * (10 - i);
   resto = 11 - (soma % 11);
   if(resto == 10 || resto == 11)
	 resto = 0;
   if(resto != parseInt(cpf.charAt(9))){
	 return false;
   }
   soma = 0;
   for(i = 0; i < 10; i ++)
	 soma += parseInt(cpf.charAt(i)) * (11 - i);
   resto = 11 - (soma % 11);
   if(resto == 10 || resto == 11)
	 resto = 0;
   if(resto != parseInt(cpf.charAt(10))){
	 return false;
   }
   return true;
}

function verificaCpf(obj) {
	if(!validarCPF(obj.value)) {
		alerta('Por favor informe um CPF válido.',obj);
		return false;
	}
	return true;
}

$(".telefone").inputmask("9999-9999");
$(".ie").inputmask("999999999-0");
$(".ddd_telefone").inputmask("(99)9999-9999");
$(".ddd").inputmask("99");
$(".parcelas").inputmask("99");
$(".data").inputmask("99/99/9999");
$(".cpf").inputmask("999.999.999-99");
$(".cep").inputmask("99999-999");
$(".cnpj").inputmask("99.999.999/9999-99");
$(".cnae").inputmask("mask", {"mask": "99.99-9/99"});
$(".hora").inputmask("99:99");
$(".ano").inputmask("9999");
$(".maiuscula").css("text-transform", "uppercase");
$(".minuscula").css("text-transform", "lowercase");
$(".pmaiuscula").css("text-transform", "capitalize");
$('a[title]').tooltip();


function decode_base64(s) {
    var e={},i,k,v=[],r='',w=String.fromCharCode;
    var n=[[65,91],[97,123],[48,58],[43,44],[47,48]];

    for(z in n){for(i=n[z][0];i<n[z][1];i++){v.push(w(i));}}
    for(i=0;i<64;i++){e[v[i]]=i;}

    for(i=0;i<s.length;i+=72){
    var b=0,c,x,l=0,o=s.substring(i,i+72);
         for(x=0;x<o.length;x++){
                c=e[o.charAt(x)];b=(b<<6)+c;l+=6;
                while(l>=8){r+=w((b>>>(l-=8))%256);}
         }
    }
    return r;
    }

function base64_encode(data) {
	  //  discuss at: http://phpjs.org/functions/base64_encode/
	  // original by: Tyler Akins (http://rumkin.com)
	  // improved by: Bayron Guevara
	  // improved by: Thunder.m
	  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // improved by: Rafał Kukawski (http://kukawski.pl)
	  // bugfixed by: Pellentesque Malesuada
	  //   example 1: base64_encode('Kevin van Zonneveld');
	  //   returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
	  //   example 2: base64_encode('a');
	  //   returns 2: 'YQ=='

	  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
	  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
	    ac = 0,
	    enc = '',
	    tmp_arr = [];

	  if (!data) {
	    return data;
	  }

	  do { // pack three octets into four hexets
	    o1 = data.charCodeAt(i++);
	    o2 = data.charCodeAt(i++);
	    o3 = data.charCodeAt(i++);

	    bits = o1 << 16 | o2 << 8 | o3;

	    h1 = bits >> 18 & 0x3f;
	    h2 = bits >> 12 & 0x3f;
	    h3 = bits >> 6 & 0x3f;
	    h4 = bits & 0x3f;

	    // use hexets to index into b64, and append result to encoded string
	    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
	  } while (i < data.length);

	  enc = tmp_arr.join('');

	  var r = data.length % 3;

	  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
	}

function limparForm(){
	$(':input', 'form').not(':button, :submit, :reset').val('').removeAttr('checked').removeAttr('selected');
	if(tinyMCE.activeEditor){
		tinyMCE.activeEditor.setContent('');
	}
}