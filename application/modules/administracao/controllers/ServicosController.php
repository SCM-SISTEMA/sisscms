<?php

class Administracao_ServicosController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
    }

	public function indexAction()
    {
    	
    	$message = new Zend_Session_Namespace('message');

    	if( $this->getRequest()->getParam('msg') ){
    		$this->view->msg = $this->getRequest()->getParam('msg');
    	}

		$this->view->arrServicos = Administracao_Model_Servicos::getInstance()->getConsultaAll(array());
    }
	    
    public function editarAction(){
    	
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
	    	
    	$arrParam = array( 'co_servico' => $this->getRequest()->getParam( 'co_servico' )  );
    	$arrServicos = Administracao_Model_Servicos::getInstance()->getConsulta( $arrParam );
    	$this->_response->appendBody(Zend_Json::encode($arrServicos));
    		
    }
    
    public function salvarAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		Zend_Loader::loadClass('Zend_Json');

    	try{
    		
    		$co_servico = $this->getRequest()->getParam('co_servico');

	    	$arrParam = array(
	    		'no_servico' 		=> $this->getRequest()->getParam('no_servico'),
	    		'ds_servico' 		=> $this->getRequest()->getParam('ds_servico'),
	    		'sg_servico' 		=> $this->getRequest()->getParam('sg_servico'),
	    		'tp_servico' 		=> $this->getRequest()->getParam('tp_servico'),
	    		'st_ativo' 			=> 'S',
	    		'dt_cadastro' 		=> dataAtual()
	    	);

	    	if( empty( trim($co_servico) ) ){
				$co_servico = Administracao_Model_Servicos::getInstance()->insert( $arrParam );
				$arrParam = array(
					'co_servico' 		=> $co_servico,
					'ds_modelo' 		=> $this->getRequest()->getParam('ds_modelo'),
				);

				Administracao_Model_ModeloContratoServico::getInstance()->insert( $arrParam );

	    	}else{
	    		unset($arrParam['dt_cadastro']);
	    		Administracao_Model_Servicos::getInstance()->update($arrParam, 'co_servico = '.$co_servico);
				$arrParamModelo = array(
					'ds_modelo' 		=> $this->getRequest()->getParam('ds_modelo')
				);
				Administracao_Model_ModeloContratoServico::getInstance()->update( $arrParamModelo, 'co_modelo_contrato_servico='.$this->getRequest()->getParam('co_modelo_contrato_servico') );
	    	}
    		$params = array(
					'msg' => 'success',
					'message' => 'Operação realizada com sucesso'
			);

    	}catch( Exception $e ){
			$params = array(
				'msg' => 'erro',
				'message' => 'Erro Ao tentar inserir/editar: '.$e
			);
    	}

		$this->_response->appendBody(Zend_Json::encode($params));
    	
    }
    
    public function salvarClausulaAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		Zend_Loader::loadClass('Zend_Json');

		try{

			if(empty($this->getRequest()->getParam('co_servico'))){
				debug('teste', 1);
				$arrParam = array(
					'ds_servico' 	=> $this->getRequest()->getParam('ds_servico'),
					'sg_servico' 	=> $this->getRequest()->getParam('sg_servico'),
					'sg_servico' 	=> $this->getRequest()->getParam('sg_servico'),
					'st_ativo' 		=> ST_REGISTRO_ATIVO,
					'dt_cadastro' 	=> date('Y-m-d')
				);

				Administracao_Model_Servicos::getInstance()->insert( $arrParam );
				$namespace = new Zend_Session_Namespace('message');
				$namespace->message = "Operação realizada com sucesso";
				$params = array('msg' => 'success' );
			}else{
				$arrParam = array(
					'co_servico' 	=> $this->getRequest()->getParam('co_servico'),
					'ds_clausula' 	=> $this->getRequest()->getParam('ds_clausula'),
					'dt_cadastro' 	=> date('Y-m-d')
				);

				Administracao_Model_Clausula::getInstance()->insert( $arrParam );
				$namespace = new Zend_Session_Namespace('message');
				$namespace->message = "Operação realizada com sucesso";
				$params = array('msg' => 'success' );
			}
    		

			$this->_response->appendBody(Zend_Json::encode($params));
	    	
    	}catch( Exception $e ){
			debug($e, 1);
    		$_SESSION['message'] = 'Erro Ao tentar inserir/editar: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'servicos', 'administracao', $params);
    	}
    	
    }
    
    public function deletarAction(){
    	
    	try{
    		
    		$co_cnae = $this->getRequest()->getParam('co_cnae');    		
    		Administracao_Model_Cnae::getInstance()->delete('co_cnae = '.$co_cnae);
	    	$namespace = new Zend_Session_Namespace('message');
	    	$namespace->message = "Operação realizada com sucesso";
    		$params = array('msg' => 'success' );
	    	$this->_helper->redirector('index', 'cnae', 'administracao', $params);
	    	
    	}catch( Exception $e ){
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = 'Erro Ao tentar excluir o registro: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'cnae', 'administracao', $params);
    	}
    	
    }
    
    public function listarClausulasAction(){
    
    	$this->_helper->layout->disableLayout();
    
    	$arrParam = array( 'co_servico' => $this->getRequest()->getParam( 'co_servico' )  );
    	
    	$arrClausula = Administracao_Model_Clausula::getInstance()->getConsultaAll( $arrParam );
    	
    	$this->view->clausulas = $arrClausula;
    
    }
    
    public function salvarTipoContratoAction(){
    
    	try{
    		$user = Zend_Auth::getInstance()->getIdentity();
    
    		$arrParam = array(
    				'ds_tipo_contrato' => $this->getRequest()->getParam('ds_tipo_contrato')
    		);
    
    		Administracao_Model_TipoContrato::getInstance()->insert( $arrParam );
    
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = "Operação realizada com sucesso";
    		$params = array('msg' => 'success' );
    		$this->_helper->redirector('index', 'servicos', 'administracao', $params);
    
    	}catch( Exception $e ){
    		$_SESSION['message'] = 'Erro Ao tentar gravar o chamado: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'servicos', 'administracao', $params);
    	}
    
    }
}


