<?php
	class Financeiro_Model_Db extends Zend_Db_Table {
		/**
		 * Setar role para usu�rio, para poder realizar cadastro, altera��o e exclus�o
		 *
		 */
		public function setRole() {
			try {
				if(Zend_Registry::get('config')->db->role) {
					$this->getAdapter()->query(Zend_Registry::get('config')->db->role);
				}
			} catch (Exception $e) {
				throw new Zend_Db_Table_Exception($e->getMessage());
			}
		}			
	}