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
$TYPE = 'reset';
$STATUS = '';

$_SESSION['user_lang'] = $LANG;

include("../../cnf/lang_$LANG.php");

if(isset($_POST['btn-reset'])) {

  $date = date('Y-m-d H:i:s');
  $new_1 = strip_tags($_POST['new1_upass']);
  $new_2 = strip_tags($_POST['new2_upass']);
  $user_id = $auth_user->getUserID($EMAIL);

  if($new_1=="" || $new_2==""){
    $error = "$repeat_new_pass";
  } else if(strlen($new_1) < 6 ){
		$error = "$new_pass_short";
	} else if ($new_1 != $new_2) {
		$error = "$pass_not_match";
  } else if ($auth_user->checkvalidToken ($KEY,$TYPE,$EMAIL) === "true") {
    if($auth_user->resetDetails($new_1,$EMAIL)){
        if($auth_user->updateModified($user_id,$date) && $auth_user->disableRestLink($KEY)){
          $_SESSION['reset_success'] = "$reset_success<br><br>";
          $auth_user->redirect('../../m/');
        }
      } else {
        $error = "SYSTEM ERROR!";
      }
    } else {
      $error = "$reset_fail";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $title ?></title>
<link rel="stylesheet" href="../../cnf/style.css" type="text/css"  />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../../js/passHover.js"></script>
</head>
<body style="min-width:600px; max-width:800px;">
    <form method="post" class="imgcontainer">
		<table align="right">
			<tr>
				<div class="imgcontainer">
					 <header class="banner" style="width:71%">
						<h1><?php echo $title ?></h1>
						<h3><?php echo $name ?></h3>
					</header>
					</div>
			</tr>
        <div id="error">
                <?php if(isset($error)) { ?>
                        <div class="alert alert-danger">
                          <?php echo "<br>".mb_strtoupper($error, 'UTF-8').""; ?>
                        </div>
                        <?php } ?>
        </div>
			</table>
            <div class="container">
                <input id="method" type="password"  size="10" class="Input" name="new1_upass" placeholder="<?php echo mb_strtoupper($profile_new, 'UTF-8') ?>" value="<?php if(isset($error)){echo $new_1;}?>" />
                </input>
              <br>
		<input id="method2" type="password"  size="10" class="Input" name="new2_upass" placeholder="<?php echo mb_strtoupper($profile_repeat, 'UTF-8') ?>" value="<?php if(isset($error)){echo $new_2;}?>" />
                </input>
							<br>
            <div>
            	<button type="submit" name="btn-reset">
                	<B><?php echo mb_strtoupper($reset, 'UTF-8') ?></B>
                </button>
            </div>
        </form>
       </div>
			 <br>
    <footer style="width:95%">
      <p>Copyright Ricardo Wagemaker © 2009 - 2018  </p>
    </footer>

</body>
</html>
