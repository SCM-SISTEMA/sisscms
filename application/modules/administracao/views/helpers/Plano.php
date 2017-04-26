<?php
class Zend_View_Helper_Plano extends Zend_View_Helper_Abstract {
	public function plano($id='',$strParam='',$arrParam=array()) {
		$arrPlano = Administracao_Model_Planos::getInstance()->getConsultaAll($arrParam);
		echo "<select name='co_plano' id='co_plano' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrPlano as $plano) {
			echo "<option value='".$plano['co_plano']."'";
				if($id == $plano['co_plano']) {
					echo " selected='selected'";
				}
			echo ">",$plano['ds_plano'],"</option>";
		}
		echo "</select>";
	}
}