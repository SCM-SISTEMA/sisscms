<?php
class Zend_View_Helper_TipoContrato extends Zend_View_Helper_Abstract {
	public function tipocontrato($id='',$strParam='',$arrParam=array()) {
		$arrTipoContrato = Administracao_Model_TipoContrato::getInstance()->getConsultaAll($arrParam);
		echo "<select name='co_tipo_contrato' id='co_tipo_contrato' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrTipoContrato as $tipocontrato) {
			echo "<option value='".$tipocontrato['co_tipo_contrato']."'";
				if($id == $tipocontrato['co_tipo_contrato']) {
					echo " selected='selected'";
				}
			echo ">", $tipocontrato['ds_tipo_contrato'] ,"</option>";
		}
		echo "</select>";
	}
}