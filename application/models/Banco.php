<?php
	class Model_Banco extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sisscm';
		protected $_name = 'tb_banco';
		
		/**
		 * Instância da classe Model_Banco
		 *
		 * @return Model_Banco
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Banco();
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
			->order(array('co_banco'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_banco",$arrParam) && $arrParam['co_banco']) {
				$sql->where('co_banco = ?');
				$arrBind[] = $arrParam['co_banco'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('co_banco'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_banco",$arrParam) && $arrParam['co_banco']) {
				$sql->where('co_banco = ?');
				$arrBind[] = $arrParam['co_banco'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}