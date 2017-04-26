<?php
	class Model_PerfilFuncionalidade extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'gst';
		protected $_name = 'rl_perfil_funcionalidade';
		
		/**
		 * Instância da classe Model_PerfilFuncionalidade
		 *
		 * @return Model_PerfilFuncionalidade
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_PerfilFuncionalidade();
			}
			
			// Setar role de permissão
			//self::$_instance->setRole();			
			
			return self::$_instance;
		}
		
		/**
		 * Consulta de funcionalidades de um determinado perfil
		 *
		 * @param int $co_perfil
		 * @return array
		 */
		public function getConsultaAll($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema);

			$arrBind = array();
			if(key_exists('co_perfil',$arrParam) && $arrParam['co_perfil']){
				$sql->where($this->_name.'.co_perfil =?');
				$arrBind[] = $arrParam['co_perfil'];
			}
			
			if(key_exists('co_funcionalidade',$arrParam) && $arrParam['co_funcionalidade']){
				$sql->where($this->_name.'.co_funcionalidade =?');
				$arrBind[] = $arrParam['co_funcionalidade'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
			}
		}
		
		/**
		 * Consulta de funcionalidades de um determinado perfil
		 *
		 * @param int $co_perfil
		 * @return array
		 */
		public function getConsultaFuncionalidadePerfil($co_perfil) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
												->join('funcionalidade','funcionalidade.co_funcionalidade = '.$this->_name.'.co_funcionalidade',array('co_funcionalidade','sg_funcionalidade','ds_principal','ds_caminho'),$this->_schema)												
												->where('rl_perfil_funcionalidade.co_perfil = ?')
												->order(array('ds_principal'));
			
			try {
				return $this->getAdapter()->fetchAll($sql,array($co_perfil));
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
			}
		}
		/**
		 * Consulta de funcionalidades de um determinado perfil
		 *
		 * @param int $co_perfil
		 * @return array
		 */
		public function getConsultaPerfilFuncionalidade($co_funcionalidade) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('co_perfil_funcionalidade', 'co_perfil', 'co_funcionalidade'),$this->_schema)
												->join('tb_perfil','tb_perfil.co_perfil = '.$this->_name.'.co_perfil',array('no_perfil'),$this->_schema)												
												->where('rl_perfil_funcionalidade.co_funcionalidade = ?')
												->order(array('no_perfil'));
			
			try {
				return $this->getAdapter()->fetchAll($sql,array($co_funcionalidade));
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
			}
		}
	}