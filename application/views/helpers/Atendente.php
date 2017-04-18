<?php
class Zend_View_Helper_Atendente extends Zend_View_Helper_Abstract {
	public function atendente($id='', $provedor='', $strParam='',$arrParam=array()) {
		if( $atendente ){
			$arrParam = array( 'id_provedor' => $atendente );
		}
		$arrAtendente = Model_Atendente::getInstance()->getAtendenteAll($arrParam);
		echo "<select name='atendente' id='atendente' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrAtendente as $atendente) {
			echo "<option value='".$atendente['id']."'";
				if($id == $atendente['id']) {
					echo " selected='selected'";
				}
			echo ">",$atendente['nome'],"</option>";
		}
		echo "</select>";
	}
}