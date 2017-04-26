<?php
	class Model_gravacoes extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sgs';
		protected $_name = 'cdr';
		
		/**
		 * Instância da classe Model_gravacoes
		 *
		 * @return Model_gravacoes
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_gravacoes();
			}
			
			// Setar role de permissão
			//self::$_instance->setRole();			
			
			return self::$_instance;
		}
		
		/**
		 * Consulta de gravacoes
		 *
		 * @param array $arrParam
		 * @return array
		 */
		public function getConsulta($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('calldate'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("titulo",$arrParam) && $arrParam['titulo']) {
				$sql->where('UPPER(titulo) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['titulo'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('calldate'))
			->limit(100);
			
			$arrBind = array();
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("titulo",$arrParam) && $arrParam['titulo']) {
				$sql->where("UPPER(titulo) LIKE UPPER(?)");
				$arrBind[] = '%'.$arrParam['titulo'].'%';
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}