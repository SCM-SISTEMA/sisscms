<?php
	class ComentariosController extends Zend_Controller_Action {
		
		/**
		 * Consulta de comentarios 
		 *
		 */
		public function indexAction()
	    {
	    	$this->_helper->layout->disableLayout();
	    	$this->_helper->viewRenderer->setNoRender();
	    	
	    	$mode = $this->getRequest()->getParam('mode');
	    	
	    	switch($mode){
	    		case 'get':
	    			$this->getMessage();
	    			break;
	    		case 'post':
	    			$this->postMessage();
	    			break;
	    		default:
	    			$this->output(false, 'Wrong mode.');
	    		break;
	    	}
	    }
	    
	    protected function getMessage(){
	    	$endtime = time() + 5;
	    	$lasttime = $this->getRequest()->getParam('lastTime');
	    	$curtime = null;
	    	
	    	while(time() <= $endtime){
	    		
	    		$row = Model_Comentarios::getInstance()->getConsultaAll( array() );
	    		
	    		if( $row ){
	    			$messages = array();
	    
	    			foreach($row as $rs ){
	    				$messages[] = array(
	    						'user' => $rs['nome'],
	    						'text' => $rs['text'],
	    						'time' => $rs['insertDate']
	    				);
	    			}
	    
	    			$curtime = strtotime($messages[0]['time']);
	    		}
	    
	    		if(!empty($messages) && $curtime != $lasttime){
	    			$this->output(true, '', array_reverse($messages), $curtime);
	    			break;
	    		}
	    		else{
	    			sleep(1);
	    		}
	    	}
	    }
	    
	    protected function postMessage(){
	    	$text = $this->getRequest()->getParam('text');
	    	$user = $this->getRequest()->getParam('user');
	    	
	    	if(empty($user) || empty($text)){
	    		$this->output(false, 'Username and Chat Text must be inputted.');
	    	}
	    	else{
	    		
	    		$rs = Model_Comentarios::getInstance()->insert(
	    			array( 
	    				'username' => $user,
	    				'text' => $text,
	    				'insertDate' => new Zend_Db_Expr('CURRENT_TIMESTAMP')	 
	    			)		
	    		);
	    		
	    		if($rs){
	    			$this->output(true, '');
	    		}
	    		else{
	    			$this->output(false, 'Chat posting failed. Please try again.');
	    		}
	    	}
	    }
	    
	    protected function output($result, $output, $message = null, $latest = null){
	    	echo json_encode(array(
	    			'result' => $result,
	    			'message' => $message,
	    			'output' => $output,
	    			'latest' => $latest
	    	));
	    }
		
		/**
		 * Consulta de usuário - controle em ajax
		 *
		 */
		public function buscarUsuarioAction() {
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(); 
			
			Zend_Loader::loadClass('Zend_Json');
			
			$arrParam = array( 'id' => $this->getRequest()->getParam('id') );

			$arrUsuario = Model_Usuario::getInstance()->getConsulta( $arrParam );
			
			if( $arrUsuario ){
				$arrResponse = array(
							'msg' => 'ok',
							'response' => $arrUsuario
						);
			}else{
				$arrResponse = array('msg' => 'false');
				
			}
			
			$this->_response->appendBody( Zend_Json::encode( $arrResponse ) );			
		}

		/**
		 * Consulta de titulares - controle em ajax
		 *
		 */
		public function buscatitularesAction() {
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(); 
			
			Zend_Loader::loadClass('Zend_Json');

			// Titulares
			$Unidade = array ("CO_SEQ_INTERNO_UORG" => $this->getRequest()->getParam('id'));
			
			$arrTitulares = Model_Usuario::getInstance()->getConsultaTitulares($Unidade);
			$this->_response->appendBody(Zend_Json::encode($arrTitulares));			
		}		
		
		/**
		 * Verificação de acesso de um determinado servidor
		 *
		 */
		public function verificaacessoservidorAction() {
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(); 
			
			Zend_Loader::loadClass('Zend_Json');

			$this->_response->appendBody(Zend_Json::encode(Model_Usuario::getInstance()->getAcessoServidor($this->getRequest()->getParam('ds_email'),crypt($this->getRequest()->getParam('co_senha'), CRYPT_STD_DES),$this->getRequest()->getParam('nu_matricula_siape'))));
		}
		
		/**
		 * Manter usuário
		 *
		 */
		public function manterAction() {
			$arrPerfil = Zend_Auth::getInstance()->getIdentity();
			
			if($this->getRequest()->isPost()) {
				$arrParam = array();
				
				if($this->getRequest()->getParam('no_usuario')) {
					$arrParam['NO_USUARIO'] = $this->getRequest()->getParam('no_usuario');
				}
				if($this->getRequest()->getParam('nu_cpf')) {
					$arrParam['NU_CPF'] = str_replace(".","",str_replace("-","",$this->getRequest()->getParam('nu_cpf')));
				}
				if($this->getRequest()->getParam('ds_email')) {
					$arrParam['DS_EMAIL'] = $this->getRequest()->getParam('ds_email');
				}
				if($this->getRequest()->getParam('co_perfil')) {
					$arrParam['CO_PERFIL'] = $this->getRequest()->getParam('co_perfil');
				}
				$arrParam['ST_REGISTRO_ATIVO'] = "S";
				
				try {
					// Consulta 
					$this->view->arrUser = Model_Usuario::getInstance()->getConsulta($arrParam);
				} catch (Exception $e) {
					throw new Zend_Controller_Action_Exception($e->getMessage());
				}
			}
			
			// Parâmetros do formulário
			$this->view->nu_cpf 	= $this->getRequest()->getParam('nu_cpf');
			$this->view->no_usuario = $this->getRequest()->getParam('no_usuario');
			$this->view->co_perfil 	= $this->getRequest()->getParam('co_perfil');
			$this->view->ds_email 	= $this->getRequest()->getParam('ds_email');
			if(array_key_exists("co_perfil", $arrPerfil)){
				if(
					$arrPerfil['perfil']['co_perfil'] == Zend_Registry::get('config')->perfil->gestor->co_perfil ||
					$arrPerfil['perfil']['co_perfil'] == Zend_Registry::get('config')->perfil->gestorunidade->co_perfil ||
					$arrPerfil['perfil']['co_perfil'] == Zend_Registry::get('config')->perfil->suporte->co_perfil
				) {
					$this->view->btnNovo = "disabled=\"disabled\"";
				}
				
				if($arrPerfil['perfil']['co_perfil'] == Zend_Registry::get('config')->perfil->administrador->co_perfil) {
					$this->view->exclusao = true;
				} else {
					$this->view->exclusao = false;
				}
			}
			
		}
		
		/**
		 * Limpar caracteres do cpf
		 *
		 * @return string
		 */
		private function limpaCaracterCpf() {
			return str_replace(".","",str_replace("-","",$this->getRequest()->getParam('nu_cpf')));
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
		 * verifica existência de email para outro cpf
		 *
		 * @return int
		 */
		private function verificaExistenciaEmail() {
			$count = 0;
			
			try {
				$count = Model_Usuario::getInstance()->getVerificaEmail($this->getRequest()->getParam('ds_email'),$this->limpaCaracterCpf());
			} catch (Exception $e) {
				throw new Zend_Controller_Action_Exception($e->getMessage());
			}
			
			if($count) {
				echo "<script>alerta('Usuário já cadastrado!.');</script>";
			}
			
			return $count;
		}
		
		/**
		 * Cadastro de usuário
		 *
		 */
		public function cadastroAction() {
			$arrPerfil = Zend_Auth::getInstance()->getIdentity();
		    
		    if($this->getRequest()->isPost()){
		    	
		    	$nu_cpf = $this->limpaCaracterCpf();
				// Consulta informações do usuário
		    	
		    	$usuario = Model_Usuario::getInstance()->getUsuarioPerfil($nu_cpf);
		    	$this->view->usuario = $usuario;
		    	
			    if($this->getRequest()->getParam('acao') == 'editar') {		    	
			    	$this->alteracao();
			    }elseif($this->getRequest()->getParam('acao') == 'cadastrar'){
			    	$this->cadastro();
			    }
		    }
		}
		
		/**
		 * Cadastro de usuário
		 *
		 */
		public function cadastro() {
	    	
	    	$nu_cpf = $this->limpaCaracterCpf($this->getRequest()->getParam('nu_cpf'));
			// Consulta existência do usuário para o cpf informado
	    	if($arrUsuario = Model_Usuario::getInstance()->getConsulta(array('NU_CPF' => $nu_cpf))) {
	    		$strBloqueio = "";
	    		if($arrUsuario[0]['ST_REGISTRO_ATIVO'] == "N") {
	    			$strBloqueio = " O usuário se encontra inativo no sistema. ";
	    		}
	    		echo "<script>alerta('Usuário já cadastrado!",$strBloqueio,"');</script>";
	    	} else if(Model_Usuario::getInstance()->getVerificaEmail($this->getRequest()->getParam('ds_email'),$nu_cpf)) {
	    		echo "<script>alerta('Usuário já cadastrado!');</script>";
	    	} else {
	    		// Senha
	    		$arrSenha = $this->criptografiaSenha();    		
	    		
	    		try {
	    			
	    			
	    			// Cadastro do usuário
	    			Model_Usuario::getInstance()->insert(
	    				array(
	    					"CO_USUARIO" => null,
	    					"NU_CPF" => $nu_cpf,
	    					"NO_USUARIO" => trim(strtoupper($this->getRequest()->getParam('no_usuario'))),
	    					"DT_CADASTRO" => new Zend_Db_Expr('CURDATE()'),
	    					"ST_REGISTRO_ATIVO" => "S",
	    					"CO_SENHA" => $arrSenha['CRIPTOGRAFIA'],
	    					"DS_EMAIL" => trim($this->getRequest()->getParam('ds_email')),
	    					"criptografia" => $arrSenha['SENHA']
	    				)
	    			);
	    			$idUsuario = Model_Usuario::getDefaultAdapter()->lastInsertId();
	    			
	    			// Cadastro do perfil
	    			Model_UsuarioPerfil::getInstance()->insert(
	    				array(
	    					"CO_USUARIO" => $idUsuario,
	    					"CO_PERFIL" => strtoupper($this->getRequest()->getParam('co_perfil')),	    					
	    					"ST_REGISTRO_ATIVO" => "S",
	    					"DT_CADASTRO" => new Zend_Db_Expr('CURDATE()')    				
	    				)
	    			);
				     
					echo "<script>alerta('Usuário cadastrado com sucesso');</script>";
	    		}catch(Exception $e){
	    			echo $e;exit;
	    		}
	   		}
	    
	    	// Parâmetros do formulário
	    	$this->view->nu_cpf			= $this->getRequest()->getParam('nu_cpf');
	    	$this->view->no_usuario		= $this->getRequest()->getParam('no_usuario');
	    	$this->view->ds_email		= $this->getRequest()->getParam('ds_email');
   		}
		
		/**
		 * Alteração de informações de um determinado usuário
		 *
		 */
		private function alteracao() {
			if(!$this->verificaExistenciaEmail()) {
				// Sessão logada
				$arrPerfil = Zend_Auth::getInstance()->getIdentity();
				
				// Cpf formatado
				$nu_cpf = $this->limpaCaracterCpf();
				
				Model_Usuario::getInstance()->getAdapter()->beginTransaction();
				
	    		try {
	    			Model_Usuario::getInstance()->update(
	    				array(
	    					"DS_EMAIL" => trim($this->getRequest()->getParam('ds_email')),
	    					"NO_USUARIO" => $this->getRequest()->getParam('no_usuario'),
	    					"ST_REGISTRO_ATIVO" => $this->getRequest()->getParam('st_registro_ativo'),
	    				),
	    				'NU_CPF = '.$nu_cpf
	    			);
	    			
	    			if($this->getRequest()->getParam('co_perfil')) {
		    			
		    			// Associar perfil(s) para o usuário
		    			Model_SistemaUsuarioPerfil::getInstance()->insert(
		    				array(
		    					"CO_PERFIL" => $this->getRequest()->getParam('co_perfil'),
		    					"NU_CPF" => $nu_cpf,
		    					"ST_REGISTRO_ATIVO" => "S",
		    					"DT_CADASTRO" => new Zend_Db_Expr("SYSDATE"),
		    					"NU_CPF_RESPONSAVEL" => $arrPerfil['perfil']['cpf']
		    				)
		    			);		    			
		    			
	    			}
	    			
	    			Model_Usuario::getInstance()->getAdapter()->commit();
		    		echo "<script>alerta('Alteração realizada com sucesso.');</script>";
	    		} catch (Exception $e) {
	    			Model_Usuario::getInstance()->getAdapter()->rollBack();
	    			
	    			throw new Zend_Controller_Action_Exception($e->getMessage());
	    		}
			}
		}
		
		
		
		/**
		 * Alteração de senha de usuário
		 *
		 */
		public function pesquisausuarioAction() {
			$arrParam = array(
				'NU_CPF' 				=> str_replace('-', '',str_replace('.', '', $this->getRequest()->getParam('nu_cpf'))),
				'NO_USUARIO' 			=> $this->getRequest()->getParam('no_usuario'),
				'DS_EMAIL'				=> $this->getRequest()->getParam('ds_email'),
				'CO_PERFIL'				=> $this->getRequest()->getParam('co_perfil')
			);
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			Zend_Loader::loadClass('Zend_Json');
			$usuarios = Model_Usuario::getInstance()->getConsulta($arrParam);
			$this->_response->appendBody(Zend_Json::encode($usuarios));
			
			$this->view->nu_cpf 			= $this->getRequest()->getParam('nu_cpf');
			$this->view->no_usuario 		= $this->getRequest()->getParam('no_usuario');
			$this->view->ds_email 			= $this->getRequest()->getParam('ds_email');
			$this->view->co_perfil			= $this->getRequest()->getParam('co_perfil');
		}
		
		/**
		 * Alteração de senha de usuário
		 *
		 */
		public function alteracaosenhaAction() {
			
			// Sessão logada
				$arrPerfil = Zend_Auth::getInstance()->getIdentity();
				
				$this->view->co_senha = $arrPerfil['perfil']['co_senha'];
			
			if($this->getRequest()->isPost()) {
				
				
				$this->getRequest()->getParam('co_senha');
				
				if($arrUsuario = Model_Usuario::getInstance()->getAcessoUsuario($arrPerfil['perfil']['email'],crypt($this->getRequest()->getParam('co_senha'), CRYPT_STD_DES))) {
					
						$nu_cpf = str_replace(".","",str_replace("-","",$this->getRequest()->getParam('nu_cpf')));
						
						if($arrUsuario){
						
						// Alterar senha
						$arrSenha = $this->criptografiaSenha();
				
				    	try {
				    		// Geração de nova senha para o usuário
				    		Model_Usuario::getInstance()->update(
				    			array(
				    				"DT_ALTERACAO_SENHA" => new Zend_Db_Expr("SYSDATE"),
				    				"CO_SENHA" => $arrSenha['CRIPTOGRAFIA']
				    			),
				    			"NU_CPF = ".$nu_cpf
				    		);
				    	} catch (Exception $e) {
				    		throw new Zend_Controller_Action_Exception($e->getMessage());
				    	}
					     
						echo "<script>alerta('A nova senha foi enviada a seu email.');</script>";
					}
				} else {
					echo "<script>alerta('A senha não confere com o email do usuário.');</script>";
				}
			}
		}
		
		/**
		 * Exclusão de usuário
		 * executado via AJAX retorno JSON
		 */
		public function excluirAction() {
			if($this->getRequest()->getParam('nu_cpf')) {
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				Zend_Loader::loadClass('Zend_Json'); 
				
				// Cpf formatado
				$nu_cpf = $this->limpaCaracterCpf();
				
	    		try {
		    			
    					// Excluir todo(s) perfil(s) associado(s) ao usuário para o sistema
    					Model_SistemaUsuarioPerfil::getInstance()->delete('CO_SISTEMA = '.Zend_Registry::get('config')->sistema->co_sistema.' AND NU_CPF = '.$nu_cpf);
	    				
    					$retorno = array('msg_certo' => 'Usuário excluído com sucesso.');
    					
	    				//Model_Usuario::getInstance()->getAdapter()->commit();
		    			 $this->_response->appendBody(Zend_Json::encode($retorno)); 
		    		} catch (Exception $e) {
		    			throw new Zend_Controller_Action_Exception($e->getMessage());
		    		}				
				
			}
		}
		
		/**
		 * Exclusão de usuário
		 * executado via AJAX retorno JSON
		 */
		public function verificaPausaAction() {
			if($this->getRequest()->getParam('nu_cpf')) {
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				Zend_Loader::loadClass('Zend_Json'); 
				
				// Cpf formatado
				$nu_cpf = $this->limpaCaracterCpf();
				
	    		try {
		    			
    					// Excluir todo(s) perfil(s) associado(s) ao usuário para o sistema
    					Model_SistemaUsuarioPerfil::getInstance()->delete('CO_SISTEMA = '.Zend_Registry::get('config')->sistema->co_sistema.' AND NU_CPF = '.$nu_cpf);
	    				
    					$retorno = array('msg_certo' => 'Usuário excluído com sucesso.');
    					
	    				//Model_Usuario::getInstance()->getAdapter()->commit();
		    			 $this->_response->appendBody(Zend_Json::encode($retorno)); 
		    		} catch (Exception $e) {
		    			throw new Zend_Controller_Action_Exception($e->getMessage());
		    		}				
				
			}
		}
	}