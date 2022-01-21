<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../cnf/class.user.php');
$user = new SENPA();



if (!isset($_SESSION['user_lang'])){
	$LANG = 'en';
	include("../cnf/lang_en.php");
} else {
	$LANG = $_SESSION['user_lang'];
	include("../cnf/lang_$LANG.php");
}

if (!isset($_SESSION['captcha'])){
	$_SESSION['captcha'] = $user -> generateCaptcha(6);
}

$captcha_session = $_SESSION['captcha'];

if($user->is_loggedin()!="")
{
	$user->redirect('../');
}

if(isset($_POST['btn-signup']))
{
	$uname = strip_tags($_POST['txt_uname']);
	$umail = strip_tags($_POST['txt_umail']);
	$upass = strip_tags($_POST['txt_upass']);
	$ucap = strip_tags($_POST['txt_captcha']);
	$status = 'new';
	$lang = $LANG;

	if($uname=="")	{
		$error[] = "<br>Provide Username!<br><br>";
	}
	else if($lang=="")	{
		$error[] = "Select Language!<br><br>";
	}
	else if($ucap !== $captcha_session) {
		$error[] = "INCORRECT CAPTCHA!<br><br>";
	}
	else if($umail=="")	{
		$error[] = "Provide email id!<br><br>";
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Please enter a valid email address!<br><br>';
	}
	else if($upass=="")	{
		$error[] = "Provide password!<br><br>";
	}
	else if(strlen($upass) < 6){
		$error[] = "Password must be atleast 6 characters<br><br>";
	}
	else
	{
			$retrive_user = $user->checkDuplicateUser($uname);
			$retrive_email = $user->checkDuplicateEmail($umail);

			if($retrive_user=="true") {
				$error[] = $user_exist."<br><br>";
			}
			else if($retrive_email=="true") {
				$error[] = $email_exist."<br><br>";
			}
			else
			{
				$personalKey = $user->personalKey(24);
				if($user->register($uname,$umail,$upass,$status,$lang,$personalKey)){
					$type = "new";
					$_SESSION['user_lang'] = $lang;
					if (!isset($_SESSION['user_lang'])){
						$LANG = 'en';
						include("../cnf/lang_en.php");
					} else {
						$LANG = $_SESSION['user_lang'];
						include("../cnf/lang_$LANG.php");
					}
					if ($user->doReset($umail,$lang,$type)){
						$_SESSION['new_created'] = $new_created."<br>".$activate_email;
						$user->redirect('../');
					}
				}
			}
	}
	$_SESSION['captcha'] = $user -> generateCaptcha(6);
}

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title . " - " . ucfirst($new_) ?></title>
<link rel="stylesheet" href="../cnf/style.css" type="text/css"  />
</head>
<main>
<center>
<body>
	<form action="" method="post" name="NewUser_Form">
		<table align="right">
	    <tr>
	      <div class="imgcontainer">
             <header class="banner" style="width:87%">
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
   <div class="container" style="text-align:center;">
      <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['joined']))
			{
			?>
                 		<div class="alert alert-info">
                      			<i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='../'>login</a> here
                 		</div>
				<?php
					header( 'Location: ../' ) ;
				?>
      <?php
			}
			?>
			<table  style="width:95%;" align="center" border="2px">
			<tr>
			<th style="padding-top: 1%; border:none; width: 20%; text-align:right; vertical-align: center;"> <a style="text-decoration: none;" href="./"><font size="20">&#10226;</font>
			<th style="padding-top: 2%; border:none; width: 35%; text-align:right; vertical-align:bottom;">
				<input autocomplete="new-password" type="text" class="Input" name="txt_captcha" placeholder="<?php echo ucfirst($captcha) ?>" value="<?php if(isset($error)){echo $ucap;}?>" /></th>
				<?php $_SESSION['captcha'] = $user -> generateCaptcha(6); ?>
			</a></th>
			<th style="padding-right: 1%;border:none; width: 30%; text-align:center; "><label> <br> <img src="../img.php" alt="Loading..." width="73%" height="45"></b></lable></th>
			</tr>
			<tr>
			<td style="border:none;" colspan="3">
			<input style="text-align:center; width:90%;" autocomplete="off" type="text" class="Input" name="txt_uname" placeholder="<?php echo ucfirst($enter_name) ?>" value="<?php if(isset($error)){echo $uname;}?>" required/>
			</td>
			</tr>
			<tr>
			<td style="border:none;" colspan="3">
			<input style="text-align:center; width:90%;" autocomplete="off" type="text" class="Input" name="txt_umail" placeholder="<?php echo ucfirst($make_email) ?>" value="<?php if(isset($error)){echo $umail;}?>" required/>
			</td>
			</tr>
			<tr>
			<td style="border:none;" colspan="3">
			<input style="text-align:center; width:90%;" autocomplete="new-password" type="password" class="Input" name="txt_upass" placeholder="<?php echo ucfirst($enter_passwd) ?>" required/>
			</td>
			</tr>
			</table>
            	<button type="submit" name="btn-signup">
                	<B>CREATE</B>
                </button>
        </form>
       </div>
			</main>
    <footer style="width:44.5%;">
     <?php include("../a/footer.php"); ?>
    </footer>
</body>
</html>
