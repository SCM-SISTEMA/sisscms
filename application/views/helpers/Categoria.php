<?php
class Zend_View_Helper_Categoria extends Zend_View_Helper_Abstract {
	public function categoria($id='',$strParam='',$arrParam=array()) {
		$arrCategoria = Model_Categoria::getInstance()->getConsultaAll($arrParam);
		echo "<select name='categoria' id='categoria' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrCategoria as $categoria) {
			echo "<option value='".$categoria['id']."'";
				if($id == $categoria['id']) {
					echo " selected='selected'";
				}
			echo ">", $categoria['titulo'],"</option>";
		}
		echo "</select>";
	}
}