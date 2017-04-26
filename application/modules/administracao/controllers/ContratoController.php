<?php

class Administracao_ContratoController extends Zend_Controller_Action
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
    	$this->view->arrContrato = $arrContrato;
    }
	    
    public function salvarAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		Zend_Loader::loadClass('Zend_Json');
    	
    	try{

    		$user = Zend_Auth::getInstance()->getIdentity();
    		$valor = $this->getRequest()->getParam('ds_valor');

	    	$arrParam = array(
	    		'co_pessoa' 			=> $this->getRequest()->getParam('co_pessoa'),
	    		'co_servico'			=> $this->getRequest()->getParam('co_servico'),
	    		'co_plano' 				=> $this->getRequest()->getParam('co_plano'),
	    		'co_forma_pagamento' 	=> $this->getRequest()->getParam('co_forma_pagamento'),
	    		'ds_contrato' 			=> $this->getRequest()->getParam('ds_contrato'),
	    		'tp_pessoa' 			=> $this->getRequest()->getParam('tp_pessoa'),
	    		'ds_valor' 				=> $valor,
	    		'st_ativo' 				=> 'S',
	    		'st_status'				=> CO_STATUS_ABERTO,
	    		'dt_cadastro' 			=> Zend_Date::now()->toString("Y-M-d hh:mm:ss"),
	    		'co_usuario'			=> $user['user']->co_usuario
	    	);

			if( $arrParam['tp_pessoa'] == 'PJ'){
				$pessoa_juridica = Administracao_Model_PessoaJuridica::getInstance()->getConsulta(array('co_pessoa_juridica' => $arrParam['co_pessoa']));
				$arrParam['co_pessoa_juridica'] = $pessoa_juridica['co_pessoa_juridica'];
				$arrParam['co_pessoa_fisica'] = null;
			}else{
				$pessoa_fisica = Administracao_Model_PessoaFisica::getInstance()->getConsulta(array('co_pessoa_fisica' => $arrParam['co_pessoa']));
				$arrParam['co_pessoa_fisica'] = $pessoa_fisica['co_pessoa_fisica'];
				$arrParam['co_pessoa_juridica'] = null;
			}

			unset($arrParam['co_pessoa']);
			unset($arrParam['tp_pessoa']);

			try{
				$mdContrato = new Administracao_Model_Contrato();
				$mdContrato->getDefaultAdapter()->beginTransaction();
				$arrformaPagamento = Administracao_Model_FormaPagamento::getInstance()->getConsulta( array( 'co_forma_pagamento' => $this->getRequest()->getParam('co_forma_pagamento') ) );
				$co_contrato = $mdContrato->getInstance()->insert( $arrParam );
				$valorParcela = $valor / $arrformaPagamento['nu_parcelas'];
				$i = 1;
				$dataVencimentoInicio = Zend_Date::now()->addDay($arrformaPagamento['nu_qtd_dias'])->toString("Y-M-d hh:mm:ss");

				while( $i<=$arrformaPagamento['nu_parcelas'] ){

					if($i == 1){
						$dataVencimento = $dataVencimentoInicio;
					}else{
						$qtdDias = $arrformaPagamento['nu_qtd_dias'] * $i;
						$dataVencimento = Zend_Date::now()->addDay($qtdDias)->toString("Y-M-d hh:mm:ss");
					}

					$arrParamBoleto = array(
						"data_vencimento" => $dataVencimento,
						"inicio_nosso_numero" => $i,
						"nosso_numero" => $co_contrato,
						"valor_boleto" => $valorParcela
					);

					$coBoleto = $this->gerarBoleto($arrParamBoleto);

					$arrParam = array(
						'co_contrato' 	=> $co_contrato,
						'ds_valor' 	  	=> $valorParcela,
						'co_boleto' 	=> $coBoleto,
						'dt_vencimento' => $dataVencimento,
						'st_status' 	=> 1,
						'st_ativo' 		=> 'S'
					);
					try{
						Administracao_Model_ContratoParcela::getInstance()->insert( $arrParam );
					}catch(Exception $e){
						debug($e->getMessage(), 1);
					}

					$i++;
				}


				$mdContrato->getDefaultAdapter()->commit();
			}catch(Exception $e){
				debug($e->getMessage(), 1);
			}

			$params = array(
				'msg' => 'success',
				'message' => 'Operação realizada com sucesso'
			);
			$this->_response->appendBody(Zend_Json::encode($params));
	    	
    	}catch( Exception $e ){
			$params = array(
				'msg' => 'error',
				'message' => 'Erro Ao tentar gravar o chamado: '.$e
			);
			$this->_response->appendBody(Zend_Json::encode($params));
    	}
    	
    }

    public function gerarAction(){

		Zend_Loader::loadClass('Zend_Json');

    	try{

    		$user = Zend_Auth::getInstance()->getIdentity();

    		$valor = str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $this->getRequest()->getParam('ds_valor') ) ) );

	    	$arrParam = array(
	    		'co_pessoa' 			=> $this->getRequest()->getParam('co_pessoa'),
	    		'co_servico'			=> $this->getRequest()->getParam('co_servico'),
	    		'co_plano' 				=> $this->getRequest()->getParam('co_plano'),
	    		'co_forma_pagamento' 	=> $this->getRequest()->getParam('co_forma_pagamento'),
	    		'tp_pessoa' 			=> $this->getRequest()->getParam('tp_pessoa'),
	    		'dt_final'				=> retornaYmd( $this->getRequest()->getParam('dt_final') ),
	    		'ds_valor' 				=> $valor,
	    		'st_ativo' 				=> 'S',
	    		'st_status'				=> CO_STATUS_ABERTO,
	    		'dt_cadastro' 			=> Zend_Date::now()->toString("Y-M-d hh:mm:ss"),
	    		'co_usuario'			=> $user['user']->co_usuario
	    	);

			if( $arrParam['tp_pessoa'] == 'PJ'){
				$arrParam['modeloContrato'] = Administracao_Model_ModeloContratoServico::getInstance()->getConsulta(array('co_servico' => $this->getRequest()->getParam('co_servico')));
				$arrParam['pessoa_juridica'] = Administracao_Model_PessoaJuridica::getInstance()->getConsulta(array('co_pessoa_juridica' => $arrParam['co_pessoa']));

				$enderecoCompleto = $arrParam['pessoa_juridica']['ds_endereco'].' ';
				$enderecoCompleto .= $arrParam['pessoa_juridica']['nu_endereco'].' ';
				$enderecoCompleto .= $arrParam['pessoa_juridica']['ds_complemento'].' - ';
				$enderecoCompleto .= $arrParam['pessoa_juridica']['no_bairro'].' - ';
				$enderecoCompleto .= $arrParam['pessoa_juridica']['no_cidade'].' - ';
				$enderecoCompleto .= $arrParam['pessoa_juridica']['sg_estado'];
				$arrParam['co_pessoa_fisica'] = null;
				$arrParam['co_pessoa_juridica'] = $arrParam['co_pessoa'];
			}else{
				$pessoa_fisica = Administracao_Model_PessoaFisica::getInstance()->getConsulta(array('co_pessoa_fisica' => $arrParam['co_pessoa']));
				$arrParam['pessoa_fisica'] = $pessoa_fisica;
				$arrParam['co_pessoa_fisica'] = $pessoa_fisica['co_pessoa_fisica'];
				$arrParam['co_pessoa_juridica'] = null;
			}

			unset($arrParam['co_pessoa']);
			unset($arrParam['tp_pessoa']);

			try{
				$arrformaPagamento = Administracao_Model_FormaPagamento::getInstance()->getConsulta( array( 'co_forma_pagamento' => $this->getRequest()->getParam('co_forma_pagamento') ) );
				$arrParam['formaPagamento'] = $arrformaPagamento;
				$arrParam['dataAtual'] = Zend_Date::now()->toString("Y-M-d hh:mm:ss");
				$valorParcela = $valor / $arrformaPagamento['nu_parcelas'];
				$arrParam['valorParcela'] = $valorParcela;

				$i = 1;
				$dataVencimentoInicio = Zend_Date::now()->addDay($arrformaPagamento['nu_qtd_dias']);
				$arrParam['dataVencimentoInicio'] = $dataVencimentoInicio;


				while( $i<=$arrformaPagamento['nu_parcelas'] ){

					if($i == 1){
						$dataVencimento = $dataVencimentoInicio;
					}else{
						$qtdDias = $arrformaPagamento['nu_qtd_dias'] * $i;
						$dataVencimento = Zend_Date::now()->addDay($qtdDias);
					}

					$arrParamBoleto = array(
						"data_vencimento" 		=> $dataVencimento,
						"inicio_nosso_numero" 	=> $i,
						"nosso_numero" 			=> 'XXX',
						"valor_boleto" 			=> $valorParcela
					);

					$coBoleto = $this->gerarBoleto($arrParamBoleto);

					$arrParamDadosBoleto[$i] = array(
						'nu_parcela'			=> $i,
						'valor_parcela'			=> moeda( $valorParcela ),
						'co_boleto' 			=> $coBoleto,
						'dt_vencimento' 		=> $dataVencimento->toString("Y-M-d hh:mm:ss"),
						'valor_parcela_extenso' => limitarTexto(valorPorExtenso( moeda( $valorParcela ) ), 50),
						'data_por_extenso'		=> $dataVencimento->get( Zend_Date::DAY ) . ' de ' . $dataVencimento->get( Zend_Date::MONTH_NAME ) . ' de ' . $dataVencimento->get( Zend_Date::YEAR ),
						'st_status' 			=> 1,
						'st_ativo' 				=> 'S'
					);
					$i++;
				}

				$arrParam['tabela_parcelas'] = $this->geraTabelaParcelas($arrParamDadosBoleto);

				$replace = array(
					'{empresa}' 	=> $arrParam['pessoa_juridica']['no_razao_social'],
					'{cnpj}' 		=> formataCNPJ($arrParam['pessoa_juridica']['nu_cnpj']),
					'{endereco}' 	=> $enderecoCompleto,
					'{tabela}' 		=> $arrParam['tabela_parcelas'],
					'{dataAtual}' 	=> Zend_Date::now()->get( Zend_Date::DAY ) . ' de ' . Zend_Date::now()->get( Zend_Date::MONTH_NAME ) . ' de ' . Zend_Date::now()->get( Zend_Date::YEAR )
				);
				$search = array_keys($replace);
				$replace = array_values($replace);
				$arrParam['modeloContrato']['ds_modelo'] = str_replace ($search, $replace, $arrParam['modeloContrato']['ds_modelo']);

				$arrParam['dadosBoleto'] = $arrParamDadosBoleto;
				$this->view->dados = $arrParam;


			}catch(Exception $e){
				debug($e->getMessage(), 1);
			}


    	}catch( Exception $e ){
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = 'Erro Ao tentar gravar o chamado: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'contrato', 'administracao', $params);
    	}

    }

    
    public function salvarFormaPagamentoAction(){
    	
    	try{
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

	    	$arrParam = array(
	    		'ds_forma_pagamento' => $this->getRequest()->getParam('ds_forma_pagamento'),
	    		'nu_parcelas' 		 => $this->getRequest()->getParam('nu_parcelas'),
	    		'nu_qtd_dias' 		 => $this->getRequest()->getParam('nu_qtd_dias')
	    	);
	    	
	    	Administracao_Model_FormaPagamento::getInstance()->insert( $arrParam );

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
    
    public function gerarContratoAction(){
		$co_contrato = base64_decode( $this->getRequest()->getParam(base64_encode('co_contrato')) );
    	$arrContrato = Administracao_Model_Contrato::getInstance()->getConsulta( array('co_contrato' => $co_contrato ) );
    	$this->view->dados	= $arrContrato;
    }

	public function gerarBoleto($arrParam){
		$codigobanco = "104";
		$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
		$nummoeda = "9";
		$fator_vencimento = fator_vencimento($arrParam["data_vencimento"]);

		//valor tem 10 digitos, sem virgula
		$valor = formata_numero($arrParam["valor_boleto"],10,0,"valor");
		//agencia � 4 digitos
			$agencia = formata_numero(NU_AGENCIA,4,0);
		//conta � 5 digitos
		$conta = formata_numero(NU_CONTA,5,0);
		//dv da conta
		$conta_dv = formata_numero(NU_CONTA_DV,1,0);
		//carteira � 2 caracteres
		$carteira = CARTEIRA;

		//conta cedente (sem dv) com 11 digitos   (Operacao de 3 digitos + Cedente de 8 digitos)
		$conta_cedente = formata_numero(CONTA_CEDENTE,11,0);
		//dv da conta cedente
		$conta_cedente_dv = formata_numero(CONTA_CEDENTE_DV,1,0);
		//nosso n�mero (sem dv) � 10 digitos
		$nnum = $arrParam["inicio_nosso_numero"] . formata_numero($arrParam["nosso_numero"],8,0);
		//nosso n�mero completo (com dv) com 11 digitos
		$nossonumero = $nnum .'-'. digitoVerificador_nossonumero($nnum);

		// 43 numeros para o calculo do digito verificador do codigo de barras
		$dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$nnum$agencia$conta_cedente", 9, 0);
		// Numero para o codigo de barras com 44 digitos
		return "$codigobanco$nummoeda$dv$fator_vencimento$valor$nnum$agencia$conta_cedente";
	}

	private function geraTabelaParcelas($arrParcelas){
		$html = '<table border="0" cellspacing="0" cellpadding="0" width="652">';
		$html .= '<tr>';
		$html .= '<td width="38" valign="top"><p>Nº</p></td>';
		$html .= '<td width="85" valign="top"><p>Valor</p></td>';
		$html .= '<td width="321" valign="top"><p>Descrição</p></td>';
		$html .= '<td width="208" valign="top"><p>Data    de Vencimento</p></td>';
	  	$html .= '</tr>';
		foreach($arrParcelas as $parcela) {
			$html .= '<tr>';
			$html .= '<td width="38" valign="top"><p>' . $parcela['nu_parcela'] . '</p></td>';
			$html .= '<td width="85" valign="top"><p>' . $parcela['valor_parcela'] . '</p></td>';
			$html .= '<td width="321" valign="top"><p>' . $parcela['valor_parcela_extenso'] . '</p></td>';
			$html .= '<td width="208" valign="top"><p>' . $parcela['data_por_extenso'] . '</p></td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		return $html;
	}


}

