<?php
class Zend_View_Helper_Servico extends Zend_View_Helper_Abstract {
	public function servico($id='',$strParam='',$arrParam=array()) {
		$arrServico = Administracao_Model_Servicos::getInstance()->getConsultaAll($arrParam);
		echo "<select name='co_servico' id='co_servico' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrServico as $servico) {
			echo "<option value='".$servico['co_servico']."'";
				if($id == $servico['co_servico']) {
					echo " selected='selected'";
				}
			echo ">",utf8_decode( $servico['ds_servico'] ),"</option>";
		}
		echo "</select>";
	}
}