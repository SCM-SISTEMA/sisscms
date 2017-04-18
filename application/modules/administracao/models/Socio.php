<?php

class Administracao_Model_Socio extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_socio';
	
	/**
	 * Instância da classe Administracao_Model_Socio
	 *
	 * @return Administracao_Model_Socio
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Socio();
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
	public function getConsultaAllSocios($arrParam) {

		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('no_socio'));
	
		$arrBind = array();
	
		if(key_exists('co_socio',$arrParam) && $arrParam['co_socio']){
			$sql->where($this->_name.'.co_socio = ?');
			$arrBind[] = $arrParam['co_socio'];
		}
	
		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_pessoa_juridica = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}
	
	/**
	 * Consulta de Sócio
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaSocio($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('co_socio'),$this->_schema)
		->order(array('no_socio'));
	
		$arrBind = array();
	
		if(key_exists('co_socio',$arrParam) && $arrParam['co_socio']){
			$sql->where($this->_name.'.co_socio = ?');
			$arrBind[] = $arrParam['co_socio'];
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
	 * Consulta de Sócio
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('co_socio'),$this->_schema)
		->join('tb_pessoa_juridica', $this->_name.'.co_pessoa_juridica = tb_pessoa_juridica.co_pessoa_juridica', array('*'))
		->order(array('no_razao_social'));
	
		$arrBind = array();
	
		if(key_exists('co_socio',$arrParam) && $arrParam['co_socio']){
			$sql->where($this->_name.'.co_socio = ?');
			$arrBind[] = $arrParam['co_socio'];
		}
	
		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_pessoa_juridica = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}

}

