<?php

class Administracao_Model_Estados extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_estados';
	
	/**
	 * Instância da classe Administracao_Model_Estados
	 *
	 * @return Administracao_Model_Estados
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Estados();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	
	/**
	 * Consulta de Estados
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaEstado($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('no_estado'));
	
		$arrBind = array();
	
		if(key_exists('co_estado',$arrParam) && $arrParam['co_estado']){
			$sql->where($this->_name.'.co_estado = ?');
			$arrBind[] = $arrParam['co_estado'];
		}

		if(key_exists('sg_estado',$arrParam) && $arrParam['sg_estado']){
			$sql->where($this->_name.'.sg_estado = ?');
			$arrBind[] = $arrParam['sg_estado'];
		}

		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_pessoa_juridica = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}
	
	/**
	 * Consulta de Estado
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('no_estado'));
	
		$arrBind = array();
	
		if(key_exists('co_estado',$arrParam) && $arrParam['co_estado']){
			$sql->where($this->_name.'.co_estado = ?');
			$arrBind[] = $arrParam['co_estado'];
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}

}

