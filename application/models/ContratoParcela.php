<?php
	class Model_ContratoParcela extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sisscm';
		protected $_name = 'tb_contrato_parcela';
		
		/**
		 * Instância da classe Model_ContratoParcela
		 *
		 * @return Model_ContratoParcela
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_ContratoParcela();
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
			->order(array('co_contrato_parcela'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_contrato_parcela",$arrParam) && $arrParam['co_contrato_parcela']) {
				$sql->where('co_contrato_parcela = ?');
				$arrBind[] = $arrParam['co_contrato_parcela'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('dt_vencimento desc'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_contrato_parcela",$arrParam) && $arrParam['co_contrato_parcela']) {
				$sql->where('co_contrato_parcela = ?');
				$arrBind[] = $arrParam['co_contrato_parcela'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		public function getConsultaVencidos($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('dt_vencimento DESC'));
			
			$sql->where("dt_vencimento < '".Zend_Date::now()->toString("Y-M-d")."'");
			$sql->where("st_quitacao != 'S'");
			
			try {
				return $this->getAdapter()->fetchAll($sql);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		public function getConsultaVencendo($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('dt_vencimento DESC'));
			
			$sql->where("dt_vencimento = '".Zend_Date::now()->toString("Y-M-d")."'");
			$sql->where("st_quitacao != 'S'");
			try {
				return $this->getAdapter()->fetchAll($sql);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}