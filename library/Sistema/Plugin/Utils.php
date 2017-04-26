<?php
class Sistema_Plugin_Utils extends Zend_Controller_Plugin_Abstract {
		
	    public function __construct() {
	        // TODO Auto-generated Constructor
	        $this->pluginLoader = new Zend_Loader_PluginLoader ();
	    }
    	
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

	//$replace = array(
	//	'dog' => 'cat',
	//	'apple' => 'orange'
	//'chevy' => 'ford'
	//);
	//$string = 'I like to eat an apple with my dog in my chevy';
	//echo str_replace_assoc($replace,$string)
	function strReplaceAssoc(array $replace, $subject) {
		return str_replace(array_keys($replace), array_values($replace), $subject);
	}

	function limitarTexto($texto, $limite){
		$texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
		return $texto;
	}
}