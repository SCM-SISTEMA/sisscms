<?php

class Administracao_Model_Contador extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_contador';
	
	/**
	 * Instância da classe Administracao_Model_Contador
	 *
	 * @return Administracao_Model_Contador
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Contador();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}

}

