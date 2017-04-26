<?php

class Administracao_Model_PlanosServicos extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'rl_plano_servico';
	
	/**
	 * Instância da classe Administracao_Model_PlanosServicos
	 *
	 * @return Administracao_Model_PlanosServicos
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_PlanosServicos();
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
		->order(array('co_plano'));
	
		$arrBind = array();
	
		if(key_exists('co_plano',$arrParam) && $arrParam['co_plano']){
			$sql->where($this->_name.'.co_plano = ?');
			$arrBind[] = $arrParam['co_plano'];
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
		->order(array('ds_plano'));
	
		$arrBind = array();
	
		if(key_exists('co_plano',$arrParam) && $arrParam['co_plano']){
			$sql->where($this->_name.'.co_plano = ?');
			$arrBind[] = $arrParam['co_plano'];
		}
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}
	
}

