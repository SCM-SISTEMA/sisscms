<?php
	class Model_Fase extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sisscm';
		protected $_name = 'tb_fase';
		
		/**
		 * Instância da classe Model_Fase
		 *
		 * @return Model_Fase
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Fase();
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
			->order(array('co_fase'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_fase",$arrParam) && $arrParam['co_fase']) {
				$sql->where('co_fase = ?');
				$arrBind[] = $arrParam['co_fase'];
			}
			
			if(array_key_exists("ds_fase",$arrParam) && $arrParam['ds_fase']) {
				$sql->where('UPPER(ds_fase) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['ds_fase'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('co_fase'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_fase",$arrParam) && $arrParam['co_fase']) {
				$sql->where('co_fase = ?');
				$arrBind[] = $arrParam['co_fase'];
			}
			
			if(array_key_exists("ds_fase",$arrParam) && $arrParam['ds_fase']) {
				$sql->where('UPPER(ds_fase) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['ds_fase'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}