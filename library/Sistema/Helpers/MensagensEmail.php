<?php
	class Sgc_Helpers_MensagensEmail {
		private static $_instance;
		
		/**
		 * Instância da classe Sindicato_Helpers_MensagensEmail
		 * 
		 */
		public static function getInstance() {
			if(self::$_instance == null) {
				self::$_instance = new Sgc_Helpers_MensagensEmail();
			}
			return self::$_instance;
		}
		
		/**
		 * Envio de email
		 * 
		 * @param string $to
		 * @param string $subject
		 * @param string $message
		 */
		public function envioEmail($to, $subject, $message) {
			// Envio de email
			$mail = new Zend_Mail();
			$mail->setBodyHtml(utf8_decode($message))
				 ->addTo($to)
				 ->setSubject(utf8_decode($subject))
				 ->send();
		}
	}