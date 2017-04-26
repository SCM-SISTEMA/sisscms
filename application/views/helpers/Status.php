<?php
class Zend_View_Helper_Status extends Zend_View_Helper_Abstract {
	public function status($id='',$strParam='',$arrParam=array()) {
		$arrStatus = Model_Status::getInstance()->getConsultaAll($arrParam);
		echo "<select name='status' id='status' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrStatus as $status) {
			echo "<option value='".$status['id']."'";
				if($id == $status['id']) {
					echo " selected='selected'";
				}
			echo '>'.$status['titulo']."</option>";
		}
		echo "</select>";
	}
}