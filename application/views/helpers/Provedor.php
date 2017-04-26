<?php
class Zend_View_Helper_Provedor extends Zend_View_Helper_Abstract {
	public function provedor($id='',$strParam='',$arrParam=array()) {
		$arrProvedor = Model_Provedor::getInstance()->getConsultaAll($arrParam);
		echo "<select name='provedor' id='provedor' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrProvedor as $provedor) {
			echo "<option value='".$provedor['id']."'";
				if($id == $provedor['id']) {
					echo " selected='selected'";
				}
			echo ">",$provedor['nome'],"</option>";
		}
		echo "</select>";
	}
}