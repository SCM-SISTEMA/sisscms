<?php

class Administracao_Model_TipoPessoa extends Administracao_Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_tipo_pessoa';
	
	/**
	 * Instância da classe Administracao_Model_TipoPessoa
	 *
	 * @return Administracao_Model_TipoPessoa
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Administracao_Model_TipoPessoa();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	public function getConsultaAllClientes() {
		$sqlPf = $this->getAdapter()->select()->from('tb_pessoa_fisica',array(
'co_pessoa_fisica as co_pessoa',
'co_tipo_pessoa',
'nu_cpf',
'nu_rg',
'no_pessoa_fisica',
'ds_nacionalidade',
'ds_estado_civil',
new Zend_Db_Expr('NULL as nu_cnpj'),
new Zend_Db_Expr('NULL as nu_ie'),
new Zend_Db_Expr('NULL as no_fantasia'),
new Zend_Db_Expr('NULL as no_razao_social'),
'ds_email',
'nu_telefone_1',
'nu_telefone_2',
'nu_telefone_3',
'ds_endereco',
'nu_endereco',
'nu_cep',
'no_bairro',
'no_cidade',
'sg_uf',
'ds_complemento',
'dt_cadastro',
'st_ativo'	),$this->_schema)
		->where('co_tipo_pessoa = 1');
		$sqlPj = $this->getAdapter()->select()->from('tb_pessoa_juridica',array(
'co_pessoa_juridica as co_pessoa',
'co_tipo_pessoa',
new Zend_Db_Expr('NULL as nu_cpf'),
new Zend_Db_Expr('NULL as nu_rg'),
new Zend_Db_Expr('NULL as no_pessoa_fisica'),
new Zend_Db_Expr('NULL as ds_nacionalidade'),
new Zend_Db_Expr('NULL as ds_estado_civil'),
'nu_cnpj',
'nu_ie',
'no_fantasia',
'no_razao_social',
'ds_email',
'nu_telefone_1',
'nu_telefone_2',
'nu_telefone_3',
'ds_endereco',
'nu_endereco',
'nu_cep',
'no_bairro',
'no_cidade',
'sg_uf',
'ds_complemento',
'dt_cadastro',
'st_ativo'),$this->_schema)
		->where('co_tipo_pessoa = 1');
		
		$sql = $this->getAdapter()->select()
		->union(array($sqlPf, $sqlPj));
	
		try {
			return $this->getAdapter()->fetchAll($sql);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage()." SQL: ".$sql);
		}
	}
	

}

