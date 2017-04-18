<?php

class Financeiro_RastreamentoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

	public function indexAction()
    {
		set_time_limit(0);
		$arrParam = array();
		if( $this->getRequest()->isPost() ) {

			$arrParam = array(
				"no_rastreamento"	=> $this->getRequest()->getParam('no_rastreamento'),
				"nu_rastreamento"	=> $this->getRequest()->getParam('nu_rastreamento'),
				"nu_cep" 			=> removeCaracter($this->getRequest()->getParam('nu_cep')),
				"dt_inicio"			=> retornaYmd( $this->getRequest()->getParam('dt_inicio') ),
				"dt_final"			=> retornaYmd( $this->getRequest()->getParam('dt_final') )
			);

			$this->view->no_rastreamento	= $this->getRequest()->getParam('no_rastreamento');
			$this->view->nu_rastreamento 	= $this->getRequest()->getParam('nu_rastreamento');
			$this->view->dt_inicio			= $this->getRequest()->getParam('dt_inicio');
			$this->view->dt_final			= $this->getRequest()->getParam('dt_final');
			$this->view->nu_cep				= $this->getRequest()->getParam('nu_cep');
		}

		if($this->getRequest()->getParam( 'inativos' )){
			$arrParam = array('st_registro_ativo' => ST_REGISTRO_INATIVO);
			$this->view->inativos = true;
		}
		$arrRastreamento = Model_Rastreamento::getInstance()->getConsultaAll($arrParam);

		$page = $this->getRequest()->getParam('nu_page') ? $this->getRequest()->getParam('nu_page'):  $this->_getParam('page', 1);
		$pcount = $this->_getParam('count_per_page', 20);

		$paginator = Zend_Paginator::factory($arrRastreamento);
		$paginator->setCurrentPageNumber($page)
			->setItemCountPerPage($pcount);

		$this->view->paginator = $paginator;
		$this->view->pcount = $pcount;
	}
	    
	    
    public function editarAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	$arrParam = array( 'co_rastreamento' => $this->getRequest()->getParam( 'co_rastreamento' )  );
    	$arrRastreamento = Model_Rastreamento::getInstance()->getConsulta( $arrParam );
    	$this->_response->appendBody(Zend_Json::encode($arrRastreamento));
    }
    
    public function salvarAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

    	try{
    		$user = Zend_Auth::getInstance()->getIdentity();

    		$arrParam = array(
    				'co_pessoa_juridica' 			=> $this->getRequest()->getParam('co_pessoa'),
    				'nu_rastreamento' 				=> $this->getRequest()->getParam('nu_rastreamento'),
    				'no_rastreamento' 				=> $this->getRequest()->getParam('no_rastreamento'),
    				'nu_cep' 						=> removeCaracter($this->getRequest()->getParam('nu_cep')),
    				'ds_identificacao_conteudo'		=> $this->getRequest()->getParam('nu_rastreamento'),
    				'dt_cadastro' 					=> Zend_Date::now()->toString("Y-M-d hh:mm:ss"),
    				'nu_mes' 						=> Zend_Date::now()->toString("M"),
    				'nu_ano' 						=> Zend_Date::now()->toString("Y"),
    				'st_registro_ativo'				=> ST_REGISTRO_ATIVO
    		);

			if( !empty($this->getRequest()->getParam('co_pessoa'))){
				$arrPessoa = Administracao_Model_PessoaJuridica::getInstance()->getConsulta(array('co_pessoa_juridica' => $this->getRequest()->getParam('co_pessoa')));
				$arrParam['no_rastreamento'] = empty($arrPessoa['no_razao_social']) ? $arrPessoa['no_fantasia'] : $arrPessoa['no_razao_social'];
			}

    		if( $this->getRequest()->getParam('co_rastreamento') ){
    			Model_Rastreamento::getInstance()->update($arrParam, 'co_rastreamento = '.$this->getRequest()->getParam('co_rastreamento'));
    		}else{
				Model_Rastreamento::getInstance()->insert( $arrParam );
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

