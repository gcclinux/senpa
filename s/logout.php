<?php
session_start(); /* Starts the session */
session_destroy(); /* Destroy started session */
header("location:../");  /* Redirect to login page */
exit;
?>
