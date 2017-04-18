<?php

class Administracao_Model_Cnae extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_cnae';
	
	/**
	 * Instância da classe Administracao_Model_Cnae
	 *
	 * @return Administracao_Model_Cnae
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Cnae();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_cnae'));
	
		$arrBind = array();
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsultaCnae($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
			->order(array('co_cnae'));
	
		$arrBind = array();
		
		if(array_key_exists("co_cnae",$arrParam) && $arrParam['co_cnae']) {
			$sql->where('co_cnae = ?');
			$arrBind[] = $arrParam['co_cnae'];
		}
	
		if(array_key_exists("cod_cnae",$arrParam) && $arrParam['cod_cnae']) {
			$sql->where('cod_cnae = ?');
			$arrBind[] = $arrParam['cod_cnae'];
		}

		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}

	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_cnae'));

		$arrBind = array();

		if(array_key_exists("co_cnae",$arrParam) && $arrParam['co_cnae']) {
			$sql->where('co_cnae = ?');
			$arrBind[] = $arrParam['co_cnae'];
		}

		if(array_key_exists("cod_cnae",$arrParam) && $arrParam['cod_cnae']) {
			$sql->where('cod_cnae = ?');
			$arrBind[] = $arrParam['cod_cnae'];
		}

		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}


}

