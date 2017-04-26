<?php
class Zend_View_Helper_CentroCusto extends Zend_View_Helper_Abstract {
	public function centrocusto($id=null,$strParam=null,$arrParam=array()) {
		$arrCentroCusto = Model_CentroCusto::getInstance()->getConsultaAll($arrParam);
		echo "<select name='co_centro_custo' id='co_centro_custo' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrCentroCusto as $centro_custo) {
			echo "<option sg='".$centro_custo['co_centro_custo']."' value='".$centro_custo['co_centro_custo']."'";
				if(!is_null($id) && $id == $centro_custo['co_centro_custo']) {
					echo " selected='selected'";
				}
			echo ">",utf8_decode( $centro_custo['ds_centro_custo'] ),"</option>";
		}
		echo "</select>";
	}
}