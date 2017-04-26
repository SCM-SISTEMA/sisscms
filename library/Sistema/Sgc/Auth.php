<?php
/**
 *
 * Descrição do arquivo (opcional).
 *
 * @author      Gilvan Junior
 * @copyright   Sgc
 * @package     Sgc_Auth
 * @since       Arquivo disponivel desde a versão 1.0
 * @version     $Id$
 */

/**
 * Sgc_Auth class
 *
 * Classe responsável pela autenticação com o SCAWEB
 *
 * @author      Gilvan Junior
 * @author      Gilvan Junior
 * @since       Arquivo disponível desde a versão 1.0
 */

class Sistema_Sgc_Auth implements Zend_Auth_Adapter_Interface {
	private $_request;


	/**
	 * Construtor da classe customizada para autenticação
	 *
	 * @param Zend_Controller_Request_Abstract $_request
	 */
	public function __construct(Zend_Controller_Request_Abstract $_request) {
		$this->_request 	= $_request;
	}

	/**
	 * Executa a autenticação
	 *
	 * @throws Zend_Auth_Adapter_Exception Se a autenticação não seja realizada
	 * @return Zend_Auth_Result
	 */
	public function authenticate() {

		if (!$this->_request->getParam("ds_login") && ! $this->_request->getParam("ds_senha")) {
			return new Zend_Auth_Result(0, null, array("Parâmetros não informados corretamente para a consulta."));
		} else {
			try {
				// Consulta acesso do usuário
				$Usuario = Model_Usuario::getInstance()->getAcessoUsuario($this->_request->getParam("ds_login"),$this->_request->getParam("ds_senha"));
			} catch (Exception $e) {
				throw new Zend_Auth_Exception($e->getMessage());
			}

			if(!$Usuario) {
				return new Zend_Auth_Result(0, "", array("Nenhum usuário encontrado para as informações."));
			}

			// Limpando a instância da webService
			$client = null;

			// Array com informações vindas do ScaWeb para a sessão
			$arrAuth = array("user" => (object) $Usuario);
			// Retorno
			return new Zend_Auth_Result(1, $arrAuth, array("Autenticação realizada com sucesso."));
		}
	}




}