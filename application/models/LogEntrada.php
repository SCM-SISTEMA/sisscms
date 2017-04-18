<?php
	class Model_LogEntrada extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'gst';
		protected $_name = 'tb_log_entrada';
		
		/**
		 * Instância da classe Model_LogEntrada
		 *
		 * @return Model_LogEntrada
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_LogEntrada();
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
			$sql = $this->getAdapter()->select()->from($this->_name,array('id', 'data', 'acao', 'usuario'),$this->_schema)
			->order(array('id'));
			
			$arrBind = array();
			
			//print_r($arrParam);die;
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("data",$arrParam) && $arrParam['data']) {
				$sql->where('data =  ? ');
				$arrBind[] = $arrParam['data'];
			}
			
			if(array_key_exists("usuario",$arrParam) && $arrParam['usuario']) {
				$sql->where('usuario =  ? ');
				$arrBind[] = $arrParam['usuario'];
			}
			
			
			if(array_key_exists("acao",$arrParam) && $arrParam['acao']) {
				$sql->where('UPPER(acao) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['acao'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			
			$sql = $this->getAdapter()->select()->from($this->_name,array('id', 'data', 'acao', 'usuario'),$this->_schema)
			->order(array('data'));
			
			$arrBind = array();
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			
			if(array_key_exists("usuario",$arrParam) && $arrParam['usuario']) {
				$sql->where('usuario =  ? ');
				$arrBind[] = $arrParam['usuario'];
			}
			
			if(array_key_exists("dt_inicio",$arrParam) && $arrParam['dt_inicio'] != null && $arrParam['dt_fim'] == null) {
				$sql->where('DATE(data) = ?');
				$arrBind[] = $arrParam['dt_inicio'];
			}
			
			if(array_key_exists("dt_inicio",$arrParam) && $arrParam['dt_inicio'] != null && array_key_exists("dt_fim",$arrParam) && $arrParam['dt_fim'] != null ) {
				$sql->where('DATE(data) between ? AND ?');
				$arrBind[] = retornaYmd( $arrParam['dt_inicio'] );
				$arrBind[] = retornaYmd( $arrParam['dt_fim'] );
			}
			
			
			if(array_key_exists("acao",$arrParam) && $arrParam['acao']) {
				$sql->where('UPPER(acao) LIKE UPPER(?) || \'%\'');
				$arrBind[] = $arrParam['acao'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}