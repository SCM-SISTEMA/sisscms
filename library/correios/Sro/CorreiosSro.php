<?php

/**
 * Classe base para para a validação e geração de dígito verificador
 * de SRO dos Correios.
 *
 * @author Ivan Wilhelm <ivan.whm@outlook.com>
 * @version 1.2
 * @abstract
 */

abstract class CorreiosSro {

    /**
     * Contém as siglas e suas respectivas descrições, adotadas
     * no sistema de identificador de objetos.
     *
     * @see http://www.correios.com.br/para-voce/precisa-de-ajuda/como-rastrear-um-objeto/siglas-utilizadas-no-rastreamento-de-objeto
     * @var array
     */
    protected static $siglasComDescricao = array(
        'AL' => 'AGENTES DE LEITURA',
        'AR' => 'AVISO DE RECEBIMENTO',
        'AS' => 'ENCOMENDA PAC – AÇÃO SOCIAL',
        'CA' => 'OBJETO INTERNACIONAL',
        'CB' => 'OBJETO INTERNACIONAL',
        'CC' => 'COLIS POSTAUX',
        'CD' => 'OBJETO INTERNACIONAL',
        'CE' => 'OBJETO INTERNACIONAL',
        'CF' => 'OBJETO INTERNACIONAL',
        'CG' => 'OBJETO INTERNACIONAL',
        'CH' => 'OBJETO INTERNACIONAL',
        'CI' => 'OBJETO INTERNACIONAL',
        'CJ' => 'REGISTRADO INTERNACIONAL',
        'CK' => 'OBJETO INTERNACIONAL',
        'CL' => 'OBJETO INTERNACIONAL',
        'CM' => 'OBJETO INTERNACIONAL',
        'CN' => 'OBJETO INTERNACIONAL',
        'CO' => 'OBJETO INTERNACIONAL',
        'CP' => 'COLIS POSTAUX',
        'CQ' => 'OBJETO INTERNACIONAL',
        'CR' => 'CARTA REGISTRADA SEM VALOR DECLARADO',
        'CS' => 'OBJETO INTERNACIONAL',
        'CT' => 'OBJETO INTERNACIONAL',
        'CU' => 'OBJETO INTERNACIONAL',
        'CV' => 'REGISTRADO INTERNACIONAL',
        'CW' => 'OBJETO INTERNACIONAL',
        'CX' => 'OBJETO INTERNACIONAL',
        'CY' => 'OBJETO INTERNACIONAL',
        'CZ' => 'OBJETO INTERNACIONAL',
        'DA' => 'REM EXPRES COM AR DIGITAL',
        'DB' => 'REM EXPRES COM AR DIGITAL BRADESCO',
        'DC' => 'REM EXPRESSA CRLV/CRV/CNH e NOTIFICAÇÃO',
        'DD' => 'DEVOLUÇÃO DE DOCUMENTOS',
        'DE' => 'REMESSA EXPRESSA TALÃO E CARTÃO C/ AR',
        'DF' => 'E-SEDEX (LÓGICO)',
        'DG' => 'SEDEX',
        'DI' => 'REM EXPRES COM AR DIGITAL ITAU',
        'DJ' => 'SEDEX',
        'DK' => 'PAC Extra Grande',
        'DL' => 'ENCOMENDA SEDEX (LÓGICO)',
        'DM' => 'e-SEDEX',
        'DN' => 'SEDEX',
        'DO' => 'SEDEX ou Remessa Expressa com AR Digital (Itaú)',
        'DP' => 'REM EXPRES COM AR DIGITAL PRF',
        'DQ' => 'SEDEX ou Remessa Expressa com AR Digital (Santander)',
        'DR' => 'Remessa Expressa com AR Digital (Santander)',
        'DS' => 'REM EXPRES COM AR DIGITAL SANTANDER',
        'DT' => 'REMESSA ECON.SEG.TRANSITO C/AR DIGITAL',
        'DU' => 'e-SEDEX',
        'DV' => 'SEDEX c/ AR digital',
        'DX' => 'ENCOMENDA SEDEX 10 (LÓGICO)',
        'EA' => 'OBJETO INTERNACIONAL',
        'EB' => 'OBJETO INTERNACIONAL',
        'EC' => 'ENCOMENDA PAC',
        'ED' => 'OBJETO INTERNACIONAL',
        'EE' => 'SEDEX INTERNACIONAL',
        'EF' => 'OBJETO INTERNACIONAL',
        'EG' => 'OBJETO INTERNACIONAL',
        'EH' => 'ENCOMENDA NORMAL COM AR DIGITAL',
        'EI' => 'OBJETO INTERNACIONAL',
        'EJ' => 'ENCOMENDA INTERNACIONAL',
        'EK' => 'OBJETO INTERNACIONAL',
        'EL' => 'OBJETO INTERNACIONAL',
        'EM' => 'OBJETO INTERNACIONAL',
        'EN' => 'ENCOMENDA NORMAL NACIONAL',
        'EO' => 'OBJETO INTERNACIONAL',
        'EP' => 'OBJETO INTERNACIONAL',
        'EQ' => 'ENCOMENDA SERVIÇO NÃO EXPRESSA ECT',
        'ER' => 'REGISTRADO',
        'ES' => 'E-SEDEX',
        'ET' => 'OBJETO INTERNACIONAL',
        'EU' => 'OBJETO INTERNACIONAL',
        'EV' => 'OBJETO INTERNACIONAL',
        'EW' => 'OBJETO INTERNACIONAL',
        'EX' => 'OBJETO INTERNACIONAL',
        'EY' => 'OBJETO INTERNACIONAL',
        'EZ' => 'OBJETO INTERNACIONAL',
        'FA' => 'FAC REGISTRATO (LÓGICO)',
        'FE' => 'ENCOMENDA FNDE',
        'FF' => 'REGISTRADO DETRAN',
        'FH' => 'REGISTRADO FAC COM AR DIGITAL',
        'FM' => 'REGISTRADO - FAC MONITORADO',
        'FR' => 'REGISTRADO FAC',
        'IA' => 'INTEGRADA AVULSA',
        'IC' => 'INTEGRADA A COBRAR',
        'ID' => 'INTEGRADA DEVOLUCAO DE DOCUMENTO',
        'IE' => 'INTEGRADA ESPECIAL',
        'IF' => 'CPF',
        'II' => 'INTEGRADA INTERNO',
        'IK' => 'INTEGRADA COM COLETA SIMULTANEA',
        'IM' => 'INTEGRADA MEDICAMENTOS',
        'IN' => 'OBJ DE CORRESP E EMS REC EXTERIOR',
        'IP' => 'INTEGRADA PROGRAMADA',
        'IR' => 'IMPRESSO REGISTRADO',
        'IS' => 'INTEGRADA STANDARD',
        'IT' => 'INTEGRADO TERMOLÁBIL',
        'IU' => 'INTEGRADA URGENTE',
        'IX' => 'EDEI Expresso',
        'JA' => 'REMESSA ECONOMICA C/AR DIGITAL',
        'JB' => 'REMESSA ECONOMICA C/AR DIGITAL',
        'JC' => 'REMESSA ECONOMICA C/AR DIGITAL',
        'JD' => 'REMESSA ECONOMICA C/AR DIGITAL',
        'JE' => 'REMESSA ECONÔMICA C/AR DIGITAL',
        'JG' => 'REGISTRATO AGÊNCIA (FÍSICO)',
        'JH' => 'REGISTRATO AGÊNCIA (FÍSICO)',
        'JI' => 'Remessa econômica Talão/Cartão',
        'JJ' => 'REGISTRADO JUSTIÇA',
        'JK' => 'Remessa econômica Talão/Cartão',
        'JL' => 'OBJETO REGISTRADO (LÓGICO)',
        'JM' => 'MALA DIRETA POSTAL ESPECIAL (LÓGICO)',
        'JN' => 'Objeto registrado econômico',
        'JO' => 'Objeto registrado urgente',
        'JP' => 'Receita Federal',
        'JQ' => 'Remessa econômica com AR Digital',
        'JR' => 'Objeto registrado urgente / prioritário',
        'JS' => 'Objeto registrado',
        'JT' => 'Objeto Registrado Urgente',
        'JV' => 'Remessa Econômica (c/ AR DIGITAL)',
        'LA' => 'LOGÍSTICA REVERSA SIMULTÂNEA - ENCOMENDA SEDEX (AGÊNCIA)',
        'LB' => 'LOGÍSTICA REVERSA SIMULTÂNEA - ENCOMENDA E-SEDEX (AGÊNCIA)',
        'LC' => 'CARTA EXPRESSA',
        'LE' => 'LOGÍSTICA REVERSA ECONOMICA',
        'LF' => 'Objeto Internacional (Prime)',
        'LI' => 'Objeto Internacional (Prime)',
        'LJ' => 'Objeto Internacional (Prime)',
        'LK' => 'Objeto Internacional (Prime)',
        'LM' => 'Objeto Internacional (Prime)',
        'LN' => 'Objeto Internacional (Prime)',
        'LP' => 'LOGÍSTICA REVERSA SIMULTÂNEA - ENCOMENDA PAC (AGÊNCIA)',
        'LS' => 'LOGISTICA REVERSA SEDEX',
        'LV' => 'LOGISTICA REVERSA EXPRESSA',
        'LX' => 'CARTA EXPRESSA',
        'LZ' => 'OBJETO INTERNACIONAL',
        'MA' => 'SERVIÇOS ADICIONAIS',
        'MB' => 'TELEGRAMA DE BALCÃO',
        'MC' => 'MALOTE CORPORATIVO',
        'MD' => 'SEDEX Mundi (Documento interno)',
        'ME' => 'TELEGRAMA',
        'MF' => 'TELEGRAMA FONADO',
        'MK' => 'TELEGRAMA CORPORATIVO',
        'ML' => 'Fecha Malas (Rabicho)',
        'MM' => 'TELEGRAMA GRANDES CLIENTES',
        'MP' => 'TELEGRAMA PRÉ-PAGO',
        'MR' => 'AR digital',
        'MS' => 'ENCOMENDA SAUDE',
        'MT' => 'TELEGRAMA VIA TELEMAIL',
        'MY' => 'TELEGRAMA INTERNACIONAL ENTRANTE',
        'MZ' => 'TELEGRAMA VIA CORREIOS ON LINE',
        'NE' => 'TELE SENA RESGATADA',
        'NX' => 'EDEI Econômico (não urgente)',
        'PA' => 'PASSAPORTE',
        'PB' => 'ENCOMENDA PAC - NÃO URGENTE',
        'PC' => 'ENCOMENDA PAC A COBRAR',
        'PD' => 'ENCOMENDA PAC - NÃO URGENTE',
        'PE' => 'PAC',
        'PF' => 'PASSAPORTE',
        'PG' => 'ENCOMENDA PAC (ETIQUETA FÍSICA)',
        'PH' => 'ENCOMENDA PAC (ETIQUETA LÓGICA)',
        'PI' => 'PAC',
        'PJ' => 'PAC',
        'PK' => 'PAC Extra Grande',
        'PL' => 'PAC',
        'PR' => 'REEMBOLSO POSTAL - CLIENTE AVULSO',
        'QQ' => 'Objeto de teste (SIGEP Web)',
        'RA' => 'REGISTRADO PRIORITÁRIO',
        'RB' => 'CARTA REGISTRADA',
        'RC' => 'CARTA REGISTRADA COM VALOR DECLARADO',
        'RD' => 'REMESSA ECONOMICA DETRAN',
        'RE' => 'REGISTRADO ECONÔMICO',
        'RF' => 'OBJETO DA RECEITA FEDERAL',
        'RG' => 'REGISTRADO DO SISTEMA SARA',
        'RH' => 'REGISTRADO COM AR DIGITAL',
        'RI' => 'REGISTRADO',
        'RJ' => 'REGISTRADO AGÊNCIA',
        'RK' => 'REGISTRADO AGÊNCIA',
        'RL' => 'REGISTRADO LÓGICO',
        'RM' => 'REGISTRADO AGÊNCIA',
        'RN' => 'REGISTRADO AGÊNCIA',
        'RO' => 'REGISTRADO AGÊNCIA',
        'RP' => 'REEMBOLSO POSTAL - CLIENTE INSCRITO',
        'RQ' => 'REGISTRADO AGÊNCIA',
        'RR' => 'CARTA REGISTRADA SEM VALOR DECLARADO',
        'RS' => 'REGISTRADO LÓGICO',
        'RT' => 'REM ECON TALAO/CARTAO SEM AR DIGITAL',
        'RU' => 'REGISTRADO SERVIÇO ECT',
        'RV' => 'REM ECON CRLV/CRV/CNH COM AR DIGITAL',
        'RW' => 'Objeto internacional',
        'RX' => 'Objeto internacional',
        'RY' => 'REM ECON TALAO/CARTAO COM AR DIGITAL',
        'RZ' => 'REGISTRADO',
        'SA' => 'SEDEX ANOREG',
        'SB' => 'SEDEX 10 AGÊNCIA (FÍSICO)',
        'SC' => 'SEDEX A COBRAR',
        'SD' => 'REMESSA EXPRESSA DETRAN',
        'SE' => 'ENCOMENDA SEDEX',
        'SF' => 'SEDEX AGÊNCIA',
        'SG' => 'SEDEX DO SISTEMA SARA',
        'SH' => 'SEDEX com AR Digital / SEDEX ou AR Digital',
        'SI' => 'SEDEX AGÊNCIA',
        'SJ' => 'SEDEX HOJE',
        'SK' => 'SEDEX AGÊNCIA',
        'SL' => 'SEDEX LÓGICO',
        'SM' => 'SEDEX MESMO DIA',
        'SN' => 'SEDEX COM VALOR DECLARADO',
        'SO' => 'SEDEX AGÊNCIA',
        'SP' => 'SEDEX PRÉ-FRANQUEADO',
        'SQ' => 'SEDEX',
        'SR' => 'SEDEX',
        'SS' => 'SEDEX FÍSICO',
        'ST' => 'REM EXPRES TALAO/CARTAO SEM AR DIGITAL',
        'SU' => 'ENCOMENDA SERVIÇO EXPRESSA ECT',
        'SV' => 'REM EXPRES CRLV/CRV/CNH COM AR DIGITAL',
        'SW' => 'E-SEDEX',
        'SX' => 'SEDEX 10',
        'SY' => 'REM EXPRES TALAO/CARTAO COM AR DIGITAL',
        'SZ' => 'SEDEX AGÊNCIA',
        'TC' => 'Objeto para treinamento',
        'TE' => 'TESTE (OBJETO PARA TREINAMENTO)',
        'TS' => 'TESTE (OBJETO PARA TREINAMENTO)',
        'VA' => 'ENCOMENDAS COM VALOR DECLARADO',
        'VC' => 'ENCOMENDAS',
        'VD' => 'ENCOMENDAS COM VALOR DECLARADO',
        'VE' => 'ENCOMENDAS',
        'VF' => 'ENCOMENDAS COM VALOR DECLARADO',
        'VV' => 'Objeto internacional',
        'XA' => 'Aviso de chegada (internacional)',
        'XM' => 'SEDEX MUNDI',
        'XR' => 'ENCOMENDA SUR POSTAL EXPRESSO',
        'XX' => 'ENCOMENDA SUR POSTAL 24 HORAS',
    );

    /**
     * Realiza a validação completa do SRO.
     * @param string $sro
     * @return boolean
     */
    public static function validaSro($sro) {
        //Valida a estrutura do SRO
        if ( ! preg_match('/[A-Z]{2}[0-9]{9}[A-Z]{2}/', $sro)) {
            return false;
        }

        //Valida a sigla do SRO
        if ( ! isset(self::$siglasComDescricao[substr($sro, 0, 2)])) {
            return false;
        }

        //Valida o dígito verificador
        if (self::calculaDigitoVerificador(substr($sro, 2, 8)) != substr($sro, 10, 1)) {
            return false;
        }
    
        return true;
    }

    /**
     * Calcula o dígito verificador do SRO.
     * Retorna -1 se o cálculo for incorreto.
     * @param string $sro SRO
     * @return int
     */
    public static function calculaDigitoVerificador($sro) {
        //Inicializa o retorno
        $retorno = -1;
        //Valida a quantidade de caracteres
        if (strlen(trim($sro)) === 8) {
            //Valida
            $soma = 0;
            for ($i = 0; $i <= 8; $i++) {
                $soma = $soma + (int) substr($sro, $i, 1) * (int) substr('86423597', $i, 1);
            }
            //Calcula o dígito validador
            switch ($soma % 11) {
                case 0:
                    $retorno = 5;
                    break;
                case 1:
                    $retorno = 0;
                    break;
                default:
                    $retorno = 11 - ($soma % 11);
                    break;
            }
        }
        //Retorna
        return $retorno;
    }

}
