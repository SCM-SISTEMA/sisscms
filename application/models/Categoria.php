<?php
	class Model_Categoria extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'gst';
		protected $_name = 'categoria';
		
		/**
		 * Instância da classe Model_Categoria
		 *
		 * @return Model_Categoria
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Categoria();
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
			$sql = $this->getAdapter()->select()->from($this->_name,array('id', 'titulo'),$this->_schema)
			->order(array('titulo'));
			
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
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('id', 'titulo'),$this->_schema)
			->order(array('titulo'));
			
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