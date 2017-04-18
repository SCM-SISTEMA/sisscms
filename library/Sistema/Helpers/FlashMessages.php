<?php

/**
 * 
 *
 * Descrição do arquivo (opcional).
 *
 * @author      Marcio Paiva Barbosa <felipe.rosa@saude.gov.br>
 * @copyright   Sindicato
 * @package     Sindicato_Helpers_
 * @since       Arquivo disponível desde a versão 1.0
 * @version     $Id$
 */

/**
 * Sindicato_Helpers_FlashMessages class
 *
 * Classe responsável por gerar mensagens de erros, info, alertas e sucesso
 *
 * @author      Gilvan Junior
 * @since       Arquivo disponível desde a versão 1.0
 */
class Sgc_Helpers_FlashMessages {

    public function flashMessages($translator = NULL) {
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $statMessages = array();
        $output = '';

        if (count($messages) > 0) {
            foreach ($messages as $message) {
                if (!array_key_exists($message['status'], $statMessages))
                    $statMessages[$message['status']] = array();

                if ($translator != NULL && $translator instanceof Zend_Translate)
                    array_push($statMessages[$message['status']], $translator->_($message['message']));
                else
                    array_push($statMessages[$message['status']], $message['message']);
            }

            foreach ($statMessages as $status => $messages) {
                $output .= '<div class="' . $status . '">';

                if (count($messages) == 1) {
                    $output .= '<ul>';
                    $output .= '<li>' . $messages[0] . '</li>';
                    $output .= '</ul>';
                } else {
                    $output .= '<ul >';
                    foreach ($messages as $message)
                        $output .= '<li>' . $message . '</li>';
                    $output .= '</ul>';
                }

                $output .= '</div>';
            }

            return $output;
        }
    }

}
