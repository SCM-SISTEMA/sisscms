<?php
class Zend_View_Helper_ContratoScm extends Zend_View_Helper_Abstract {
	public function contratoscm( $arrParam ) {
		echo '
<p align="center"><strong>CONTRATO DE PRESTAÇÃO DE SERVIÇOS DE CONSULTORIA</strong></p>
<p align="center"><strong>IDENTIFICAÇÃO DAS PARTES CONTRATANTES</strong></p>
<p><strong>SCM  ENGENHARIA DE TELECOMUNICAÇÕES LTDA</strong>, com sede em C 01 Lote  01/12 Sala 338, CEP: 72010-010 Edifício Taguatinga Trade Center – Taguatinga  Centro - Brasília - DF, inscrita no CNPJ sob o n.º10.225.044/0001-73, doravante  denominada <strong>contratada.</strong></p>
	<p><b>'.$arrParam['no_pessoa'].',</b> Estabelecida  na '.$arrParam['ds_endereco'].', CEP: '.$arrParam['nu_cep'].', com '.$arrParam['tp_documento'].' nº '.$arrParam['nu_documento'].', doravante denominada <strong>contratante</strong>, tem justo e acordado este  instrumento contratual, regido pelas seguintes cláusulas e condições: </p>
<p><strong>DO OBJETO DO CONTRATO</strong></p>
<p><strong>Cláusula 1ª.</strong> É objeto do presente  contrato a prestação de consultoria por parte da CONTRATADA à CONTRATANTE para  obtenção de autorização para prestação do Serviço de Comunicação Multimídia  junto a ANATEL.</p>
<p><strong>&nbsp;</strong></p>
<p><strong>DAS OBRIGAÇÕES DA CONTRATADA</strong></p>
<p><strong>Cláusula 2ª.</strong> São deveres da <strong>CONTRATADA</strong></p>
<ol>
  <li>Informar  à CONTRATANTE os documentos necessários para a obtenção de autorização do  Serviço de Comunicação Multimídia, junto à Anatel.</li>
  <li>Instruir  à CONTRATANTE a respeito do procedimento para registro da empresa no CREA.</li>
  <li>Analisar  a documentação da CONTRATANTE indicando as modificações necessárias para  atender ao disposto na Legislação Vigente, em especial o disposto na Lei n.°  9.472, no Decreto n.° 2.617/98, no Decreto n.° 2.534/98, na Resolução n.° 73 da  Anatel, na Resolução n.° 65, da Anatel, e na Resolução n.° 614, da Anatel.</li>
  <li>  Elaborar Projeto Básico conforme disposto no  Anexo II da Resolução n.° 614 da Anatel, e tendo como base informações técnicas  passadas pela CONTRATANTE.</li>
  <li>  Elaborar Projeto de Instalação conforme  disposto no Anexo III da Resolução n.° 614 da Anatel, e tendo como base  informações técnicas passadas pela CONTRATANTE.</li>
  <li>  Auxiliar a CONTRATANTE na preparação dos  documentos necessários para o licenciamento das Estações de Telecomunicações do  Serviço Comunicação Multimídia.</li>
  <li>  Representar a CONTRATANTE junto à Anatel  durante todo o processo de licenciamento, tomando todas as medidas necessárias  para que o mesmo corra com a maior celeridade possível.</li>
  <li>  Oferecer ao CONTRATANTE a cópia do presente  instrumento, contendo todas as especificidades da prestação de serviço  contratada.</li>
</ol>
<p>&nbsp;</p>
<p><strong>DAS OBRIGAÇÕES DA CONTRATANTE</strong></p>
<p><strong>Cláusula 3ª.</strong> São deveres da  CONTRATANTE:</p>
<ol>
  <li>Fornecer  à CONTRATADA todas as informações e documentos necessários para o bom andamento  e cumprimento do objeto deste contrato, bem como uma procuração (datada e com  firma reconhecida) para representá-la junto à Anatel;</li>
  <li>Alterar  os documentos, quando necessário, de acordo com as indicações da CONTRATADA,  para atender as exigências legais indispensáveis à obtenção da autorização.</li>
  <li>Realizar  dentro do prazo o pagamento dos boletos emitidos pela Anatel em nome da  CONTRATANTE, em especial o Preço Público pelo Direito de Exploração de Serviços  de Telecomunicações e pelo Direito de Exploração do Serviço de Comunicação  Multimídia (SCM) e a Taxa de Fiscalização de Instalação (TFI).</li>
  <li>Realizar  o pagamento conforme disposto nas cláusulas 4ª.</li>
</ol>
<p>&nbsp;</p>
<p><strong>DO CUSTO E DA FORMA DE PAGAMENTO</strong></p>
<p><strong>Cláusula 4ª.</strong> A consultoria para auxiliar na obtenção de autorização do Serviço de  Comunicação Multimídia, junto à Anatel, possui custo total de R$ ' . $arrParam['valor_contrato'].' ('.$arrParam['valor_contrato_extenso'].') a ser pago pela  CONTRATANTE à CONTRATADA, em boleto bancário conforme abaixo: <br />
</p>';
		foreach ($arrParam['parcelas'] as $parcelas ){
			echo 'R$ ' . $parcelas['valor_parcela'] . ' (' . $parcelas['valor_parcela_extenso'] . ') no dia ' . $parcelas['data_por_extenso'] . '<br />';
		}
		echo '

<p>                            <br />
  <strong>Cláusula 5ª.</strong> Os pagamentos referidos  nas cláusulas 4ª poderão ser feitos por meio de boleto bancário.</p>
<p><strong>DA MULTA</strong></p>
<p><strong>Cláusula 6ª.</strong> Em caso de inadimplemento por parte do CONTRATANTE  quanto ao pagamento do serviço prestado, deverá incidir sobre o valor do  presente instrumento, multa pecuniária de 2%, juros de mora de 1% ao mês,  acrescido de correção monetária calculada &ldquo;pro rata temporis&rdquo; pelo Índice Geral  de Preços de Mercado (IGP-M) da Fundação Getúlio Vargas.</p>
<p><strong>Parágrafo  único.</strong> Em caso de cobrança judicial ou extrajudicial pelo escritório  contratado, devem ser acrescidas custas e 20% de honorários advocatícios.</p>
<p><strong>Cláusula 7ª.</strong> O Pagamento não sendo efetuado em até 10(dez) dias após o seu respectivo  vencimento, a CONTRATADA suspenderá a prestação dos serviços, sem prejuízo da  exigibilidade dos encargos contratuais, ficando o seu restabelecimento  condicionado ao pagamento do(s) valor(es) em atraso, acrescido (s) da multa e  dos juros, de acordo com os trabalhos desenvolvidos até o momento da  notificação formal de rescisão.</p>
<p><strong>DA CONFIDENCIALIDADE DAS INFORMAÇÕES</strong></p>
<p><strong>Cláusula 8ª. </strong>As Partes comprometem-se a não revelar as informações confidenciais a  qualquer pessoa ou entidade, que não aquelas relacionadas à negociação, sem o  prévio consentimento, por escrito, da parte Reveladora</p>
<p><strong>Cláusula 9ª.</strong> Caso seja requerida a revelação das informações confidenciais por qualquer foro  de jurisdição competente, lei, regulação, agência do governo, administração de  ordem legal, desde que a Parte requerida a revelar forneça a outra parte aviso  prévio de tal ordem ou requerimento, no prazo de 10 (dez) dias, contados da  notificação da entidade, permitindo a Parte Reveladora requerer medida cautelar  ou outro recurso legal apropriado.</p>
<p><strong>Cláusula 10ª.</strong> Para o propósito deste instrumento, o termo &ldquo;informação confidencial&rdquo; significa  toda informação relevante relacionada às Partes ou a seus negócios e a  operações, reveladas ou de qualquer outra maneira tornadas disponíveis pela  Parte Reveladora à Parte Receptora, inclusive modelos de documentos.</p>
<p><strong>&nbsp;</strong></p>
<p><strong>DAS DISPOSIÇÕES GERAIS</strong></p>
<p><strong>Cláusula 11ª.</strong> A CONTRATANTE não poderá utilizar-se de qualquer formulário, modelo de  contrato, modelo de carta, projeto, apresentação, panfleto ou qualquer  documento criado e utilizado pela CONTRATADA, salvo com sua autorização prévia  por escrito.</p>
<p><strong>Cláusula 12ª.</strong> O presente contrato não estabelece entre as  partes, de forma direta ou indireta, qualquer forma de sociedade, associação,  relação de emprego ou responsabilidade solidária ou conjunta.</p>
<p><strong>Cláusula  13ª.</strong> Não se estabelece por  força do presente contrato, qualquer vinculo empregatício ou responsabilidade  por parte da CONTRATADA ou da CONTRATANTE com relação aos empregados, prepostos  ou terceiros das outras partes, que venham a ser envolvidos na prestação dos  serviços objeto do presente Contrato.</p>
<p>            <br />
  <strong>Cláusula 14ª. </strong>Em caso de Rescisão  Contratual por parte da Contratante, será devido pela mesma o valor referente  ao trabalho desenvolvido até o momento da notificação por escrito com a  intenção de rescisão do contrato.</p>
<p>            <br />
  <strong>PARÁGRAFO PRIMEIRO</strong>: Para efeitos de rescisão,  o processo de prestação de serviços para emissão de licença junto a ANATEL está  dividida em 3 (três) partes,sendo:</p>
<p>1ª A primeira  parte implica na realização da adequação do contrato social da empresa as  normas estabelecidas pela ANATEL no tocante ao seu objeto e atividade fim, bem  como ao registro de responsável técnico junto ao CREA. Tal serviço corresponde  a 30% (trinta por cento) do valor do contrato; </p>
<p>2ª A segunda  parte implica na elaboração do projeto básico por Engenheiro, bem como o  posterior envio da documentação regular a ANATEL com o acompanhamento dos  trâmites até a respectiva aprovação e o conseqüente envio do Boleto e os Termos  da licença. Tais serviços correspondem a 70% (setenta por cento) do contrato.</p>
<p>3ª A terceira  e última parte implica no recebimento do cadastro da contratante e posterior  elaboração e aprovação do projeto de instalação, bem como o cadastramento das  torres de transmissão do SCM, que representará 100% (cem por cento) do valor  deste contrato.</p>
<p>            <strong>Cláusula 15ª</strong>. Não serão gerados, em  hipótese alguma, novos boletos de pagamento, devendo os vencimentos atrasados  serem pagos inseridos os juros e encargos legais.</p>
<p><strong>DO FORO</strong></p>
<p><strong>Cláusula 16ª. </strong>Para dirimir quaisquer  controvérsias oriundas do contrato, as partes elegem o foro da circunscrição  judiciária de Brasília - DF, com renúncia expressa a qualquer outro por mais  privilegiado que seja.</p>
<p>Por estarem  assim justos e contratadas, firmam o presente instrumento, em duas vias de  igual teor, juntamente com 2 (duas) testemunhas.</p>
<p><strong>Brasília - DF, ' . $arrParam['dt_contrato'] . '.</strong></p>
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