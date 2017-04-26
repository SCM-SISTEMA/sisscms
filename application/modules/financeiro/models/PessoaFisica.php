<?php

class Administracao_Model_PessoaFisica extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_pessoa_fisica';
	
	/**
	 * Instância da classe Administracao_Model_PessoaFisica
	 *
	 * @return Administracao_Model_PessoaFisica
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_PessoaFisica();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	/**
	 * Consulta de Pessoa Física
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('no_pessoa_fisica'));
	
		$arrBind = array();
	
		if(key_exists('co_pessoa_fisica',$arrParam) && $arrParam['co_pessoa_fisica']){
			$sql->where('co_pessoa_fisica = ?');
			$arrBind[] = $arrParam['co_pessoa_fisica'];
		}
	
		if(key_exists('no_pessoa_fisica',$arrParam) && $arrParam['no_pessoa_fisica']){
			$sql->where("UPPER(no_pessoa_fisica)  LIKE UPPER(?)");
			$arrBind[] = '%'.$arrParam['no_pessoa_fisica'].'%';
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}
	
	/**
	 * Consulta de Pessoa Física
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->order(array('no_pessoa_fisica'));
	
		$arrBind = array();
	
		if(key_exists('co_pessoa_fisica',$arrParam) && $arrParam['co_pessoa_fisica']){
			$sql->where('co_pessoa_fisica = ?');
			$arrBind[] = $arrParam['co_pessoa_fisica'];
		}
	
		if(key_exists('no_pessoa_fisica',$arrParam) && $arrParam['no_pessoa_fisica']){
			$sql->where("UPPER(no_pessoa_fisica)  LIKE UPPER(?)");
			$arrBind[] = '%'.$arrParam['no_pessoa_fisica'].'%';
		}
		
		debug($sql->__toString(), 1);
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}

}

