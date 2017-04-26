<?php
	class Model_Comentarios extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'gst';
		protected $_name = 'cht_chat';
		
		/**
		 * Instância da classe Model_Comentarios
		 *
		 * @return Model_Comentarios
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Comentarios();
			}
			
			// Setar role de permissão
			//self::$_instance->setRole();			
			
			return self::$_instance;
		}
		
		/**
		 * Consulta de Comentarios
		 *
		 * @param array $arrParam
		 * @return array
		 */
		public function getConsulta($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->joinLeft('usuario', 'usuario.id = '.$this->_name.'.username', array('nome'), $this->_schema)
			->order(array('id'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("timestamp",$arrParam) && $arrParam['timestamp']) {
				$sql->where('timestamp = ?');
				$arrBind[] = $arrParam['timestamp'];
			}
			
			if(array_key_exists("usuario",$arrParam) && $arrParam['usuario']) {
				$sql->where('usuario = ?');
				$arrBind[] = $arrParam['usuario'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
			->joinLeft('usuario', 'usuario.id = '.$this->_name.'.username', array('nome'), $this->_schema)
			->order(array('insertDate asc'))
			->limitPage(0, 30);
			
			$arrBind = array();
			
			# Traz apenas as conversas do dia atual
			$sql->where('DATE( `insertDate` ) = CURDATE()');
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("timestamp",$arrParam) && $arrParam['timestamp']) {
				$sql->where('timestamp = ?');
				$arrBind[] = $arrParam['timestamp'];
			}
			
			if(array_key_exists("usuario",$arrParam) && $arrParam['usuario']) {
				$sql->where('usuario = ?');
				$arrBind[] = $arrParam['usuario'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}