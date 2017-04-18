<?php

class Email_IndexController extends Zend_Controller_Action
{
	private $user;

    public function init()
    {
        /* Initialize action controller here */
		$this->user = Zend_Auth::getInstance()->getIdentity();
		$this->view->user = $this->user;
    }

	public function indexAction()
    {
		set_time_limit(0);


		if( $this->getRequest()->isPost()){

			$arrParamEmail = array(
				'ds_assunto' 		=> $this->getRequest()->getParam('ds_assunto'),
				'ds_mensagem' 		=> $this->getRequest()->getParam('mensagem'),
				'dt_cadastro' 		=> dataAtual()
			);

			try {
				$emailAdapter = Model_Email::getDefaultAdapter();
				$emailAdapter->beginTransaction();


				$arrUsuario = $this->getRequest()->getParam('co_usuario');
				$co_email = Model_Email::getInstance()->insert($arrParamEmail);

				foreach ($arrUsuario as $usuario) {
					$arrParamRLEmailUsuario = array(
						'co_usuario' => $usuario,
						'co_email' => $co_email
					);
					Model_EmailUsuario::getInstance()->insert($arrParamRLEmailUsuario);
				}

				$upload = new Zend_File_Transfer_Adapter_Http();
				$files  = $upload->getFileInfo();
				$upload->setDestination('anexos');
				foreach($files as $file => $fileInfo) {
					$date = getdate();
					$no_arquivo = $date[0].'.'.pathinfo($files[$file]['name'], PATHINFO_EXTENSION);
					$files[$file]['name'] = $no_arquivo;

					if ($upload->isUploaded($file)) {
						if ($upload->isValid($file)) {
							$upload->receive($file);
						}
					}
				}

				$emailAdapter->commit();

				$this->_helper->redirector('index', 'index', 'email');

			}catch( Exception $e ){
				debug($e->getMessage(), 1);
			}

		}

		if( $this->getRequest()->getParam('st_enviado') ) {
			$arrParam = array("st_enviado" => $this->getRequest()->getParam('st_enviado'));
		}
		if( $this->getRequest()->getParam('st_encaminhado') ) {
			$arrParam = array("st_encaminhado" => $this->getRequest()->getParam('st_encaminhado'));
		}
		if( $this->getRequest()->getParam('st_deletado') ) {
			$arrParam = array("st_deletado" => $this->getRequest()->getParam('st_deletado'));
		}
		if( $this->getRequest()->getParam('st_urgente') ) {
			$arrParam = array("st_urgente" => $this->getRequest()->getParam('st_urgente'));
		}
		if( $this->getRequest()->getParam('st_lido') ) {
			$arrParam = array("st_lido" => $this->getRequest()->getParam('st_lido'));
		}
		if( $this->getRequest()->getParam('st_importante') ) {
			$arrParam = array("st_importante" => $this->getRequest()->getParam('st_importante'));
			if( $this->getRequest()->getParam('co_importante') == 0 || $this->getRequest()->getParam('co_importante') == 1 ) {
				$importante = ($this->getRequest()->getParam('co_importante') == 0) ? null : 1;
				$arrParamEdit = array("st_importante" => $importante);
				Model_Email::getInstance()->update($arrParamEdit, 'co_email='.$this->getRequest()->getParam('co_email'));
			}
		}

		$arrParam['co_usuario'] = $this->user['user']->co_usuario;

		$arrEmailGeral = Model_Email::getInstance()->getConsultaAll(array('co_usuario'=>$this->user['user']->co_usuario));
		$arrEmail = Model_Email::getInstance()->getConsultaAll($arrParam);
		$arrUsuarios = Model_Usuario::getInstance()->getConsultaAll(array());

		$this->view->cLidos = array_sum(array_map(function($b) {return empty($b) ? 0 : 1;}, array_column($arrEmailGeral, 'st_lido')));
		$this->view->cNaoLidos = array_sum(array_map(function($b) {return !empty($b) ? 0 : 1;}, array_column($arrEmailGeral, 'st_lido')));
		$this->view->cImportante = array_sum(array_map(function($b) {return empty($b) ? 0 : 1;}, array_column($arrEmailGeral, 'st_importante')));
		$this->view->cEncaminhados = array_sum(array_map(function($b) {return empty($b) ? 0 : 1;}, array_column($arrEmailGeral, 'st_encaminhado')));
		$this->view->cEnviados = array_sum(array_map(function($b) {return empty($b) ? 0 : 1;}, array_column($arrEmailGeral, 'st_enviado')));
		$this->view->cDeletados = array_sum(array_map(function($b) {return empty($b) ? 0 : 1;}, array_column($arrEmailGeral, 'st_deletado')));
		$this->view->cTotal = count($arrEmailGeral);
		$this->view->cFiltro = count($arrEmail);
		$this->view->arrUsuarios = $arrUsuarios;

		$page = $this->getRequest()->getParam('nu_page') ? $this->getRequest()->getParam('nu_page'):  $this->_getParam('page', 1);
		$pcount = $this->_getParam('count_per_page', 20);

		$paginator = Zend_Paginator::factory($arrEmail);
		$paginator->setCurrentPageNumber($page)
			->setItemCountPerPage($pcount);

		$this->view->paginator = $paginator;
		$this->view->pcount = $pcount;
	}
	    
	    
    public function entradaAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	$arrParam = array( 'co_rastreamento' => $this->getRequest()->getParam( 'co_rastreamento' )  );
    	$arrRastreamento = Model_Rastreamento::getInstance()->getConsulta( $arrParam );
    	$this->_response->appendBody(Zend_Json::encode($arrRastreamento));
    }


    public function enviadosAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	$arrParam = array( 'co_rastreamento' => $this->getRequest()->getParam( 'co_rastreamento' )  );
    	$arrRastreamento = Model_Rastreamento::getInstance()->getConsulta( $arrParam );
    	$this->_response->appendBody(Zend_Json::encode($arrRastreamento));
    }

    public function importantesAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	$arrParam = array( 'co_rastreamento' => $this->getRequest()->getParam( 'co_rastreamento' )  );
    	$arrRastreamento = Model_Rastreamento::getInstance()->getConsulta( $arrParam );
    	$this->_response->appendBody(Zend_Json::encode($arrRastreamento));
    }

    public function deletarAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
    
    	try{

			Model_Rastreamento::getInstance()->update(array('st_registro_ativo' => ST_REGISTRO_INATIVO), 'co_rastreamento = '.$this->getRequest()->getParam('co_rastreamento'));

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

    public function ativarAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

    	try{

			Model_Rastreamento::getInstance()->update(array('st_registro_ativo' => ST_REGISTRO_ATIVO), 'co_rastreamento = '.$this->getRequest()->getParam('co_rastreamento'));

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

	public function rastrearObjetoAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$objeto = $this->getRequest()->getParam('objeto');
		$params = array(
			'retorno' => utf8_encode($this->retornaRastreio($objeto))
		);
		$this->_response->appendBody(Zend_Json::encode($params));

	}

	private function retornaRastreio( $codigo ){
		$url='http://websro.correios.com.br/sro_bin/txect01$.Inexistente?P_LINGUA=001&P_TIPO=002&P_COD_LIS=' . $codigo;
		$retorno = file_get_contents( $url );
		preg_match('#<table[^>]+>(.+?)</table>#ims',$retorno, $tabela);
		return ( count( $tabela ) > 0 ) ? $tabela[0] : "objeto não encontrado" ;
	}
}

