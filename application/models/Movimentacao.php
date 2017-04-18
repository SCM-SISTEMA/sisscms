<?php
	class Model_Movimentacao extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sisscm';
		protected $_name = 'tb_movimentacao';
		
		/**
		 * Instância da classe Model_Movimentacao
		 *
		 * @return Model_Movimentacao
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Movimentacao();
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
			->order(array('co_movimentacao'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_movimentacao",$arrParam) && $arrParam['co_movimentacao']) {
				$sql->where('co_movimentacao = ?');
				$arrBind[] = $arrParam['co_movimentacao'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('co_movimentacao'));
			$sql->where("st_ativo = 'S'");
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_movimentacao",$arrParam) && $arrParam['co_movimentacao']) {
				$sql->where('co_movimentacao = ?');
				$arrBind[] = $arrParam['co_movimentacao'];
			}
			
			if(array_key_exists("tp_movimentacao",$arrParam) && $arrParam['tp_movimentacao']) {
				$sql->where('tp_movimentacao = ?');
				$arrBind[] = $arrParam['tp_movimentacao'];
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
			$sql->where("st_ativo = 'S'");
			
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