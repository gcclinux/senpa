<?php

	session_start();
	require_once(__DIR__ . '/cnf/class.user.php');
	$session = new SENPA();

	if(!$session->is_loggedin())
	{
		$session->redirect('../');
	}
?>
