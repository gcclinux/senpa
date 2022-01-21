<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); /* Starts the session */
require_once(__DIR__ . '/../cnf/class.user.php');
$login = new SENPA();

if (!isset($_SESSION['user_lang'])){
	include("../cnf/lang_en.php");
	$lang = 'en';
} else {
	$lang = $_SESSION['user_lang'];
	include("../cnf/lang_$lang.php");
}


if (!isset($_SESSION['captcha'])){
	$_SESSION['captcha'] = $login -> generateCaptcha(6);
}
$type = "unlock";

if(isset($_POST['btn-login']))
{
	$umail = strip_tags($_POST['txt_uname_email']);
	$ucap = strip_tags($_POST['txt_captcha']);

	if(!strcmp($ucap,$_SESSION['captcha'])) {
				$retrive_email = $login->checkDuplicateEmail($umail);
				$retrive_type = $login->checkNotNew($umail);
				if ($retrive_type === "true"){
					if($retrive_email === "true") {
								if(!$login->doUnlock($umail,$lang,$type)) {
									$error = "<br> SYSTEM ERROR UNLOCKING!";
								} else {
									$_SESSION['reset_sent'] = $unlock_email;
								}
						} else {
							$error = "<br>$invalid_email";
						}
				} else {
					$error = "<br>$no_reset";
				}
			} else {
			$error = "<br>$wrong_capt";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title . " - " . ucfirst($unlock_btn) ?></title>
<link rel="stylesheet" href="../cnf/style.css" type="text/css"  />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
</head>
<main>
<center>
<body>
<form action="" method="post" name="Reset_Form">
	<table align="right">
    <tr>
      <div class="imgcontainer">
           <header class="banner" style="width:91%">
              <h1><?php echo $title ?></h1>
              <h3><?php echo $name ?></h3>
            </header>
        </div>
    </tr>
		<tr>
			<td class="png">
				<label><?php echo ucfirst($return); ?></labe><br>
				<a href="../../"><img src="../png/close.png"></a>
			</td>
		</tr>
  </table>

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
		<table  style="width:760px;" align="center" border="2px">
			<tr>
				<th style="padding-top: 1%; border:none; width: 20%; text-align:right; vertical-align: center;"> <a style="text-decoration: none;" href="./"><font size="20">&#10226;</font>
				<th style="padding-top: 2%; border:none; width: 35%; text-align:right; vertical-align:bottom;"> <input autocomplete="new-password" type="text" class="Input" name="txt_captcha" placeholder="<?php echo ucfirst($captcha) ?>" value="<?php if(isset($error)){echo $ucap;}?>" /></th>
					<?php $_SESSION['captcha'] = $login-> generateCaptcha(6); ?>
				</a></th>
				<th style="padding-right: 1%;border:none; width: 30%; text-align:center; "><label> <br> <img src="../img.php" alt="Loading..." width="73%" height="45"></b></lable></th>
			</tr>

			<tr>
				<td style="border: none;" colspan="3">
					<input style="border: none; text-align:center; width:90%;" type="text" class="Input" name="txt_uname_email" placeholder="<?php echo ucfirst($make_email) ?>" value="<?php if(isset($error)){echo $umail;}?>" required/>
				</td>
			</tr>
		</tr>
	</table>
		<br />
    <button name="btn-login" type="submit" value="Reset"><?php echo ucfirst($unlock_btn) ?></button>
  </div>
</form>
</main>
    <footer style="width:44%;">
      <?php include("../a/footer.php"); ?>
    </footer>

  </body>
</html>
