<?php
/**
 * 
 *
 * Descrição do arquivo (opcional).
 *
 * @author      Gilvan Junior
 * @copyright   Sgc
 * @package     Sgc_
 * @since       Arquivo disponível desde a versão 1.0
 * @version     $Id$
 */

/**
 * AutenticacaoController class
 *
 * Este controller é responsável por receber as informações do SCAWEB
 * e efetuar o login/logout da aplicação.
 *
 * @author      Gilvan Junior
 * @since       Arquivo disponível desde a versão 1.0
 */
class AutenticacaoController extends Zend_Controller_Action
{

    /**
     * Login da aplicação
     *
     * @throws Zend_Auth_Adapter_Exception Se a autenticação não seja realizada
     * @return Sistema_Sgc_Auth
     */
    public function indexAction() {
    	// Autenticação
    	$auth = Zend_Auth::getInstance();
    	$result = $auth->authenticate(new Sistema_Sgc_Auth($this->getRequest()));

		if($this->getRequest()->isPost()) {
			$server = $_SERVER['HTTP_REFERER'];
			
			if( $result->getCode() == 0 ){
				$message = $result->getMessages();
				$this->view->message = $message[0];
			}
			
			//verifica na tabela tb_usuario
			if($this->getRequest()->getParam('ds_login') && $this->getRequest()->getParam('ds_senha')) {
				// Classe de controle de autenticação
		    	
		    	
		    	// Verifica a existência de alguma autenticação (identidade) já realizada para a sessão atual
		    	// Se não existir autenticação, realizar a autenticação customizada.
		    	if(!$auth->hasIdentity()) {
		    		try {
		    			
	                    if(!$result->getCode()) {



		                    // Consulta existência do usuário para o email informado
				    		if($arrUsuario = Model_Usuario::getInstance()->getConsulta(array('LOGIN' => $this->getRequest()->getParam('ds_login')))) {
				    			
				    			if(count($arrUsuario) == 0) {				    				
				    				echo "<script>alerta('Usuário Inativo!');</script>";
				    			}				    			
				    		}else{		      
		                    	echo "<script>alerta('Dados Incorretos!');</script>";
				    		}
	                    } else {
	                    	$this->_redirect("");
	                    }
		    		} catch (Zend_Auth_Adapter_Exception $e) {
		    			throw new Zend_Controller_Action_Exception($e->getMessage());
		    		}
		    	}else {
	                  $this->_redirect('administracao/contrato');
	                    }
			}
			
		}
    }

    /**
     * Logout da aplicação
     *
     */
    public function logoutAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$user = Zend_Auth::getInstance()->getIdentity();
		
		# E = entrada, S = saida
		Model_LogEntrada::getInstance()->insert( array('data' => dataAtual(), 'usuario' => $user['user']->id, 'acao' => 'S' ) );
		
        Zend_Registry::getInstance()->_unsetInstance();
        // Limpar identidade(storage) para a sessão atual
    	Zend_Auth::getInstance()->clearIdentity();

	    $this->_redirect("autenticacao");
    	
    }
    
	/**
	 * Criptografia e geração de senha
	 *
	 * @return array
	 */
    
	private function criptografiaSenha() {
		// senha randômica
		$senhaRandom = mt_rand();
		
		// Retorno
		return array("SENHA" => $senhaRandom, "CRIPTOGRAFIA" => crypt($senhaRandom, CRYPT_STD_DES));
	}    
    
    /**
     * Geração de nova senha
     *
     */
    public function novasenhaAction() {
    	if($this->getRequest()->isPost()) {
    		// CPF formatado
    		$ds_email = $this->getRequest()->getParam('ds_email');
    		
    		// Consulta informações do usuário
    		$arrUsuario = Model_Usuario::getInstance()->getConsulta(
    			array(
	    			"DS_EMAIL" => $ds_email,
    				"ST_REGISTRO_ATIVO" => "S"
    			)
    		);

    		if($arrUsuario) {
	    		// Senha
	    		$arrSenha = $this->criptografiaSenha();
		
		    	try {
		    		// Geração de nova senha para o usuário
		    		Model_Usuario::getInstance()->update(
		    			array(
		    				"DS_SENHA" => $arrSenha['CRIPTOGRAFIA']
		    			),
		    			"DS_EMAIL = ".$ds_email
		    		);
		    	} catch (Exception $e) {
		    		throw new Zend_Controller_Action_Exception($e->getMessage());
		    	}
		
				$message = "
				Prezado(a) ".$arrUsuario[0]['NO_USUARIO'].", <br><br> 
				Sua senha de acesso ao sistema CAP foi gerada. <br><br>
				Para realizar o login no sistema, utilize os dados abaixo: <br><br>
				Login:	".$this->getRequest()->getParam('ds_email')." <br><br>
				Senha: 	".$arrSenha['SENHA']." <br><br>
				O sistema pode ser acessado no seguinte endereço: <br><br>".
				Zend_Registry::get("config")->sistema->url."<br><br>
				Atenciosamente.
				";
		    	
		    	/* // Envio de email
				$mail = new Zend_Mail();
				$mail->setBodyHtml(utf8_decode($message))
					 ->setFrom(Zend_Registry::get("config")->mailSiarh->from,utf8_decode(Zend_Registry::get("config")->mailSiarh->fromName))
				     ->addTo($this->getRequest()->getParam('ds_email'))
				     ->setSubject(utf8_decode("Senha de acesso ao CAP"))
				     ->send()
				     ; */
				     
				echo "<script>alerta('".$message."');</script>";
    		} else {
    			echo "<script>alerta('Dados Incorretos!');</script>";
    		}
    	}
    	
    	// Parâmetros do formulário
    	$this->view->ds_email		= $this->getRequest()->getParam('ds_email');
    }
}
