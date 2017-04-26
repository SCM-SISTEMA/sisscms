<?php
class Zend_View_Helper_TipoProtocolo extends Zend_View_Helper_Abstract {
	public function tipoprotocolo($id='',$strParam='',$arrParam=array()) {
		$arrTipoProtocolo = Administracao_Model_TipoProtocolo::getInstance()->getConsultaAll($arrParam);
		echo "<select name='co_tipo_protocolo' id='co_tipo_protocolo' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrTipoProtocolo as $tipoprotocolo) {
			echo "<option value='".$tipoprotocolo['co_tipo_protocolo']."'";
				if($id == $tipoprotocolo['co_tipo_protocolo']) {
					echo " selected='selected'";
				}
			echo ">", $tipoprotocolo['ds_tipo_protocolo'] ,"</option>";
		}
		echo "</select>";
	}
}