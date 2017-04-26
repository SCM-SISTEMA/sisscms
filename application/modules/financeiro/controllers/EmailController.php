<?php

class Financeiro_EmailController extends Zend_Controller_Action
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

			if($this->getRequest()->getParam('st_listar')){

				$arrParam = array(
					"co_pessoa_juridica"	=> $this->getRequest()->getParam('co_pessoa'),
					"dt_inicio_vencimento"	=> retornaYmd( $this->getRequest()->getParam('dt_inicio_vencimento') ),
					"dt_final_vencimento"	=> retornaYmd( $this->getRequest()->getParam('dt_final_vencimento') ),
					"dt_inicio_pagamento"	=> retornaYmd( $this->getRequest()->getParam('dt_inicio_pagamento') ),
					"dt_final_pagamento"	=> retornaYmd( $this->getRequest()->getParam('dt_final_pagamento') ),
					"st_consulta"			=> $this->getRequest()->getParam('st_consulta')
				);

				$arrEmailPj = Model_Boleto::getInstance()->getConsultaEmail($arrParam);
				$this->view->arrEmailPj = $arrEmailPj;
			}
		}

	}

	public function enviarEmailAction()
	{
		$mail = new PHPMailer();

		try {
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPDebug = 1;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "tls";
			$mail->Port = 587;
			$mail->Username = 'scmfinanceiro@gmail.com';
			//$mail->Password = '123scm!@#';
			$mail->Password = 'boanoite';
			//$mail->Password = 'boanoite';
			$mail->FromName = 'SCM Financeiro';
			$mail->addAddress( 'scmfinanceiro@gmail.com' );
			$mail->addCC( $this->getRequest()->getParam('ds_emailcc') );
			$mail->addBCC( $this->getRequest()->getParam('ds_emailcco') );
			$mail->Subject = $this->getRequest()->getParam('ds_assunto');
			$body = $this->getRequest()->getParam('ds_mensagem');
			$mail->MsgHTML($body);
			if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;die;
            } else {
                echo "Message sent!";
            }
		} catch (phpmailerException $e) {
			debug($e->errorMessage(), 1 ); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
			debug( $e->getMessage(), 1); //Boring error messages from anything else!
		}

		$namespace = new Zend_Session_Namespace('message');
		$namespace->message = "Mensagem enviada com sucesso";
		$namespace->msg = "success";
		$this->_helper->redirector('index', 'email', 'financeiro');
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

	public function retornaDadosAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$dados = $this->getRequest()->getParam('dados');

		$arrParam = array(
			"dt_inicio_vencimento"	=> retornaYmd( $this->getRequest()->getParam('dt_inicio_vencimento') ),
			"dt_final_vencimento"	=> retornaYmd( $this->getRequest()->getParam('dt_final_vencimento') ),
			"st_consulta"			=> $this->getRequest()->getParam('st_consulta')
		);
		$arrBoletos = Model_Boleto::getInstance()->getConsultaEmail($arrParam);
		$params = array(
			'retorno' => $arrBoletos
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

