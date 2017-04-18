<?php
class Zend_View_Helper_Usuario extends Zend_View_Helper_Abstract {
	public function usuario($id='',$strParam='',$arrParam=array()) {
		$arrUsuarios = Model_Usuario::getInstance()->getConsultaAll($arrParam);
		
		echo "<select name='co_usuario' id='co_usuario' " . $strParam . ">";
		foreach ($arrUsuarios as $usuario) {
			echo "<option value='".$usuario['co_usuario']."'";
				if($id == $usuario['co_usuario']) {
					echo " selected='selected'";
				}
			echo ">", $usuario['no_usuario'],"</option>";
		}
		echo "</select>";
	}
}