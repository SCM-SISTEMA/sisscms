<?php

class Administracao_Model_PessoaJuridica extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_pessoa_juridica';
	
	/**
	 * Instância da classe Administracao_Model_PessoaJuridica
	 *
	 * @return Administracao_Model_PessoaJuridica
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_PessoaJuridica();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	/**
	 * Consulta de Pessoa Jurídica
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_contador', $this->_name.'.co_pessoa_juridica = tb_responsavel_tecnico.co_pessoa_juridica', array('*'))
		->order(array('no_razao_social'));
	
		$arrBind = array();
	
		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_pessoa_juridica = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}
	
		if(key_exists('no_pessoa_juridica',$arrParam) && $arrParam['no_pessoa_juridica']){
			$sql->where("UPPER(no_pessoa_juridica)  LIKE UPPER(?)");
			$arrBind[] = '%'.$arrParam['no_pessoa_juridica'].'%';
		}
		debug($sql->__toString(), 1);
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}
	
	/**
	 * Consulta de Pessoa Jurídica
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_contador', $this->_name.'.co_pessoa_juridica = tb_contador.co_pessoa_juridica', array('co_contador', 'no_contador', 'nu_telefone as nu_tel_contador', 'ds_skypemsn as ds_skypemsn_contador', 'ds_email as ds_email_contador'))
		->order(array('no_razao_social'));
	
		$arrBind = array();
	
		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_pessoa_juridica = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}
	
		if(key_exists('no_pessoa_juridica',$arrParam) && $arrParam['no_pessoa_juridica']){
			$sql->where("UPPER(no_pessoa_juridica)  LIKE UPPER(?)");
			$arrBind[] = '%'.$arrParam['no_pessoa_juridica'].'%';
		}
		
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}

}

