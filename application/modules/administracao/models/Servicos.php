<?php

class Administracao_Model_Servicos extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_servico';
	
	/**
	 * Instância da classe Administracao_Model_Servicos
	 *
	 * @return Administracao_Model_Servicos
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Servicos();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_servico'));

		$arrBind = array();

		if(key_exists('tp_servico',$arrParam) && $arrParam['tp_servico']){
			$sql->where($this->_name.'.tp_servico = ?');
			$arrBind[] = $arrParam['tp_servico'];
		}

		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_modelo_contrato_servico', 'tb_modelo_contrato_servico.co_servico = '.$this->_name.'.co_servico', array('ds_modelo', 'co_modelo_contrato_servico'), $this->_schema)
		->order(array('co_servico'));
	
		$arrBind = array();
		
		if(array_key_exists("co_servico",$arrParam) && $arrParam['co_servico']) {
			$sql->where($this->_name.'.co_servico = ?');
			$arrBind[] = $arrParam['co_servico'];
		}
		
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

