<?php

class Financeiro_ContratoController extends Zend_Controller_Action
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
    	
    	$arrContrato = Administracao_Model_Contrato::getInstance()->getConsultaAll(array());
    	
    	$page = $this->_getParam('page', 1);
    	$pcount = $this->_getParam('count_per_page', 20);
    	
    	$paginator = Zend_Paginator::factory($arrContrato);
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($pcount);
    	
    	$this->view->paginator = $paginator;
    	$this->view->pcount = $pcount;
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
    
    public function salvarAction(){
    	
    	try{
    		$user = Zend_Auth::getInstance()->getIdentity();
    		
	    	$arrParam = array(
	    		'co_pessoa' 			=> $this->getRequest()->getParam('co_pessoa'),
	    		'co_servico' 			=> $this->getRequest()->getParam('co_servico'),
	    		'co_forma_pagamento' 	=> $this->getRequest()->getParam('co_forma_pagamento'),
	    		'tp_pessoa' 			=> $this->getRequest()->getParam('tp_pessoa'),
	    		'dt_vencimento'			=> retornaYmd( $this->getRequest()->getParam('dt_vencimento') ),
	    		'dt_final'				=> retornaYmd( $this->getRequest()->getParam('dt_final') ),
	    		'ds_valor' 				=> str_replace('.', ',', str_replace(',', '', str_replace('R$ ', '', $this->getRequest()->getParam('ds_valor') ) ) ),
	    		'st_ativo' 				=> 'S',
	    		'st_status'				=> CO_STATUS_ABERTO,
	    		'dt_cadastro' 			=> Zend_Date::now()->toString("Y-M-d hh:mm:ss"),
	    		'co_usuario'			=> $user['user']->id
	    	);
	    	
	    	Administracao_Model_Contrato::getInstance()->insert( $arrParam );
	    	
	    	$namespace = new Zend_Session_Namespace('message');
	    	$namespace->message = "Operação realizada com sucesso";
	    	$params = array('msg' => 'success' );
	    	$this->_helper->redirector('index', 'contrato', 'administracao', $params);
	    	
    	}catch( Exception $e ){
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = 'Erro Ao tentar gravar o chamado: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'contrato', 'administracao', $params);
    	}
    	
    }
    
    
    public function salvarFormaPagamentoAction(){
    	
    	try{
    		$user = Zend_Auth::getInstance()->getIdentity();
    		
	    	$arrParam = array(
	    		'ds_forma_pagamento' => $this->getRequest()->getParam('ds_forma_pagamento'),
	    		'nu_parcelas' 		 => $this->getRequest()->getParam('nu_parcelas'),
	    		'nu_qtd_dias' 		 => $this->getRequest()->getParam('nu_qtd_dias')
	    	);
	    	
	    	Administracao_Model_FormaPagamento::getInstance()->insert( $arrParam );
	    	
	    	$namespace = new Zend_Session_Namespace('message');
	    	$namespace->message = "Operação realizada com sucesso";
	    	$params = array('msg' => 'success' );
	    	$this->_helper->redirector('index', 'contrato', 'administracao', $params);
	    	
    	}catch( Exception $e ){
    		$_SESSION['message'] = 'Erro Ao tentar gravar o chamado: '.$e;
    		$params = array('msg' => 'erro' );
	    	$this->_helper->redirector('index', 'cnae', 'administracao', $params);
    	}
    	
    }
    
    public function dadosContratoAction(){
    	$co_contrato = base64_decode( $this->getRequest()->getParam('co_contrato') );
    	$arrDados = array();
    	 
    	$arrContrato = Administracao_Model_Contrato::getInstance()->getConsulta( array('co_contrato' => $co_contrato ) );
    	$dataVencimento = new Zend_Date( $arrContrato['dt_vencimento'] );
    	$dataCadastro	= new Zend_Date( $arrContrato['dt_cadastro'] );
    	$dataFinal		= new Zend_Date( $arrContrato['dt_final'] );
    	
    	$arrDados['ds_servico'] 			= $arrContrato['ds_servico'];
    	$arrDados['nu_telefone'] 			= $arrContrato['nu_telefone_1'];
    	$arrDados['ds_forma_pagamento']		= $arrContrato['ds_forma_pagamento'];
    	$arrDados['dt_contrato']	 		= $dataCadastro->get( Zend_Date::DAY ) . ' de ' . $dataCadastro->get( Zend_Date::MONTH_NAME ) . ' de ' . $dataCadastro->get( Zend_Date::YEAR );
    	$arrDados['dt_final']	 			= $dataFinal->get( Zend_Date::DAY ) . ' de ' . $dataFinal->get( Zend_Date::MONTH_NAME ) . ' de ' . $dataFinal->get( Zend_Date::YEAR );
    	$arrDados['valor_contrato'] 		= moeda( $arrContrato['ds_valor'] );
    	$arrDados['valor_contrato_extenso'] = escreverValorMoeda( moeda( $arrContrato['ds_valor'] ) );
    	
    	if( $arrContrato['tp_pessoa'] == 'PJ' ){
	    	$arrCliente = Administracao_Model_PessoaJuridica::getInstance()->getConsulta( array( 'co_pessoa_juridica' => $arrContrato['co_pessoa'] ) );
	    	$arrDados['no_pessoa'] = $arrCliente['no_razao_social'];
	    	$arrDados['tp_documento'] = 'CNPJ';
	    	$arrDados['nu_documento'] = formataCNPJ( $arrCliente['nu_cnpj'] );
    	}elseif( $arrContrato['tp_pessoa'] == 'PF' ){
	    	$arrCliente = Administracao_Model_PessoaFisica::getInstance()->getConsulta( array( 'co_pessoa_fisica' => $arrContrato['co_pessoa'] ));
	    	$arrDados['no_pessoa'] = strtoupper( $arrCliente['no_pessoa_fisica'] );
	    	$arrDados['tp_documento'] = 'CPF';
	    	$arrDados['nu_documento'] = formataCPF( $arrCliente['nu_cpf'] );
    	}
    	
    	$arrDados['ds_endereco'] = $arrCliente['ds_endereco'] . ' ' . $arrCliente['nu_endereco'];
    	$arrDados['nu_cep'] = formataCEP( $arrCliente['nu_cep'] );
    	
    	$arrformaPagamento = Administracao_Model_FormaPagamento::getInstance()->getConsulta( array( 'co_forma_pagamento' => $arrContrato['co_forma_pagamento'] ) );
    	
    	$arrServico = Administracao_Model_Servicos::getInstance()->getConsulta( array( 'co_servico' => $arrContrato['co_servico'] ) );
    	$arrTipoServico = Administracao_Model_TipoContrato::getInstance()->getConsulta( array( 'co_tipo_contrato' => $arrServico['co_tipo_contrato'] ));
    	
    	$arrContratoParcela = Administracao_Model_ContratoParcela::getInstance()->getConsultaAll( array( 'co_contrato' => $arrContrato['co_contrato'] ) );
    	$arrParcelas = array();
    	foreach( $arrContratoParcela as $key => $parcelas ){
    		$dataVencimento = new Zend_Date( $parcelas['dt_vencimento'] );
    		$arrParcelas[$key]['co_contrato_parcela'] 	= $parcelas['co_contrato_parcela'];
    		$arrParcelas[$key]['valor_parcela'] 		= moeda( $parcelas['ds_valor'] );
    		$arrParcelas[$key]['st_status'] 			= $parcelas['st_status'];
    		$arrParcelas[$key]['valor_parcela_extenso'] = escreverValorMoeda( moeda( $parcelas['ds_valor'] ) );
    		$arrParcelas[$key]['data_por_extenso'] 		= $dataVencimento->get( Zend_Date::DAY ) . ' de ' . $dataVencimento->get( Zend_Date::MONTH_NAME ) . ' de ' . $dataVencimento->get( Zend_Date::YEAR );
    	}
    	
    	
    	$arrDados['parcelas'] = $arrParcelas;
    	$arrDados['tipoContrato'] = $arrServico['co_tipo_contrato'];
    	
    	$this->view->dados	= $arrDados;
    	
    }
    
    public function gerarBoletoAction(){
    	$this->_helper->layout->disableLayout();
    	
    	$co_parcela = (int) base64_decode( $this->getRequest()->getParam('co_contrato_parcela') );
    	
    	$arrContratoParcela = Administracao_Model_ContratoParcela::getInstance()->getConsulta( array( 'co_contrato_parcela' => $co_parcela ) );
    	
    	
    	$arrContrato = Administracao_Model_Contrato::getInstance()->getConsulta( array( 'co_contrato' => $arrContratoParcela['co_contrato'] ) );
    	
    	$this->view->dados = $arrContrato;
    	$this->view->parcela = $arrContratoParcela;
    	
    	
    }
	    
	    

}

