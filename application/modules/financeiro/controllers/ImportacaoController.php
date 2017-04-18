<?php

class Financeiro_ImportacaoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

	public function indexAction()
    {
		if( $this->getRequest()->isPost() ) {

			$arquivoAdapter = new Zend_File_Transfer_Adapter_Http();
			$arquivoAdapter->addValidator('Count', false, array('min' => 1, 'max' => 1))
				->addValidator('Extension', false, 'ret');

			if (!$arquivoAdapter->isValid()) {
				$_SESSION['message'] = 'Extensão inválida, favor selecionar um arquivo válido!';
				$params = array('msg' => 'error');
				$this->_helper->redirector('financeiro', 'importacao', null, $params);
			}else{
				$no_arquivo = $_FILES['no_arquivo']['name'];
				$tmp = $_FILES['no_arquivo']['tmp_name'][0];
				$lendo = @fopen($tmp, "r");
				if (!$lendo) {
					$_SESSION['message'] = 'Erro ao abrir a URL!';
					$params = array('msg' => 'error');
					$this->_helper->redirector('financeiro', 'importacao', null, $params);
				}
				$i = 0;
				while (!feof($lendo)) {
					$i++;
					$linha = fgets($lendo, 9999);
					$t_u_segmento = substr($linha, 13, 1);//Segmento T ou U
					$t_tipo_reg = substr($linha, 7, 1);//Tipo de Registro
					if ($t_u_segmento == 'T') {
						$t_cod_banco = substr($linha, 0, 3);//Código do banco na compensação
						$t_lote = substr($linha, 3, 4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
						$t_n_sequencial = substr($linha, 8, 5);//Nº Sequencial do registro no lote
						$t_cod_seg = substr($linha, 15, 2);//Cód. Segmento do registro detalhe
						$t_cod_conv_banco = substr($linha, 23, 6);//Código do convênio no banco - Código fornecido pela CAIXA, através da agência de relacionamento do cliente. Deve ser preenchido com o código do Cedente (6 posições).
						$t_n_banco_sac = substr($linha, 32, 3);//Numero do banco de sacados
						$t_mod_nosso_n = substr($linha, 39, 2);//Modalidade nosso número
						$t_id_titulo_banco = substr($linha, 41, 15);//Identificação do titulo no banco - Número adotado pelo Banco Cedente para identificar o Título.
						$t_cod_carteira = substr($linha, 57, 1);//Código da carteira - Código adotado pela FEBRABAN, para identificar a característica dos títulos. 1=Cobrança Simples, 3=Cobrança Caucionada, 4=Cobrança Descontada
						$t_num_doc_cob = substr($linha, 58, 11);//Número do documento de cobrança - Número utilizado e controlado pelo Cliente, para identificar o título de cobrança.
						$t_dt_vencimento = substr($linha, 73, 8);//Data de vencimento do titulo - Data de vencimento do título de cobrança.
						$t_v_nominal = substr($linha, 81, 13);//Valor nominal do titulo - Valor original do Título. Quando o valor for expresso em moeda corrente, utilizar 2 casas decimais.
						$t_cod_banco2 = substr($linha, 96, 3);//Código do banco
						$t_cod_ag_receb = substr($linha, 99, 5);//Codigo da agencia cobr/receb - Código adotado pelo Banco responsável pela cobrança, para identificar o estabelecimento bancário responsável pela cobrança do título.
						$t_dv_ag_receb = substr($linha, 104, 1);//Dígito verificador da agencia cobr/receb
						$t_id_titulo_empresa = substr($linha, 105, 25);//identificação do título na empresa - Campo destinado para uso da Empresa Cedente para identificação do Título. Informar o Número do Documento - Seu Número.
						$t_cod_moeda = substr($linha, 130, 2);//Código da moeda
						$t_tip_inscricao = substr($linha, 132, 1);//Tipo de inscrição - Código que identifica o tipo de inscrição da Empresa ou Pessoa Física perante uma Instituição governamental: 0=Não informado, 1=CPF, 2=CGC / CNPJ, 9=Outros.
						$t_num_inscricao = substr($linha, 133, 15);//Número de inscrição - Número de inscrição da Empresa (CNPJ) ou Pessoa Física (CPF).
						$t_nome = substr($linha, 148, 40);//Nome - Nome que identifica a entidade, pessoa física ou jurídica, Cedente original do título de cobrança.
						$t_v_tarifa_custas = substr($linha, 198, 13);//Valor da tarifa/custas
						$t_id_rejeicoes = substr($linha, 213, 10);//Identificação para rejeições, tarifas, custas, liquidação e baixas
					}
					if ($t_u_segmento == 'U') {
						$t_id_titulo_banco;
						$u_cod_banco = substr($linha, 0, 3);//Código do banco na compensação
						$u_lote = substr($linha, 3, 4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
						$u_tipo_reg = substr($linha, 7, 1);//Tipo de Registro - Código adotado pela FEBRABAN para identificar o tipo de registro: 0=Header de Arquivo, 1=Header de Lote, 3=Detalhe, 5=Trailer de Lote, 9=Trailer de Arquivo.
						$u_n_sequencial = substr($linha, 8, 5);//Nº Sequencial do registro no lote
						$u_cod_seg = substr($linha, 15, 2);//Cód. Segmento do registro detalhe
						$u_juros_multa = substr($linha, 17, 15);//Jurus / Multa / Encargos - Valor dos acréscimos efetuados no título de cobrança, expresso em moeda corrente.
						$u_desconto = substr($linha, 32, 15);//Valor do desconto concedido - Valor dos descontos efetuados no título de cobrança, expresso em moeda corrente.
						$u_abatimento = substr($linha, 47, 15);//Valor do abat. concedido/cancel. - Valor dos abatimentos efetuados ou cancelados no título de cobrança, expresso em moeda corrente.
						$u_iof = substr($linha, 62, 15);//Valor do IOF recolhido - Valor do IOF - Imposto sobre Operações Financeiras - recolhido sobre o Título, expresso em moeda corrente.
						$u_v_pago = substr($linha, 77, 15);//Valor pago pelo sacado - Valor do pagamento efetuado pelo Sacado referente ao título de cobrança, expresso em moeda corrente.
						$u_v_liquido = substr($linha, 92, 15);//Valor liquido a ser creditado - Valor efetivo a ser creditado referente ao Título, expresso em moeda corrente.
						$u_v_despesas = substr($linha, 107, 15);//Valor de outras despesas - Valor de despesas referente a Custas Cartorárias, se houver.
						$u_v_creditos = substr($linha, 122, 15);//Valor de outros creditos - Valor efetivo de créditos referente ao título de cobrança, expresso em moeda corrente.
						$u_dt_ocorencia = substr(substr($linha, 137, 8), 4, 4) . '-' . substr(substr($linha, 137, 8), 2, 2) . '-' . substr(substr($linha, 137, 8), 0, 2);//Data da ocorrência - Data do evento que afeta o estado do título de cobrança.
						$u_dt_efetivacao = substr($linha, 145, 8);//Data da efetivação do credito - Data de efetivação do crédito referente ao pagamento do título de cobrança.
						$u_dt_debito = substr($linha, 157, 8);//Data do débito da tarifa
						$u_cod_sacado = substr($linha, 167, 15);//Código do sacado no banco
						$u_cod_banco_comp = substr($linha, 210, 3);//Cód. Banco Correspondente compens - Código fornecido pelo Banco Central para identificação na Câmara de Compensação, do Banco ao qual será repassada a Cobrança do Título.
						$u_nn_banco = substr($linha, 213, 20);//Nosso Nº banco correspondente - Código fornecido pelo Banco Correspondente para identificação do Título de Cobrança. Deixar branco (Somente para troca de arquivos entre Bancos).

						$u_juros_multa = substr($u_juros_multa, 0, 13) . '.' . substr($u_juros_multa, 13, 2);
						$u_desconto = substr($u_desconto, 0, 13) . '.' . substr($u_desconto, 13, 2);
						$u_abatimento = substr($u_abatimento, 0, 13) . '.' . substr($u_abatimento, 13, 2);
						$u_iof = substr($u_iof, 0, 13) . '.' . substr($u_iof, 13, 2);
						$u_v_pago = substr($u_v_pago, 0, 13) . '.' . substr($u_v_pago, 13, 2);
						$u_v_liquido = substr($u_v_liquido, 0, 13) . '.' . substr($u_v_liquido, 13, 2);
						$u_v_despesas = substr($u_v_despesas, 0, 13) . '.' . substr($u_v_despesas, 13, 2);
						$u_v_creditos = substr($u_v_creditos, 0, 13) . '.' . substr($u_v_creditos, 13, 2);

						$data_agora = date('Y-m-d');
						$hora_agora = date('H:i:s');
						$nosso_numero[] = $t_id_titulo_banco;
						$arrParam = array(
							'dt_ocorrencia' 		=> $u_dt_ocorencia,
							'ds_valor_pago' 	=> formatValShow(doubleval($u_v_pago), 2),
							'dt_processamento' 	=> retornaDataAtualYMD()
						);
						try{
							Model_Boleto::getInstance()->update($arrParam, 'nu_nosso_numero = '.$t_id_titulo_banco);
							$arrRetorno[] = Model_Boleto::getInstance()->getConsulta(array('nu_nosso_numero' => $t_id_titulo_banco));
						}catch(Exception $e){
							debug($e, 1);
						}

					}
				}

			}
			fclose($lendo);
			$this->view->retorno = $arrRetorno;
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
    
    public function salvarAction(){
    	
    	try{
    		$user = Zend_Auth::getInstance()->getIdentity();
    		$arrParam = array(
	    		'tp_movimentacao' 	=> $this->getRequest()->getParam('tp_movimentacao'),
	    		'co_banco' 			=> $this->getRequest()->getParam('co_banco'),
	    		'ds_documento' 		=> $this->getRequest()->getParam('ds_documento'),
	    		'dt_movimentacao' 	=> retornaYmd( $this->getRequest()->getParam('dt_movimentacao') ),
	    		'ds_movimentacao' 	=> $this->getRequest()->getParam('ds_movimentacao'),
	    		'ds_fornecedor' 	=> $this->getRequest()->getParam('ds_fornecedor'),
	    		'ds_valor' 			=> str_replace('.', ',', str_replace(',', '', str_replace('R$ ', '', $this->getRequest()->getParam('ds_valor') ) ) ),
	    		'ds_observacao' 	=> $this->getRequest()->getParam('ds_observacao'),
	    		'dt_cadastro' 		=> date('Y-m-d'),
	    		'co_usuario' 		=> $user['user']->co_usuario, 
	    		'st_ativo'	 		=> 'S', 
    		);
    		
    		if( $this->getRequest()->getParam('dt_pagamento') ){
    			$arrParam['dt_vencimento'] = retornaYmd( $this->getRequest()->getParam('dt_pagamento') );
    			$arrParam['st_quitacao'] = 'S';
    		}
    		
    		if( $this->getRequest()->getParam('dt_vencimento') )
    			$arrParam['dt_vencimento'] = retornaYmd( $this->getRequest()->getParam('dt_vencimento') );
	    	
    		$namespace = new Zend_Session_Namespace('message');

	    	try{
	    		Model_Movimentacao::getInstance()->insert( $arrParam );
	    		
	    		$namespace->message = "Operação realizada com sucesso";
	    		$namespace->success = true;
	    		$this->_helper->redirector('index', 'movimentacoes', 'financeiro');
	    	}catch( Exception $e ){
	    		$namespace->message = $e->getMessage();
	    		$namespace->error = true;
	    		$this->_helper->redirector('index', 'movimentacoes', 'financeiro');
	    	}
	    	
	    	
    	}catch( Exception $e ){
    		$namespace = new Zend_Session_Namespace('message');
    		$namespace->message = 'Erro Ao tentar gravar o chamado: '.$e;
    		$params = array('msg' => 'erro' );
    		$this->_helper->redirector('index', 'movimentacoes', 'financeiro', $params);
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
    
    public function despesasAction(){
    	$this->_helper->layout->disableLayout();
    	$message = new Zend_Session_Namespace('message');
    	
    	if( $this->getRequest()->getParam('msg') ){
    		$this->view->msg = $this->getRequest()->getParam('msg');
    	}
    	
    	$arrDespesas = Model_Movimentacao::getInstance()->getConsultaAll(array('tp_movimentacao' => 'D'));
    	$this->view->arrDespesas = $arrDespesas;
    }
    
    public function boletosAction(){
    	$this->_helper->layout->disableLayout();
    	$message = new Zend_Session_Namespace('message');
    	
    	if( $this->getRequest()->getParam('msg') ){
    		$this->view->msg = $this->getRequest()->getParam('msg');
    	}
    	$arrBoletos = Model_ContratoParcela::getInstance()->getConsultaAll(array());
    	$this->view->arrBoletos = $arrBoletos;
    }
    
    public function graficosAction(){
    	$this->_helper->layout->disableLayout();
    	$message = new Zend_Session_Namespace('message');
    	
    	if( $this->getRequest()->getParam('msg') ){
    		$this->view->msg = $this->getRequest()->getParam('msg');
    	}

		$arrReceitas = Model_Movimentacao::getInstance()->getConsultaAll(array('tp_movimentacao' => 'R'));
    	$this->view->arrReceitas = $arrReceitas;
    }
    
    public function despesasVencidasAction(){
		$this->_helper->layout->disableLayout();
    	$message = new Zend_Session_Namespace('message');
    	
    	if( $this->getRequest()->getParam('msg') ){
    		$this->view->msg = $this->getRequest()->getParam('msg');
    	}
    	
    	$arrDespesas = Model_Movimentacao::getInstance()->getConsultaVencidos(array('tp_movimentacao' => 'D'));
    	
    	$dPage = $this->_getParam('page', 1);
    	$dPcount = $this->_getParam('count_per_page', 20);
    	
    	$dPaginator = Zend_Paginator::factory($arrDespesas);
    	$dPaginator->setCurrentPageNumber($dPage)
    	->setItemCountPerPage($dPcount);
    	
    	$this->view->dPaginator = $dPaginator;
    	$this->view->dPcount = $dPcount;
    }
    
    public function despesasVencendoAction(){
		$this->_helper->layout->disableLayout();
    	$message = new Zend_Session_Namespace('message');
    	
    	if( $this->getRequest()->getParam('msg') ){
    		$this->view->msg = $this->getRequest()->getParam('msg');
    	}
    	
    	$arrDespesas = Model_Movimentacao::getInstance()->getConsultaVencendo(array('tp_movimentacao' => 'D'));
    	
    	$dPage = $this->_getParam('page', 1);
    	$dPcount = $this->_getParam('count_per_page', 20);
    	
    	$dPaginator = Zend_Paginator::factory($arrDespesas);
    	$dPaginator->setCurrentPageNumber($dPage)
    	->setItemCountPerPage($dPcount);
    	
    	$this->view->dPaginator = $dPaginator;
    	$this->view->dPcount = $dPcount;
    }
    
    public function quitarAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$co_movimentacao = $this->getRequest()->getParam( 'co_movimentacao' );
    	
    	$arrParam = array('st_quitacao' => 'S', 'dt_quitacao' => Zend_Date::now()->toString("Y-M-d hh:mm:ss") );
    	
    	try{ 
    		Model_Movimentacao::getInstance()->update($arrParam, 'co_movimentacao = '.$co_movimentacao );
    		$params = array('msg' => 'success' );
    		
    	}catch( Exception $e ){
    		$params = array('msg' => 'erro' );
    	}
    	
    	$this->_response->appendBody(Zend_Json::encode($params));
    }
    
    public function quitarParcelaAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$user = Zend_Auth::getInstance()->getIdentity();
    	
    	$co_contrato_parcela = $this->getRequest()->getParam( 'co_contrato_parcela' );
    	
    	$arrParam = array( 'dt_quitacao' => Zend_Date::now()->toString("Y-M-d hh:mm:ss"), 'co_usuario_quitacao' => $user['user']->co_usuario );
    	
    	try{ 
    		Model_ContratoParcela::getInstance()->update($arrParam, 'co_contrato_parcela = '.$co_contrato_parcela );
    		$params = array('msg' => 'success' );
    		
    	}catch( Exception $e ){
    		$params = array('msg' => 'erro' );
    	}
    	
    	$this->_response->appendBody(Zend_Json::encode($params));
    }
    
    public function deletarAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$co_movimentacao = $this->getRequest()->getParam( 'co_movimentacao' );
    	
    	$arrParam = array( 'st_ativo' => 'N' );
    	
    	try{ 
    		Model_Movimentacao::getInstance()->update($arrParam, 'co_movimentacao = '.$co_movimentacao );
    		$params = array('msg' => 'success' );
    		
    	}catch( Exception $e ){
    		$params = array('msg' => 'erro' );
    	}
    	
    	$this->_response->appendBody(Zend_Json::encode($params));
    }
	    
	    

}

