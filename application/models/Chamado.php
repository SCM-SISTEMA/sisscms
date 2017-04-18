<?php
	class Model_Chamado extends Model_Db {
		private static $_instance = null;
		protected $_schema = 'gst';
		protected $_name = 'chamado';
		
		/**
		 * Instância da classe Model_Chamado
		 *
		 * @return Model_Chamado
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Model_Chamado();
			}
			
			// Setar role de permissão
			//self::$_instance->setRole();			
			
			return self::$_instance;
		}
		
		/**
		 * Consulta de usuários
		 *
		 * @param array $arrParam
		 * @return array
		 */
		public function getConsulta($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('id', 'protocolo', 'data', 'hora', 'status', 'status.imagem', 'id_provedor', 'provedor.nome as no_provedor', 'id_cliente', 'clientes.nome as no_cliente', 'id_categoria', 'categoria.titulo as ds_categoria', 'prob_info', 'prob_const', 'agendamento', 'obs', 'emails', 'proprietario'),$this->_schema)
			->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
			->joinLeft('clientes', 'clientes.id = '.$this->_name.'.id_cliente', array('nome as no_cliente'), $this->_schema)
			->joinLeft('categoria', 'categoria.id = '.$this->_name.'.id_categoria', array('titulo as ds_categoria'), $this->_schema)
			->joinLeft('status', 'status.id = '.$this->_name.'.status', array('titulo as ds_status'), $this->_schema);
			
			$arrBind = array();
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('chamado.id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("protocolo",$arrParam) && $arrParam['protocolo']) {
				$sql->where('protocolo = ?');
				$arrBind[] = $arrParam['protocolo'];
			}
			
			if(array_key_exists("id_categoria",$arrParam) && $arrParam['id_categoria']) {
				$sql->where('id_categoria = ?');
				$arrBind[] = $arrParam['id_categoria'];
			}
			
			if(array_key_exists("id_status",$arrParam) && $arrParam['id_status']) {
				$sql->where('id_status = ?');
				$arrBind[] = $arrParam['id_status'];
			}
			
			if(array_key_exists("id_cliente",$arrParam) && $arrParam['id_cliente']) {
				$sql->where('id_cliente = ?');
				$arrBind[] = $arrParam['id_cliente'];
			}
			
			if(array_key_exists("id_provedor",$arrParam) && $arrParam['id_provedor']) {
				$sql->where($this->_name.'.id_provedor = ?');
				$arrBind[] = $arrParam['id_provedor'];
			}
			
			try {
				return $this->getAdapter()->fetchRow($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		
		public function getConsultaAll($arrParam) {
			$sql = $this->getAdapter()->select()->from($this->_name,array('id', 'protocolo', 'data', 'hora', 'status', 'status.imagem', 'id_provedor', 'provedor.nome as no_provedor', 'id_cliente', 'clientes.nome as no_cliente', 'id_categoria', 'categoria.titulo as ds_categoria'),$this->_schema)
			->joinLeft('provedor', 'provedor.id = '.$this->_name.'.id_provedor', array('nome as no_provedor'), $this->_schema)
			->joinLeft('clientes', 'clientes.id = '.$this->_name.'.id_cliente', array('nome as no_cliente'), $this->_schema)
			->joinLeft('categoria', 'categoria.id = '.$this->_name.'.id_categoria', array('titulo as ds_categoria'), $this->_schema)
			->joinLeft('status', 'status.id = '.$this->_name.'.status', array('titulo as ds_status'), $this->_schema)
			->order(array('chamado.id  DESC '));
			
			$arrBind = array();
			
			if(array_key_exists("id",$arrParam) && $arrParam['id']) {
				$sql->where('id = ?');
				$arrBind[] = $arrParam['id'];
			}
			
			if(array_key_exists("protocolo",$arrParam) && $arrParam['protocolo']) {
				$sql->where('protocolo = ?');
				$arrBind[] = $arrParam['protocolo'];
			}
			
			if(array_key_exists("id_categoria",$arrParam) && $arrParam['id_categoria']) {
				$sql->where('id_categoria = ?');
				$arrBind[] = $arrParam['id_categoria'];
			}
			
			if(array_key_exists("id_status",$arrParam) && $arrParam['id_status']) {
				$sql->where('status = ?');
				$arrBind[] = $arrParam['id_status'];
			}
			
			if(array_key_exists("id_provedor",$arrParam) && $arrParam['id_provedor']) {
				$sql->where($this->_name.'.id_provedor = ?');
				$arrBind[] = $arrParam['id_provedor'];
			}
			
			if(array_key_exists("id_cliente",$arrParam) && $arrParam['id_cliente']) {
				$sql->where('id_cliente = ?');
				$arrBind[] = $arrParam['id_cliente'];
			}
			
			if(array_key_exists("data",$arrParam) && $arrParam['data']) {
				$sql->where('data < ? || \'%\'');
				$arrBind[] = $arrParam['data'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
		public function getSlaVencido($arrParam) {
			ini_set('memory_limit', '-1');
			$sql = $this->getAdapter()->select()->from($this->_name,array('id'),$this->_schema);
			$sql->where('status = 1');
			
			$arrBind = array();
			
			if(array_key_exists("data",$arrParam) && $arrParam['data']) {
				$sql->where('data < ? || \'%\'');
				$arrBind[] = $arrParam['data'];
			}
			
			try {
				return $this->getAdapter()->fetchAll($sql,$arrBind);
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
			}
		}
		
	}