<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
set_include_path("." . PATH_SEPARATOR . ($UserDir = dirname($_SERVER['DOCUMENT_ROOT'])) . "Mail/PEAR/" . PATH_SEPARATOR);

require_once "Mail/Mail.php";
require_once "Mail/Mine/Mail/mime.php";

$IP = $_GET['ip'];
$EMAIL = 'ricardo@wagemaker.co.uk';
$host = "ssl://mail3.gridhost.co.uk";
$port = "465";
$username = "admin@senpa.co.uk";
$password = "ey2u9WdQe3xyD7D";
$to = "$EMAIL";
$email_address = "Senpa Admin<noreply@senpa.co.uk>";
$email_from = "Senpa Admin<noreply@senpa.co.uk>";
$email_subject = "Your account has been locked" ;
$email_body = "
<!DOCTYPE html>
<html lang=\"en\">
        <head>
        <style>
        body, html {
                height: 98%;
                width: 100%;
        }
        body{
                background: #0040FF;
        }
        .banner {
                background-color: #11233b;
                color: white;
                width: 100%;
                text-align: center;
                margin:0 auto;
        }

	        </style>
        </head>

        <header class=\"banner\">
        	<h1>S.E.N.P.A</h1>
		<B> &nbsp; Securely Encrypted Network Password Archive &nbsp;</B>
		<br><br>
	</header>
	<br><br>
	<p> There has been a attempt and failure in login in using your SENPA account!</p>
	<div style=\"color:white;\">Address: $IP has failed to login and locked your account!</div>
	<p> If this was not you please click on this LINK ... so we can investigate this further!</P>
	<p> If this was you please click on this LINK ... and provide your recovery KEY!</P>
	<br>
</html>

";

$crlf = "\r\n";
$mime = new Mail_mime($crlf);
$mime->setHTMLBody($email_body);
$body = $mime->get();
$headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $email_subject, 'Reply-To' => $email_address);
$headers = $mime->headers($headers);

$smtp = Mail::factory('smtp', array ('host' => $host, 'port' => $port, 'auth' => true, 'username' => $username, 'password' => $password));
$mail = $smtp->send($to, $headers, $email_body);


if (PEAR::isError($mail)) {
echo("-" . $mail->getMessage() . "\n");
} else {
echo("Message successfully sent!\n");
}


?>
