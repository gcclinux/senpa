<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../../cnf/class.user.php');
$login = new SENPA();

if (!isset($_SESSION['user_lang'])){
	include("../../cnf/lang_en.php");
} else {
	$LANG = $_SESSION['user_lang'];
	include("../../cnf/lang_$LANG.php");
}

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title . " - " . ucfirst($reset) ?></title>
<link rel="stylesheet" href="../../cnf/style.css" type="text/css"  />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
</head>
<main>
<center>
<body>
	<form action="../../l/" method="post" name="Reset">
	<div class="container";>
		 <header class="banner" style="width:90%;">
			<h1><?php echo $title ?></h1>
			<h3><?php echo $name ?></h3>
		</header>
		</div>

		<div id="error">
		        <?php
					if(isset($error))
					{
						?>
		                <div class="alert alert-danger">
				<?php
					echo "&nbsp;".mb_strtoupper($error, 'UTF-8')."!<br>";
				 ?>
		                </div>
		                <?php
					}
				?>
		</div>

  <div class="container" style="text-align:center;">
		<tr>
				<td colspan="2" align="center" valign="top"><?php echo ucwords($_SESSION['reset_sent']);?></td>
		</tr>
		<br />

		<form action="../../logout.php" method="post" name="Reset">
		<button type="submit"><?php echo mb_strtoupper($return, 'UTF-8') ?></button>
		</form>

  </div>
<br />
<label style="color:black;"> <?php echo ucfirst($no_account) ?><a style="color:black;" href="../../n/"><?php echo ucfirst($make_account) ?></a></label>
--
<label style="color:black;"> <?php echo $yes_account . " "?><a style="color:black;" href="../../"><?php echo $sign_in ?></a></label>
</main>
    <footer style="width:50%;">
      <p>Copyright Ricardo Wagemaker © 2009 - 2018  </p>
    </footer>

  </body>
</html>
