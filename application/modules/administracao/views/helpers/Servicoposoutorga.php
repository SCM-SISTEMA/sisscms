<?php
class Zend_View_Helper_Servicoposoutorga extends Zend_View_Helper_Abstract {
	public function servicoposoutorga($id='',$strParam='',$arrParam=array()) {
		$arrParam['tp_servico'] = 'O';
		$arrServico = Administracao_Model_Servicos::getInstance()->getConsultaAll($arrParam);
		echo '<select name="co_servico_posoutorga[]" multiple="multiple" id="co_servico_posoutorga"' . $strParam . '>';
		foreach ($arrServico as $servico) {
			echo "<option value='".$servico['co_servico']."'";
				if($id == $servico['co_servico']) {
					echo " selected='selected'";
				}
			echo ">", $servico['no_servico'] ,"</option>";
		}
		echo "</select>";
	}
}