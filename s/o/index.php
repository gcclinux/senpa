<?php
require_once("../session.php");
require_once(__DIR__ . '/../cnf/class.user.php');
$user = new SENPA();

$LANG = $_SESSION['user_lang'];
include("../cnf/lang_$LANG.php");

if($user->is_loggedin()=="")
{
	$user->redirect('../');
}

if(isset($_POST['btn-signup']))

{
	$exdate = strip_tags($_POST['txt_date']);
	$real_user = $_SESSION['real_user'];
	$ugroup = strip_tags($_POST['txt_group']);
	$uname = strip_tags($_POST['txt_uname']);
	$umail = strip_tags($_POST['txt_umail']);
	$upass = strip_tags($_POST['txt_upass']);
	$uurl = strip_tags($_POST['txt_url']);
	$utotp = strip_tags($_POST['txt_totp']);
	$ttotp = strip_tags($_POST['otp_time']);
	$ltotp = strip_tags($_POST['otp_lengh']);
	$ucomm = strip_tags($_POST['txt_comment']);
	$user_id = "unknow";

	try {
		$stmt = $user->runQuery("SELECT user_id, user_email FROM admins WHERE user_email= '$real_user'");
		$stmt->execute(array(':user_id'=>$user_id));
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		if($row['user_id']!="") {
			$user_id = $row['user_id'];
		} else {
			$stmt = $user->runQuery("SELECT user_id, user_name FROM admins WHERE user_name= '$real_user'");
			$stmt->execute(array(':user_id'=>$user_id));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			if($row['user_id']!="") {
				$user_id = $row['user_id'];
			} else {
				$user->redirect('../logout.php');
			}
		}
	} catch(PDOException $e) { echo $e->getMessage();}


	try
		{
			if (strlen($utotp) > 2 && strlen($utotp) < 8) {
				$error = "$totc_short";
			} else {
				if($user->newotpentry($user_id,$exdate,$ugroup,$uname,$umail,$upass,$uurl,$utotp,$ttotp,$ltotp,$ucomm)){
					$user->redirect('../m/');
				}
			}
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $title ?></title>
<link rel="stylesheet" href="../cnf/style.css" type="text/css"  />
<script src="../js/showPass.js"></script>
</head>
<body>
	<center>
    <form autocomplete="off" method="post" class="imgcontainer" style="width:60%;">
	<input autocomplete="off" name="hidden" type="text" style="display:none;">
		<table align="right">
			<tr>
			<td class="png">
				<a href="../m/"><img src="../png/close.png"></a>
			</td>
			</tr>
			<tr>
			<div class="imgcontainer">
				 <header class="banner" style="width:82%">
					<h1><?php echo $title ?></h1>
					<h3><?php echo $name ?></h3>
				</header>
                                <!-- <a href="https://my.tsohost.com/aff.php?aff=5940" target="_blank">
                                <img src="/s/png/Tsohost.Affiliate.Cloud.Hosting.Leaderboard.png" style="width:86.5%;margin-top:10px" alt="tsohost">
                                </a> -->
			</div>
			</tr>
			</table>
		<div id="error">
			<?php if(isset($error)) { ?>
				<div class="alert alert-danger">
					<?php echo "".mb_strtoupper($error, 'UTF-8')."!<br>"; ?>
				</div>
			<?php } ?>
		</div>
	    <div class="container">
		<?php
		echo "<table style=\"width:89.5%\" align=\"center\" border=\"1px\">";
		echo "<tr>";
		    echo "<th style=\"border:none; width:20%;\" align=\"right\"><label> <b> ".ucwords($record_expiration).":  </b></label></th>";
		    echo "<th style=\"border:none; width:80%;\" colspan=\"2\" align=\"center\"><input style=\"text-align:center; width:100%;\" type=\"date\" name=\"txt_date\" value=".date('d-m-Y')." /></th>";
		  echo "</tr>";

		echo "<tr>";
		echo "<td style=\"border:none;\" align=\"right\"><b> ".ucwords($record_groups).":  </b></td>";
		echo "<td colspan=\"2\" style=\"border:none;\" align=\"center\">";
		echo "<select style=\"text-align:center; width:100%; padding-left:17em\" name=\"txt_group\" id=\"wgtmsr\" size=\"1\">";
		echo "<option value=\"GROUP_2\">".mb_strtoupper($otc_select, 'UTF-8')."</option>";
		echo "</select><br>";
		echo "</td>";
		echo "</tr>";

					echo "<tr>";
						echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($field_name).":  </b></label></td>";
						echo "<td colspan=\"2\" style=\"border:none;\" align=\"center\">";
						echo "<input style=\"text-align:center; width:100%;\" type=\"text\" name=\"txt_uname\" placeholder=\"".mb_strtoupper($site_ref, 'UTF-8')."\"  value=\"\">";
						echo "</td>";
					echo "</tr>";

					echo "<tr>";
						echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($newTOTP).":  </b></label></td>";
						echo "<td colspan=\"2\" style=\"border:none;\" align=\"center\">";
						echo "<input style=\"text-align:center; width:100%;\" type=\"text\" name=\"txt_totp\" placeholder=\"$keyDesc\"  value=\"\">";
						echo "</td>";
					echo "</tr>";

					echo "<tr>";
						echo "<td style=\"border:none;\" align=\"right\"><b> ".ucwords("")."  </b></td>";
						echo "<td colspan=\"1\" style=\"border:none;\" align=\"left\">";
						echo "<select style=\"text-align:center; width:50%;\" name=\"otp_time\" id=\"wgtmsr\" size=\"1\">";
							echo "<option value=\"30\">30 Seconds</option>";
							echo "<option value=\"60\">60 Seconds</option>";
							echo "</select>";
						echo "</td>";
						echo "<td colspan=\"1\" style=\"border:none;\" align=\"left\">";
						echo "<select style=\"text-align:center; width:50%;\" name=\"otp_lengh\" id=\"wgtmsr\" size=\"1\">";
							echo "<option value=\"6\">6 Characters</option>";
							echo "<option value=\"8\">8 Characters</option>";
							echo "</select>";
						echo "</td>";
					echo "</tr>";

					echo "<tr>";
						echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($item_user).":  </b></label></td>";
						echo "<td colspan=\"2\" style=\"border:none;\" align=\"center\">";
						echo "<input style=\"text-align:center; width:100%;\" type=\"text\" name=\"txt_umail\" placeholder=\"".mb_strtoupper($user_ref, 'UTF-8')."\"  value=\"\">";
						echo "</td>";
					echo "</tr>";

					echo "<tr>";
						echo "<td style=\"border:none;\" align=\"right\">";
							echo "<input type=\"checkbox\" onclick=\"showPass()\"><b>".ucwords($show_pass)."</b>";
						echo "</td>";
						echo "<td colspan=\"2\" style=\"border:none;\" align=\"center\">";
						echo "<input style=\"text-align:center; width:100%;\"   id=\"myInput\" type=\"password\" name=\"txt_upass\" autocomplete=\"new-password\" placeholder=\"".mb_strtoupper($pass_ref, 'UTF-8')."\" value=\"\" >";
						echo "</td>";
					echo "</tr>";

					echo "<tr>";
						echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($item_url).":  </b></label></td>";
						echo "<td colspan=\"2\" style=\"border:none;\" align=\"center\">";
						echo "<input style=\"text-align:center; width:100%;\" type=\"text\" name=\"txt_url\" placeholder=\"".mb_strtoupper($url_ref, 'UTF-8')."\"  value=\"\">";
						echo "</td>";
					echo "</tr>";

					echo "<tr>";
					echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($comm_ref).":  </b></label> </td>";
						echo "<td style=\"border:none;\" colspan=\"2\">";
							echo "<textarea name=\"txt_comment\" rows=\"5\"></textarea></br>";
						echo "</td>";
					echo "</tr>";

				 ?>
				</table>
            <div>
            	<button type="submit" name="btn-signup">
                	<B><?php echo ucwords($submit) ?></B>
                </button>
            </div>
        </form>
       </div>
            <br />
    <footer style="width:58%">
      <p>Copyright Ricardo Wagemaker © 2009 - 2018  </p>
    </footer>

</body>
</html>
