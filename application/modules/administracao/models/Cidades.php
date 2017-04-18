<?php

class Administracao_Model_Cidades extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_cidades';
	
	/**
	 * Instância da classe Administracao_Model_Cidades
	 *
	 * @return Administracao_Model_Cidades
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Cidades();
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
		->order(array('no_cidade'));
	
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

