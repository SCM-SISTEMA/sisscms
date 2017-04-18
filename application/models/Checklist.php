<?php
	class Model_Checklist extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sisscm';
		protected $_name = 'tb_checklist';
		
		/**
		 * Instância da classe Model_Checklist
		 *
		 * @return Model_Checklist
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Checklist();
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
			->joinLeft('tb_fase', 'tb_fase.co_fase = '.$this->_name.'.co_fase', array('ds_fase'), $this->_schema)
			->order(array('co_checklist'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_checklist",$arrParam) && $arrParam['co_checklist']) {
				$sql->where('co_checklist = ?');
				$arrBind[] = $arrParam['co_checklist'];
			}
			
			if(array_key_exists("ds_checklist",$arrParam) && $arrParam['ds_checklist']) {
				$sql->where('UPPER(ds_checklist) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['ds_checklist'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->joinLeft('tb_fase', 'tb_fase.co_fase = '.$this->_name.'.co_fase', array('ds_fase'), $this->_schema)
			->order(array('co_checklist'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_checklist",$arrParam) && $arrParam['co_checklist']) {
				$sql->where('co_checklist = ?');
				$arrBind[] = $arrParam['co_checklist'];
			}
			
			if(array_key_exists("co_fase",$arrParam) && $arrParam['co_fase']) {
				$sql->where($this->_name.'.co_fase = ?');
				$arrBind[] = $arrParam['co_fase'];
			}
			
			if(array_key_exists("ds_checklist",$arrParam) && $arrParam['ds_checklist']) {
				$sql->where('UPPER(ds_checklist) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['ds_checklist'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}