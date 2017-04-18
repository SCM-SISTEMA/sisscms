<?php
	class Model_EmailUsuario extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'sisscm';
		protected $_name = 'rl_email_usuario';
		
		/**
		 * Instância da classe Model_EmailUsuario
		 *
		 * @return Model_EmailUsuario
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_EmailUsuario();
			}
			
			// Setar role de permissão
			//self::$_instance->setRole();			
			
			return self::$_instance;
		}
		
		/**
		 * Informações de um determinado usuário e seu perfil
		 *
		 * @param string $nu_cpf
		 * @return array
		 */
		public function getUsuarioEmail($ds_email) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('co_usuario', 'no_usuario','ds_login','st_registro_ativo'),$this->_schema)
			->order(array('no_usuario'));
			$arrBind = array();
												
			if($cpf) {
				$sql->where('UPPER(ds_email) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $ds_email;
			}
												
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		/**
		 * Consulta de usuários
		 *
		 * @param array $arrParam
		 * @return array
		 */
		public function getConsulta($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->order(array('no_usuario'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
		if(array_key_exists("co_usuario",$arrParam) && $arrParam['co_usuario']) {
				$sql->where($this->_name.'.co_usuario = ?');
				$arrBind[] = $arrParam['co_usuario'];
			}
			
			if(array_key_exists("ds_login",$arrParam) && $arrParam['ds_login']) {
				$sql->where('UPPER(ds_login) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['ds_login'];
			}
			
			if(array_key_exists("ds_senha",$arrParam) && $arrParam['ds_senha']) {
				$sql->where('ds_senha LIKE ? || \'%\'');
				$arrBind[] = $arrParam['ds_senha'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('co_usuario', 'no_usuario','ds_login','st_registro_ativo'),$this->_schema)
			->order(array('no_usuario'));
			$arrBind = array();
		
			if(array_key_exists("co_usuario",$arrParam) && $arrParam['co_usuario']) {
				$sql->where($this->_name.'.co_usuario = ?');
				$arrBind[] = $arrParam['co_usuario'];
			}
		
			if(array_key_exists("st_registro_ativo",$arrParam) && $arrParam['st_registro_ativo']) {
				$sql->where('st_registro_ativo = ?');
				$arrBind[] = $arrParam['st_registro_ativo'];
			}
		
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaUsuario($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('co_usuario', 'no_usuario','ds_email','st_registro_ativo'),$this->_schema)
			->order(array('no_usuario'));
		
			$arrBind = array();
		
			if(array_key_exists("co_usuario",$arrParam) && $arrParam['co_usuario']) {
				$sql->where('co_usuario = ?');
				$arrBind[] = $arrParam['co_usuario'];
			}
		
			if(array_key_exists("st_registro_ativo",$arrParam) && $arrParam['st_registro_ativo']) {
				$sql->where('st_registro_ativo = ?');
				$arrBind[] = $arrParam['st_registro_ativo'];
			}
		
			if(array_key_exists("no_usuario",$arrParam) && $arrParam['no_usuario']) {
				$sql->where('UPPER(no_usuario) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['no_usuario'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		/**
		 * Acesso do usuário
		 *
		 * @param string $ds_usuario
		 * @param string $co_senha
		 * @return array
		 */
		public function getAcessoUsuario($ds_login, $co_senha) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('co_usuario', 'ds_login', 'id_perfil'),$this->_schema)
				->joinLeft('tb_perfil', 'tb_perfil.id_perfil = '.$this->_name.'.id_perfil', array('no_perfil'), $this->_schema)
												->where('UPPER(ds_login) = UPPER(?)')
												->where('ds_senha = md5(?)')
												;
			try {
				return $this->getAdapter()->fetchRow($sql,array($ds_login,$co_senha));
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		/**
		 * Consulta de existência de email para outro cpf
		 *
		 * @param string $ds_email
		 * @param string $nu_cpf
		 * @return unknown
		 */
		public function getVerificaEmail($ds_email) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('no_usuario','ds_email'),$this->_schema)
												->where('UPPER(ds_email) = UPPER(?)')
												;
												
			try {
				return $this->getAdapter()->fetchAll($sql,array($ds_email));
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}

		/**
		 * Consulta de existência de email para outro cpf
		 *
		 * @param string $ds_email
		 * @param string $nu_cpf
		 * @return unknown
		 */
		public function verificaCpfEmail($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('no_usuario','ds_email'),$this->_schema)
			->where('UPPER(ds_email) like ?')
			->orWhere('nu_cpf = ?')
			->orWhere('UPPER(no_usuario) like ?')
			;
		//print_r($arrParam);die;
			try {
				return $this->getAdapter()->fetchAll($sql,$arrParam);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
				
	}