<?php

class Sgc_Helpers_FormHelper {

    public static function keyEvents($jsFunctions) {
        $arrKeyEvents = array(
            'onkeydown'
            , 'onkeypress'
            , 'onchange'
            , 'onkeyup'
        );

        foreach ($arrKeyEvents as $keyEvent) {
            $strEvents .= $keyEvent . '="' . $jsFunctions . '" ';
        }

        return $strEvents;
    }

    /**
     * Método que cria a validação.
     *
     * @author Mário Eugênio
     * @since 2008
     * @param string $tipoValidacao
     * @return string
     */
    public static function validar($tipoValidacao = false) {

        if ($tipoValidacao) {

            $tipoValidacao = 'Validacao.checar(this, \'' . $tipoValidacao . '\')';
        } else {

            $tipoValidacao = 'Validacao.checar(this)';
        }

        return $tipoValidacao;
    }

    /**
     * Método que cria um input file com configurações de controle de extensão no clientes.
     *
     * @author Mário Eugênio
     * @since 2008
     * @param array $params
     * @return string
     * @exception Se o paramêtro que for passado não for um array.
     * @example <?php echo FormHelper::inputFile(
     * 			array(
     * 				  'id' => 'dsCaminhofoto'
     * 				, 'name' => 'dsCaminhofoto'
     * 				, 'value' => View::getFormData('dsCaminhofoto')
     * 				, 'extensao' => array('jpg', 'gif', 'bmp', 'png')
     * 			)
     * 			)?>
     */
    public static function inputFile(array $params) {

        return sprintf("<input type=\"file\" value=\"%s\" name=\"%s\" id=\"%s\" size=\"38\" %s"
                , $params["value"]
                , $params["name"]
                , $params["id"]
                , self::keyEvents('uploadMask( this, \'' . implode("|", $params["extensao"]) . '\' );'));
    }

    public static function setFormRequestUnset() {
        $session = new Zend_Session_Namespace('request');
        unset($session->request);
    }

    public static function setFormRequest(array $formData) {
        $session = new Zend_Session_Namespace('request');
        unset($session->request);

        $request = array();
        if ($formData) {
            foreach ($formData as $key => $value) {

                $request[$key] = $value;
            }
        }

        $session->request = $request;
    }

    public static function getFormRequest($requestName) {
        $session = new Zend_Session_Namespace('request');

        $return = "";

        if ($session->request) {
            foreach ($session->request as $key => $value) {
                if ($key == $requestName) {

                    $return = $value;
                    break;
                }
            }
        }

        return ( $return );
    }

    public static function setStringTextarea($str, $nCarac) {
        return ($str);
    }

    public static function getStringTextarea($str) {
        return ($str);
    }

    /**
     * Monta um Combo Select
     *
     * @param array $params
     * @return string
     */
    public static function select(array $params) {
        $firstOption = $params['firstOption'];
        $value = $params['value'];

        $onReady = $params['onReady'];

        if ($params['defaultValue'] != '') {
            $defValue = $params['defaultValue'];
        } else {
            $defValue = NULL;
        }

        if ($params['type'] == 'array') {
            $isObject = false;
        } else {
            $isObject = true;
        }
        $params['type'] = NULL;
        $params['onReady'] = NULL;

        $arrObj = $params['dataSource'];

        $methodForValue = $params['methodForValue'];
        $methodForName = $params['methodForName'];

        $params['defaultValue'] = NULL;
        $params['dataSource'] = NULL;
        $params['methodForValue'] = NULL;
        $params['methodForName'] = NULL;
        $params['title'] = NULL;
        $params['firstOption'] = NULL;
        $params['value'] = NULL;

        if ($onReady) {
            $select = '<script>$(document).ready( function(){ ' . $onReady . ' } );</script>';
        }

        $select .= '<select ';

        foreach ($params as $key => $param) {
            if ($param != NULL) {
                $select .= $key . '="' . $param . '" ';
            }
        }

        $select .= '>';

        if ($firstOption) {
            $select .= '<option value="' . $defValue . '">' . $firstOption . '</option>';
        }

        if (sizeof($arrObj) > 0 && $arrObj != NULL && $arrObj != 'NULL') {
            if ($isObject) {

                foreach ($arrObj as $obj) {
                    $selected = ( $obj->$methodForValue() == $value ) ? 'selected="selected"' : '';
                    $select .= '<option value="' . $obj->$methodForValue() . '"' . $selected . '>' . $obj->$methodForName() . '</option>';
                }
            } else {
                foreach ($arrObj as $array) {
                    $selected = ( $array[$methodForValue] == $value ) ? 'selected="selected"' : '';
                    $select .= '<option value="' . $array[$methodForValue] . '"' . $selected . '>' . $array[$methodForName] . '</option>';
                }
            }
        }

        $select .= '</select>';
        return( $select );
    }

    /**
     * Montagem de parâmetros url da página
     * 
     * @param array $request
     * @param boolean $url
     */
 	public static function getUrlParams(array $request, $url=false) {
		$strParamUrl = "";
		
		foreach ($request as $param => $value) {
			if(strcasecmp($param, "controller") && strcasecmp($param, "action") && strcasecmp($param, "module")) {
				if($value) {
					if(strpos($value,"/")) {
						$strParamUrl .= "=".$param."=".str_replace("/", " ", $value);
					} else {
						$strParamUrl .= "=".$param."=".$value;
					}
				}
			}
		}
		
		if($url) {
			$strParamUrl = $request['controller']."=".$request['action'].$strParamUrl;
		}
		
		return $strParamUrl;
    }
    
    /**
     * Formatação correta para navegação de parâmetros da url
     * 
     * @param string $urlParam
     */
    public static function getFormataUrlParams($urlParam) {
    	return str_replace("=","/", $urlParam);
    }
}