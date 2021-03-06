<?php

class Financeiro_Model_Rastreamento extends Financeiro_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_rastreamento';
	
	/**
	 * Instância da classe Financeiro_Model_Rastreamento
	 *
	 * @return Financeiro_Model_Rastreamento
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Financeiro_Model_Rastreamento();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_servico'));
	
		$arrBind = array();
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_servico'));
	
		$arrBind = array();
		
		if(array_key_exists("co_servico",$arrParam) && $arrParam['co_servico']) {
			$sql->where('co_servico = ?');
			$arrBind[] = $arrParam['co_servico'];
		}
		
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

