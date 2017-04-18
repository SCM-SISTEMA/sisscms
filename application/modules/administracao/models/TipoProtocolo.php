<?php

class Administracao_Model_TipoProtocolo extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_tipo_protocolo';
	
	/**
	 * Instância da classe Administracao_Model_TipoProtocolo
	 *
	 * @return Administracao_Model_TipoProtocolo
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_TipoProtocolo();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_tipo_protocolo'));
	
		$arrBind = array();
		
		if(array_key_exists("co_tipo_protocolo",$arrParam) && $arrParam['co_tipo_protocolo']) {
			$sql->where('co_tipo_protocolo = ?');
			$arrBind[] = $arrParam['co_tipo_protocolo'];
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
		->order(array('co_tipo_protocolo'));
	
		$arrBind = array();
		
		if(array_key_exists("co_tipo_protocolo",$arrParam) && $arrParam['co_tipo_protocolo']) {
			$sql->where('co_tipo_protocolo = ?');
			$arrBind[] = $arrParam['co_tipo_protocolo'];
		}
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

