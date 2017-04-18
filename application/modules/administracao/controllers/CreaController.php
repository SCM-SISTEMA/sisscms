<?php

class Administracao_CreaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
    }

	public function indexAction()
    {
    	$arrResponsavel = Administracao_Model_ResponsavelTecnico::getInstance()->getConsultaAll(array());
    	$this->view->arrResponsavel = $arrResponsavel;
    }
	    
    public function editarAction(){
    	
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
	    	
    	$arrParam = array( 'co_responsavel_tecnico' => $this->getRequest()->getParam( 'co_responsavel_tecnico' )  );
    	$arrResponsavelTecnico = Administracao_Model_ResponsavelTecnico::getInstance()->getConsulta( $arrParam );
    	
    	$this->_response->appendBody( Zend_Json::encode( $arrResponsavelTecnico ) );
    		
    }
    
    public function salvarAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
    	
    	try{
    		
    		$co_responsavel_tecnico = $this->getRequest()->getParam('co_responsavel_tecnico');

	    	$arrParam = array(
	    		'tp_responsavel_tecnico' 	=> $this->getRequest()->getParam('tp_responsavel_tecnico'),
	    		'nu_registro_crea'		 	=> $this->getRequest()->getParam('nu_registro_crea'),
	    		'st_recebimento_direto'		=> $this->getRequest()->getParam('st_recebimento_direto'),
	    		'no_responsavel_tecnico'	=> $this->getRequest()->getParam('no_resp_tec'),
	    		'nu_telefone'	 			=> removeCaracter( $this->getRequest()->getParam('nu_tel_resp_tec') ),
	    		'ds_email'			 		=> $this->getRequest()->getParam('ds_email_resp_tec'),
	    		'nu_whatsapp' 				=> removeCaracter( $this->getRequest()->getParam('nu_whatsapp') )
	    	);

	    	if( empty( $co_responsavel_tecnico ) ){
	    		try{
	    			$arrParam['st_ativo'] = 'S';
		    		Administracao_Model_ResponsavelTecnico::getInstance()->insert( $arrParam );
	    		}catch( Exception $e){
					$params = array(
						'msg' => 'erro',
						'message' => 'Erro Ao tentar inserir/editar: '.$e
					);
					$this->_response->appendBody(Zend_Json::encode($params));
	    		}
	    	}else{
	    		Administracao_Model_ResponsavelTecnico::getInstance()->update($arrParam, 'co_responsavel_tecnico = '.$co_responsavel_tecnico);
	    	}
    	}catch( Exception $e ){
			$params = array(
				'msg' => 'erro',
				'message' => 'Erro Ao tentar inserir/editar: '.$e
			);
			$this->_response->appendBody(Zend_Json::encode($params));
    	}

		$params = array(
			'msg' => 'success',
			'message' => 'Operação realizada com sucesso'
		);
		$this->_response->appendBody(Zend_Json::encode($params));
    	
    }
    
    public function deletarAction(){
    	
    	try{
    		
    		$co_responsavel_tecnico = $this->getRequest()->getParam('co_responsavel_tecnico');
    		Administracao_Model_ResponsavelTecnico::getInstance()->update(array('st_ativo' => 'N'), 'co_responsavel_tecnico = '.$co_responsavel_tecnico);
	    	$namespace = new Zend_Session_Namespace('message');
	    	$namespace->message = "Operação realizada com sucesso";
    		$params = array('msg' => 'success' );
	    	$this->_helper->redirector('index', 'crea', 'administracao', $params);
	    	
    	}catch( Exception $e ){
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = 'Erro Ao tentar excluir o registro: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'crea', 'administracao', $params);
    	}
    	
    }
    
    public function dadosAction()
    {
    
    	$message = new Zend_Session_Namespace('message');
    
    	if( $this->getRequest()->getParam('msg') ){
    		$this->view->msg = $this->getRequest()->getParam('msg');
    	}
    	
    	$co_responsavel_tecnico = $this->getRequest()->getParam('co_responsavel_tecnico');
    	$this->view->co_responsavel_tecnico = $co_responsavel_tecnico;
    
    	$this->view->objResponsavel = (object)Administracao_Model_ResponsavelTecnico::getInstance()->getConsulta( array( 'co_responsavel_tecnico' => $co_responsavel_tecnico ) );
    	$arrVisto 		= Administracao_Model_Visto::getInstance()->getConsultaAll( array( 'co_responsavel_tecnico' => $co_responsavel_tecnico ) );
		foreach($arrVisto as $visto){
			$arrEstadoVisto[] =  $visto['co_estado'];
		}
    	$this->view->arrAnuidade 	= Administracao_Model_AnuidadeCrea::getInstance()->getConsultaAll( array( 'co_responsavel_tecnico' => $co_responsavel_tecnico ) );
    	$this->view->arrEmpresas 	= Administracao_Model_ResponsavelTecnicoPessoaJuridica::getInstance()->getConsultaAll( array( 'co_responsavel_tecnico' => $co_responsavel_tecnico ) );
    	$this->view->arrEstados 	= Administracao_Model_Estados::getInstance()->getConsultaAll(array());
    	$this->view->arrVisto 	= $arrEstadoVisto;
    }
    
    public function salvarVistoAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		Zend_Loader::loadClass('Zend_Json');

    	$sg_uf 					= $this->getRequest()->getParam('sg_uf');
    	$co_responsavel_tecnico = $this->getRequest()->getParam( 'co_responsavel_tecnico' );
    	
    	$arrVistos = Administracao_Model_Visto::getInstance()->getConsultaEstados( array( 'co_responsavel_tecnico' => $co_responsavel_tecnico ) );

    	foreach( $sg_uf as $co_estado ){
    		if( in_array( $co_estado, array_column($arrVistos, 'co_estado') ) ){
				continue;
    		}else{
    			$arrParam = array( 'co_responsavel_tecnico' => $co_responsavel_tecnico, 'co_estado' => (int)$co_estado );
    			try{
    				Administracao_Model_Visto::getInstance()->insert( $arrParam );

    			}catch( Exception $e ){
    				debug( $e->getMessage(), 1 );
    			}
    		}
    		
    	}
    	$params = array('co_responsavel_tecnico' => $co_responsavel_tecnico, 'msg' => 'success', 'message' => "Operação realizada com sucesso" );
		$this->_response->appendBody(Zend_Json::encode($params));

    }

    public function carregarVistosAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		Zend_Loader::loadClass('Zend_Json');
    	$co_responsavel_tecnico = $this->getRequest()->getParam( 'co_responsavel_tecnico' );
    	$arrVistos = Administracao_Model_Visto::getInstance()->getConsultaEstados( array( 'co_responsavel_tecnico' => $co_responsavel_tecnico ) );
		$this->_response->appendBody(Zend_Json::encode($arrVistos));

    }

    public function salvarAnuidadeAction(){
    	
    	$this->_helper->layout->disableLayout();
    	
    	$co_responsavel_tecnico = $this->getRequest()->getParam( 'co_responsavel_tecnico' );
    	$date = getdate();
    	$no_arquivo = $date[0].'.'.pathinfo($_FILES['no_arquivo']['name'], PATHINFO_EXTENSION);

    	$arrParam = array(
    			'nu_ano' 				 => $this->getRequest()->getParam( 'nu_ano' ),
    			'co_responsavel_tecnico' => $co_responsavel_tecnico,
    			'ds_lbl_arquivo'	 	 => $_FILES['no_arquivo']['name'],
    			'no_arquivo'	 		 => $no_arquivo
    	);

    	$imageAdapter = new Zend_File_Transfer_Adapter_Http();
    	$imageAdapter->setDestination('anuidade');
    	$imageAdapter->addValidator('Count', false, array('min' =>1, 'max' => 1))
    	->addValidator('Extension', false, 'doc,docx,pdf')
    	->addValidator('Size', false, array('max' => '100000kB'));
    	
    	$imageAdapter->addFilter('Rename', array('target' => 'anuidade/'.$no_arquivo,
    			'overwrite' => true));
    	
    		
   		if (!$imageAdapter->isValid())
   		{
   			$namespace = new Zend_Session_Namespace('message');
   			$namespace->message = "Não foi possível enviar o arquivo, verifique se o tamanho é maior que 5Mb ou se a extensão é válida";
   			$params = array('co_responsavel_tecnico' => $co_responsavel_tecnico, 'msg' => 'erro' );
   			$this->_helper->redirector('dados', 'crea', 'administracao', $params);
   		}

   		$imageAdapter->receive();
    	
   		$arrAnuidade = Administracao_Model_AnuidadeCrea::getInstance()->getConsulta( $arrParam );
    	
    	if( !$arrAnuidade ){
    		try{
    			Administracao_Model_AnuidadeCrea::getInstance()->insert( $arrParam );
    			$namespace = new Zend_Session_Namespace('message');
    			$namespace->message = "Operação realizada com sucesso";
    		}catch( Exception $e ){
    			debug( $e->getMessage(), 1 );
    		}
    	}else{
    		$params = array('co_responsavel_tecnico' => $co_responsavel_tecnico, 'msg' => 'erro' );
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = "Não foi possível efetuar a operação, já existe um registro para essa anuidade!";
    		$this->_helper->redirector('dados', 'crea', 'administracao', $params);
    	}
    	
    	$params = array('co_responsavel_tecnico' => $co_responsavel_tecnico, 'msg' => 'success' );
    	$this->_helper->redirector('dados', 'crea', 'administracao', $params);
    }
}

