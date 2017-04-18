//Specify affected tags. Add or remove from list:
var tgs = new Array( 'div', 'class', 'a', 'ul', 'ol', 'li', 'span' );

//Specify spectrum of different font sizes:
var szs = new Array( 'xx-small','x-small','11px','small','medium','large');
var startSz = 2;

function array_merge() {
	var merged = new Array();
	for( var i = 0; i < arguments.length; i++ )
	{
		for( var j = 0; j < arguments[i].length; j++ )
		{
			merged = merged.concat( arguments[i][j] );
		}
	}
	return merged;
}
function pesquisa()
{
	busca = document.getElementById("busca1").value;
	if( busca =='' ){
		alert('O campo de pesquisa esta vazio.');
		return;
	}

	url = 'http://portal.mec.gov.br/sesu/index.php?searchword='+ busca +'&submit=Pesquisar&option=search';
	window.open(url,'pesquisa','scrollbars=yes,resizable=yes,width=785,height=460');
}

function textoDentro(){
     busca = document.getElementById("busca1").value;
     if(busca ==' digite aqui...'){
         document.getElementById("busca1").value = "";
     }
     if(busca == '')
     {
           document.getElementById("busca1").value = " digite aqui...";
     }
}
function ts( trgt,inc ) {


	

if (!document.getElementById) return
	var d = document,cEl = null,sz = startSz,i,j,cTags;
	
	sz += inc;
	
	if ( sz > 5 ) return;
	if (inc == 0 ) sz = 2;
	if ( sz < -1 ) return;
	
	    startSz = sz;
		if ( !( cEl = d.getElementById( trgt ) ) ) cEl = d.getElementsByTagName( trgt )[ 0 ];
		
		cEl.style.fontSize = szs[ sz ];
		
		for ( i = 0; i < tgs.length; i++ ) {
		cTags = cEl.getElementsByTagName( tgs[ i ] );
		
		for ( j = 0; j < cTags.length; j++ ) {cTags[ j ].style.fontSize = szs[ sz ];}
	}
	
}

function nova_janela( obj )
{
   if ( obj.value != '0' )
   {
      var newWindow = window.open( obj.value,'site' );
      newWindow.focus();
   }
}
function MM_openBrWindow( theURL, winName, features )
{
    var newWindow = window.open( theURL, winName, features );
    newWindow.focus();
}

function redirecionar( url )
{
    window.location.href = url;
}

//-----------------  Fun��es para manipular o Editor HTML ------------------------------------
<!--
	var EW_DHTMLEditors = [];
	function updateEditor() {
		if (typeof EW_DHTMLEditors != 'undefined') {
			if (FCKeditorAPI) {
				var inst;
				for (inst in FCKeditorAPI.__Instances){
					//document.getElementById(inst).focus();
					FCKeditorAPI.__Instances[inst].UpdateLinkedField();
				}	
			}
		}
		
	}

//-->

function EW_DHTMLEditor(name) {
	this.name = name;
	this.create = function() { this.active = true; }
	this.editor = null;
	this.active = false;
}


function EW_createEditor(name) {
	if (typeof EW_DHTMLEditors == 'undefined')
		return;
	for (var i = 0; i < EW_DHTMLEditors.length; i++) {
    var ed = EW_DHTMLEditors[i];
		var cr = !ed.active;
		if (name) cr = cr && ed.name == name;
		if (cr) {
			if (typeof ed.create == 'function')
				ed.create();
			if (name)
				break;
		}
	}
}
//--------------------------------------------------------------------------------------------

// verifica se o browser tem suporte a ajax
function ajaxTry()
{
	try
	{
		ajax = new ActiveXObject("Microsoft.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(ex)
		{
			try
			{
				ajax = new XMLHttpRequest();
			}
			catch(exc)
			{
				alert("Esse browser n�o tem recursos para uso do Ajax");
				ajax = null;
			}
		}
	}
	
	return ajax;
}
// Fim da verifica��o do browser.

// TRIM - javascript
// modo de usar: myvar.trim();
String.prototype.trim = function()
{
	return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

// Fun��o generica para mudar de pagina usando a pagin��o generica do adodb
// redireciona para a mesma p�gina mantendo os par�metros passados via GET e incluindo o par�metro emec_next_page
// este metodo pode ser sobrescrito
function mudaPagina( emec_next_page )
{
	var url 				= document.location.href.substring( 0, document.location.href.indexOf( '?' ) );
	var params				= document.location.href.substring( document.location.href.indexOf( '?' ) + parseInt(1) ).split( '&' );
	var newParams			= "?";
	
	for(var i = 0; i < params.length; i++ )
	{
		if( params[i].indexOf( 'emec_next_page' ) >= 0 )
		{
			continue;
		}
		if( newParams == '?' )
		{
			newParams += params[i];
		}
		else
		{
			newParams += '&' + params[i];
		}
	}
	
	newParams += '&emec_next_page=' + emec_next_page;
	
	if( newParams != '?' )
	{
		url = url + newParams
	}
	
	document.location.href = url;
}

function atualizaXinha( elementId )
{
	if (typeof HTMLElement != 'undefined' && typeof HTMLElement.prototype.__defineGetter__ == 'function'){	
	HTMLElement.prototype.__defineGetter__("innerText", function () {	
		return this.textContent;
	});
	}
	
	var element		= window.document.getElementById( elementId );
	var oFrameXinha = window.document.getElementById( 'XinhaIFrame_' + elementId );
	if( oFrameXinha )
	{
		if( oFrameXinha.contentDocument )
		{
			 sTextHTML = oFrameXinha.contentDocument.body.innerHTML;
			 sTextTEXT = oFrameXinha.contentDocument.body.textContent;								     
		}
		else
		{
			 sTextHTML = oFrameXinha.Document.body.innerHTML;
			 sTextTEXT = oFrameXinha.Document.body.innerText;
		}

		if( sTextTEXT.length > 1 )
		{
			element.value = sTextHTML;
		}
		else
		{
			element.value = '';
		}
	}
}

/**
 * Preenche o campo pager_id da ultima pagina��o renderizada
 */
function populateLastPagerId()
{
	var pager_id 		= document.getElementsByName( 'pager_id' );
	var	actual_pager_id	= 0;
	var last_pager_id	= getLastPagerId();
	
	if( pager_id.length == 0 )
		return;
			
	if( pager_id.length > 1 )
	{
		actual_pager_id = getMaxPagerId() + parseInt(1);
	}
	if( !last_pager_id )
	{
		actual_pager_id = 0;
	}
	
	last_pager_id.value = parseInt( actual_pager_id );
}
function getMaxPagerId()
{
	var pager_id 		= document.getElementsByName( 'pager_id' );
	var	max_pager_id	= 0;
	
	if( pager_id.length == 0 )
		return;
		
	if( pager_id.length > 1 )
	{
		for( var i = 0; i < parseInt( pager_id.length ); i++ )
		{
			if( parseInt( pager_id[i].value ) >= parseInt( max_pager_id ) )
			{
				max_pager_id = parseInt( pager_id[i].value );
			}
		}
	}
	else
	{
		return parseInt( pager_id[0].value );
	}
	
	return max_pager_id;
}
function getLastPagerId()
{
	var pager_id 		= document.getElementsByName( 'pager_id' );
	var last_pager_id;
	
	if( pager_id.length == 0 )
		return;
			
	if( pager_id.length > 1 )
	{		
		for( var i = 0; i < pager_id.length; i++ )
		{
			if( pager_id[i].value == '' )
			{
				last_pager_id = pager_id[i];
			}
		}
	}
	if( !last_pager_id )
	{
		last_pager_id 	= pager_id[0];
	}
	
	return last_pager_id;
}
/**
 * Preenche todos os campos pager_id de todas as pagina��es renderizadas
 */
function repopulateAllPagerId()
{
	var pager_id 		= document.getElementsByName( 'pager_id' );
	
	for( var i = 0; i < pager_id.length; i++ )
	{
		pager_id[i].id = i + parseInt(1);
	}
}
/**
 * retorna a largura do corpo da p�gina
 * CROSS-BROWSE
 */
function getBodyWidth()
{
    var width;
    var scrollWidth;
    var offsetWidth;

    if (document.width)
    {
        width = document.width;
    }
    else if (document.body)
    {
        if (document.body.scrollWidth)
        {
            width = scrollWidth = document.body.scrollWidth;
        }
        if (document.body.offsetWidth)
        {
            width = offsetWidth = document.body.offsetWidth;
        }

        if (scrollWidth && offsetWidth)
        {
            width = Math.max(scrollWidth, offsetWidth);
        }
    }
	if(!parseInt(width))
		width = 0;		
    return width;
}
/**
 * retorna a altura do corpo da p�gina
 * CROSS-BROWSE
 */
function getBodyHeight()
{
    var height;
    var scrollHeight;
    var offsetHeight;

    if (document.height)
    {
        height = document.height;
    }
    else if (document.body)
    {
        if (document.body.scrollHeight)
        {
            height = scrollHeight = document.body.scrollHeight;
        }
        if (document.body.offsetHeight)
        {
            height = offsetHeight = document.body.offsetHeight;
        }

        if (scrollHeight && offsetHeight)
        {
            height = Math.max(scrollHeight, offsetHeight);
        }
    }
	if(!parseInt(height))
		height = 0;
    return height;
}
/**
 * retorna a largura da tela do browse
 * CROSS-BROWSE
 */
function getViewPortWidth()
{
    var width= 0;

    if (window.innerWidth)
    {
        width= window.innerWidth- 18;
    }
    else if ((document.documentElement) && (document.documentElement.clientWidth))
    {
        width= document.documentElement.clientWidth;
    }
    else if ((document.body) && (document.body.clientWidth))
    {
        width= document.body.clientWidth;
    }
	if(!parseInt(width))
		width = 0;		
    return width;
}
/**
 * retorna a altura da tela do browse
 * CROSS-BROWSE
 */
function getViewPortHeight()
{
    var height = 0;

    if (window.innerHeight)
    {
        height = window.innerHeight;
    }
    else if ((document.documentElement) && (document.documentElement.clientHeight))
    {
        height = document.documentElement.clientHeight;
    }
    else if ((document.body) && (document.body.clientHeight))
    {
        height = document.body.clientHeight;
    }
	if(!parseInt(height))
		height = 0;
    return height;
}
/**
 * retorna largura (x) e altura (y)
 * identifica qual � maior o corpo da p�gina ou tela do browse e retorna o que for maior
 * CROSS-BROWSE
 */
function getBodyXY()
{
	var y = (getViewPortHeight() > getBodyHeight() ? getViewPortHeight() : getBodyHeight());
	var x = (getViewPortWidth() > getBodyWidth() ? getViewPortWidth() : getBodyWidth());
	
	return [parseInt(x), parseInt(y)];
}
function closeDivCarregar()
{
	var divCarregar 	= document.getElementById( 'divCarregar' );
	var divCarregarImg	= document.getElementById( 'divCarregarImg' );
	
	if( !divCarregar )
	{
		return;
	}
	document.body.removeChild( divCarregar );
	document.body.removeChild( divCarregarImg );
}
function loadDivCarregar()
{
	var divCarregar					= document.createElement( 'div' );
	var divCarregarImg				= document.createElement( 'div' );
	var txtCarregando				= document.createTextNode( 'Carregando...' );

	divCarregar.id 					= divCarregar.name = 'divCarregar';
	divCarregar.className			= 'divCarregar';
	divCarregar.style.display		= 'block';
	divCarregar.style.width			= getBodyXY()[0] + 'px';
	divCarregar.style.height		= getBodyXY()[1] + 'px';
	
	divCarregarImg.id				= divCarregarImg.name = 'divCarregarImg';
	divCarregarImg.className		= 'divCarregarImg';
	divCarregarImg.appendChild( txtCarregando );
	
	var screenH 					= getViewPortHeight();
	var screenW 					= getViewPortWidth();
	var scrollH 					= getScrollXY()[0];
	var scrollW 					= getScrollXY()[1];
	var top							= scrollH + (( screenH / 2) - (divCarregarImg.offsetHeight / 2));
	var left						= scrollW + (( screenW / 2) - (divCarregarImg.offsetWidth / 2));

	divCarregarImg.style.top		= top + 'px';
	divCarregarImg.style.left		= left + 'px';
	
	document.body.appendChild( divCarregar );
	document.body.appendChild( divCarregarImg );
}
function getScrollXY() 
{
	var scrOfX = 0, scrOfY = 0;
	
  	if( typeof( window.pageYOffset ) == 'number' ) 
	{
		//Netscape compliant
		scrOfY = window.pageYOffset;
		scrOfX = window.pageXOffset;
  	} 
	else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) 
	{
		//DOM compliant
		scrOfY = document.body.scrollTop;
		scrOfX = document.body.scrollLeft;
	} 
	else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) 
	{
    	//IE6 standards compliant mode
    	scrOfY = document.documentElement.scrollTop;
    	scrOfX = document.documentElement.scrollLeft;
  	}
  	return [scrOfY, scrOfX];
}

/**
 * Remove TAGs que o Ms Word gera quando importa texto para o Editor Xinha.
 * 
 * @param STRING html
 * @return STRING
 */
function removeTagsWord(html)
{
	// Remove HTML comments
	html = html.replace(/<!--[\w\s\d@{}:.;,'"%!#_=&|?~()[*+\/\-\]]*-->/gi, "" );
	html = html.replace(/<!--[^\0]*-->/gi, '');
	// Remove all HTML tags
	html = html.replace(/<\/?\s*HTML[^>]*>/gi, "" );
	// Remove all BODY tags
	html = html.replace(/<\/?\s*BODY[^>]*>/gi, "" );
	// Remove all META tags
	html = html.replace(/<\/?\s*META[^>]*>/gi, "" );
	// Remove all link tags
	html = html.replace(/<\/?\s*link[^>]*>/gi, "" );
	// Remove all SPAN tags
//	html = html.replace(/<\/?\s*SPAN[^>]*>/gi, "" );
	// Remove all FONT tags
//	html = html.replace(/<\/?\s*FONT[^>]*>/gi, "");
	// Remove all IFRAME tags.
	html = html.replace(/<\/?\s*IFRAME[^>]*>/gi, "");
	// Remove all STYLE tags & content
//	html = html.replace(/<\/?\s*STYLE[^>]*>(.|[\n\r\t])*<\/\s*STYLE\s*>/gi, "" );
	// Remove all TITLE tags & content
	html = html.replace(/<\s*TITLE[^>]*>(.|[\n\r\t])*<\/\s*TITLE\s*>/gi, "" );
	// Remove javascript
	html = html.replace(/<\s*SCRIPT[^>]*>[^\0]*<\/\s*SCRIPT\s*>/gi, "");
	// Remove all HEAD tags & content
	html = html.replace(/<\s*HEAD[^>]*>(.|[\n\r\t])*<\/\s*HEAD\s*>/gi, "" );
	// Remove Class attributes
//	html = html.replace(/<\s*(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3") ;
	// Remove Style attributes
//	html = html.replace(/<\s*(\w[^>]*) style="([^"]*)"([^>]*)/gi, "<$1$3") ;
	// Remove Lang attributes
	html = html.replace(/<\s*(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3") ;
	// Remove XML elements and declarations
	html = html.replace(/<\\?\?xml[^>]*>/gi, "") ;
	// Remove Tags with XML namespace declarations: <o:p></o:p>
	html = html.replace(/<\/?\w+:[^>]*>/gi, "") ;
	// Replace the &nbsp;
	html = html.replace(/&nbsp;/, " " );
	// Transform <p><br /></p> to <br>
	//html = html.replace(/<\s*p[^>]*>\s*<\s*br\s*\/>\s*<\/\s*p[^>]*>/gi, "<br>");
	html = html.replace(/<\s*p[^>]*><\s*br\s*\/?>\s*<\/\s*p[^>]*>/gi, "<br>");
	// Remove <P>
	html = html.replace(/<\s*p[^>]*>/gi, "");
	// Replace </p> with <br>
	html = html.replace(/<\/\s*p[^>]*>/gi, "<br>");

	// Remove any <br> at the end
	html = html.replace(/(\s*<br>\s*)*$/, "");
	html = html.trim();
	return html;
}