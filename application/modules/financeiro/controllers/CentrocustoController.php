<?php

class Financeiro_CentrocustoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

	public function indexAction()
    {
		$arrCentroCusto = Model_CentroCusto::getInstance()->getConsultaAll(array());

		$this->view->arrCentroCusto = $arrCentroCusto;
	}
	    
	    
    public function editarAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	$arrParam = array( 'co_centro_custo' => $this->getRequest()->getParam( 'co_centro_custo' )  );
    	$arrCentroCusto = Model_CentroCusto::getInstance()->getConsulta( $arrParam );
    	$this->_response->appendBody(Zend_Json::encode($arrCentroCusto));
    }
    
    public function salvarAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

    	try{
    		$arrParam = array(
    				'ds_centro_custo' 		=> $this->getRequest()->getParam('ds_centro_custo'),
    				'st_custo_variavel'		=> ($this->getRequest()->getParam('st_custo_variavel') == 'N') ? null : $this->getRequest()->getParam('st_custo_variavel')
    		);

    		if( $this->getRequest()->getParam('co_centro_custo') ){
    			Model_CentroCusto::getInstance()->update($arrParam, 'co_centro_custo = '.$this->getRequest()->getParam('co_centro_custo'));
    		}else{
				Model_CentroCusto::getInstance()->insert( $arrParam );
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
}

