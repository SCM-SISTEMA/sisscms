<?php
class Zend_View_Helper_Cliente extends Zend_View_Helper_Abstract {
	public function cliente($id='',$strParam='',$arrParam=array()) {
		$arrClientes = Administracao_Model_TipoPessoa::getInstance()->getConsultaAllClientes();
		
		echo "<select name='co_pessoa' id='co_pessoa' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrClientes as $cliente) {
			if(!empty($cliente['no_fantasia']))
				$tp_pessoa = 'PJ';
			else 
				$tp_pessoa = 'PF';
			echo "<option  tp_pessoa='".$tp_pessoa."' value='".$cliente['co_pessoa']."'";
				if($id == $cliente['co_pessoa']) {
					echo " selected='selected'";
				}
			if(!empty($cliente['no_fantasia']))
				$nome = $cliente['no_fantasia'];
			if(!empty($cliente['no_pessoa_fisica']))
				$nome = $cliente['no_pessoa_fisica'];
			
			echo ">", $nome,"</option>";
		}
		echo "</select>";
	}
}