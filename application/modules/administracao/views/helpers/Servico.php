<?php
class Zend_View_Helper_Servico extends Zend_View_Helper_Abstract {
	public function servico($id='',$strParam='',$arrParam=array()) {
		$arrServico = Administracao_Model_Servicos::getInstance()->getConsultaAll($arrParam);
		if( $arrParam['multiselect'] ){
			echo '<select name="co_servico[]" multiple="multiple" id="co_servico"' . $strParam . '>';
		}else{
			echo "<select name='co_servico' id='co_servico'" . $strParam . ">";
		}
		foreach ($arrServico as $servico) {
			echo "<option value='".$servico['co_servico']."'";
				if($id == $servico['co_servico']) {
					echo " selected='selected'";
				}
			echo ">", $servico['sg_servico'] ,"</option>";
		}
		echo "</select>";
	}
}