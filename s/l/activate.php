<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../cnf/class.user.php');
$auth_user = new SENPA();

$EMAIL = $_GET['email'];
$KEY = $_GET['token'];
$LANG = $_GET['lang'];
$TYPE = 'new';
$STATUS = 'active';

$_SESSION['user_lang'] = $LANG;

include("../cnf/lang_$LANG.php");

$_SESSION['active_success'] = '';

if ($auth_user->checkvalidToken ($KEY,$TYPE,$EMAIL) === "true") {
  if ($auth_user->activateUser($EMAIL,$TYPE,$STATUS) && $auth_user->disableRestLink($KEY)){
    $_SESSION['active_success'] = "$active_done<br><br>";
    $auth_user->redirect('../');
  } else {
    echo "SYSTEM ERROR!";
  }
} else {
  echo mb_strtoupper($invalid_token, 'UTF-8')."<br>";
  echo "EMAIL: ".$EMAIL."<br>";
  echo "TYPE: ".$TYPE."<br>";
  echo "KEY: ".$KEY."<br>";
  
echo "$test";
}

?>
