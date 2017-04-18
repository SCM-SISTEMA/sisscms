<?php

class Model_Boleto extends Model_Db
{
	private static $_instance = null;
	protected $_schema = 'sisscm';
	protected $_name = 'tb_boleto';
	
	/**
	 * Instância da classe Model_Boleto
	 *
	 * @return Model_Boleto
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Model_Boleto();
		}
	
		// Setar role de permissão
		//self::$_instance->setRole();
	
		return self::$_instance;
	}
	
	
	/**
	 * Consulta de Boleto
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaAll($arrParam) {

		$vencidos = new Zend_Db_Expr("CASE WHEN ds_valor_pago=0 and dt_vencimento < DATE_FORMAT(NOW(),'%Y-%m-%d') THEN ds_valor ELSE 0 END as vencidos");
		$vencendo = new Zend_Db_Expr("CASE WHEN ds_valor_pago=0 and dt_vencimento BETWEEN DATE_FORMAT(now(),'%Y-%m-%d') and DATE_FORMAT(DATE_ADD(now(),INTERVAL 2 DAY),'%Y-%m-%d') THEN ds_valor ELSE 0 END as vencendo");

		$pagos = new Zend_Db_Expr("CASE WHEN dt_ocorrencia IS NOT NULL THEN ds_valor_pago ELSE 0 END as pagos");

		$abertos = new Zend_Db_Expr("CASE WHEN dt_ocorrencia IS NULL THEN ds_valor ELSE 0 END as abertos");

		$vencer = new Zend_Db_Expr("CASE WHEN ds_valor_pago=0 and dt_vencimento > DATE_FORMAT(DATE_ADD(now(),INTERVAL 2 DAY),'%Y-%m-%d') THEN ds_valor ELSE 0 END as vencer");
		$sql = $this->getAdapter()->select()->from($this->_name,array('*', 'cast(ds_valor AS DECIMAL(10,2)) as valor',$abertos,$vencidos, $vencendo, $pagos, $vencer ),$this->_schema)
		->joinLeft('tb_pessoa_juridica','tb_pessoa_juridica.co_pessoa_juridica = '.$this->_name.'.co_cliente',array('no_razao_social', 'ds_endereco', 'nu_endereco', 'no_bairro', 'no_cidade', 'sg_uf', 'nu_cep'),$this->_schema)
		->order(array('dt_vencimento asc'));
		$sql->where($this->_name.".st_ativo = 'S'");

		$arrBind = array();
	
		if(key_exists('co_boleto',$arrParam) && $arrParam['co_boleto']){
			$sql->where($this->_name.'.co_boleto = ?');
			$arrBind[] = $arrParam['co_boleto'];
		}

		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_cliente = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}

		if(key_exists('nu_documento',$arrParam) && $arrParam['nu_documento']){
			$sql->where($this->_name.'.nu_documento LIKE UPPER(?)');
			$arrBind[] = '%'.$arrParam['nu_documento'].'%';
		}

		if(key_exists('nu_nosso_numero',$arrParam) && $arrParam['nu_nosso_numero']){
			$sql->where($this->_name.'.nu_nosso_numero LIKE UPPER(?)');
			$arrBind[] = '%'.$arrParam['nu_nosso_numero'].'%';
		}

		if(key_exists('ds_fic_comp',$arrParam) && $arrParam['ds_fic_comp']){
			$sql->where($this->_name.'.ds_fic_comp = ?');
			$arrBind[] = $arrParam['ds_fic_comp'];
		}

		if(key_exists('dt_inicio_vencimento',$arrParam) && !empty($arrParam['dt_inicio_vencimento']) && key_exists('dt_final_vencimento',$arrParam) && !empty($arrParam['dt_final_vencimento'])){
			$sql->where($this->_name.'.dt_vencimento BETWEEN ? and ?');
			$arrBind[] = $arrParam['dt_inicio_vencimento'];
			$arrBind[] = $arrParam['dt_final_vencimento'];
		}

		if(key_exists('dt_inicio_pagamento',$arrParam) && !empty($arrParam['dt_inicio_pagamento']) && key_exists('dt_final_pagamento',$arrParam) && !empty($arrParam['dt_final_pagamento'])){
			$sql->where($this->_name.'.dt_ocorrencia BETWEEN ? and ?');
			$arrBind[] = $arrParam['dt_inicio_pagamento'];
			$arrBind[] = $arrParam['dt_final_pagamento'];
			$sql->where($this->_name.'.ds_valor_pago > 0');
		}

		if(key_exists('st_pagamento',$arrParam) && $arrParam['st_pagamento']){
			if($arrParam['st_pagamento'] == 1){
				$sql->where($this->_name.'.dt_ocorrencia IS NULL');
			}else{
				$sql->where($this->_name.'.dt_ocorrencia IS NOT NULL');
			}
		}

		if(key_exists('st_consulta',$arrParam) && $arrParam['st_consulta']){
			switch($arrParam['st_consulta']){
				case 'pagos':
					$sql->where($this->_name.'.dt_ocorrencia IS NOT NULL');
					break;
				case 'vencendo':
					$sql->where($this->_name.'.dt_ocorrencia IS NULL and dt_vencimento BETWEEN DATE_FORMAT(now(),\'%Y-%m-%d\') and DATE_FORMAT(DATE_ADD(now(),INTERVAL 2 DAY),\'%Y-%m-%d\')');
					break;
				case 'vencidos':
					$sql->where($this->_name.'.dt_ocorrencia IS NULL and dt_vencimento < DATE_FORMAT(NOW(),\'%Y-%m-%d\')');
					break;
				case 'avencer':
					$sql->where($this->_name.'.dt_ocorrencia IS NULL and dt_vencimento > DATE_FORMAT(DATE_ADD(now(),INTERVAL 2 DAY),\'%Y-%m-%d\')');
					break;
			}
		}

		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}

	/**
	 * Consulta de Boleto
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsulta($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_pessoa_juridica','tb_pessoa_juridica.co_pessoa_juridica = '.$this->_name.'.co_cliente',
			array('no_razao_social', 'ds_endereco', 'nu_endereco', 'no_bairro', 'no_cidade', 'sg_uf', 'nu_cep'),
			$this->_schema)
		->order(array('dt_vencimento desc'));

		$arrBind = array();

		if(key_exists('co_boleto',$arrParam) && $arrParam['co_boleto']){
			$sql->where($this->_name.'.co_boleto = ?');
			$arrBind[] = $arrParam['co_boleto'];
		}

		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_cliente = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}

		if(key_exists('nu_documento',$arrParam) && $arrParam['nu_documento']){
			$sql->where($this->_name.'.nu_documento = ?');
			$arrBind[] = $arrParam['nu_documento'];
		}

		if(key_exists('nu_nosso_numero',$arrParam) && $arrParam['nu_nosso_numero']){
			$sql->where($this->_name.'.nu_nosso_numero = ?');
			$arrBind[] = $arrParam['nu_nosso_numero'];
		}

		if(key_exists('ds_fic_comp',$arrParam) && $arrParam['ds_fic_comp']){
			$sql->where($this->_name.'.ds_fic_comp = ?');
			$arrBind[] = $arrParam['ds_fic_comp'];
		}

		if(key_exists('dt_inicio_vencimento',$arrParam) && !empty($arrParam['dt_inicio_vencimento']) && key_exists('dt_final_vencimento',$arrParam) && !empty($arrParam['dt_final_vencimento'])){
			$sql->where($this->_name.'.dt_vencimento BETWEEN ? and ?');
			$arrBind[] = $arrParam['dt_inicio_vencimento'];
			$arrBind[] = $arrParam['dt_final_vencimento'];
		}

		if(key_exists('dt_inicio_pagamento',$arrParam) && !empty($arrParam['dt_inicio_pagamento']) && key_exists('dt_final_pagamento',$arrParam) && !empty($arrParam['dt_final_pagamento'])){
			$sql->where($this->_name.'.dt_ocorrencia BETWEEN ? and ?');
			$arrBind[] = $arrParam['dt_inicio_pagamento'];
			$arrBind[] = $arrParam['dt_final_pagamento'];
		}

		if(key_exists('st_pagamento',$arrParam) && $arrParam['st_pagamento']){
			if($arrParam['st_pagamento'] == 1)
				$sql->where($this->_name.'.ds_valor_pago = 0');
			else
				$sql->where($this->_name.'.ds_valor_pago > 0');
		}

		try {
			return $this->getAdapter()->fetchRow($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}

	/**
	 * Consulta de Boleto
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaPdf($arrParam) {
		$sql = $this->getAdapter()->select()->from($this->_name,array('*'),$this->_schema)
		->joinLeft('tb_pessoa_juridica','tb_pessoa_juridica.co_pessoa_juridica = '.$this->_name.'.co_cliente',
			array('no_razao_social', 'ds_endereco', 'nu_endereco', 'no_bairro', 'no_cidade', 'sg_uf', 'nu_cep'),
			$this->_schema)
		->order(array('no_razao_social'));


		$vencidos = new Zend_Db_Expr("CASE WHEN ds_valor_pago=0 and dt_vencimento < DATE_FORMAT(NOW(),'%Y-%m-%d') THEN ds_valor ELSE 0 END as vencidos");
		$vencendo = new Zend_Db_Expr("CASE WHEN ds_valor_pago=0 and dt_vencimento BETWEEN DATE_FORMAT(now(),'%Y-%m-%d') and DATE_FORMAT(DATE_ADD(now(),INTERVAL 2 DAY),'%Y-%m-%d') THEN ds_valor ELSE 0 END as vencendo");

		$pagos = new Zend_Db_Expr("CASE WHEN dt_ocorrencia IS NOT NULL THEN ds_valor_pago ELSE 0 END as pagos");

		$abertos = new Zend_Db_Expr("CASE WHEN dt_ocorrencia IS NULL THEN ds_valor ELSE 0 END as abertos");

		$vencer = new Zend_Db_Expr("CASE WHEN ds_valor_pago=0 and dt_vencimento > DATE_FORMAT(DATE_ADD(now(),INTERVAL 2 DAY),'%Y-%m-%d') THEN ds_valor ELSE 0 END as vencer");
		$sql = $this->getAdapter()->select()->from($this->_name,array('*', 'cast(ds_valor AS DECIMAL(10,2)) as valor',$abertos,$vencidos, $vencendo, $pagos, $vencer ),$this->_schema)
			->joinLeft('tb_pessoa_juridica','tb_pessoa_juridica.co_pessoa_juridica = '.$this->_name.'.co_cliente',array('no_razao_social', 'ds_endereco', 'nu_endereco', 'no_bairro', 'no_cidade', 'sg_uf', 'nu_cep'),$this->_schema)
			->order(array('dt_vencimento desc'));
		$sql->where($this->_name.".st_ativo = 'S'");

		$arrBind = array();


		$arrBind = array();

		if(key_exists('co_boleto',$arrParam) && $arrParam['co_boleto']){
			$sql->where($this->_name.'.co_boleto = ?');
			$arrBind[] = $arrParam['co_boleto'];
		}

		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_cliente = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}

		if(key_exists('nu_documento',$arrParam) && $arrParam['nu_documento']){
			$sql->where($this->_name.'.nu_documento = ?');
			$arrBind[] = $arrParam['nu_documento'];
		}

		if(key_exists('ds_nosso_numero',$arrParam) && $arrParam['ds_nosso_numero']){
			$sql->where($this->_name.'.ds_nosso_numero = ?');
			$arrBind[] = $arrParam['ds_nosso_numero'];
		}

		if(key_exists('ds_fic_comp',$arrParam) && $arrParam['ds_fic_comp']){
			$sql->where($this->_name.'.ds_fic_comp = ?');
			$arrBind[] = $arrParam['ds_fic_comp'];
		}

		if(key_exists('dt_inicio_vencimento',$arrParam) && !empty($arrParam['dt_inicio_vencimento']) && key_exists('dt_final_vencimento',$arrParam) && !empty($arrParam['dt_final_vencimento'])){
			$sql->where($this->_name.'.dt_vencimento BETWEEN ? and ?');
			$arrBind[] = $arrParam['dt_inicio_vencimento'];
			$arrBind[] = $arrParam['dt_final_vencimento'];
		}

		if(key_exists('dt_inicio_pagamento',$arrParam) && !empty($arrParam['dt_inicio_pagamento']) && key_exists('dt_final_pagamento',$arrParam) && !empty($arrParam['dt_final_pagamento'])){
			$sql->where($this->_name.'.dt_ocorrencia BETWEEN ? and ?');
			$arrBind[] = $arrParam['dt_inicio_pagamento'];
			$arrBind[] = $arrParam['dt_final_pagamento'];
		}

		if(key_exists('st_pagamento',$arrParam) && $arrParam['st_pagamento']){
			if($arrParam['st_pagamento'] == 1)
				$sql->where($this->_name.'.ds_valor_pago = 0');
			else
				$sql->where($this->_name.'.ds_valor_pago > 0');
		}

		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}

	/**
	 * Consulta de Boleto
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getUltimoNossoNumero() {
		$sql = $this->getAdapter()->select()->from($this->_name,array('MAX(nu_nosso_numero) + 1 as nu_nosso_numero'),$this->_schema);
		try {
			return $this->getAdapter()->fetchRow($sql);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}

	/**
	 * Consulta de Boleto
	 *
	 * @param array $arrParam
	 * @return array
	 */
	public function getConsultaEmail($arrParam) {

		$sql = $this->getAdapter()->select()->from($this->_name,array(),$this->_schema)
			->joinLeft('tb_pessoa_juridica','tb_pessoa_juridica.co_pessoa_juridica = '.$this->_name.'.co_cliente',array('co_pessoa_juridica','ds_email', 'no_razao_social'),$this->_schema)
			->order(array('no_razao_social'))
		->distinct('co_pessoa_juridica');
		$sql->where($this->_name.".st_ativo = 'S'");

		$arrBind = array();

		if(key_exists('co_boleto',$arrParam) && $arrParam['co_boleto']){
			$sql->where($this->_name.'.co_boleto = ?');
			$arrBind[] = $arrParam['co_boleto'];
		}

		if(key_exists('co_pessoa_juridica',$arrParam) && $arrParam['co_pessoa_juridica']){
			$sql->where($this->_name.'.co_cliente = ?');
			$arrBind[] = $arrParam['co_pessoa_juridica'];
		}

		if(key_exists('dt_inicio_vencimento',$arrParam) && !empty($arrParam['dt_inicio_vencimento']) && key_exists('dt_final_vencimento',$arrParam) && !empty($arrParam['dt_final_vencimento'])){
			$sql->where($this->_name.'.dt_vencimento BETWEEN ? and ?');
			$arrBind[] = $arrParam['dt_inicio_vencimento'];
			$arrBind[] = $arrParam['dt_final_vencimento'];
		}

		if(key_exists('dt_inicio_pagamento',$arrParam) && !empty($arrParam['dt_inicio_pagamento']) && key_exists('dt_final_pagamento',$arrParam) && !empty($arrParam['dt_final_pagamento'])){
			$sql->where($this->_name.'.dt_ocorrencia BETWEEN ? and ?');
			$arrBind[] = $arrParam['dt_inicio_pagamento'];
			$arrBind[] = $arrParam['dt_final_pagamento'];
			$sql->where($this->_name.'.ds_valor_pago > 0');
		}

		if(key_exists('st_consulta',$arrParam) && $arrParam['st_consulta']){
			switch($arrParam['st_consulta']){
				case 'pagos':
					$sql->where($this->_name.'.dt_ocorrencia IS NOT NULL');
					break;
				case 'abertos':
					$sql->where($this->_name.'.dt_ocorrencia IS NULL');
					break;
			}
		}

		try {
			return $this->getAdapter()->fetchAll($sql,$arrBind);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage().' SQL: '.$sql);
		}
	}

}

