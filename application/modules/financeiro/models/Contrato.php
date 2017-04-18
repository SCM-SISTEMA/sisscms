<?php

class Administracao_Model_Contrato extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_contrato';
	
	/**
	 * Instância da classe Administracao_Model_Contrato
	 *
	 * @return Administracao_Model_Contrato
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Contrato();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->join('tb_servico', 'tb_servico.co_servico = '.$this->_name.'.co_servico', array('ds_servico', 'co_tipo_contrato'), $this->_schema)
		->join('tb_forma_pagamento', 'tb_forma_pagamento.co_forma_pagamento = '.$this->_name.'.co_forma_pagamento', array('ds_forma_pagamento', 'nu_parcelas', 'nu_qtd_dias'), $this->_schema)
		->order(array('co_contrato'));
	
		$arrBind = array();
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
				->joinLeft('tb_pessoa_juridica', 'tb_pessoa_juridica.co_pessoa_juridica = '.$this->_name.'.co_pessoa', array('no_razao_social', 'ds_endereco as ds_endereco_pj','nu_endereco as nu_endereco_pj', 'no_bairro as no_bairro_pj', 'no_cidade as no_cidade_pj', 'sg_uf as sg_uf_pj', 'nu_cep as nu_cep_pj', 'nu_telefone_1'), $this->_schema)
				->joinLeft('tb_pessoa_fisica', 'tb_pessoa_fisica.co_pessoa_fisica= '.$this->_name.'.co_pessoa', array('no_pessoa_fisica', 'ds_endereco as ds_endereco_pf', 'nu_endereco as nu_endereco_pf', 'no_bairro as no_bairro_pf', 'no_cidade as no_cidade_pf', 'sg_uf as sg_uf_pf', 'nu_cep as nu_cep_pf', 'nu_telefone_1'), $this->_schema)
				->joinLeft('tb_servico', 'tb_servico.co_servico = '.$this->_name.'.co_servico', array('ds_servico', 'sg_servico'), $this->_schema)
				->joinLeft('tb_forma_pagamento', 'tb_forma_pagamento.co_forma_pagamento = '.$this->_name.'.co_forma_pagamento', array('ds_forma_pagamento'), $this->_schema)
		->order(array('co_contrato'));
	
		$arrBind = array();
		
		if(array_key_exists("co_contrato",$arrParam) && $arrParam['co_contrato']) {
			$sql->where('co_contrato = ?');
			$arrBind[] = $arrParam['co_contrato'];
		}
		
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

