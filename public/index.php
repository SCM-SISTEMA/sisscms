<?php
/**
 *
 *
 * Descrição do arquivo (opcional).
 *
 * @author      Gilvan Junior
 * @copyright   Sindicato
 * @package     Sindicato_
 * @since       Arquivo disponível desde a versão 1.0
 * @version     $Id$
 */
/**
 * Define o diretorio onde a aplicação esta rodando.
 * Se não tiver definido anteriormente, define usando
 * realpath(dirname(__FILE__) . '/../application').
 * O resultado será C:\<caminho_para_localhost>\<app nome>\application
 */
defined('APPLICATION_PATH') || define('APPLICATION_PATH',
realpath(dirname(__FILE__) . '/../application'));

/**
 * Define o ambiente da aplicação.
 * Assume 'producao' como ambiente padrão se a variavel APPLICATION_ENV
 * nao tiver sido setada no arquivo .htaccess
 */
defined('APPLICATION_ENV') || define('APPLICATION_ENV',
(getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'local'));

/**
 * Define o diretório de biblioteca da aplicação.
 */
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

$strPath = $_SERVER["DOCUMENT_ROOT"] . "/sisscm";

define("PATHSERVER", $strPath);

/**
 * Classe principal do zend framework
 */
require_once 'Zend/Application.php';


/**
 * Cria uma instancia de Zend_Application passando o ambiente e o caminho
 * para o arquivo de configuracões.
 * Este componente irá automatizar algumas configurações definidas no config.ini
 */
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/config.ini'
);

/**
 * Carregos configurações basicas do bootstrap
 * como layout, view, models ....
 */
$application->bootstrap();

/**
 * Inicia efetivamente a aplicacao.
 * Neste momento é dado o dispatch para instanciar os controllers e actions
 */

require_once APPLICATION_PATH . '/../library/Thumb/ThumbLib.inc.php';
require_once APPLICATION_PATH . '/configs/const.php';
require_once APPLICATION_PATH . '/Utils.php';
require_once APPLICATION_PATH . '/../library/correios/Correios.php';
require_once APPLICATION_PATH . '/../library/correios/rastreamento/CorreiosRastreamento.php';
require_once APPLICATION_PATH . '/../library/correios/rastreamento/CorreiosRastreamentoResultado.php';
require_once APPLICATION_PATH . '/../library/correios/rastreamento/CorreiosRastreamentoResultadoOjeto.php';
require_once APPLICATION_PATH . '/../library/correios/rastreamento/CorreiosRastreamentoResultadoEvento.php';
require_once APPLICATION_PATH . '/../library/correios/Sro/CorreiosSro.php';
require_once APPLICATION_PATH . '/../library/correios/Sro/CorreiosSroDados.php';

require_once 'mpdf/mpdf.php';
require_once 'fpdf/fpdf.php';
require_once 'phpjasperxml/class/PHPJasperXML.inc.php';
require_once 'phpjasperxml/class/tcpdf/tcpdf.php';
require_once 'phpjasperxml/class/tcpdf/tcpdf.php';
require_once 'phpmailer/class.phpmailer.php';


$application->run();