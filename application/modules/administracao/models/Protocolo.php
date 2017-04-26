<?php

class Administracao_Model_Protocolo extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_protocolo';
	
	/**
	 * Instância da classe Administracao_Model_Protocolo
	 *
	 * @return Administracao_Model_Protocolo
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Protocolo();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_tipo_protocolo', 'tb_tipo_protocolo.co_tipo_protocolo = '.$this->_name.'.co_tipo_protocolo', array('ds_tipo_protocolo'), $this->_schema)
		->order(array('co_protocolo'));
	
		$arrBind = array();
		
		if(array_key_exists("co_protocolo",$arrParam) && $arrParam['co_protocolo']) {
			$sql->where($this->_name.'.co_protocolo = ?');
			$arrBind[] = $arrParam['co_protocolo'];
		}
		
		if(array_key_exists("co_contrato",$arrParam) && $arrParam['co_contrato']) {
			$sql->where($this->_name.'.co_contrato = ?');
			$arrBind[] = $arrParam['co_contrato'];
		}
		
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_tipo_protocolo', 'tb_tipo_protocolo.co_tipo_protocolo = '.$this->_name.'.co_tipo_protocolo', array('ds_tp_protocolo'), $this->_schema)
		->order(array('co_protocolo'));
	
		$arrBind = array();
		
		if(array_key_exists("co_protocolo",$arrParam) && $arrParam['co_protocolo']) {
			$sql->where($this->_name.'.co_protocolo = ?');
			$arrBind[] = $arrParam['co_protocolo'];
		}
		
		if(array_key_exists("co_contrato",$arrParam) && $arrParam['co_contrato']) {
			$sql->where($this->_name.'.co_contrato = ?');
			$arrBind[] = $arrParam['co_contrato'];
		}
		
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

