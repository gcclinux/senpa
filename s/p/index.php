<?php
require_once("../session.php");
require_once(__DIR__ . '/../cnf/class.user.php');
$user = new SENPA();

$LANG = $_SESSION['user_lang'];
include("../cnf/lang_$LANG.php");

$user_id = $_SESSION['user_session'];
$user_name = $user->getUserName($user_id);
$user_email = $user->getUserEmail($user_id);
$user_token = $user->getUserToken($user_id);
$date = date('Y-m-d H:i:s');

if ($user_id == ""){
	$user_id->redirect('../');
}

if(isset($_POST['btn-signup'])) {

  $uemail = strip_tags($_POST['curr_email']);
	$current = strip_tags($_POST['curr_upass']);
	$new_1 = strip_tags($_POST['new1_upass']);
	$new_2 = strip_tags($_POST['new2_upass']);
	$lang = strip_tags($_POST['radio']);

	$duplicate = $user->checkDuplicateEmail($uemail);

	if($current=="" && $lang!="" && $new_1=="" && $new_2==""){
		if($user->updateLang($user_id,$lang)){
			if($user->updateModified($user_id,$date)){
				$user->redirect('../m/');
			}
		} else {
			$error[] = "$failed_lang";
		}
	} else if($current=="" && $lang=="")	{
		$error[] = "$provide_curr_pass";
	} else if($new_1=="")	{
		$error[] = "$provide_new_pass";
	} else if($new_2=="")	{
		$error[] = "$repeat_new_pass";
  } else if(strlen($new_1) < 6 ){
		$error[] = "$new_pass_short";
	} else if ($new_1 != $new_2) {
		$error[] = "$pass_not_match";
	} else if ($uemail!=="" && ($duplicate==="true") && ($uemail!==$user_email)) {
	 	$error[] = "$invalid_email";
	} else {
		if($user->updateDetails($user_id,$current,$new_1,$new_2,$uemail)){
				if($user->updateModified($user_id,$date)){
					$user->redirect('../m/');
				}
		} else {
			$error[] = "$incorrect_pass";
		}
	}
} elseif (isset($_POST['btn-delete'])) {
		if($user->delete_user($user_token)){
			$user->redirect('../logout.php');
		}
}

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title ?></title>
<link rel="stylesheet" href="../cnf/style.css" type="text/css"  />
<script src="../js/autoclose.js"></script>

</head>
<body style="min-width:600px; max-width:800px;">
    <form method="post" class="imgcontainer">
	<table align="right">
		<tr>
			<div class="imgcontainer">
			 	<header class="banner" style="width:75%;margin-left:10%;">
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
	<br>

      <?php
	if(isset($error)) {
	 	foreach($error as $error)
	 	{
            	echo "<br><label style=\"color:red\">".ucwords($error)."</label>";
		}
		} else {
			echo "<br>";
		}
		?>
      <div class="container" style="width:100%;margin-left:-13px;">
		<input type="text" size="10" class="Input" style="color:black;background-color:lightgrey;"; value="<?php echo $user_name ?>" readonly="readonly" />
		<input type="text" size="10" class="Input" style="color:black;background-color:lightgrey;"; value="<?php echo $user_token ?>" readonly="readonly" />
		<input type="text" size="10" class="Input" name="curr_email" placeholder="<?php echo $user_email ?>" value="<?php if(isset($error)){echo $uemail;}?>" />
         	<input type="password"  size="10" class="Input" name="curr_upass" placeholder="<?php echo mb_strtoupper($profile_current, 'UTF-8') ?>" value="<?php if(isset($error)){echo $current;}?>" />
		<input type="password"  size="10" class="Input" name="new1_upass" placeholder="<?php echo mb_strtoupper($profile_new, 'UTF-8') ?>" value="<?php if(isset($error)){echo $new_1;}?>" />
		<input type="password"  size="10" class="Input" name="new2_upass" placeholder="<?php echo mb_strtoupper($profile_repeat, 'UTF-8') ?>" value="<?php if(isset($error)){echo $new_2;}?>" />
						<button class="delete" type="submit" name="btn-delete">
								<B><?php echo ucwords($delete_account) ?></B>
						</button>
		<br>
			<tr>
				<td style="border:none;" colspan="2">
					<hr width="80%">
					<label><?php echo ucfirst($language); ?></labe><br>
					<input type="radio" name="radio" value="en"><img src="../png/uk.png" alt="Loading..." width="32px" height="19px"></img></input>
					<input type="radio" name="radio" value="br"><img src="../png/brasil.png" alt="Loading..." width="32px" height="20px"></img></input>
					<input type="radio" name="radio" value="pl"><img src="../png/pl.gif" alt="Loading..." width="32px" height="20px"></img></input>
					<input type="radio" name="radio" value="ta"><img src="../png/india.png" alt="Loading..." width="32px" height="20px"></img></input>
				</td>
			</tr>
            <div>
            	<button type="submit" name="btn-signup">
                	<B><?php echo ucwords($profile_update) ?></B>
                </button>
            </div>
        </form>
       </div>
			 <br>
    <footer style="width:95%">
      <?php include("../a/footer.php"); ?>
    </footer>

</body>
</html>
