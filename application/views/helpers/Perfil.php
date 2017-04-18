<?php
class Zend_View_Helper_Perfil extends Zend_View_Helper_Abstract {
	public function perfil($id_perfil='',$strParam='',$arrParam=array()) {
		$arrPerfil = Model_Perfil::getInstance()->getConsultaAll($arrParam);
// 		debug($arrPerfil, 1);
		echo "<select name='id_perfil' id='id_perfil' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrPerfil as $perfil) {
			echo "<option value='".$perfil['id']."'";
				if($id_perfil && $id_perfil == $perfil['id']) {
					echo " selected='selected'";
				}
			echo ">",$perfil['titulo'],"</option>";
		}
		echo "</select>";
	}
}