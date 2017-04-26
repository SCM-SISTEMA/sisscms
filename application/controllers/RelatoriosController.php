<?php
	class RelatoriosController extends Zend_Controller_Action {
		public function indexAction()
	    {
	    	
	    }
	    
	    public function abrirAction(){
	    	
	    	if($this->getRequest()->isDispatched()) {
	    		
	    		$this->view->provedor = $this->getRequest()->getParam('provedor');
	    		$this->view->cliente = $this->getRequest()->getParam('clientes');
	    	}
	    	
	    	if( $this->getRequest()->getParam('provedor') ){
	    		$this->view->protocolo = $this->gerarProtocolo( $this->getRequest()->getParam('provedor') );
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
	    
	    public function visualizarAction(){
	    	
	    	$id_chamado = $this->getRequest()->getParam('id_chamado');
	    	$objChamado = (object) Model_Chamado::getInstance()->getConsulta( array( 'id' => $id_chamado ) );
	    	$this->view->chamado = $objChamado;
	    	$objProvedor = (object) Model_Provedor::getInstance()->getConsulta( array( 'id' => $objChamado->id_provedor) );
	    	$this->view->provedor = $objProvedor;
	    	$objCliente = (object) Model_Cliente::getInstance()->getConsulta( array( 'id' => $objChamado->id_cliente) );
	    	$this->view->cliente = $objCliente;
	    	$objCategoria = (object) Model_Categoria::getInstance()->getConsulta( array( 'id' => $objChamado->id_categoria) );
	    	$this->view->categoria = $objCategoria;
	    	$objStatus = (object) Model_Status::getInstance()->getConsulta( array( 'id' => $objChamado->status) );
	    	$this->view->status = $objStatus;
	    	
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
	    
	    public function gerarProtocolo( $co_provedor ){
	    	
	    	$ultimo = Model_Protocolo::getInstance()->getUltimoProtocolo();
	    	$protocolo = (int) $ultimo['co_protocolo'];
	    	$ano = date('Y');
	    	$protocolo = $ano.$co_provedor.date('mdhis');
	    	
// 	    	$protocolo = Model_Protocolo::getInstance()->insert( array( 'nucccbhhnmm,._protocolo' => $protocolo ) );
	    	
// 	    	$protocolo_atual = Model_Protocolo::getInstance()->getConsulta( array( 'co_protocolo' => $protocolo ) );
	    	
// 	    	return $protocolo_atual['co_protocolo'];
	    	return $protocolo;
	    	
	    }

	}