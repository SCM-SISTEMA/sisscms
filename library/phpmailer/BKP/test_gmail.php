<?php

//error_reporting(E_ALL);
error_reporting(E_STRICT);

date_default_timezone_set('America/Toronto');

include("class.phpmailer.php");
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$body             = $mail->getFile('examples/contents.html');
$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server

$mail->Username   = "candangojungle@gmail.com";  // GMAIL username
$mail->Password   = "teksab";            // GMAIL password

$mail->AddReplyTo("ricardo@casademoises.org","Ricardo - Casa de Moises");

$mail->From       = "ricardo@casademoises.org";
$mail->FromName   = "Ricardo - Casa de Moises";

$mail->IsHTML(true); // send as HTML

$mail->Subject    = "PHPMailer Teste Submetido via gmail";

$mail->Body       = "Hi,<br>This is the HTML BODY<br>";                      //HTML Body
//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->WordWrap   = 50; // set word wrap

//$mail->MsgHTML($body);

$mail->AddAddress("ricardosbarboza@gmail.com", "Barboza");

$mail->AddAttachment("examples/images/phpmailer.gif");             // attachment         

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Messagem enviada!";
}

?>
