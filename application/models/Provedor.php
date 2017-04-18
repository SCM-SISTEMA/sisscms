<?php
	class Model_Provedor extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'gst';
		protected $_name = 'provedor';
		
		/**
		 * Instância da classe Model_Categoria
		 *
		 * @return Model_Provedor
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Provedor();
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
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('nome'));
			
			$arrBind = array();
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
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