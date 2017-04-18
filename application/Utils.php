<?php


function pr($var, $label=NULL, $echo=TRUE)
{
    Zend_Debug::dump($var, $label, $echo);
}

function dump($object)
{
	Paiva_Controller_Plugin_Debug::dump($object);
}


function debug( $mixPrint , $boolExit = 0 )
{
	echo "<fieldset><legend>Debug</legend><pre>";
	echo var_dump( $mixPrint );
	echo "<br /><br />";

	$aTrace = debug_backtrace();
	echo $aTrace[0]['file'] . " - " . $aTrace[0]['line'];

	echo "</pre></fieldset><br />";
	if( $boolExit ) exit;
}

/**
 * Verifica se o componente pode ser exibido dentro do script
 *
 * @param $recurso String nome do controller
 * @param $acao String nome do metodo Action
 * @return bool
 */
function isAllowed($recurso, $acao){
    $arrPerfil = Zend_Auth::getInstance()->getIdentity();
    $nomePerfil = $arrPerfil['perfil']['perfil'];
	
    return Datasus_Scpa_Acl::getInstance()->isAllowed($nomePerfil, $recurso, $acao);
}

/**
 * Formata valor para inserir no banco
 *
 * @param $valor
 * @param $precisao
 * @return valor no formato 1000.00
 */
function formatValToDb($valor, $precisao = null){

	//Se o valor for maio que 0, formata o valor
	if(($valor > 0) OR (strlen(trim($valor)) > 0))
	{
		if(($precisao == null) OR ($precisao == ''))
		{
			$valor = Zend_Locale_Format::getNumber($valor);
		}
		else
		{
			$valor = Zend_Locale_Format::getNumber($valor, array('precision' => $precisao));
		}
	}
	else
	{
		$valor = '';
	}
	
	if(APPLICATION_ENV == 'local'){
		return str_replace('.',',',$valor);
	} else {
		return $valor;
	}
}

/**
 * Formata valor para exibir no formulario
 *
 * @param $valor
 * @param $precisao
 * @return valor no formato 1.000,00
 */
function formatValShow($valor, $precisao = null){

	if(APPLICATION_ENV == 'local'){
		$valor = str_replace(',','.',$valor);
	}

	//Se o valor for maio que 0, formata o valor
	if(($valor > 0) OR (strlen(trim($valor)) > 0))
	{
		if(($precisao == null) OR ($precisao == ''))
		{
			$valor = Zend_Locale_Format::toNumber($valor);
		}
		else
		{
			$valor = Zend_Locale_Format::toNumber($valor, array('precision' => $precisao));
		}
	}
	else
	{
		$valor = '';
	}
	
	return $valor;
}

/**
 * Formata valor para exibir no formulario
 *
 * @param $co_termo_ajuste
 * @param $data
 * @return true or false
 */
function verificaPrestacoesFechadasTermoAjuste($co_termo_ajuste, $data){
	// Verifica se a data foi informada
	if($data)
	{
        if(Model_PrestacaoConta::getInstance()->consultaPrestacoesFechadasTermoAjuste($co_termo_ajuste, $data))
        {
            return true;
        }
        else
        {
            return false;
        }
	}
}

function logger($message, $type = Zend_Log::INFO)
{
	Paiva_Controller_Plugin_Debug::logger($message, $type);
}

function formataCEP($cep) {
    $cep = preg_replace("[^0-9]", "", $cep);
    if (strlen($cep) == 8) return $cep[0] . $cep[1] . $cep[2] . $cep[3] . $cep[4] . '-'. $cep[5] . $cep[6] . $cep[7];
    else return false;
}

function formataCNPJ($cnpj){
    if(strlen($cnpj) < 14){
    	$cnpj = str_pad($cnpj, 14, "0", STR_PAD_LEFT);
    }
    $cnpj = preg_replace("[^0-9]", "", $cnpj);
    if (strlen($cnpj) == 14) return $cnpj[0] . $cnpj[1] . "." . $cnpj[2] . $cnpj[3] . $cnpj[4] .  "." . $cnpj[5] . $cnpj[6] . $cnpj[7] .'/'. $cnpj[8] . $cnpj[9] . $cnpj[10] . $cnpj[11] . '-' . $cnpj[12] . $cnpj[13];	
    else return false;
}

function formataCPF($cpf){
    if($cpf){
        return substr($cpf,0,3).'.'.substr($cpf,3,3).'.'.substr($cpf,6,3).'-'.substr($cpf,9,2);
    } else {
        return FALSE;
    }
}

function formataTEL($tel) {
	if($tel){
        return '('.substr($tel,0,2).')&nbsp;'.substr($tel,2,9);
    } else {
        return FALSE;
    }
}

function retiraFormatacaoCPF($cpf){
    if($cpf){
        $array = explode(".", $cpf);
        $cpf   = $array[0].$array[1].$array[2];
        $array = explode("-", $cpf);
        $cpf   = $array[0].$array[1];
        return $cpf;
    } else {
        return FALSE;
    }
}

function retiraFormatacaoCEP($cep){
    if($cep){
    	$cep = str_replace('.', '', str_replace('-', '', $cep));
        return $cep;
    } else {
        return FALSE;
    }
}

function retiraFormatacaoTEL($cpf){
    if($tel){
        $array = explode("-", $tel);
        $tel   = $array[0].$array[1];
        return $tel;
    } else {
        return FALSE;
    }
}

function retiraFormatacaoDATA($data){
    if($data){
        $array = explode("/", $data);
        $data   = $array[0].$array[1].$array[2];
        return $data;
    } else {
        return FALSE;
    }
}

function retiraFormatacaoCNPJ($cnpj){
    if($cnpj){
        $array = explode(".", $cnpj);
        $cnpj   = $array[0].$array[1].$array[2];
        $array = explode("/", $cnpj);
        $cnpj   = $array[0].$array[1];
		$array = explode("-", $cnpj);
        $cnpj   = $array[0].$array[1];
        return $cnpj;
    } else {
        return FALSE;
    }
}

function formataCNS($cns){
    if($cns){
        return substr($cns,0,5).' '.substr($cns,4,5).' '.substr($cns,10,5);
    } else {
        return FALSE;
    }
}

function retiraFormatacaoCNS($cns){
    if($cns){
        $array = explode(" ", $cns);
        return $array[0].$array[1].$array[2];;
    } else {
        return FALSE;
    }
}

/**
 * Retorna código do usuário no SCPA
 *
 * @return código
 */
function codigoUsuarioSCPA(){
    $arrPerfil = Zend_Auth::getInstance()->getIdentity();
    return $arrPerfil['perfil']['id'];
}

/**
 * Retorna data atual
 *
 * @return SYSDATE
 */
function dataAtual(){
    return new Zend_Db_Expr("now()");
}

/**
 * Retorna data atual
 *
 * @return ZENDDATE
 */
function retornaDataAtualYMDHMS(){
    return Zend_Date::now()->toString("Y-M-d hh:mm:ss");
}

/**
 * Retorna data atual
 *
 * @return ZENDDATE
 */
function retornaDataAtualDMYHMS(){
    return Zend_Date::now()->toString("d-M-Y hh:mm:ss");
}

/**
 * Retorna data atual
 *
 * @return ZENDDATE
 */
function retornaDataAtualDMY(){
    return Zend_Date::now()->toString("d-M-Y");
}

/**
 * Retorna data atual
 *
 * @return ZENDDATE
 */
function retornaDataAtualYMD(){
    return Zend_Date::now()->toString("Y-M-d");
}

/**
 * Retorna data atual
 *
 * @return ZENDDATE
 */
function retornaHoraAtual(){
    return Zend_Date::now()->toString("hh:mm:ss");
}

/**
 * Retorna data atual
 *
 * @return ZENDDATE
 */
function dataAtualZend(){
    return Zend_Date::now();
}

/**
 * Retorna data atual somada aos dias passados no parametro
 *
 * @return Zend_Date
 */
function retornaDataAtualZendAdicionada($qtd_dias){
    return Zend_Date::now()->addDay($qtd_dias);
}

/**
 * Retorna data atual somada aos dias passados no parametro
 *
 * @return ZENDDATE
 */
function adicionarDiaData($data, $qtd_dias){
	$date = new Zend_Date($data);
	return $date->addDay($qtd_dias)->toString("Y-M-d");
}

/**
 * Retorna data atual somada aos dias passados no parametro
 *
 * @return ZENDDATE
 */
function adicionarMesData($data, $qtd_month){
	$date = new Zend_Date($data);
	return $date->addMonth($qtd_month)->toString("Y-M-d");
}

/**
 * Retorna data atual somada aos dias passados no parametro
 *
 * @return ZENDDATE
 */
function dataAtualAdicionadaComHoras($qtd_dias){
	return Zend_Date::now()->addDay($qtd_dias)->toString("Y-M-d hh:mm:ss");
}

/**
 * Retorna IP do usuário logado
 *
 * @return IP
 */
function ipUsuarioLog(){
    return $_SERVER["REMOTE_ADDR"];
}

/**
 * Retira formatação SIPAR
 *
 * @return nu_sipar
 */
function retiraFormatacaoSIPAR($nu_sipar){
    if($nu_sipar){
        return str_replace("-","",str_replace("/","",str_replace(".","",$nu_sipar)));
    } else {
        return false;
    }
}

function removeCaracter($char){
	$arrCaracter = array(',', '.', '(', ')', '-', '/');
	return str_replace($arrCaracter, '', $char);
}
		
function retornaDmy($dateString){
	if($dateString) {
		$date = new Zend_Date($dateString, 'Y-m-d');
		return $date->toString('dd/m/Y');
	}
	return false;
}
		
function retornaDmyHora($dateString){
	$arrDateString = explode(' ', $dateString);
	$date = new Zend_Date($arrDateString[0], 'Y-m-d');
	return $date->toString('d/m/Y').' - '.$arrDateString[1]; 
}

function retornaYmd($dateString){
	if($dateString){
		return implode('-', array_reverse(explode('/', $dateString)));
	}
	return false;
}

function removePriceFormat($price){
	return floatval( str_replace(',', '.', str_replace(',', '', str_replace('R$ ', '', $price ) ) ) );
}

function retornaStatus( $co_status ){
	
	switch( $co_status ){
		case CO_STATUS_ABERTO:
			return DS_STATUS_ABERTO;
		break;
		case CO_STATUS_DEVEDOR:
			return DS_STATUS_DEVEDOR;
		break;
		case CO_STATUS_QUITADO;
			return DS_STATUS_QUITADO;
		break;
		case CO_STATUS_SUSPENSO:
			return DS_STATUS_SUSPENSO;
	}
}
function retornaClassStatus( $co_status ){
	switch( $co_status ){
		case CO_STATUS_ABERTO:
			return 'info';
		break;
		case CO_STATUS_DEVEDOR:
			return 'warning';
		break;
		case CO_STATUS_QUITADO;
			return 'success';
		break;
		case CO_STATUS_SUSPENSO:
			return 'error';
	}
}

function moeda($get_valor) {
	return number_format( $get_valor , 2, ',', '.'); //retorna o valor formatado para gravar no banco
}

function numeroEscrito($n) {

	$numeros[1][0] = '';
	$numeros[1][1] = 'um';
	$numeros[1][2] = 'dois';
	$numeros[1][3] = 'três';
	$numeros[1][4] = 'quatro';
	$numeros[1][5] = 'cinco';
	$numeros[1][6] = 'seis';
	$numeros[1][7] = 'sete';
	$numeros[1][8] = 'oito';
	$numeros[1][9] = 'nove';

	$numeros[2][0] = '';
	$numeros[2][10] = 'dez';
	$numeros[2][11] = 'onze';
	$numeros[2][12] = 'doze';
	$numeros[2][13] = 'treze';
	$numeros[2][14] = 'quatorze';
	$numeros[2][15] = 'quinze';
	$numeros[2][16] = 'dezesseis';
	$numeros[2][17] = 'dezesete';
	$numeros[2][18] = 'dezoito';
	$numeros[2][19] = 'dezenove';
	$numeros[2][2] = 'vinte';
	$numeros[2][3] = 'trinta';
	$numeros[2][4] = 'quarenta';
	$numeros[2][5] = 'cinquenta';
	$numeros[2][6] = 'sessenta';
	$numeros[2][7] = 'setenta';
	$numeros[2][8] = 'oitenta';
	$numeros[2][9] = 'noventa';

	$numeros[3][0] = '';
	$numeros[3][1] = 'cem';
	$numeros[3][2] = 'duzentos';
	$numeros[3][3] = 'trezentos';
	$numeros[3][4] = 'quatrocentos';
	$numeros[3][5] = 'quinhentos';
	$numeros[3][6] = 'seiscentos';
	$numeros[3][7] = 'setecentos';
	$numeros[3][8] = 'oitocentos';
	$numeros[3][9] = 'novecentos';

	$qtd = strlen($n);

	$compl[0] = ' mil ';
	$compl[1] = ' milhão ';
	$compl[2] = ' milhões ';
	$numero = "";
	$casa = $qtd;
	$pulaum = false;
	$x = 0;
	for ($y = 0; $y < $qtd; $y++) {

		if ($casa == 5) {

			if ($n[$x] == '1') {

				$indice = '1' . $n[$x + 1];
				$pulaum = true;
			} else {

				$indice = $n[$x];
			}

			if ($n[$x] != '0') {

				if (isset($n[$x - 1])) {

					$numero .= ' e ';
				}

				$numero .= $numeros[2][$indice];

				if ($pulaum) {

					$numero .= ' ' . $compl[0];
				}
			}
		}

		if ($casa == 4) {

			if (!$pulaum) {

				if ($n[$x] != '0') {

					if (isset($n[$x - 1])) {

						$numero .= ' e ';
					}
				}
			}

			$numero .= $numeros[1][$n[$x]] . ' ' . $compl[0];
		}

		if ($casa == 3) {

			if ($n[$x] == '1' && $n[$x + 1] != '0') {

				$numero .= 'cento ';
			} else {

				if ($n[$x] != '0') {

					if (isset($n[$x - 1])) {

						$numero .= ' e ';
					}

					$numero .= $numeros[3][$n[$x]];
				}
			}
		}

		if ($casa == 2) {

			if ($n[$x] == '1') {

				$indice = '1' . $n[$x + 1];
				$casa = 0;
			} else {

				$indice = $n[$x];
			}

			if ($n[$x] != '0') {

				if (isset($n[$x - 1])) {

					$numero .= ' e ';
				}

				$numero .= $numeros[2][$indice];
			}
		}

		if ($casa == 1) {

			if ($n[$x] != '0') {
				if ($numeros[1][$n[$x]] <= 10)
					$numero .= ' ' . $numeros[1][$n[$x]];
				else
					$numero .= ' e ' . $numeros[1][$n[$x]];
			} else {

				$numero .= '';
			}
		}

		if ($pulaum) {

			$casa--;
			$x++;
			$pulaum = false;
		}

		$casa--;
		$x++;
	}

	return $numero;
}

/**
 * Retorna uma string do valor
 *
 * @param string $n - Valor a ser traduzido, pode ser no formato americano ou brasileiro
 * @example escreverValorMoeda('1.530,64');
 * @example escreverValorMoeda('1530.64');
 * @return string
 */
function escreverValorMoeda($n){
	//Converte para o formato float
	if(strpos($n, ',') !== FALSE){
		$n = str_replace('.','',$n);
		$n = str_replace(',','.',$n);
	}

	//Separa o valor "reais" dos "centavos";
	$n = explode('.',$n);

	return ucfirst(numeroEscrito($n[0])). ' reais' . ((isset($n[1]) && $n[1] > 0)?' e '.numeroEscrito($n[1]).' centavos':'');

}

function retornaTpMovimentacao( $tp_movimentacao ){
	switch ( $tp_movimentacao ){
		case 'D':
			return 'Despesa';
		break;
		case 'R':
			return 'Receita';
	}
}


function limitarTexto($texto, $limite = 100){
	$contador = mb_strlen($texto);
	if ( $contador >= $limite ) {
		$texto =  substr_replace($texto, (strlen($texto) > $limite ? '...' : ''), $limite);
		return $texto;
	}
	else{
		return $texto;
	}
}

function limitar($texto, $limite = 100){
	$contador = mb_strlen($texto);
	if ( $contador >= $limite ) {
		$texto =  substr_replace($texto, (strlen($texto) > $limite ? '' : ''), $limite);
		return $texto;
	}
	else{
		return $texto;
	}
}

function valorPorExtenso( $valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false )
{

	$valor = removerFormatacaoNumero( $valor );

	$singular = null;
	$plural = null;

	if ( $bolExibirMoeda )
	{
		$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
		$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
	}
	else
	{
		$singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
		$plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
	}

	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");


	if ( $bolPalavraFeminina )
	{

		if ($valor == 1)
		{
			$u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
		}
		else
		{
			$u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
		}


		$c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");


	}


	$z = 0;

	$valor = number_format( $valor, 2, ".", "." );
	$inteiro = explode( ".", $valor );

	for ( $i = 0; $i < count( $inteiro ); $i++ )
	{
		for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ )
		{
			$inteiro[$i] = "0" . $inteiro[$i];
		}
	}

	// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
	$rt = null;
	$fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
	for ( $i = 0; $i < count( $inteiro ); $i++ )
	{
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

		$r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
		$t = count( $inteiro ) - 1 - $i;
		$r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ( $valor == "000")
			$z++;
		elseif ( $z > 0 )
			$z--;

		if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
			$r .= ( ($z > 1) ? " de " : "") . $plural[$t];

		if ( $r )
			$rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
	}

	$rt = mb_substr( $rt, 1 );

	return($rt ? trim( $rt ) : "zero");

}

function removerFormatacaoNumero( $strNumero )
{

	$strNumero = trim( str_replace( "R$", null, $strNumero ) );

	$vetVirgula = explode( ",", $strNumero );
	if ( count( $vetVirgula ) == 1 )
	{
		$acentos = array(".");
		$resultado = str_replace( $acentos, "", $strNumero );
		return $resultado;
	}
	else if ( count( $vetVirgula ) != 2 )
	{
		return $strNumero;
	}

	$strNumero = $vetVirgula[0];
	$strDecimal = mb_substr( $vetVirgula[1], 0, 2 );

	$acentos = array(".");
	$resultado = str_replace( $acentos, "", $strNumero );
	$resultado = $resultado . "." . $strDecimal;

	return $resultado;

}

