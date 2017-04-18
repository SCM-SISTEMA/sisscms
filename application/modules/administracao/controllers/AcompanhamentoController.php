<?php

class Administracao_AcompanhamentoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
    }

	public function indexAction()
    {
    	
    	$message = new Zend_Session_Namespace('message');
    	$co_contrato = base64_decode( $this->getRequest()->getParam('co_contrato') );

    	if( $this->getRequest()->getParam('msg') ){
    		$this->view->msg = $this->getRequest()->getParam('msg');
    	}
    	
   		$arrContratoChecklist = Administracao_Model_ContratoChecklist::getInstance()->getConsultaAll( array('co_contrato' => $co_contrato ) );

    	if( $this->getRequest()->isPost()){
    		
    		if( $this->getRequest()->getParam('co_protocolo') == "" && $this->getRequest()->getParam('nu_protocolo') ){
    			
    			$date = getdate();
    			$no_arquivo = $date[0].'.'.pathinfo($_FILES['no_arquivo']['name'], PATHINFO_EXTENSION);
    			
    			$imageAdapter = new Zend_File_Transfer_Adapter_Http();
    			$imageAdapter->setDestination('protocolo');
    			$imageAdapter->addValidator('Count', false, array('min' =>1, 'max' => 1))
    			->addValidator('Extension', false, 'doc,docx,pdf')
    			->addValidator('Size', false, array('max' => '100000kB'));
    			
    			$imageAdapter->addFilter('Rename', array('target' => 'protocolo/'.$no_arquivo,
    					'overwrite' => true));
    			
    			if (!$imageAdapter->isValid())
    			{
    				$namespace = new Zend_Session_Namespace('message');
    				$namespace->message = "Não foi possível enviar o arquivo, verifique se o tamanho é maior que 5Mb ou se a extensão é válida";
    				$params = array('co_contrato' => base64_encode( $co_contrato ), 'msg' => 'erro' );
    				$this->_helper->redirector('index', 'acompanhamento', 'administracao', $params);
    			}
    			
    			$imageAdapter->receive();
    			
    			$arrParam = array(
    					'co_contrato' 		=> $co_contrato,
    					'nu_protocolo' 		=> $this->getRequest()->getParam('nu_protocolo'),
    					'co_tipo_protocolo' => $this->getRequest()->getParam('co_tipo_protocolo'),
    					'dt_abertura' 		=> retornaYmd( $this->getRequest()->getParam('dt_abertura') ),
    					'no_arquivo' 		=> $no_arquivo,
    					'ds_lbl_arquivo' 	=> $_FILES['no_arquivo']['name'],
    					'dt_cadastro' 		=> dataAtual()
    			);
    			
    			Administracao_Model_Protocolo::getInstance()->insert( $arrParam );
    			
    		}elseif( $this->getRequest()->getParam('co_protocolo') != "" && $this->getRequest()->getParam('nu_protocolo') ){
    			$co_protocolo = $this->getRequest()->getParam('co_protocolo');
    			$arrParam = array( 
    					'co_contrato' => $this->getRequest()->getParam('co_contrato'),
    					'nu_protocolo' => $this->getRequest()->getParam('nu_protocolo'), 
    					'tp_protocolo' => $this->getRequest()->getParam('tp_protocolo'), 
    					'dt_abertura' =>  retornaYmd( $this->getRequest()->getParam('dt_abertura') )
    					);
    			Administracao_Model_Protocolo::getInstance()->update( $arrParam, 'co_protocolo = '.$co_protocolo );
    		}else{

				try{
    		
	    		$co_contrato = base64_decode( $this->getRequest()->getParam('co_contrato') );
	    		
	    		$arrChecklist = $this->getRequest()->getParam('fase');

	    		foreach ( $arrChecklist as $checklist ){
		    		if( in_array( array( 'co_contrato' => $co_contrato, 'co_checklist' => $checklist ), $arrContratoChecklist ) ){
		    			continue;
		    		}else{
		    			Administracao_Model_ContratoChecklist::getInstance()->insert( array('co_contrato' => $co_contrato, 'co_checklist' => $checklist ) );
		    		}
	    		}

				}catch(Exception $e){
					$namespace = new Zend_Session_Namespace('message');
					$namespace->msg = "error";
					$namespace->message = $e->getMessage();
					$this->_redirect('/administracao/acompanhamento/index/co_contrato/'.$this->getRequest()->getParam('co_contrato'));
				}
    		}
    		
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->msg = "success";
    		$namespace->message = "Operação realizada com sucesso!";
    		$this->_redirect('/administracao/acompanhamento/index/co_contrato/'.$this->getRequest()->getParam('co_contrato'));
    		
    	}
    	
    	$this->view->empresa 				= Model_Checklist::getInstance()->getConsultaAll(array('co_fase' => CO_FASE_EMPRESA));
    	$this->view->fase1 					= Model_Checklist::getInstance()->getConsultaAll(array('co_fase' => CO_FASE_1));
    	$this->view->fase2 					= Model_Checklist::getInstance()->getConsultaAll(array('co_fase' => CO_FASE_2));
    	$this->view->fase3 					= Model_Checklist::getInstance()->getConsultaAll(array('co_fase' => CO_FASE_3));
    	$this->view->ultimafase 			= Model_Checklist::getInstance()->getConsultaAll(array('co_fase' => CO_FASE_4));
    	$this->view->co_contrato			= $co_contrato;
    	$this->view->arrcontratoChecklist 	= $arrContratoChecklist;

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

