<?php
class Zend_View_Helper_FormaPagamento extends Zend_View_Helper_Abstract {
	public function formapagamento($id='',$strParam='',$arrParam=array()) {
		$arrFormaPagamento = Administracao_Model_FormaPagamento::getInstance()->getConsultaAll($arrParam);
		echo "<select name='co_forma_pagamento' id='co_forma_pagamento' " . $strParam . ">";
		echo "<option value=''>-- Selecione --</option>";
		foreach ($arrFormaPagamento as $formaPagamento) {
			echo "<option value='".$formaPagamento['co_forma_pagamento']."'";
				if($id == $formaPagamento['co_forma_pagamento']) {
					echo " selected='selected'";
				}
			echo ">",utf8_decode( $formaPagamento['ds_forma_pagamento'] ),"</option>";
		}
		echo "</select>";
	}
}