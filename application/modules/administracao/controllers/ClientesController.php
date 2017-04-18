<?php

class Administracao_ClientesController extends Zend_Controller_Action
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

    public function cadastroAction(){

		if($this->getRequest()->getParam('id')){
			$id_pessoa =  $this->getRequest()->getParam('id');

			//http://receitaws.com.br/v1/cnpj/10225044000173

			if( $this->getRequest()->getParam('tp_pessoa') == 'pj' ){
				$arrCoPessoa = array('co_pessoa_juridica' => $id_pessoa );
				$arrPessoa = Administracao_Model_PessoaJuridica::getInstance()->getConsulta($arrCoPessoa);
				$arrSocio = Administracao_Model_Socio::getInstance()->getConsultaAllSocios($arrCoPessoa);
				$arrREspTec = Administracao_Model_ResponsavelTecnicoPessoaJuridica::getInstance()->getConsultaAll($arrCoPessoa);
				$this->view->arrSocio 	= $arrSocio;
				$this->view->arrREspTec = $arrREspTec;
				$arrCnae = Administracao_Model_ClienteCnae::getInstance()->getConsultaAll( array('co_pessoa_juridica' => $id_pessoa) );
				foreach($arrCnae as $cnae){
					if($cnae['tp_cnae'] == 1){
						$arrCnaePrimario[] = $cnae;
					}else{
						$arrCnaeSecundario[] = $cnae;
					}
				}

				$this->view->arrCnaePrimario = $arrCnaePrimario;
				$this->view->arrCnaeSecundario = $arrCnaeSecundario;
			}else{
				$arrCoPessoa = array('co_pessoa_fisica' => $id_pessoa );
				$arrPessoa = Administracao_Model_PessoaFisica::getInstance()->getConsulta($arrCoPessoa);
			}

			$this->view->arrPessoa 	= $arrPessoa;
		}

		$this->view->arrResponsavelTecnico = Administracao_Model_ResponsavelTecnico::getInstance()->getConsultaAll( array() );

	}
	    
    public function visualizarAction(){

		$coPessoa =  $this->getRequest()->getParam('id');

		if( $this->getRequest()->getParam('tp_pessoa') == 'pj' ){
			$arrPessoa = Administracao_Model_PessoaJuridica::getInstance()->getConsulta(array('co_pessoa_juridica' => $coPessoa ));
			$arrSocio = Administracao_Model_Socio::getInstance()->getConsultaAllSocios(array('co_pessoa_juridica' => $coPessoa ));
			$arrREspTec = Administracao_Model_ResponsavelTecnicoPessoaJuridica::getInstance()->getConsultaAll(array('co_pessoa_juridica' => $coPessoa ));
			$arrCnae = Administracao_Model_ClienteCnae::getInstance()->getConsultaAll( array('co_pessoa_juridica' => $coPessoa) );
			foreach($arrCnae as $cnae){
				if($cnae['tp_cnae'] == 1){
					$arrCnaePrimario[] = $cnae;
				}else{
					$arrCnaeSecundario[] = $cnae;
				}
			}
			$this->view->arrCnaePrimario = $arrCnaePrimario;
			$this->view->arrCnaeSecundario = $arrCnaeSecundario;
			$this->view->arrSocio 	= $arrSocio;
			$this->view->arrREspTec = $arrREspTec;
			$this->view->tipo_pessoa = 1;
		}else{
			$this->view->tipo_pessoa = 0;
			$arrCoPessoa = array('co_pessoa_fisica' => $coPessoa );
			$arrPessoa = Administracao_Model_PessoaFisica::getInstance()->getConsulta($arrCoPessoa);
		}

		$this->view->arrPessoa 	= $arrPessoa;

	}

    public function salvarAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		Zend_Loader::loadClass('Zend_Json');

		$dataParams = $this->getRequest()->getParam('data');

		$dadosPj = $dataParams['pj'];
		$dadosPf = $dataParams['pf'];

		# verifica se foi selecionado pessoa juridica
		if( $dataParams['tp_pessoa'] == 'pj' ){

			$objModelPessoaJuridica = new Administracao_Model_PessoaJuridica();
			$objModelPessoaJuridica->getAdapter()->beginTransaction();

			# Seta o array para criação de pessoa jurídica
			$arrParam = array(
				'nu_cnpj' 							=> removeCaracter( $dadosPj['nu_cnpj'] ),
				'nu_ie' 							=> $dadosPj['nu_ie'],
				'no_fantasia' 						=> $dadosPj['no_fantasia'],
				'no_razao_social' 					=> $dadosPj['no_razao_social'],
				'nu_telefone_1' 					=> $dataParams['nu_telefone_1'],
				'nu_telefone_2' 					=> $dataParams['nu_telefone_2'],
				'ds_email' 							=> $dataParams['ds_email'],
				'ds_endereco' 						=> $dataParams['ds_endereco'],
				'ds_endereco_correspondecia' 		=> $dataParams['ds_endereco_correspondecia'],
				'nu_endereco' 						=> $dataParams['nu_endereco'],
				'nu_cep' 							=> removeCaracter($dataParams['nu_cep']),
				'no_bairro'							=> $dataParams['no_bairro'],
				'no_cidade'							=> $dataParams['no_cidade'],
				'sg_uf'								=> $dataParams['sg_uf'],
				'ds_complemento'					=> $dataParams['ds_complemento'],
				'no_responsavel'					=> $dataParams['no_responsavel'],
				'dt_cadastro' 						=> dataAtual(),
				'st_ativo' 							=> ST_REGISTRO_ATIVO
			);

			# retorno da pessoa jurídica inserida
			try{

				if($dadosPj['co_pessoa_juridica']){
					$objModelPessoaJuridica->update( $arrParam, 'co_pessoa_juridica = '.$dadosPj['co_pessoa_juridica'] );
					$co_pessoa_juridica = $dadosPj['co_pessoa_juridica'];
				}else{
					$co_pessoa_juridica = $objModelPessoaJuridica->insert( $arrParam );
				}
			}catch (Exception $e){
				$params = array(
					'msg' => 'error',
					'message' => $e->getMessage()
				);
				$this->_response->appendBody(Zend_Json::encode($params));
			}

			//INSERE O CNAE PRIMARIO
			if($dadosPj['nu_cnae_primario']) {
				foreach ($dadosPj['nu_cnae_primario'] as $cnae) {
					$arrCnae = explode('|', $cnae);
					$coCnae = Administracao_Model_Cnae::getInstance()->getConsulta(array('cod_cnae' => $arrCnae[0]));

					$arrParamCC = array(
						'cod_cnae' => $arrCnae[0],
						'co_pessoa_juridica' => $co_pessoa_juridica
					);

					$arrClienteCnae = Administracao_Model_ClienteCnae::getInstance()->getConsultaAll($arrParamCC);
					if (count($arrClienteCnae) == 0 && $coCnae) {
						Administracao_Model_ClienteCnae::getInstance()->insert(array('co_cnae' => $coCnae['co_cnae'], 'co_pessoa_juridica' => $co_pessoa_juridica));
					}

					if (!$coCnae) {
						$arrParam = array(
							'cod_cnae' => $arrCnae[0],
							'ds_cnae' => utf8_decode($arrCnae[1]),
							'tp_cnae' => 1
						);

						try {
							$coCnae = Administracao_Model_Cnae::getInstance()->insert($arrParam);
							Administracao_Model_ClienteCnae::getInstance()->insert(array('co_cnae' => $coCnae, 'co_pessoa_juridica' => $co_pessoa_juridica));
						} catch (Exception $e) {
							$params = array(
								'msg' => 'error',
								'message' => $e->getMessage()
							);

							$this->_response->appendBody(Zend_Json::encode($params));
						}
					}
				}
			}

			//INSERE O CNAE SECUNDARIO
			if($dadosPj['nu_cnae_secundario']) {
				foreach ($dadosPj['nu_cnae_secundario'] as $cnae) {
					$arrCnae = explode('|', $cnae);
					$coCnae = Administracao_Model_Cnae::getInstance()->getConsulta(array('cod_cnae' => $arrCnae[0]));

					$arrParamCC = array(
						'co_cnae' => $coCnae['co_cnae'],
						'co_pessoa_juridica' => $co_pessoa_juridica
					);

					$arrClienteCnae = Administracao_Model_ClienteCnae::getInstance()->getConsultaAll($arrParamCC);
					if (count($arrClienteCnae) == 0 && $coCnae) {
						Administracao_Model_ClienteCnae::getInstance()->insert(array('co_cnae' => $coCnae['co_cnae'], 'co_pessoa_juridica' => $co_pessoa_juridica));
					}

					if (!$coCnae) {
						$arrParam = array(
							'cod_cnae' => $arrCnae[0],
							'ds_cnae' => utf8_decode($arrCnae[1]),
							'tp_cnae' => 2
						);

						try {
							$coCnae = Administracao_Model_Cnae::getInstance()->insert($arrParam);
							Administracao_Model_ClienteCnae::getInstance()->insert(array('co_cnae' => $coCnae, 'co_pessoa_juridica' => $co_pessoa_juridica));
						} catch (Exception $e) {
							$params = array(
								'msg' => 'error',
								'message' => $e->getMessage()
							);
							$this->_response->appendBody(Zend_Json::encode($params));
						}
					}
				}
			}

			/*# vincula os sócios pela pessoa juridica

			if($dadosPj['socio']){
				foreach( $dadosPj['socio'] as $socio ){

					$arrParamSocio = array(
						'co_pessoa_juridica'	=> $co_pessoa_juridica,
						'no_socio'		 		=> $socio['nome'],
						'ds_profissao' 			=> $socio['profissao'],
						'ds_cargo' 				=> $socio['cargo'],
						'nu_telefone'			=> removeCaracter( $socio['telefone'] ),
						'nu_whatsapp'			=> removeCaracter( $socio['nu_whatsapp'] )
					);

					try{
						Administracao_Model_Socio::getInstance()->insert( $arrParamSocio );
					}catch (Exception $e){
						$params = array(
							'msg' => 'error',
							'message' => $e->getMessage()
						);
						$this->_response->appendBody(Zend_Json::encode($params));
					}
				}
			}



			# vincula o responsável técnico
			$arrParamResponsavelTecnico = array(
				'co_pessoa_juridica'		=> $dadosPj['co_pessoa_juridica'],
				'co_responsavel_tecnico' 	=> $dadosPj['co_responsavel_tecnico']
			);
			$arrParamRT = Administracao_Model_ResponsavelTecnicoPessoaJuridica::getInstance()->getConsultaAll($arrParamResponsavelTecnico);

			if(empty($arrParamRT)){
				try{
					Administracao_Model_ResponsavelTecnicoPessoaJuridica::getInstance()->insert( $arrParamResponsavelTecnico );
				}catch (Exception $e){
					$params = array(
						'msg' => 'error',
						'message' => $e->getMessage()
					);
					$this->_response->appendBody(Zend_Json::encode($params));
				}
			}

			# vincula o contador
			$arrParamContador = array(
				'co_pessoa_juridica'	=> $co_pessoa_juridica,
				'no_contador' 			=> $dadosPj['no_contador'],
				'nu_telefone'			=> removeCaracter( $dadosPj['nu_tel_contador'] ),
				'ds_skypemsn'			=> $dadosPj['ds_skypemsn_contador'],
				'ds_email' 				=> $dadosPj['ds_email_contador']
			);

			try{
				Administracao_Model_Contador::getInstance()->insert( $arrParamContador );
			}catch (Exception $e){
				debug($e, 1);
				$objModelPessoaJuridica->getAdapter()->rollBack();
			}*/

			$objModelPessoaJuridica->getAdapter()->commit();

		}elseif( $dataParams['tp_pessoa'] == 'pf' ){

			$objModelPessoaFisica = new Administracao_Model_PessoaFisica();
			$objModelPessoaFisica->getAdapter()->beginTransaction();

			# Seta o array para criação de pessoa jurídica
			$arrParam = array(
				'co_tipo_pessoa' 	=> 1,
				'nu_cpf' 			=> removeCaracter( $dadosPf['nu_cpf'] ),
				'no_pessoa_fisica'	=> $dadosPf['no_pessoa_fisica'],
				'ds_estado_civil'	=> $dadosPf['ds_estado_civil'],
				'ds_nacionalidade'	=> $dadosPf['ds_nacionalidade'],
				'nu_telefone_1' 	=> removeCaracter( $dataParams['nu_telefone_1'] ),
				'nu_telefone_2' 	=> removeCaracter( $dataParams['nu_telefone_2'] ),
				'nu_whatsapp' 		=> removeCaracter( $dataParams['nu_whatsapp'] ),
				'ds_email' 			=> $dataParams['ds_email'],
				'ds_endereco' 		=> $dataParams['ds_endereco'],
				'nu_endereco' 		=> $dataParams['nu_endereco'],
				'nu_cep' 			=> $dataParams['nu_cep'],
				'no_bairro'			=> $dataParams['no_bairro'],
				'no_cidade'			=> $dataParams['no_cidade'],
				'sg_uf'				=> $dataParams['sg_uf'],
				'ds_complemento'	=> $dataParams['ds_complemento'],
				'dt_cadastro' 		=> dataAtual(),
				'st_ativo' 			=> 'S'
			);

			# retorno da pessoa jurídica inserida
			try{
				$co_pessoa_fisica = $objModelPessoaFisica->insert( $arrParam );
				$objModelPessoaFisica->getAdapter()->commit();
			}catch (Exception $e){
				$params = array(
					'msg' => 'error',
					'message' => $e->getMessage()
				);
				$this->_response->appendBody(Zend_Json::encode($params));
			}
		}

		$params = array(
			'msg' => 'success',
			'message' => 'Operação realizada com sucesso'
		);
		$this->_response->appendBody(Zend_Json::encode($params));

	}

	/**
	 *
     */
	public function buscarCepAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
		$cep = removeCaracter($this->getRequest()->getParam('nu_cep'));
		$url = 'viacep.com.br/ws/'.$cep.'/json/';
		$ch = curl_init();
		curl_setopt_array($ch, array
		(
			CURLOPT_URL 			=> $url,
			CURLOPT_RETURNTRANSFER	=> TRUE
		));

		$response = curl_exec($ch);
		$this->_response->appendBody($this->retornaJsonFormatado($response));
    }

	public function buscarCnpjAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$ch = curl_init();

		$nu_cnpj = $this->getRequest()->getParam( 'nu_cnpj' );
		$url = "http://receitaws.com.br/v1/cnpj/".removeCaracter($nu_cnpj);
    	curl_setopt_array($ch, array
		(
			CURLOPT_URL 			=> $url,
			CURLOPT_RETURNTRANSFER	=> TRUE
		));

    	$response = curl_exec($ch);
    	curl_close($ch);
    	$this->_response->appendBody($this->retornaJsonFormatado($response));

    }

	public function roboAtualizaDadosReceitaClienteAction(){
		set_time_limit(0);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();


		$arrClientes = Administracao_Model_PessoaJuridica::getInstance()->getConsultaAll(array());
		foreach($arrClientes as $cliente){

			$ch = curl_init();
			if($cliente['co_pessoa_juridica'] && strlen($cliente['nu_cnpj']) == 14) {

				$nu_cnpj = removeCaracter($cliente['nu_cnpj']);
				$url = "http://receitaws.com.br/v1/cnpj/" . removeCaracter($nu_cnpj);
				curl_setopt_array($ch, array
				(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => TRUE
				));

				$response = curl_exec($ch);

				$arr = json_decode($this->retornaJsonFormatado($response));

				if ($arr) {

					$arrParam = array(
						'no_razao_social' => $arr->nome,
						'no_fantasia' => $arr->fantasia,
						'nu_telefone_2' => $arr->telefone,
						'no_bairro' => $arr->bairro,
						'ds_endereco' => $arr->logradouro,
						'nu_endereco' => $arr->numero,
						'nu_cep' => removeCaracter($arr->cep),
						'nu_cnpj' => removeCaracter($arr->cnpj),
						'no_cidade' => $arr->municipio,
						'ds_complemento' => $arr->complemento,
						'sg_uf' => $arr->uf,
					);

					Administracao_Model_PessoaJuridica::getInstance()->update($arrParam, 'co_pessoa_juridica = ' . $cliente['co_pessoa_juridica']);

				}


				curl_close($ch);
			}

		}


	}

	public function roboAtualizaCnaeAction(){
		set_time_limit(0);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$arrClientes = Administracao_Model_PessoaJuridica::getInstance()->getConsultaAll(array());
		foreach($arrClientes as $cliente){

			$ch = curl_init();

			if($cliente['co_pessoa_juridica']) {

				$nu_cnpj = $cliente['nu_cnpj'];
				if(!empty($nu_cnpj)) {
					$url = "http://receitaws.com.br/v1/cnpj/" . removeCaracter($nu_cnpj);
					curl_setopt_array($ch, array
					(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => TRUE
					));

					$response = curl_exec($ch);

					$arr = json_decode($this->retornaJsonFormatado($response));

					$arrAtividadePrincipal = $arr->atividade_principal;
					$arrAtividadeSecundaria = $arr->atividades_secundarias;


					if($arrAtividadePrincipal && $arr->status != 'ERROR'){
						foreach ($arrAtividadePrincipal as $atividadePrincipal) {
							$codCnae = $atividadePrincipal->code;
							$dsCnae = $atividadePrincipal->text;
							$arrCnae = Administracao_Model_Cnae::getInstance()->getConsulta(array('cod_cnae' => $codCnae));

							if (!$arrCnae) {
								$arrParamC = array(
									'cod_cnae' => $codCnae,
									'ds_cnae' => $dsCnae,
									'tp_cnae' => 1
								);
								Administracao_Model_Cnae::getInstance()->insert($arrParamC);
								$arrCnaeAdd = Administracao_Model_Cnae::getInstance()->getConsulta($arrParamC);
								$arrParamCC = array(
									'co_cnae' => $arrCnaeAdd['co_cnae'],
									'co_pessoa_juridica' => $cliente['co_pessoa_juridica']
								);
								Administracao_Model_ClienteCnae::getInstance()->insert($arrParamCC);
							}elseif($arrCnae){
								$arrParamCC = array(
									'co_cnae' => $arrCnae['co_cnae'],
									'co_pessoa_juridica' => $cliente['co_pessoa_juridica']
								);
								$arrClienteCnae = Administracao_Model_ClienteCnae::getInstance()->getConsultaAll($arrParamCC);

								if(count($arrClienteCnae) == 0){
									$arrParamCC = array(
										'co_cnae' => $arrCnae['co_cnae'],
										'co_pessoa_juridica' => $cliente['co_pessoa_juridica']
									);
									Administracao_Model_ClienteCnae::getInstance()->insert($arrParamCC);
								}

							}
						}
					}

					if($arrAtividadeSecundaria && $arr->status != 'ERROR'){
						foreach ($arrAtividadeSecundaria as $atividadeSecundaria) {
							$codCnae = $atividadeSecundaria->code;
							$dsCnae = $atividadeSecundaria->text;
							$arrCnae = Administracao_Model_Cnae::getInstance()->getConsulta(array('cod_cnae' => $codCnae));

							if (!$arrCnae) {
								$arrParamC = array(
									'cod_cnae' => $codCnae,
									'ds_cnae' => $dsCnae,
									'tp_cnae' => 2
								);
								Administracao_Model_Cnae::getInstance()->insert($arrParamC);
								$arrCnaeAdd = Administracao_Model_Cnae::getInstance()->getConsulta($arrParamC);
								$arrParamCC = array(
									'co_cnae' => $arrCnaeAdd['co_cnae'],
									'co_pessoa_juridica' => $cliente['co_pessoa_juridica']
								);
								Administracao_Model_ClienteCnae::getInstance()->insert($arrParamCC);
							}elseif($arrCnae){
								$arrParamCC = array(
									'co_cnae' => $arrCnae['co_cnae'],
									'co_pessoa_juridica' => $cliente['co_pessoa_juridica']
								);
								$arrClienteCnae = Administracao_Model_ClienteCnae::getInstance()->getConsultaAll($arrParamCC);

								if(count($arrClienteCnae) == 0){
									$arrParamCC = array(
										'co_cnae' => $arrCnae['co_cnae'],
										'co_pessoa_juridica' => $cliente['co_pessoa_juridica']
									);
									Administracao_Model_ClienteCnae::getInstance()->insert($arrParamCC);
								}

							}
						}
					}
				}

				curl_close($ch);
			}

		}


	}

	public function verificaExistenciaClienteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();


		$nu_cnpj = removeCaracter($this->getRequest()->getParam( 'nu_cnpj' ));
		if(!is_null($nu_cnpj) && !empty($nu_cnpj)){
			$arrCliente = Administracao_Model_PessoaJuridica::getInstance()->getConsulta(array('nu_cnpj' => $nu_cnpj));
			if($arrCliente){
				$params = array(
					'msg' => 'error',
					'message' => 'Já existe um cliente cadastrado para este cnpj!'
				);
				$this->_response->appendBody(Zend_Json::encode($params));
			}else{
				$params = array(
					'msg' => 'success'
				);
				$this->_response->appendBody(Zend_Json::encode($params));
			}
		}
    }

	public function roboAtualizaUfClienteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();


		$arrClientes = Administracao_Model_PessoaJuridica::getInstance()->getConsultaAll(array());
		foreach($arrClientes as $cliente){
			if(!empty($cliente['sg_uf'])){
				$estado = Administracao_Model_Estados::getInstance()->getConsultaEstado(array('sg_estado' => $cliente['sg_uf']));
				Administracao_Model_PessoaJuridica::getInstance()->update(array('sg_uf' =>$estado['co_estado'] ), 'co_pessoa_juridica ='.$cliente['co_pessoa_juridica']);
			}else{
				continue;
			}


		}
    }

	public function roboAtualizaCnpjClienteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();


		$arrClientes = Administracao_Model_PessoaJuridica::getInstance()->getConsultaAll(array());
		foreach($arrClientes as $cliente){
			if(!empty($cliente['nu_cnpj'])){
				Administracao_Model_PessoaJuridica::getInstance()->update(array('nu_cnpj' =>removeCaracter( $cliente['nu_cnpj'] ) ), 'co_pessoa_juridica ='.$cliente['co_pessoa_juridica']);
			}else{
				continue;
			}


		}
    }

	public function roboAtualizaCepClienteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();


		$arrClientes = Administracao_Model_PessoaJuridica::getInstance()->getConsultaAll(array());
		foreach($arrClientes as $cliente){
			if(!empty($cliente['nu_cep'])){
				Administracao_Model_PessoaJuridica::getInstance()->update(array('nu_cep' =>removeCaracter( $cliente['nu_cep'] ) ), 'co_pessoa_juridica ='.$cliente['co_pessoa_juridica']);
			}else{
				continue;
			}


		}
    }

	private function retornaJsonFormatado($html){
		return substr(substr( str_replace('\"', '"',Zend_Json::encode(preg_replace("/[\n\r]/","",($html)))),1), 0,-1);
	}

}

