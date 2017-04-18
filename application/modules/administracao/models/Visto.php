<?php

class Administracao_Model_Visto extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_visto_responsavel_tecnico';
	
	/**
	 * Instância da classe Administracao_Model_Visto
	 *
	 * @return Administracao_Model_Visto
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Visto();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_estados', 'tb_estados.co_estado = '.$this->_name.'.co_estado', array('sg_estado', 'no_estado'), $this->_schema)
		->order(array('sg_estado'));
	
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
		->order(array('co_estado'));
	
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

	public function getConsultaEstados($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('co_estado'),$this->_schema)
			->joinLeft('tb_estados', 'tb_estados.co_estado = '.$this->_name.'.co_estado', array('sg_estado', 'no_estado'), $this->_schema)
		->order(array('co_estado'));
	
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

