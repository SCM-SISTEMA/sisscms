<?php

class Administracao_Model_AreaTecnica extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_area_tecnica';
	
	/**
	 * Instância da classe Administracao_Model_AreaTecnica
	 *
	 * @return Administracao_Model_AreaTecnica
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_AreaTecnica();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	/**
	 * Consulta de todas Áreas Técnicas
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('ds_area_tecnica'));
	
		$arrBind = array();
	
		if(key_exists('co_area_tecnica',$arrParam) && $arrParam['co_area_tecnica']){
			$sql->where($this->_name.'.co_area_tecnica = ?');
			$arrBind[] = $arrParam['co_area_tecnica'];
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}
	
	/**
	 * Consulta de Área Técnica
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('ds_area_tecnica'));
	
		$arrBind = array();
	
		if(key_exists('co_area_tecnica',$arrParam) && $arrParam['co_area_tecnica']){
			$sql->where($this->_name.'.co_area_tecnica = ?');
			$arrBind[] = $arrParam['co_area_tecnica'];
		}
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}
	
}

