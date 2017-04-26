<?php
class Zend_View_Helper_ContratoCallCenterAtivo extends Zend_View_Helper_Abstract {
	public function contratocallcenterativo( $arrParam ) {
		echo '
<p align="center"><strong>CONTRATO DE PRESTAÇÃO DE SERVIÇOS DE CONSULTORIA </strong></p>
<p align="center"><strong>IDENTIFICAÇÃO DAS PARTES CONTRATANTES</strong></p>
<p><strong>SCM  ENGENHARIA DE TELECOMUNICAÇÕES LTDA</strong>, com sede em C 01 Lote  01/12 Sala 338, CEP: 72010-010 Edifício Taguatinga Trade Center – Taguatinga  Centro - Brasília - DF, inscrita no CNPJ sob o n.º10.225.044/0001-73, doravante  denominada <strong>contratada.</strong></p>
	<p><b>'.$arrParam['no_pessoa'].',</b> Estabelecida  na '.$arrParam['ds_endereco'].', CEP: '.$arrParam['nu_cep'].', com '.$arrParam['tp_documento'].' nº '.$arrParam['nu_documento'].', doravante denominada <strong>contratante</strong>, tem justo e acordado este  instrumento contratual, regido pelas seguintes cláusulas e condições: </p>
<p><strong>DO OBJETO DO CONTRATO</strong></p>
<p><strong>Cláusula 1ª.</strong> Constitui objeto deste  contrato a prestação de serviços de atendimento ao clientes através de Call  Center ATIVO, «HORÁRIO COMERCIAL», de « DE SEGUNDA À SEXTA-FEIRA DE 08H ÀS 18H.  SÁBADO DE 08H ÀS 16H, pelo  prazo mínimo de 12 (doze) meses a contar da assinatura do contrato, podendo ser  renovado automaticamente após seu vencimento.</p>
<p><strong>&nbsp;</strong></p>
<p><strong>DAS OBRIGAÇÕES DA CONTRATADA</strong></p>
<p><strong>Cláusula 2ª.</strong> São deveres da <strong>CONTRATADA</strong></p>
<ol>
  <li>Praticar todos os atos necessários para o bom andamento do Atendimento  aos clientes, de forma Receptiva ou Ativa objeto do presente contrato.</li>
</ol>
<p>&nbsp;</p>
<ol>
  <li>Disponibilizar,  semanalmente, ou sempre que solicitado pela CONTRATANTE, relatório completo e  pormenorizado sobre o andamento do Atendimento que estão sob sua  responsabilidade, através de meios magnéticos e internet;</li>
</ol>
<p><strong>DAS OBRIGAÇÕES DA CONTRATANTE</strong></p>
<p><strong>Cláusula 3ª.</strong> São deveres da  CONTRATANTE:</p>
<ol>
  <li>Trazer ao conhecimento da CONTRATADA e fornecer todos os materiais e  dados necessários, via sistema, planilha ou relatórios, à execução da prestação  de serviços oriunda do presente instrumento, encaminhando toda a documentação  referente aos clientes Ativos e Receptivos, respeitando o sigilo bancário caso  haja.</li>
</ol>
<p>&nbsp;</p>
<ol>
  <li>Pagar à CONTRATADA nos exatos termos descritos na Cláusula 4ª deste  instrumento.</li>
</ol>
<p>&nbsp;</p>
<p><strong>DO CUSTO E DA FORMA DE PAGAMENTO</strong></p>
<p><strong>Cláusula  4ª.</strong> Por este instrumento, a CONTRATANTE se obriga a  pagar à CONTRATADA, pela prestação de serviços objeto deste contrato, um valor  mensal de R$ ' . $arrParam['valor_contrato'].' ('.$arrParam['valor_contrato_extenso'].') para cada Posição de Atendimento Ativo ou  Receptivo (PAs), incluída toda a infra-estrutura necessária, disponibilizada  para realização dos serviços contratados. <br />
  O valor mensal dos  serviços a ser faturado pela CONTRATADA deverá ser obtido a partir da aplicação  da fórmula a seguir:<br />
  <strong>VP = P*(N-Fa) + P* (0,15*Tts)</strong></p>
<p>a) VP = Valor de Referência a ser pago mensalmente à CONTRATADA.<br />
  b) N – Quantidade de atendimentos efetuados pelos atendentes e que serão<br />
  quantificados pelo PABX/DAC.<br />
  c) Fa – Quantidade de atendimentos que obtiveram falha<br />
  d) Tts – Duração total, mensal, em minutos, dos atendimentos efetuados  pela<br />
  CONTRATADA;<br />
  e) P – &ldquo;Valor Base por Atendimento&rdquo; estipulado pela CONTRATADA.</p>
<p><strong>Cláusula 5ª</strong>. O pagamento dar-se-á  sempre até o dia 10 (dez) de cada mês, mediante apresentação de nota fiscal de  serviço pela CONTRATADA com 5 (cinco) dias úteis de antecedência <br />
  <strong>Cláusula 6ª. </strong>Os pagamentos referidos na  cláusula 4ª poderá ser feitos por meio de boleto bancário.</p>
<p><strong>DA MULTA </strong></p>
<p><strong>Cláusula 7ª.</strong> Em caso de inadimplemento por parte do CONTRATANTE  quanto ao pagamento do serviço prestado, deverá incidir sobre o valor do  presente instrumento, multa pecuniária de 2%, juros de mora de 1% ao mês,  acrescido de correção monetária calculada &ldquo;pro rata temporis&rdquo; pelo Índice Geral  de Preços de Mercado (IGP-M) da Fundação Getúlio Vargas.</p>
<p><strong>Parágrafo único.</strong> Em caso de cobrança judicial ou extrajudicial por escritório contratado, devem  ser acrescidas custas e 20% de honorários advocatícios.</p>
<p><strong>Cláusula 8ª.</strong> O Pagamento não sendo efetuado em até 10(dez) dias após o seu respectivo  vencimento, a CONTRATADA <u>suspenderá a prestação dos serviços</u>, sem  prejuízo da exigibilidade dos encargos contratuais, ficando o seu  restabelecimento condicionado ao pagamento do(s) valor(es) em atraso, acrescido  (s) da multa e dos juros pactuados neste contrato.</p>
<p><strong>&nbsp;</strong></p>
<p><strong>DA RESCISÃO</strong></p>
<p><strong>Cláusula  9ª. N</strong>o caso de rescisão antes do prazo definido no objeto deste contrato, o  contratante arcará com multa de 30% (dez por cento) do valor anual do contrato,  tomando-se por base até os três últimos meses de faturamento, com o fim de  reparar os investimentos realizados para atendimento da contratante sem  prejuízo das perdas e danos apuradas;</p>
<p><strong>DA CONFIDENCIALIDADE DAS INFORMAÇÕES</strong></p>
<p><strong>Cláusula 10ª. </strong>As Partes comprometem-se a não revelar as informações confidenciais a  qualquer pessoa ou entidade, que não aquelas relacionadas à negociação, sem o  prévio consentimento, por escrito das partes.</p>
<p><strong>Cláusula 11ª.</strong> Caso seja requerida a revelação das informações confidenciais por qualquer foro  de jurisdição competente, lei, regulação, agência do governo, administração de  ordem legal, desde que a Parte requerida a revelar forneça a outra parte aviso  prévio de tal ordem ou requerimento, no prazo de 10 (dez) dias, contados da  notificação da entidade, permitindo a Parte Reveladora requerer medida cautelar  ou outro recurso legal apropriado.</p>
<p><strong>Cláusula 12ª.</strong> Para o propósito deste instrumento, o termo &ldquo;informação confidencial&rdquo; significa  toda informação relevante relacionada às Partes ou a seus negócios e a  operações, reveladas ou de qualquer outra maneira tornadas disponíveis pela Parte  Reveladora à Parte Receptora, inclusive modelos de documentos.</p>
<p><strong>&nbsp;</strong></p>
<p><strong>DAS DISPOSIÇÕES GERAIS</strong></p>
<p><strong>Cláusula 13ª.</strong> A CONTRATANTE não poderá utilizar-se de qualquer formulário, modelo de  contrato, modelo de carta, projeto, apresentação, panfleto ou qualquer documento  criado e utilizado pela CONTRATADA, salvo com sua autorização prévia por  escrito.</p>
<p><strong>Cláusula 14ª.</strong> O presente contrato não estabelece entre as  partes, de forma direta ou indireta, qualquer forma de sociedade, associação,  relação de emprego ou responsabilidade solidária ou conjunta.</p>
<p><strong>Cláusula  15ª.</strong> Não se estabelece por  força do presente contrato, qualquer vinculo empregatício ou responsabilidade  por parte da CONTRATADA ou da CONTRATANTE com relação aos empregados, prepostos  ou terceiros das outras partes, que venham a ser envolvidos na prestação dos  serviços objeto do presente Contrato.</p>
<p>            <br />
  <strong>Cláusula 16ª. </strong>Em caso de Rescisão  Contratual por parte da Contratante, será devido pela mesma o valor referente  ao trabalho desenvolvido até o momento da notificação por escrito com a  intenção de rescisão do contrato.</p>
<p>&nbsp;</p>
<p>            <strong>Cláusula 17ª</strong>. Não serão gerados, em  hipótese alguma, novos boletos de pagamento, devendo os vencimentos atrasados  serem pagos, inseridos de juros e acréscimos legais e contratuais.</p>
<p><strong>&nbsp;</strong></p>
<p><strong>DO FORO</strong></p>
<p><strong>Cláusula 18ª. </strong>Para dirimir quaisquer  controvérsias oriundas do contrato, as partes elegem o foro da circunscrição  judiciária de Brasília - DF, com renúncia expressa a qualquer outro por mais  privilegiado que seja.</p>
<p>Por estarem  assim justos e contratadas, firmam o presente instrumento, em duas vias de  igual teor, juntamente com 2 (duas) testemunhas.</p>
<p><strong>Brasília - DF, '.$arrParam['dt_contrato'].'.</strong></p>
<p>&nbsp;</p>
<p>
________________________________________________<br />
<strong>' . $arrParam['no_pessoa'] . '</strong><br />
  ' . $arrParam['tp_documento'] . '  sob o n.º ' . $arrParam['nu_documento'] . '<br />
  CONTRATANTE</p>
<p>&nbsp;</p>
<p><strong>&nbsp;</strong></p>
<p><strong>&nbsp;</strong></p>
<p>
________________________________________________<br />
<strong>SCM ENGENHARIA  DE TELECOMUNICAÇÕES LTDA</strong><br />
  CNPJ  sob o n.º 10.225.044/0001-73<br />
  CONTRATADA</p>
	';
	}
}