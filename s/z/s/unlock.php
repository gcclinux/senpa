<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../../cnf/class.user.php');
$auth_user = new SENPA();

$EMAIL = $_GET['email'];
$KEY = $_GET['token'];
$LANG = $_GET['lang'];
$TYPE = 'unlock';
$_SESSION['user_lang'] = $LANG;

include("../../cnf/lang_$LANG.php");

if($auth_user->checkvalidToken ($KEY,$TYPE,$EMAIL) === "true") {
  $STATUS = 'active';
  $COUNT = '2';
  if($auth_user->setUserUnlocked($EMAIL,$STATUS,$COUNT)){
    $_SESSION['reset_success'] = "$unlock_success<br><br>";
    $auth_user->redirect('../../m/');
  } else {
    echo "Redirecting ......";
  }
} else {
  echo "INVALID TOKEN";
}

?>
