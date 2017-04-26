<?php
class Zend_Controller_Action_Helper_Utils extends Zend_Controller_Action_Helper_Abstract {
		function removeCaracter($char){
			$arrCaracter = array(',', '.', '(', ')', '-', '/');
			return str_replace($arrCaracter, '', $char);
		}
		
		public function retornaDmy($dateString){
	       return implode('', array_reverse(explode('-', $dateString)));
		}
		
		public function retornaYmd($dateString){
	        $date = new Zend_Date($dateString, 'd/m/Y');
	       return $date->toString('Y-m-d'); 
		}
}