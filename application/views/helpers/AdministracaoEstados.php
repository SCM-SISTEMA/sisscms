<?php
class Zend_View_Helper_AdministracaoEstados extends Zend_View_Helper_Abstract {
	public function administracaoestados($id=null,$strParam=null,$arrParam=array()) {
		$arrEstados = Administracao_Model_Estado::getInstance()->getConsultaAll($arrParam);
		if( !is_null($strParam)){
				echo "<select name='data[sg_uf]' id='sg_uf' " . $strParam . ">";
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