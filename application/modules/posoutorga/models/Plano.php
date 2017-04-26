<?php

class Posoutorga_Model_Plano extends Zend_Db_Table
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_planos';
	
	/**
	 * Instância da classe Posoutorga_Model_Plano
	 *
	 * @return Posoutorga_Model_Plano
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Posoutorga_Model_Plano();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('co_plano'));
	
		$arrBind = array();
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('co_plano'));
	
		$arrBind = array();
		
		if(array_key_exists("co_plano",$arrParam) && $arrParam['co_plano']) {
			$sql->where('co_plano = ?');
			$arrBind[] = $arrParam['co_plano'];
		}
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

