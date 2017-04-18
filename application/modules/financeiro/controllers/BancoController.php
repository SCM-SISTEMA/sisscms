<?php

class Financeiro_BancoController extends Zend_Controller_Action
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
    	
    	$arrBanco = Model_Banco::getInstance()->getConsultaAll(array());
    	
    	$page = $this->_getParam('page', 1);
    	$pcount = $this->_getParam('count_per_page', 20);
    	
    	$paginator = Zend_Paginator::factory($arrBanco);
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($pcount);
    	
    	$this->view->paginator = $paginator;
    	$this->view->pcount = $pcount;
    }
	    
	    
    public function editarAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$arrParam = array( 'co_banco' => $this->getRequest()->getParam( 'co_banco' )  );
    	$arrBanco = Model_Banco::getInstance()->getConsulta( $arrParam );
    	
    	$this->_response->appendBody(Zend_Json::encode($arrBanco));
    }
    
    public function salvarAction(){
    	
    	try{
    		$user = Zend_Auth::getInstance()->getIdentity();
    		
    		$arrParam = array(
    				'ds_banco' 		=> $this->getRequest()->getParam('ds_banco'),
    				'ds_saldo' 		=> removePriceFormat( $this->getRequest()->getParam('ds_saldo') ),
    				'ds_limite' 	=> removePriceFormat( $this->getRequest()->getParam('ds_limite') ),
    				'dt_cadastro' 	=> Zend_Date::now()->toString("Y-M-d hh:mm:ss"),
    				'co_usuario'	=> $user['user']->id
    		);
    		
    		if( $this->getRequest()->getParam('co_banco') ){
    			Model_Banco::getInstance()->update($arrParam, 'co_banco = '.$this->getRequest()->getParam('co_banco'));
    		}else{
    			Model_Banco::getInstance()->insert( $arrParam );
    		}
	    	
	    	$namespace = new Zend_Session_Namespace('message');
	    	$namespace->message = "Operação realizada com sucesso";
	    	$params = array('msg' => 'success' );
	    	$this->_helper->redirector('index', 'banco', 'financeiro', $params);
	    	
    	}catch( Exception $e ){
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = 'Erro Ao tentar gravar o chamado: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'banco', 'financeiro', $params);
    	}
    	
    }
    
    public function deletarAction(){
    
    	try{
    
    		$co_banco = $this->getRequest()->getParam('co_banco');
    		Model_Banco::getInstance()->delete('co_banco = '.$co_banco);
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = "Operação realizada com sucesso";
    		$params = array('msg' => 'success' );
    		$this->_helper->redirector('index', 'banco', 'financeiro', $params);
    
    	}catch( Exception $e ){
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = 'Erro Ao tentar excluir o registro: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'banco', 'financeiro', $params);
    	}
    
    }
}

