<?php
	class Model_Cliente extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'gst';
		protected $_name = 'clientes';
		
		/**
		 * Instância da classe Model_Cliente
		 *
		 * @return Model_Cliente
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Cliente();
			}
			
			// Setar role de permissão
			//self::$_instance->setRole();			
			
			return self::$_instance;
		}
		
		/**
		 * Consulta de CHAVES
		 *
		 * @param array $arrParam
		 * @return array
		 */
		public function getConsulta($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
			->order(array('nome'));
			
			$arrBind = array();
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where($this->_name.'.id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("nome",$arrParam) && $arrParam['nome']) {
				$sql->where('UPPER(nome) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['nome'];
			}
			
			if(array_key_exists("id_provedor",$arrParam) && $arrParam['id_provedor']) {
				$sql->where('id_provedor = ?');
				$arrBind[] = $arrParam['id_provedor'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
			->order(array('nome'));
			
			$arrBind = array();
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("id_provedor",$arrParam) && $arrParam['id_provedor']) {
				$sql->where('id_provedor = ?');
				$arrBind[] = $arrParam['id_provedor'];
			}
			
			if(array_key_exists("nome",$arrParam) && $arrParam['nome']) {
				$sql->where('UPPER(nome) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['nome'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}