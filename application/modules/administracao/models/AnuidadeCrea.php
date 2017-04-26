<?php

class Administracao_Model_AnuidadeCrea extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_anuidade_crea';
	
	/**
	 * Instância da classe Administracao_Model_AnuidadeCrea
	 *
	 * @return Administracao_Model_AnuidadeCrea
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_AnuidadeCrea();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_responsavel_tecnico'));
	
		$arrBind = array();
		
		if(array_key_exists("co_responsavel_tecnico",$arrParam) && $arrParam['co_responsavel_tecnico']) {
			$sql->where('co_responsavel_tecnico = ?');
			$arrBind[] = $arrParam['co_responsavel_tecnico'];
		}
		
		if(array_key_exists("nu_ano",$arrParam) && $arrParam['nu_ano']) {
			$sql->where('nu_ano = ?');
			$arrBind[] = $arrParam['nu_ano'];
		}
		
		if(array_key_exists("tp_registro_crea",$arrParam) && $arrParam['tp_registro_crea']) {
			$sql->where('tp_registro_crea = ?');
			$arrBind[] = $arrParam['tp_registro_crea'];
		}
	
		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_responsavel_tecnico'));
	
		$arrBind = array();
		
		if(array_key_exists("co_responsavel_tecnico",$arrParam) && $arrParam['co_responsavel_tecnico']) {
			$sql->where('co_responsavel_tecnico = ?');
			$arrBind[] = $arrParam['co_responsavel_tecnico'];
		}
		
		if(array_key_exists("nu_ano",$arrParam) && $arrParam['nu_ano']) {
			$sql->where('nu_ano = ?');
			$arrBind[] = $arrParam['nu_ano'];
		}
		
		if(array_key_exists("tp_registro_crea",$arrParam) ) {
			$sql->where('tp_registro_crea = ?');
			$arrBind[] = $arrParam['tp_registro_crea'];
		}
		
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

