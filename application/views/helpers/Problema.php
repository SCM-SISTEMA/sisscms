<?php
class Zend_View_Helper_Problema extends Zend_View_Helper_Abstract {
	public function problema($id='',$strParam='',$arrParam=array()) {
		$arrProblema = Model_Problema::getInstance()->getConsultaAll($arrParam);
		echo "<select name='prob_info' id='prob_info' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrProblema as $problema) {
			echo "<option value='".$problema['id']."'";
				if($id == $problema['id']) {
					echo " selected='selected'";
				}
			echo ">", $problema['titulo'],"</option>";
		}
		echo "</select>";
	}
}