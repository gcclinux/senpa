<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/cnf/class.user.php');
$login = new SENPA();

if(!isset($_SESSION['UserData']['Username'])){
		$_SESSION['captcha_login'] = $login -> generateCaptcha(6);
		if(!isset($_SESSION['user_lang'])){
			$login->redirect('../');
		} else {
			$login->redirect('./l/');
		}	
	exit;
} else {
	$login->redirect('./m/');
}
?>
