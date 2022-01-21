<?php
session_start();
$code = $_SESSION['qr_code'];
include('qrlib.php');
QRcode::png($code);
 ?>
