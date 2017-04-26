<?php
	class CategoriaController extends Zend_Controller_Action {
		public function indexAction()
	    {
	    	
	    	$arrParam = array();
	    	
	    	if( $this->getRequest()->getParam('msg') ){
	    		$this->view->msg = $this->getRequest()->getParam('msg');
	    	}
	    	
	        if( $this->getRequest()->isPost() ) {
	        	if( $this->getRequest()->getParam('act') == 'pesq' ){
	        		$arrParam = array('titulo' => $this->getRequest()->getParam('titulo'));
	        	}elseif( $this->getRequest()->getParam('act') == 'salvar' ){
	        		if( $this->salvar() ){
	        			$_SESSION['message'] = 'Categoria Inserida com sucesso: '.$e;
	        			$params = array('msg' => 'sucesso' );
	        			$this->_helper->redirector('index', 'categoria', null, $params);
	        		}else{
	        			$_SESSION['message'] = 'Erro Ao tentar gravar o chamado: '.$e;
	        			$params = array('msg' => 'erro' );
	        			$this->_helper->redirector('index', 'categoria', null, $params);
	        		}
	        	}
	        	
	        }
	        
	        $categorias = Model_Categoria::getInstance()->getConsultaAll($arrParam);
	        
	        $page = $this->_getParam('page', 1);
	        
	        $paginator = Zend_Paginator::factory($categorias);
	        $paginator->setCurrentPageNumber($page)
	        ->setItemCountPerPage(10);
	        
	        $this->view->paginator = $paginator;	        
	    }
	    
	    public function salvar(){
	    	
	    	try{
	    		$arrParam = array(
	    				'titulo' => $this->getRequest()->getParam('titulo')
	    		);
	    
	    		Model_Categoria::getInstance()->insert( $arrParam );
	    		return true;
	    
	    	}catch( Exception $e ){
	    		debug( $e, 1);
	    	}
	    
	    }

	}