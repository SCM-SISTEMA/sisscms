<?php

class Posoutorga_SiciController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
    }

	public function indexAction()
    {
		$arrClientes = Administracao_Model_TipoPessoa::getInstance()->getConsultaAllClientes();
		$this->view->arrClientes = $arrClientes;
    }

	public function gerarArquivoAction()
    {

		$id_pessoa = $this->getRequest()->getParam( 'id_pessoa' );
		if( $this->getRequest()->getParam('tp_pessoa') == 'pj' ){
			$arrCoPessoa = array('co_pessoa_juridica' => $id_pessoa );
			$arrPessoa = Administracao_Model_PessoaJuridica::getInstance()->getConsulta($arrCoPessoa);
			$arrSocio = Administracao_Model_Socio::getInstance()->getConsultaAllSocios($arrCoPessoa);
			$arrREspTec = Administracao_Model_ResponsavelTecnicoPessoaJuridica::getInstance()->getConsultaAll($arrCoPessoa);
			$this->view->arrSocio 	= $arrSocio;
			$this->view->arrREspTec = $arrREspTec;
		}else{
			$arrCoPessoa = array('co_pessoa_fisica' => $id_pessoa );
			$arrPessoa = Administracao_Model_PessoaFisica::getInstance()->getConsulta($arrCoPessoa);
		}

		$this->view->arrCidades = Administracao_Model_Cidades::getInstance()->getConsultaAll(array());
		$this->view->arrPessoa 	= $arrPessoa;
    }

    public function criarArquivoXmlAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$xml = simplexml_load_string( stripslashes("<?xml version='1.0' encoding='utf-8'?> <root></root>"));
		$arrParams = $this->getRequest()->getParams();

		$nu_mes = $arrParams['nu_mes'];
		$nu_ano = $arrParams['nu_ano'];
		$co_fistel = $arrParams['co_fistel'];
		$item4 = $arrParams['item4'];
		$item5 = $arrParams['item5'];
		$item9 = $arrParams['item9'];
		$item10 = $arrParams['item10'];
		$ipl3 = $arrParams['ipl3'];
		$qaipl4sm = $arrParams['qaipl4sm'];
		$ipl6im = $arrParams['ipl6im'];
		$iau1 = $arrParams['iau1'];
		$ipl1 = $arrParams['ipl1'];
		$ipl2 = $arrParams['ipl2'];
		$iem1 = $arrParams['iem1'];
		$iem2 = $arrParams['iem2'];
		$iem3 = $arrParams['iem3'];
		$iem6 = $arrParams['iem6'];
		$iem7 = $arrParams['iem7'];
		$iem8 = $arrParams['iem8'];

		// UPLOADSICI
		$uploadSICI = $xml->addChild('UploadSICI');
		$uploadSICI->addAttribute('mes', $nu_mes);
		$uploadSICI->addAttribute('ano', $nu_ano);

		// OUTORGA
		$outorga = $uploadSICI->addChild('Outorga');
		$outorga->addAttribute('fistel', $co_fistel);

		// ITEM4
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'ITEM4');
		foreach($item4 as $item => $valor){
			$conteudo = $indicador->addChild('Conteudo');
			$conteudo->addAttribute('valor', empty($valor) ? '0,00' : $valor);
			$conteudo->addAttribute('item', $item);
			$conteudo->addAttribute('uf', '');
		}

		// ITEM5
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'ITEM5');
		foreach($item5 as $item => $valor){
			$conteudo = $indicador->addChild('Conteudo');
			$conteudo->addAttribute('valor', empty($valor) ? '0,00' : $valor);
			$conteudo->addAttribute('item', $item);
			$conteudo->addAttribute('uf', '');
		}

		// ITEM9
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'ITEM9');
		foreach($item9 as $item => $valor){
			$pessoa = $indicador->addChild('Pessoa');
			$pessoa->addAttribute('Item', strtoupper($item));
			foreach($valor as $item => $val){
				$conteudo = $pessoa->addChild('Conteudo');
				$conteudo->addAttribute('valor', empty($val) ? '0,00' : $val );
				$conteudo->addAttribute('item', $item);
				$conteudo->addAttribute('uf', 'xx');
			}
		}

		// ITEM10
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'ITEM10');
		foreach($item10 as $item => $valor){
			$pessoa = $indicador->addChild('Pessoa');
			$pessoa->addAttribute('Item', strtoupper($item));
			foreach($valor as $item => $val){
				$conteudo = $pessoa->addChild('Conteudo');
				$conteudo->addAttribute('valor', empty($val) ? '0,00' : $val );
				$conteudo->addAttribute('item', $item);
				$conteudo->addAttribute('uf', 'xx');
			}
		}

		// IPL3
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IPL3');

		if(is_null($ipl3)){
			$ipl3[0] = array(
				"co_municipio"=> empty($valor['co_municipio']) ? 0 : $valor['co_municipio'],
				"item"=> array(
					'f' => array( 'a' => '0,00'),
					'j' => array( 'a' => '0,00')
				),
			);
		}

		foreach($ipl3 as $item => $valor){
			$municipio = $indicador->addChild('Municipio');
			$municipio->addAttribute('codmunicipio', $valor['co_municipio'] );

			foreach($valor['item'] as $item => $val){
				$pessoa = $municipio->addChild('Pessoa');
					$pessoa->addAttribute('item', strtoupper($item));
					foreach($val as $i => $v){
						$conteudo = $pessoa->addChild('Conteudo');
						$conteudo->addAttribute('valor', empty($v) ? '0,00' : $v );
						$conteudo->addAttribute('item', $i);
					}

				}
		}

		// QAIPL4SM
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'QAIPL4SM');

		if(is_null($qaipl4sm)){
			$qaipl4sm[0] = array(
				"co_municipio"=> empty($valor['co_municipio']) ? 0 : $valor['co_municipio'],
				"item"=> array(
					'f' => array( 'a' => '0,00'),
					'j' => array( 'a' => '0,00')
				),
			);
		}

		foreach($qaipl4sm as $item => $valor){
			$municipio = $indicador->addChild('Municipio');
			$municipio->addAttribute('codmunicipio',$valor['co_municipio'] );

			foreach($valor['item'] as $item => $val){
					$tecnologia = $municipio->addChild('Tecnologia');
					$tecnologia->addAttribute('item', strtoupper($item));
					$conteudo = $tecnologia->addChild('Conteudo');
					$conteudo->addAttribute('nome', 'QAIPL4SM');
					foreach($val as $i => $v){
						if( $i == 'total'){
							$conteudo = $tecnologia->addChild('Conteudo');
							$conteudo->addAttribute('valor', empty($v) ? '0,00' : $v );
							$conteudo->addAttribute('nome', $i);
						}else{
							$conteudo = $tecnologia->addChild('Conteudo');
							$conteudo->addAttribute('valor', empty($v) ? '0,00' : $v );
							$conteudo->addAttribute('faixa', str_replace('faixa', '', $i));
						}
					}

				}
		}

		// IPL6IM
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IPL6IM');
		$conteudo = $indicador->addChild('Conteudo');
		$conteudo->addAttribute('valor', empty($ipl6im['valor']) ? '0,00' : $ipl6im['valor']);
		$conteudo->addAttribute('codmunicipio', $ipl6im['codmunicipio']);

		// IAU1
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IAU1');
		$conteudo = $indicador->addChild('Conteudo');
		$conteudo->addAttribute('valor', empty($iau1['valor']) ? '0,00' : $iau1['valor']);

		// IPL1
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IPL1');
		foreach($ipl1 as $item => $valor){
			$conteudo = $indicador->addChild('Conteudo');
			$conteudo->addAttribute('valor', empty($valor) ? '0,00' : $valor);
			$conteudo->addAttribute('item', $item);
		}

		// IPL2
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IPL2');
		foreach($ipl2 as $item => $valor){
			$conteudo = $indicador->addChild('Conteudo');
			$conteudo->addAttribute('valor', empty($valor) ? '0,00' : $valor);
			$conteudo->addAttribute('item', $item);
		}

		// IEM1
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IEM1');
		foreach($iem1 as $item => $valor){
			$conteudo = $indicador->addChild('Conteudo');
			$conteudo->addAttribute('valor', empty($valor) ? '0,00' : $valor);
			$conteudo->addAttribute('item', $item);
		}

		// IEM2
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IEM2');
		foreach($iem2 as $item => $valor){
			$conteudo = $indicador->addChild('Conteudo');
			$conteudo->addAttribute('valor', empty($valor) ? '0,00' : $valor);
			$conteudo->addAttribute('item', $item);
		}

		// IEM3
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IEM3');
		$conteudo = $indicador->addChild('Conteudo');
		$conteudo->addAttribute('valor', empty($iem3['valor']) ? '0,00' : $iem3['valor']);
		$conteudo->addAttribute('item', $iem3['item']);

		// IEM6
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IEM6');
		$conteudo = $indicador->addChild('Conteudo');
		$conteudo->addAttribute('valor', empty($iem6['valor']) ? '0,00' : $iem6['valor']);
		$conteudo->addAttribute('item', $iem6['item']);

		// IEM7
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IEM7');
		$conteudo = $indicador->addChild('Conteudo');
		$conteudo->addAttribute('valor', empty($iem7['valor']) ? '0,00' : $iem7['valor']);
		$conteudo->addAttribute('item', $iem7['item']);

		// IEM8
		$indicador = $outorga->addChild('Indicador');
		$indicador->addAttribute('Sigla', 'IEM8');
		foreach($iem8 as $item => $valor){
			$conteudo = $indicador->addChild('Conteudo');
			$conteudo->addAttribute('valor', empty($valor) ? '0,00' : $valor);
			$conteudo->addAttribute('item', $item);
		}

		debug($xml, 1);
		debug($arrParams, 1);
	}

    public function editarAction(){

    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
	    	
    	$arrParam = array( 'co_status' => $this->getRequest()->getParam( 'co_status' )  );
    	$arrStatus = Administracao_Model_Status::getInstance()->getConsulta( $arrParam );

    	$this->_response->appendBody(Zend_Json::encode($arrStatus));
    		
    }

    public function vincularServicoPlanoAction(){

    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();

    	$arrParam = $this->getRequest()->getParam( 'data' );
		try {
			if (sizeof($arrParam['co_servico'])) {
				Administracao_Model_PlanosServicos::getInstance()->delete( 'co_plano = '.$arrParam['co_plano']);
				foreach ($arrParam['co_servico'] as $co_servico) {
					Administracao_Model_PlanosServicos::getInstance()->insert(array('co_plano' => $arrParam['co_plano'], 'co_servico' => $co_servico));
				}
			}

			$params = array(
				'msg' => 'success',
				'message' => 'Operação realizada com sucesso'
			);
			$this->_response->appendBody(Zend_Json::encode($params));
		}catch(Exception $e){
			$params = array(
				'msg' => 'error',
				'message' => $e->getMessage()
			);
			$this->_response->appendBody(Zend_Json::encode($params));
		}

    }

    public function retornarServicosPlanoAction(){

    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();

    	$co_plano = $this->getRequest()->getParam( 'co_plano' );

			if ($co_plano) {
				$arrServicosPlano = Administracao_Model_PlanosServicos::getInstance()->getConsultaAll(array('co_plano' => $co_plano));
				$this->_response->appendBody(Zend_Json::encode($arrServicosPlano));
			}else{
				$params = array(
					'msg' => 'error',
					'message' => 'Erro'
				);
				$this->_response->appendBody(Zend_Json::encode($params));
			}

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

