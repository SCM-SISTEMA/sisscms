<?php
class Zend_View_Helper_PessoaJuridica extends Zend_View_Helper_Abstract {
	public function pessoajuridica($id='',$strParam='',$arrParam=array()) {
		$arrClientes = Administracao_Model_PessoaJuridica::getInstance()->getConsultaAll(array());

		echo "<select name='co_pessoa[]' id='co_pessoa' " . $strParam . ">";
		foreach ($arrClientes as $cliente) {
			if(!empty($cliente['no_razao_social']) && !empty($cliente['no_fantasia'])){

				echo '<option  tp_pessoa="2" value="'.$cliente['co_pessoa_juridica'].'" cep="'.$cliente['nu_cep'].'"';
				if($id == $cliente['co_pessoa_juridica']) {
					echo " selected='selected'";
				}
				echo ">", empty($cliente['no_razao_social']) ? limitarTexto($cliente['no_fantasia'], 30) : limitarTexto($cliente['no_razao_social'], 30),"</option>";
			}
		}
		echo "</select>";
	}
}