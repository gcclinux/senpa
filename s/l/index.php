<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_set_cookie_params(360,"/");
session_start();
require_once(__DIR__ . '/../cnf/class.user.php');
$login = new SENPA();

$LANG = $_SESSION['user_lang'];
include("../cnf/lang_$LANG.php");


$_SESSION['real_user'] = "unknown";
if (!isset($_SESSION['captcha_login'])){
  $login->redirect('../logout.php');
} else {
  $login_captcha = $_SESSION['captcha_login'];
}

$date = date('Y-m-d H:i:s');
if($login->is_loggedin()!="") {
	if($login->updateLogin($_SESSION['user_session'],$date)){
		$login->redirect('../m/');
	}
}

if(isset($_POST['btn-login'])) {
        $uname = strip_tags($_POST['txt_uname_email']);
        $umail = strip_tags($_POST['txt_uname_email']);
        $upass = strip_tags($_POST['txt_password']);
        $ucap = strip_tags($_POST['txt_captcha']);

        $_SESSION['real_user'] = $uname;
        $attempts = $login->getUserAttempt($uname);

        if(!strcmp($ucap,$login_captcha) && $attempts <= 2 ){
                if($login->doLogin($uname,$umail,$upass)) {
                        if($login->updateLogin($_SESSION['user_session'],$date)){
                                if($login->setUserAttempt($uname,'0')){}
                                $login->redirect('../m/');
                        }
                } else {
                        if (!isset($_SESSION['reset_email'])){
                                if ( $attempts === NULL || $attempts == 0 ){
                                        $attempts = 1;
                                        if($login->setUserAttempt($uname,$attempts)){}
                                } else {
                                        $attempts = ($attempts + 1);
                                        if($login->setUserAttempt($uname,$attempts)){}
                                }
                                $error = "<br>$incorrect_details";
                                $error .= "<br>Failure: ".$attempts;
                        } else {
                                $status = $_SESSION['user_status'];
                                $error = "<br>$account: $status";
                        }
                }
        } else {
                if ($attempts == 3){
                  $failed = "locked";
                      if($login->setUserLock($uname,$failed)){
                        $error = "$locked_account";
                      }
                } else {
                        $error = "$wrong_capt";
                }
        }
}

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title . " - " . ucfirst($login_) ?></title>
<link rel="stylesheet" href="../cnf/style.css" type="text/css"  />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<script type="text/javascript">
   document.cookie = 'window_width='+window.innerWidth+'; path=/';
</script>
</head>
<main>
<center>
<body>
<form action="" method="post" name="Login_Form">
  <table align="right">
    <tr>
      <div class="imgcontainer">
        <header class="banner" style="width:87%">
          <h1><?php echo $title ?></h1>
          <h3><?php echo $name ?></h3>
        </header>
        </div>
    </tr>
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
			if (strpos($error, 'account') !== false && strpos($error, 'new') !== false) {
				echo "<label style=\"color:black;\">" .ucfirst($not_active ). "</label><br><br>";
                            	echo "<label style=\"color:black;\">" .ucfirst($activate_subject)."? <a style=\"color:black;\" href=\"resend.php\">" . ucfirst($resend)."</a></label>";
			} else {
				echo "&nbsp;".mb_strtoupper($error, 'UTF-8')."!<br>";
			}
			 ?>
		         </div>
		<?php
		}
		?>
		</div>

  <div class="container" style="text-align:center;">
   <?php if(isset($msg)){?>
    <tr>
      <td colspan="2" align="center" valign="top"><?php echo $msg;?></td>
    </tr>
    <?php } ?>
	<tr>
		<td colspan="2" align="center" valign="top"><?php if (isset($_SESSION['new_created'])){	echo ucwords($_SESSION['new_created']); }?></td>
		<td colspan="2" align="center" valign="top"><?php if (isset($_SESSION['reset_sent'])){	echo ucwords($_SESSION['reset_sent']); }?></td>
		<td colspan="2" align="center" valign="top"><?php if (isset($_SESSION['reset_success'])){ echo ucwords($_SESSION['reset_success']); }?></td>
		<td colspan="2" align="center" valign="top"><?php if (isset($_SESSION['active_success'])){ echo ucwords($_SESSION['active_success']); }?></td>
	</tr>
		<table  style="width:95%;" align="center" border="2px">
      <tr>
        <th style="padding-top: 1%; border:none; width: 20%; text-align:right; vertical-align: center;"> <a style="text-decoration: none;" href="../"><font size="20">&#10226;</font>
          <th style="padding-top: 2%; border:none; width: 35%; text-align:right; vertical-align:bottom;">
	     <input autocomplete="new-password" type="text" class="Input" name="txt_captcha" placeholder="<?php echo ucfirst($captcha) ?>" value="<?php if(isset($error)){echo $ucap;}?>"/>
	  </th>
          </a></th>
        <th style="padding-right: 1%;border:none; width: 30%; text-align:center; "><label> <br> <img src="../img0.php" alt="Loading..." width="73%" height="45"></b></lable></th>
      </tr>
	<tr>
		<td style="border: none;" colspan="3">
        	<input style="text-align:center; width:90%;" type="text" class="Input" name="txt_uname_email" placeholder="<?php echo ucfirst($enter_name) ?>" value="<?php if(isset($error)){echo $uname;}?>" required/>
		</td>
	</tr>
	<td style="border: none;" colspan="3">
 		<input style="text-align:center; width:90%;" type="password" class="Input" autocomplete="new-password" name="txt_password" placeholder="<?php echo ucfirst($enter_passwd) ?>" value="<?php if(isset($error)){echo $upass;}?>" required/>
	</td>
	</tr>
	</table>
		<br />
    <button name="btn-login" type="submit" value="Login"><?php echo ucfirst($login_) ?></button>
  </div>
</form>
</main>
    <footer style="width:44.5%;">
      <?php include("../a/footer.php"); ?>
    </footer>

  </body>
</html>
