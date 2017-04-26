<?php

class Financeiro_Model_Boleto extends Financeiro_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_boleto';
	
	/**
	 * Instância da classe Financeiro_Model_Boleto
	 *
	 * @return Financeiro_Model_Boleto
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Financeiro_Model_Boleto();
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

