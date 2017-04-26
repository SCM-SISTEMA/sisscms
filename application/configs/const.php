<?php
// MODULOS
define('ADMINISTRACAO', 'Administração');
define('FINANCEIRO', 'Financeiro');

// ST_REGISTRO
define('ST_REGISTRO_ATIVO', 'S');
define('ST_REGISTRO_INATIVO', 'N');

define('CONTRATO_CALL_CENTER_ATIVO', '7');
define('CONTRATO_CALL_CENTER_RECEPTIVO', '6');
define('CONTRATO_PROJETO_SCM', '1');
define('CONTRATO_RADIOENLACES ', '3');
define('PRESTACAO_DE_SERVIÇOS_DE_CONSULTORIA  ', '2');

// STATUS
define('CO_STATUS_ABERTO', 1 );
define('DS_STATUS_ABERTO', 'Aberto' );
define('CO_STATUS_DEVEDOR', 2 );
define('DS_STATUS_DEVEDOR', 'Devedor' );
define('CO_STATUS_QUITADO', 3 );
define('DS_STATUS_QUITADO', 'Quitado' );
define('CO_STATUS_SUSPENSO', 4 );
define('DS_STATUS_SUSPENSO', 'Suspenso' );

// FASE
define('CO_FASE_EMPRESA', 1 );
define('CO_FASE_1', 2 );
define('CO_FASE_2', 3 );
define('CO_FASE_3', 4 );
define('CO_FASE_4', 5 );

// DADOS DA SUA CONTA - CEF
define('NU_AGENCIA', '0630');
define('NU_CONTA', '00001699');
define('NU_CONTA_DV', '3');

// DADOS PERSONALIZADOS - CEF
define('CONTA_CEDENTE', '250783');
//$dadosboleto["conta_cedente"] = "87000000414"; // ContaCedente do Cliente, sem digito (Somente Números)
define('CONTA_CEDENTE_DV', '8');
// $dadosboleto["conta_cedente_dv"] = "3"; // Digito da ContaCedente do Cliente
define('CARTEIRA', 'SR');
// $dadosboleto["carteira"] = "SR";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)

// SEUS DADOS
// $dadosboleto["identificacao"] = "BoletoPhp - Código Aberto de Sistema de Boletos";
define('IDENTIFICACAO', 'SCM ENGENHARIA DE TELECOMUNICAÇÕES');
// $dadosboleto["cpf_cnpj"] = "";
define('CPF_CNPJ', '10.225.044/0001-73');
// $dadosboleto["endereco"] = "Coloque o endereço da sua empresa aqui";
define('ENDERECO', utf8_decode('C 01 Lote 01/12 Sala 338, CEP: 72010-010 Edif�cio Taguatinga Trade Center - Taguatinga Centro'));
// $dadosboleto["cidade_uf"] = "Cidade / Estado";
define('CIDADE_UF', utf8_decode('Brasília - DF'));
// $dadosboleto["cedente"] = "Coloque a Razão Social da sua empresa aqui";
define('CEDENTE', utf8_decode('SCM ENGENHARIA DE TELECOMUNICAÇÕES LTDA'));

define('CO_TP_RESPONSAVEL_TECNICO_TECNICO', '0');
define('CO_TP_RESPONSAVEL_TECNICO_ENGENHEIRO', '1');