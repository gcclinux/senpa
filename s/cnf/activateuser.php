<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
set_include_path("." . PATH_SEPARATOR . ($UserDir = dirname($_SERVER['DOCUMENT_ROOT'])) . "Mail/PEAR/" . PATH_SEPARATOR);

require_once "Mail/Mail.php";
require_once "Mail/Mine/Mail/mime.php";

$EMAIL = $_SESSION['reset_email'];
$KEY = $_SESSION['reset_token'];
$LANG = $_SESSION['reset_lang'];
$TYPE = $_SESSION['reset_type'];

include("lang_$LANG.php");
$config = include('config.php');

$host = $config['mailserver'];
$port = $config['mailport'];
$username = $config['mailuser'];
$password = $config['mailpass'];
$to = "$EMAIL";
$email_address = $config['mailsender'];
$email_from = $config['mailsender'];
$email_subject = '';
$link = '';
$email_body = '';

if ($TYPE === "reset"){
	$email_subject = mb_strtoupper($reset_subject, 'UTF-8');
	$link = $config['rootURL']."/s/r/s/reset.php?email=$EMAIL&token=$KEY&lang=$LANG";
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
                	color: white;
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
                        ".ucwords($email_title).",
                        <br>
                        ".ucfirst($reset_msg)."
                        <br><br><big>
                        ".mb_strtoupper($security, 'UTF-8')."
                        </big></b><br>
                        ".ucwords($security_msg)."
                        <br><br>
                        <big><a href=$link>Reset</a></big>
                        <br><br>
                        ".ucwords($many_thanks)."
                        <br><br>
	</html>
";
} else if ($TYPE === "new"){
	$email_subject = mb_strtoupper($activate_subject, 'UTF-8');
	$link = $config['rootURL']."/s/l/activate.php?email=$EMAIL&token=$KEY&lang=$LANG";
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
                	color: white;
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
                 	".ucwords($email_title).",
                         <br>
                        ".ucfirst($activation_msg)."
                        <br><br><big>
                        ".mb_strtoupper($security, 'UTF-8')."
                        </big></b><br>
                        ".ucwords($security_msg)."
                        <br><br>
                        <big><a href=$link>Activate</a></big>
                        <br><br>
                        ".ucwords($many_thanks)."
                        <br><br>
	</html>
";
} else if ($TYPE === "unlock"){
	$email_subject = mb_strtoupper($unlock_subject, 'UTF-8');
	$link = $config['rootURL']."/senpa/s/z/s/unlock.php?email=$EMAIL&token=$KEY&lang=$LANG";
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
                	color: white;
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
                 	".ucwords("$email_title")."
                         <br>
                        ".ucfirst($activation_msg, 'UTF-8')."
                        <br><br><big>
                        ".mb_strtoupper($security, 'UTF-8')."
                        </big></b><br>
                        ".ucwords($security_msg, 'UTF-8')."
                        <br><br>
                        <big><a href=$link>Unlock</a></big>
                        <br><br>
                        ".ucwords($many_thanks)."
                        <br><br>
	</html>
";
}

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
}

?>
