<?php
class Zend_View_Helper_AreaTecnica extends Zend_View_Helper_Abstract {
	public function areatecnica($id='',$strParam='',$arrParam=array()) {
		$arrAreaTecnica = Administracao_Model_AreaTecnica::getInstance()->getConsultaAll( array() );
		
		echo "<select name='co_area_tecnica' id='co_area_tecnica' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrAreaTecnica as $area) {
			echo "<option value='".$area['co_area_tecnica']."'";
				if($id == $area['co_area_tecnica']) {
					echo " selected='selected'";
				}
			
			echo ">".$area['ds_area_tecnica']."</option>";
		}
		echo "</select>";
	}
}