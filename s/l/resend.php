<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../cnf/class.user.php');
$auth_user = new SENPA();

$TYPE = $_SESSION['reset_type'];

if ($TYPE === "new"){
  require_once(__DIR__ . '/../cnf/activateuser.php');
  $auth_user->redirect("../logout.php");
} else {
  echo mb_strtoupper($invalid_token, 'UTF-8');
}

?>
