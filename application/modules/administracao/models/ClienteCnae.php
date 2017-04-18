<?php

class Administracao_Model_ClienteCnae extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'rl_cnae_pessoa_juridica';
	
	/**
	 * Instância da classe Administracao_Model_ClienteCnae
	 *
	 * @return Administracao_Model_ClienteCnae
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_ClienteCnae();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
				->joinLeft('tb_cnae', $this->_name.'.co_cnae = tb_cnae.co_cnae', array('*'), $this->_schema)
		->order(array('ds_cnae'));
	
		$arrBind = array();
		
		if(key_exists('co_cnae',$arrParam) && $arrParam['co_cnae']){
			$sql->where($this->_name.'.co_cnae = ?');
			$arrBind[] = $arrParam['co_cnae'];
		}
		
		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_pessoa_juridica = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getCnaeCliente($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('co_cnae'),$this->_schema);
	
		$arrBind 	= array();
		$arrCoCnae 	= array();
		
		if(key_exists('co_cnae',$arrParam) && $arrParam['co_cnae']){
			$sql->where($this->_name.'.co_cnae = ?');
			$arrBind[] = $arrParam['co_cnae'];
		}
		
		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_pessoa_juridica = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}
	
		try {
			$arrCnae = $this->getAdapter()->fetchAll($sql,$arrBind);
			
			foreach ($arrCnae as $coCnae){
				$arrCoCnae[] = $coCnae['co_cnae'];
			}
			
			return $arrCoCnae;
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}

}

