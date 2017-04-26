<?php

class Administracao_Model_ResponsavelTecnicoPessoaJuridica extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'rl_responsavel_tecnico_pessoa_juridica';
	
	/**
	 * Instância da classe Administracao_Model_ResponsavelTecnicoPessoaJuridica
	 *
	 * @return Administracao_Model_ResponsavelTecnicoPessoaJuridica
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_ResponsavelTecnicoPessoaJuridica();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
				->joinLeft('tb_pessoa_juridica', $this->_name.'.co_pessoa_juridica = tb_pessoa_juridica.co_pessoa_juridica', array('*'), $this->_schema)
				->joinLeft('tb_responsavel_tecnico', $this->_name.'.co_responsavel_tecnico = tb_responsavel_tecnico.co_responsavel_tecnico', array('*'), $this->_schema)
		->order(array('no_razao_social'));
	
		$arrBind = array();
		
		if(key_exists('co_responsavel_tecnico',$arrParam) && $arrParam['co_responsavel_tecnico']){
			$sql->where($this->_name.'.co_responsavel_tecnico = ?');
			$arrBind[] = $arrParam['co_responsavel_tecnico'];
		}
		
		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_pessoa_juridica = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
				->joinLeft('tb_pessoa_juridica', $this->_name.'.co_pessoa_juridica = tb_pessoa_juridica.co_pessoa_juridica', array('*'), $this->_schema)
				->joinLeft('tb_responsavel_tecnico', $this->_name.'.co_responsavel_tecnico = tb_responsavel_tecnico.co_responsavel_tecnico', array('*'), $this->_schema)
		->order(array('no_razao_social'));
	
		$arrBind = array();
		
		if(key_exists('co_responsavel_tecnico',$arrParam) && $arrParam['co_responsavel_tecnico']){
			$sql->where($this->_name.'.co_responsavel_tecnico = ?');
			$arrBind[] = $arrParam['co_responsavel_tecnico'];
		}
		
		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_pessoa_juridica = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}
		
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}

}

