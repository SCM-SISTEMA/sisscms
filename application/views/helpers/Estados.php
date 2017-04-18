<?php
class Zend_View_Helper_Estados extends Zend_View_Helper_Abstract {
	public function estados($id='',$strParam='',$arrParam=array()) {
		$arrEstados = Administracao_Model_Estados::getInstance()->getConsultaAll($arrParam);
		echo "<select name='sg_uf' id='sg_uf' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrEstados as $estado) {
			echo "<option value='".$estado['co_estado']."'";
				if($id == $estado['co_estado']) {
					echo " selected='selected'";
				}
			echo ">",utf8_decode( $estado['no_estado'] ),"</option>";
		}
		echo "</select>";
	}
}