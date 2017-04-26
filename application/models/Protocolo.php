<?php
	class Model_Protocolo extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sisscm';
		protected $_name = 'tb_protocolo';
		
		/**
		 * Instância da classe Model_Protocolo
		 *
		 * @return Model_Protocolo
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Protocolo();
			}
			
			// Setar role de permissão
			//self::$_instance->setRole();			
			
			return self::$_instance;
		}
		
		/**
		 * Consulta de Protocolo
		 *
		 * @param array $arrParam
		 * @return array
		 */
		public function getConsulta($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('co_protocolo', 'nu_protocolo'),$this->_schema)
			->order(array('nu_protocolo'));
			
			$arrBind = array();
			
			if(array_key_exists("co_protocolo",$arrParam) && $arrParam['co_protocolo']) {
				$sql->where('co_protocolo = ?');
				$arrBind[] = $arrParam['co_protocolo'];
			}
			
			if(array_key_exists("protocolo",$arrParam) && $arrParam['protocolo']) {
				$sql->where('protocolo LIKE ? || \'%\'');
				$arrBind[] = $arrParam['protocolo'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('id', 'protocolo'),$this->_schema)
			->order(array('protocolo'));
			
			$arrBind = array();
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("protocolo",$arrParam) && $arrParam['protocolo']) {
				$sql->where('protocolo LIKE ? || \'%\'');
				$arrBind[] = $arrParam['protocolo'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		public function getUltimoProtocolo() {
			$sql = $this->getAdapter()->select()->from($this->_name,array('max( co_protocolo ) as co_protocolo'),$this->_schema);
		
			try {
				return $this->getAdapter()->fetchRow($sql);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}