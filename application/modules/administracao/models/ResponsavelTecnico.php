<?php

class Administracao_Model_ResponsavelTecnico extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_responsavel_tecnico';
	
	/**
	 * Instância da classe Administracao_Model_ResponsavelTecnico
	 *
	 * @return Administracao_Model_ResponsavelTecnico
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_ResponsavelTecnico();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_area_tecnica', 'tb_area_tecnica.co_area_tecnica = '.$this->_name.'.co_area_tecnica', array('ds_area_tecnica'), $this->_schema)
		->where("st_ativo = 'S'")
		->order(array('co_responsavel_tecnico'));
	
		$arrBind = array();
		
		if(array_key_exists("co_responsavel_tecnico",$arrParam) && $arrParam['co_responsavel_tecnico']) {
			$sql->where('co_responsavel_tecnico = ?');
			$arrBind[] = $arrParam['co_responsavel_tecnico'];
		}
		
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_area_tecnica', 'tb_area_tecnica.co_area_tecnica = '.$this->_name.'.co_area_tecnica', array('ds_area_tecnica'), $this->_schema)
		->where("st_ativo = 'S'")
		->order(array('co_responsavel_tecnico'));
	
		$arrBind = array();
	
		if(array_key_exists("co_responsavel_tecnico",$arrParam) && $arrParam['co_responsavel_tecnico']) {
			$sql->where('co_responsavel_tecnico = ?');
			$arrBind[] = $arrParam['co_responsavel_tecnico'];
		}
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsultaAnuidadeCrea($arrParam) {
		$sql = $this->getAdapter()->select()->distinct()->from($this->_name,array('co_responsavel_tecnico', 'no_responsavel_tecnico', 'co_area_tecnica', 'nu_registro_nacional', 'nu_registro_regional'),$this->_schema)
		->joinLeft('tb_area_tecnica', 'tb_area_tecnica.co_area_tecnica= '.$this->_name.'.co_area_tecnica', array('ds_area_tecnica'), $this->_schema)
		->join('tb_anuidade_crea', 'tb_anuidade_crea.co_responsavel_tecnico = '.$this->_name.'.co_responsavel_tecnico', array(), $this->_schema)
		->order(array('co_responsavel_tecnico'));
	
		$arrBind = array();
	
		if(array_key_exists("co_responsavel_tecnico",$arrParam) && $arrParam['co_responsavel_tecnico']) {
			$sql->where('co_responsavel_tecnico = ?');
			$arrBind[] = $arrParam['co_responsavel_tecnico'];
		}
		
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

