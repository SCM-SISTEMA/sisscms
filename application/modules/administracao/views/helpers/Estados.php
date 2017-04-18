<?php
class Zend_View_Helper_Estados extends Zend_View_Helper_Abstract {
	public function estados($id=null,$strParam=null,$arrParam=array()) {
		$arrEstados = Administracao_Model_Estados::getInstance()->getConsultaAll($arrParam);
		if( $arrParam['multiselect'] == false ){
			echo "<select name='data[sg_uf]' id='sg_uf' " . $strParam . ">";
		}elseif($arrParam['multiselect']){
			$strRequired = isset($arrParam["required"]) ? 'required="required"' : "";
			echo '<select name="sg_uf[]" multiple=multiple id="sg_uf" ' . $strRequired .'>';
		}else{
			echo "<select name='sg_uf' id='sg_uf'>";
		}
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrEstados as $estado) {
			echo "<option sg='".$estado['sg_estado']."' value='".$estado['co_estado']."'";
				if(!is_null($id) && $id == $estado['co_estado']) {
					echo " selected='selected'";
				}
			echo ">",$estado['no_estado'],"</option>";
		}
		echo "</select>";
	}
}