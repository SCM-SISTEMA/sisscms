<?php

class Administracao_PlanosController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
    }

	public function indexAction()
    {
    	$arrPlanos = Administracao_Model_Planos::getInstance()->getConsultaAll(array());
    	$arrServicos = Administracao_Model_Servicos::getInstance()->getConsultaAll(array());
		$this->view->arrPlanos = $arrPlanos;
		$this->view->arrServicos = $arrServicos;
    }
	    
    public function editarAction(){
    	
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
	    	
    	$arrParam = array( 'co_status' => $this->getRequest()->getParam( 'co_status' )  );
    	$arrStatus = Administracao_Model_Status::getInstance()->getConsulta( $arrParam );

    	$this->_response->appendBody(Zend_Json::encode($arrStatus));
    		
    }

    public function vincularServicoPlanoAction(){

    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();

    	$arrParam = $this->getRequest()->getParam( 'data' );
		try {
			if (sizeof($arrParam['co_servico'])) {
				Administracao_Model_PlanosServicos::getInstance()->delete( 'co_plano = '.$arrParam['co_plano']);
				foreach ($arrParam['co_servico'] as $co_servico) {
					Administracao_Model_PlanosServicos::getInstance()->insert(array('co_plano' => $arrParam['co_plano'], 'co_servico' => $co_servico));
				}
			}

			$params = array(
				'msg' => 'success',
				'message' => 'Operação realizada com sucesso'
			);
			$this->_response->appendBody(Zend_Json::encode($params));
		}catch(Exception $e){
			$params = array(
				'msg' => 'error',
				'message' => $e->getMessage()
			);
			$this->_response->appendBody(Zend_Json::encode($params));
		}

    }

    public function retornarServicosPlanoAction(){

    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();

    	$co_plano = $this->getRequest()->getParam( 'co_plano' );

			if ($co_plano) {
				$arrServicosPlano = Administracao_Model_PlanosServicos::getInstance()->getConsultaAll(array('co_plano' => $co_plano));
				$this->_response->appendBody(Zend_Json::encode($arrServicosPlano));
			}else{
				$params = array(
					'msg' => 'error',
					'message' => 'Erro'
				);
				$this->_response->appendBody(Zend_Json::encode($params));
			}

    }

    public function salvarAction(){
    	
    	try{
    		
    		$co_status = $this->getRequest()->getParam('co_status');
    		
	    	$arrParam = array(
	    		'ds_status' 	=> $this->getRequest()->getParam('ds_status')
	    	);
	    	
	    	if( empty( $co_status ) ){
	    		Administracao_Model_Status::getInstance()->insert( $arrParam );
	    	}else{
	    		Administracao_Model_Status::getInstance()->update($arrParam, 'co_status = '.$co_status);
	    	}
	    	$namespace = new Zend_Session_Namespace('message');
	    	$namespace->message = "Operação realizada com sucesso";
    		$params = array('msg' => 'success' );
	    	$this->_helper->redirector('index', 'status', 'administracao', $params);
	    	
    	}catch( Exception $e ){
    		$_SESSION['message'] = 'Erro Ao tentar inserir/editar: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'status', 'administracao', $params);
    	}
    	
    }
    
    public function deletarAction(){
    	
    	try{
    		
    		$co_status = $this->getRequest()->getParam('co_status');    		
    		Administracao_Model_Status::getInstance()->delete('co_status = '.$co_status);
	    	$namespace = new Zend_Session_Namespace('message');
	    	$namespace->message = "Operação realizada com sucesso";
    		$params = array('msg' => 'success' );
	    	$this->_helper->redirector('index', 'status', 'administracao', $params);
	    	
    	}catch( Exception $e ){
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = 'Erro Ao tentar excluir o registro: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'status', 'administracao', $params);
    	}
    	
    }
    
    public function listarProtocolosAction(){
    	$this->_helper->layout->disableLayout();
    	
    	$co_contrato = base64_decode( $this->getRequest()->getParam('co_contrato') );
    	
    	$arrProtocolos = Administracao_Model_Protocolo::getInstance()->getConsultaAll( array( 'co_contrato' => $co_contrato ) );
    	
    	$page = $this->_getParam('page', 1);
    	$pcount = $this->_getParam('count_per_page', 20);
    	
    	$paginator = Zend_Paginator::factory( $arrProtocolos );
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($pcount);
    	
    	$this->view->paginatorProtocolos = $paginator;
    	$this->view->pcount = $pcount;
    	
    }
    
    public function editarProtocoloAction(){
    
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    
    	$arrParam = array( 'co_protocolo' => $this->getRequest()->getParam( 'co_protocolo' )  );
    	$arrProtocolo = Administracao_Model_Protocolo::getInstance()->getConsulta( $arrParam );
    
    	$this->_response->appendBody( Zend_Json::encode( $arrProtocolo ) );
    
    }
}

