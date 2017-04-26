<?php

class Administracao_StatusController extends Zend_Controller_Action
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
    	
    	$arrStatus = Administracao_Model_Status::getInstance()->getConsultaAll(array());
    	
    	$page = $this->_getParam('page', 1);
    	$pcount = $this->_getParam('count_per_page', 20);
    	
    	$paginator = Zend_Paginator::factory($arrStatus);
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($pcount);
    	
    	$this->view->paginator = $paginator;
    	$this->view->pcount = $pcount;
    }
	    
    public function editarAction(){
    	
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
	    	
    	$arrParam = array( 'co_status' => $this->getRequest()->getParam( 'co_status' )  );
    	$arrStatus = Administracao_Model_Status::getInstance()->getConsulta( $arrParam );
    	
    	$this->_response->appendBody(Zend_Json::encode($arrStatus));
    		
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
}

