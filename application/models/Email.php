<?php
	class Model_Email extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sisscm';
		protected $_name = 'tb_email';
		
		/**
		 * Instância da classe Model_Email
		 *
		 * @return Model_Email
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Email();
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
				->joinLeft('rl_email_usuario','rl_email_usuario.co_email = '.$this->_name.'.co_email',array(),$this->_schema)
				->joinLeft('rl_email_anexo','rl_email_anexo.co_email = '.$this->_name.'.co_email',array('ds_anexo'),$this->_schema)
				->joinLeft('tb_usuario','tb_usuario.co_usuario = rl_email_usuario.co_usuario',array('no_usuario', 'ds_login'),$this->_schema)
			->order(array('co_email'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_email",$arrParam) && $arrParam['co_email']) {
				$sql->where('co_email = ?');
				$arrBind[] = $arrParam['co_email'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
				->joinLeft('rl_email_usuario','rl_email_usuario.co_email = '.$this->_name.'.co_email',array(),$this->_schema)
				->joinLeft('rl_email_anexo','rl_email_anexo.co_email = '.$this->_name.'.co_email',array('ds_anexo'),$this->_schema)
				->joinLeft('tb_usuario','tb_usuario.co_usuario = rl_email_usuario.co_usuario',array('no_usuario', 'ds_login'),$this->_schema)
			->order(array('dt_cadastro desc'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("co_usuario",$arrParam) && $arrParam['co_usuario']) {
				$sql->where('rl_email_usuario.co_usuario = ?');
				$arrBind[] = $arrParam['co_usuario'];
			}

			if(array_key_exists("co_email",$arrParam) && $arrParam['co_email']) {
				$sql->where('co_email = ?');
				$arrBind[] = $arrParam['co_email'];
			}

			if(array_key_exists("st_deletado",$arrParam) && $arrParam['st_deletado']) {
				$sql->where('st_deletado IS NOT NULL');
			}

			if(array_key_exists("st_encaminhado",$arrParam) && $arrParam['st_encaminhado']) {
				$sql->where('st_encaminhado IS NOT NULL');
			}

			if(array_key_exists("st_importante",$arrParam) && $arrParam['st_importante']) {
				$sql->where('st_importante IS NOT NULL');
			}

			if(array_key_exists("st_enviado",$arrParam) && $arrParam['st_enviado']) {
				$sql->where('st_enviado IS NOT NULL');
			}

			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		public function getConsultaVencidos($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
				->joinLeft('rl_email_usuario','rl_email_usuario.co_email = '.$this->_name.'.co_email',array(),$this->_schema)
				->joinLeft('rl_email_anexo','rl_email_anexo.co_email = '.$this->_name.'.co_email',array('ds_anexo'),$this->_schema)
				->joinLeft('tb_usuario','tb_usuario.co_usuario = rl_email_usuario.co_usuario',array('no_usuario', 'ds_login'),$this->_schema)
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