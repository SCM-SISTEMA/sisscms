<?php

class Administracao_Model_Endereco
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_endereco';
	
	/**
	 * Instância da classe Administracao_Model_Endereco
	 *
	 * @return Administracao_Model_Endereco
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_Endereco();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}

}

