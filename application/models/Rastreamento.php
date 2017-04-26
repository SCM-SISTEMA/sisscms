<?php

class Model_Rastreamento extends Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_rastreamento';
	
	/**
	 * Instância da classe Model_Rastreamento
	 *
	 * @return Model_Rastreamento
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Model_Rastreamento();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAll($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		// 		->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
		->order(array('co_rastreamento')
		);

		$arrBind = array();

		if(array_key_exists("no_rastreamento",$arrParam) && $arrParam['no_rastreamento']) {
			$sql->where('no_rastreamento like ?');
			$arrBind[] = '%'.$arrParam['no_rastreamento'].'%';
		}

		if(array_key_exists("co_rastreamento",$arrParam) && $arrParam['co_rastreamento']) {
			$sql->where('co_rastreamento = ?');
			$arrBind[] = $arrParam['co_rastreamento'];
		}

		if(array_key_exists("nu_rastreamento",$arrParam) && $arrParam['nu_rastreamento']) {
			$sql->where('nu_rastreamento = ?');
			$arrBind[] = $arrParam['nu_rastreamento'];
		}

		if(array_key_exists("nu_cep",$arrParam) && $arrParam['nu_cep']) {
			$sql->where('nu_cep = ?');
			$arrBind[] = $arrParam['nu_cep'];
		}

		if(key_exists('dt_inicio',$arrParam) && !empty($arrParam['dt_inicio']) && key_exists('dt_final',$arrParam) && !empty($arrParam['dt_final'])){
			$sql->where($this->_name.'.dt_cadastro BETWEEN ? and ?');
			$arrBind[] = $arrParam['dt_inicio'];
			$arrBind[] = $arrParam['dt_final'];
		}

		if(array_key_exists("st_registro_ativo",$arrParam) && $arrParam['st_registro_ativo']) {
			$sql->where('st_registro_ativo = ?');
			$arrBind[] = $arrParam['st_registro_ativo'];
		}else{
			$sql->where('st_registro_ativo = ?');
			$arrBind[] = ST_REGISTRO_ATIVO;
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
		->order(array('dt_cadastro'));
	
		$arrBind = array();

		if(array_key_exists("co_rastreamento",$arrParam) && $arrParam['co_rastreamento']) {
			$sql->where('co_rastreamento = ?');
			$arrBind[] = $arrParam['co_rastreamento'];
		}
		
	
		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

