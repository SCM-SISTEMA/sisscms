<?php
class Zend_View_Helper_Usuarios extends Zend_View_Helper_Abstract {
	public function usuarios($co_usuario='',$strParam='',$arrParam=array()) {
		$arrUsuario = Model_Usuario::getInstance()->getConsultaAll($arrParam);
// 		debug($arrUsuario, 1);
		echo "<select name='co_usuario[]' multiple=multiple id='co_usuario' " . $strParam . ">";
		foreach ($arrUsuario as $usuario) {
			echo "<option value='".$usuario['co_usuario']."'";
				if($co_usuario && $co_usuario == $usuario['co_usuario']) {
					echo " selected='selected'";
				}
			echo ">",$usuario['ds_login'],"</option>";
		}
		echo "</select>";
	}
}