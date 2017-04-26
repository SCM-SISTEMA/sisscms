<?php

class Administracao_Model_Clientes extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_clientes';
	
	/**
	 * Instância da classe Administracao_Model_Clientes
	 *
	 * @return Administracao_Model_Clientes
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Clientes();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	/**
	 * Consulta de Sócio
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('co_pessoa_juridica'));
	
		$arrBind = array();
	
		if(key_exists('co_cidade',$arrParam) && $arrParam['co_cidade']){
			$sql->where($this->_name.'.co_cidade = ?');
			$arrBind[] = $arrParam['co_cidade'];
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}
	
}

