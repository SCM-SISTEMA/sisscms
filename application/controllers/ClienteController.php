<?php
	class ClienteController extends Zend_Controller_Action {
		public function indexAction()
	    {
	    	
	    	if( $this->getRequest()->getParam('msg') ){
	    		$this->view->msg = $this->getRequest()->getParam('msg');
	    	}
	    	
	    	$arrParam = array();
	    	if($this->getRequest()->isPost()) {
	    		$arrParam = array(
			    				'id_categoria' => $this->getRequest()->getParam('categoria'),
			    				'id_provedor' => $this->getRequest()->getParam('provedor'),
			    				'id_status' => $this->getRequest()->getParam('status'),
			    				'data' => $this->getRequest()->getParam('data')
	    					);
	    	}
	    	
	    	$clientes = Model_Cliente::getInstance()->getConsultaAll( $arrParam );
	    	
	    	$page = $this->_getParam('page', 1);
	    	$pcount = $this->_getParam('count_per_page', 20);
	    	
	    	$paginator = Zend_Paginator::factory($clientes);
	    	$paginator->setCurrentPageNumber($page)
	    	->setItemCountPerPage($pcount);
	    	
	    	$this->view->paginator = $paginator;
	    	$this->view->pcount = $pcount;
	    }
	    
	    public function abrirAction(){
	    	
	    	if($this->getRequest()->isDispatched()) {
	    		
	    		$this->view->provedor = $this->getRequest()->getParam('provedor');
	    		$this->view->protocolo = $this->gerarProtocolo();
	    	}
	    	
	    	if( $this->getRequest()->isPost() && $this->getRequest()->getParam('protocolo') ) {
	    		if( $this->salvar() ){
	    			$_SESSION['message'] = 'Chamado aberto com sucesso!';
					$params = array('msg' => 'sucesso', 'protocolo' => $this->getRequest()->getParam('protocolo'));
					$this->_helper->redirector('index', 'chamados', null, $params);
	    		}
	    	}
	    }
	    
	    public function editarAction(){
	    	
	    	$id_chamado = $this->getRequest()->getParam('id_chamado');
	    	$this->view->chamado = (object) Model_Chamado::getInstance()->getConsulta( array( 'id' => $id_chamado ) );
	    	
	    	if( $this->getRequest()->isPost() && $this->getRequest()->getParam('protocolo') ) {
	    		if( $this->editar() ){
	    			$_SESSION['message'] = 'Chamado alterado com sucesso!';
					$params = array('msg' => 'sucesso', 'protocolo' => $this->getRequest()->getParam('protocolo'));
					$this->_helper->redirector('index', 'chamados', null, $params);
	    		}
	    	}
	    }
	    
	    public function salvar(){
	    	
	    	try{
	    		$user = Zend_Auth::getInstance()->getIdentity();
		    	$arrParam = array(
		    		'id_provedor' => $this->getRequest()->getParam('provedor'),
		    		'id_cliente' => $this->getRequest()->getParam('clientes'),
		    		'protocolo' => $this->getRequest()->getParam('protocolo'),
		    		'data' => retornaYmd( $this->getRequest()->getParam('data') ),
		    		'id_categoria' => $this->getRequest()->getParam('categoria'),
		    		'status' => $this->getRequest()->getParam('id_status'),
		    		'hora' => $this->getRequest()->getParam('hora'),
		    		'prob_info' => trim( $this->getRequest()->getParam('prob_info') ),
		    		'prob_const' => trim( $this->getRequest()->getParam('prob_const') ),
		    		'agendamento' => trim( $this->getRequest()->getParam('agendamento') ),
		    		'obs' => trim( $this->getRequest()->getParam('observacoes') ),
		    		'emails' => $this->getRequest()->getParam('emails'),
		    		'proprietario' => $this->getRequest()->getParam('proprietario'),
		    		'usuario' => $user['user']->id
		    	);
		    	
		    	return Model_Chamado::getInstance()->insert( $arrParam );
		    	
	    	}catch( Exception $e ){
	    		$_SESSION['message'] = 'Erro Ao tentar gravar o chamado: '.$e;
	    		$params = array('msg' => 'erro' );
	    		$this->_helper->redirector('index', 'chamados', null, $params);
	    	}
	    	
	    }
	    
	    public function editar(){
	    	
	    	try{
	    		$user = Zend_Auth::getInstance()->getIdentity();
		    	$arrParam = array(
		    		'id_provedor' => $this->getRequest()->getParam('provedor'),
		    		'id_cliente' => $this->getRequest()->getParam('clientes'),
		    		'protocolo' => $this->getRequest()->getParam('protocolo'),
		    		'data' => retornaYmd( $this->getRequest()->getParam('data') ),
		    		'id_categoria' => $this->getRequest()->getParam('categoria'),
		    		'status' => $this->getRequest()->getParam('status'),
		    		'hora' => $this->getRequest()->getParam('hora'),
		    		'prob_info' => trim( $this->getRequest()->getParam('prob_info') ),
		    		'prob_const' => trim( $this->getRequest()->getParam('prob_const') ),
		    		'agendamento' => trim( $this->getRequest()->getParam('agendamento') ),
		    		'obs' => trim( $this->getRequest()->getParam('observacoes') ),
		    		'emails' => $this->getRequest()->getParam('emails'),
		    		'proprietario' => $this->getRequest()->getParam('proprietario'),
		    		'usuario' => $user['user']->id
		    	);
		    	
		    	return Model_Chamado::getInstance()->update(
		    			$arrParam,
		    			"id = ".$this->getRequest()->getParam('id_chamado')
		    	);
		    	
	    	}catch( Exception $e ){
	    		$_SESSION['message'] = 'Erro Ao tentar gravar o chamado: '.$e;
	    		$params = array('msg' => 'erro' );
	    		$this->_helper->redirector('index', 'chamados', null, $params);
	    	}
	    	
	    }
	    
	    public function recentesAction(){
	    	
	    	$this->_helper->layout->disableLayout();
	    	
	    	$arrParam = array( 'id_cliente' => $this->getRequest()->getParam( 'clientes' ) );
	    	
	    	$chamados = Model_Chamado::getInstance()->getConsultaAll( $arrParam );
	    	
	    	$page = $this->_getParam('page', 1);
	    	
	    	$paginator = Zend_Paginator::factory($chamados);
	    	$paginator->setCurrentPageNumber($page)
	    	->setItemCountPerPage(10);
	    	
	    	$this->view->paginatorRecentes = $paginator;
	    	
	    	
	    }
	    
	    public function gerarProtocolo(){
	    	
	    	$ultimo = Model_Protocolo::getInstance()->getUltimoProtocolo();
	    	$protocolo = (int) $ultimo['protocolo'];
	    	++$protocolo;
	    	$protocolo = Model_Protocolo::getInstance()->insert( array( 'protocolo' => $protocolo ) );
	    	
	    	$protocolo_atual = Model_Protocolo::getInstance()->getConsulta( array( 'id' => $protocolo ) );
	    	
	    	return $protocolo_atual['protocolo'];
	    	
	    }

	}