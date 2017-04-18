<?php
class Zend_View_Helper_Banco extends Zend_View_Helper_Abstract {
	public function banco($id='',$strParam='',$arrParam=array()) {
		$arrBanco = Model_Banco::getInstance()->getConsultaAll( array() );
		
		echo '<select name="co_banco" id="co_banco" ' . $strParam . ' required>';
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrBanco as $banco) {
			echo "<option  co_banco='".$banco['co_banco']."' value='".$banco['co_banco']."'";
				if($id == $banco['co_banco']) {
					echo " selected='selected'";
				}
			echo ">", $banco['ds_banco'],"</option>";
		}
		echo "</select>";
	}
}