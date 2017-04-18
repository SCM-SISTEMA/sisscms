<?php

class Administracao_Model_ContratoChecklist extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'rl_contrato_checklist';
	
	/**
	 * Instância da classe Administracao_Model_ContratoChecklist
	 *
	 * @return Administracao_Model_ContratoChecklist
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_ContratoChecklist();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_contrato'));
	
		$arrBind = array();
		
		if(array_key_exists("co_contrato",$arrParam) && $arrParam['co_contrato']) {
			$sql->where('co_contrato = ?');
			$arrBind[] = $arrParam['co_contrato'];
		}
		
		if(array_key_exists("co_checklist",$arrParam) && $arrParam['co_checklist']) {
			$sql->where('co_checklist = ?');
			$arrBind[] = $arrParam['co_checklist'];
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_contrato'));
	
		$arrBind = array();
		
		if(array_key_exists("co_contrato",$arrParam) && $arrParam['co_contrato']) {
			$sql->where('co_contrato = ?');
			$arrBind[] = $arrParam['co_contrato'];
		}
		
		if(array_key_exists("co_checklist",$arrParam) && $arrParam['co_checklist']) {
			$sql->where('co_checklist = ?');
			$arrBind[] = $arrParam['co_checklist'];
		}
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

