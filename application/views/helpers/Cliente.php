<?php
class Zend_View_Helper_Cliente extends Zend_View_Helper_Abstract {
	public function cliente($id='', $provedor='', $strParam='',$arrParam=array()) {
		if( $provedor ){
			$arrParam = array( 'id_provedor' => $provedor );
		}
		$arrCliente = Model_Cliente::getInstance()->getConsultaAll($arrParam);
		echo "<select name='clientes' id='clientes' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrCliente as $cliente) {
			echo "<option value='".$cliente['id']."'";
				if($id == $cliente['id']) {
					echo " selected='selected'";
				}
			echo ">",$cliente['nome'],"</option>";
		}
		echo "</select>";
	}
}