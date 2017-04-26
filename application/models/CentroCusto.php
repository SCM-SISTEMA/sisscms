<?php
	class Model_CentroCusto extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sisscm';
		protected $_name = 'tb_centro_custo';
		
		/**
		 * Instância da classe Model_CentroCusto
		 *
		 * @return Model_CentroCusto
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_CentroCusto();
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
			->order(array('co_centro_custo'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_centro_custo",$arrParam) && $arrParam['co_centro_custo']) {
				$sql->where('co_centro_custo = ?');
				$arrBind[] = $arrParam['co_centro_custo'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('co_centro_custo desc'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_centro_custo",$arrParam) && $arrParam['co_centro_custo']) {
				$sql->where('co_centro_custo = ?');
				$arrBind[] = $arrParam['co_centro_custo'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}