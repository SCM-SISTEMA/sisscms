<?php

class Administracao_CnaeController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
    }

	public function indexAction()
    {
    	if( $this->getRequest()->getParam('msg') ){
    		$this->view->msg = $this->getRequest()->getParam('msg');
    	}

		$this->view->arrCnae = Administracao_Model_Cnae::getInstance()->getConsultaAll(array());

    }
	    
    public function editarAction(){
    	
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
	    	
    	$arrParam = array( 'co_cnae' => $this->getRequest()->getParam( 'co_cnae' )  );
    	$arrCnae = Administracao_Model_Cnae::getInstance()->getConsulta( $arrParam );
    	
    	$this->_response->appendBody(Zend_Json::encode($arrCnae));
    		
    }
    
    public function salvarAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
    	
    	try{
    		
    		$co_cnae = $this->getRequest()->getParam('co_cnae');

	    	$arrParam = array(
	    		'ds_cnae' 	=> $this->getRequest()->getParam('ds_cnae'),
	    		'cod_cnae' 	=> $this->getRequest()->getParam('cod_cnae'),
	    		'sg_cnae' 	=> $this->getRequest()->getParam('sg_cnae')
	    	);

	    	if( empty( $co_cnae ) ){
	    		Administracao_Model_Cnae::getInstance()->insert( $arrParam );
	    	}else{
	    		Administracao_Model_Cnae::getInstance()->update($arrParam, 'co_cnae = '.$co_cnae);
	    	}

			$params = array(
				'msg' => 'success',
				'message' => 'Operação realizada com sucesso'
			);
			$this->_response->appendBody(Zend_Json::encode($params));
	    	
    	}catch( Exception $e ){
			$params = array(
				'msg' => 'error',
				'message' => $e->getMessage()
			);

			$this->_response->appendBody(Zend_Json::encode($params));
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
}

