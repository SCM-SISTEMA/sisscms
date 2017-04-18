<?php
/**
 * 
 *
 * Descrição do arquivo (opcional).
 *
 * @author      Gilvan Junior
 * @copyright   Sistema
 * @package     Sistema
 * @since       Arquivo disponível desde a versão 1.0
 * @version     $Id$
 */

/**
 * Sistema_Plugin_Seguranca class
 * ACL pattern implementação
 *
 * Classe responsável pela regras de segurança do sistema
 *
 * @author      Gilvan Junior
 * @author      Gilvan Junior
 * @since       Arquivo disponível desde a versão 1.0
 */

class Sistema_Plugin_Seguranca extends Zend_Controller_Plugin_Abstract {

    /**
     * Singleton instance
     *
     * @var SistemaAcl
     */
    private $_acl = null;

    /**
     * Singleton instance
     *
     * @var Zend_Auth
     */
    private $_auth = null;

    /**
     * Singleton instance
     *
     * @var Sistema_Plugin_Seguranca
     */
    protected static $_instance = null;

    /**
     * Retorna a instância Sistema_Sgc_Acl
     * Singleton pattern implementação
     *
     * @return Sistema_Sgc_Acl Uma abstração do Zend_Auth
     */
    public static function getInstance() {
        if( self::$_instance === null ) {
            self::$_instance = new Sistema_Plugin_Seguranca(Zend_Auth::getInstance(), Sistema_Sgc_Acl::getInstance());
        }
        return self::$_instance;
    }


    /**
    * Construtor da classe
    *
    * @param Sistema_Sgc_Acl $acl Aguarda uma instância do Sistema_Sgc_Acl
    * @param Zend_Auth $auth Aguarda uma instância do Zend_Auth
    */
    public function __construct(Sistema_Sgc_Acl $acl, Zend_Auth $auth) {
        $this->_acl = $acl;
        $this->_auth = $auth;
    }

    /**
     * Executa uma verificação de permissão de acesso
     * ao controller requisitado, caso o usuário não tenha permissão
     * redireciono para o controller index e action index
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
	    if( 	
	    		(strcasecmp("index",$this->_request->getControllerName()) &&
	            strcasecmp("autenticacao",$this->_request->getControllerName()) &&
	            strcasecmp("error",$this->_request->getControllerName()))
	          ) {
	          	$validar = false;
				if(strcasecmp("inscricao",$this->_request->getControllerName())){
					$validar = true;
				}elseif(
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("index", $this->_request->getActionName()) ) &&
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("categoria", $this->_request->getActionName()) ) && 
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("buscaendereco", $this->_request->getActionName()) ) && 
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("carregacidade", $this->_request->getActionName()) ) && 
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("carregacurso", $this->_request->getActionName()) ) && 
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("instituicao", $this->_request->getActionName()) ) && 
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("pesquisainstituicao", $this->_request->getActionName()) ) && 
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("polo", $this->_request->getActionName()) ) && 
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("pesquisapolo", $this->_request->getActionName()) ) && 
	            (!strcasecmp("inscricao",$this->_request->getControllerName()) && strcasecmp("salvar", $this->_request->getActionName()) ) &&
	            (!strcasecmp("usuario",$this->_request->getControllerName()) && strcasecmp("cadastro", $this->_request->getActionName()) )
				)
				{
					$validar = true;
				}

				if($validar){
		            // Array provento da autenticação realizada
		            $arrAutenticacao = $this->_auth->getStorage()->read();
		
		            // Verificação de permissão para o perfil da sessão
		            // e o controle definido da Acl para ele
		            if( !$this->_acl->getRoles() ||
		                !$this->_acl->has($this->_request->getControllerName()) 
		                ) {
		                // Direcionamento para a index
		                //$this->_redirect("");
		                $request->setControllerName("index");
		                $request->setActionName("index");
		            }
				}
        }
		 
    }
}