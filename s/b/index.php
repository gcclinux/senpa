<?php
	require_once("../session.php");
	$LANG = $_SESSION['user_lang'];
	include("../cnf/lang_$LANG.php");

	$user_id = $_SESSION['user_session'];

	if ($user_id == ""){
		header("location:../");
	}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title ?></title>
<link rel="stylesheet" href="../cnf/style.css" type="text/css"  />
</head>
<body>
	<center>
    <form class="imgcontainer" style="width:60%;" method="post" id="export" action="0.php">
		<input autocomplete="off" name="hidden" type="text" style="display:none;">
		<table align="right">
			<tr>
				<div class="imgcontainer">
				 <header class="banner" style="width:82%">
					<h1><?php echo $title ?></h1>
					<h3><?php echo $name ?></h3>
				</header>
				</div>
			</tr>
			<tr>
			<td class="png">
				<label><?php echo ucfirst($return); ?></labe><br>
				<a href="../m/"><img src="../png/close.png"></a>
			</td>
			</tr>
			</table>

			<table style="width:87%" align="center" border="5" >
				<tr>
				<td align="center"><br><strong><?php echo strtoupper($important).":</strong> ".$csv_export_notice ?><br></td>
				</tr>
				<tr>
				<br>
				</tr>
				</table>
			<tr>
			<button type="submit" id="submit" name="export" class="btn-submit"><?php echo $export ?></button>
			</tr>
			</form>
			<br>
<footer style="width:78%">
	<?php include(__DIR__ . "/../a/footer.php"); ?>
</footer>

</body>
</html>
