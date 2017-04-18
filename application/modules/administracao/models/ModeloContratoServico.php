<?php

class Administracao_Model_ModeloContratoServico extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_modelo_contrato_servico';
	
	/**
	 * Instância da classe Administracao_Model_ModeloContratoServico
	 *
	 * @return Administracao_Model_ModeloContratoServico
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_ModeloContratoServico();
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
		->joinLeft('tb_servico', 'tb_servico.co_servico = '.$this->_name.'.co_servico', array('no_servico'), $this->_schema)
		->order(array('co_modelo_contrato_servico'));
	
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

