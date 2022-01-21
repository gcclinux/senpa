<?php
require_once("../session.php");
require_once(__DIR__ . '/../cnf/class.user.php');
$user = new SENPA();
$LANG = $_SESSION['user_lang'];
include("../cnf/lang_$LANG.php");

$user_id = $_SESSION['user_session'];
$item_id = $_POST['item_id'];
$real_user = $_SESSION['real_user'];

// Security check validate user
if ($user_id == ""){
	header("location:../");
}

// Collect all columns from passwd table that matches $item_id

$stmt = $user->runQuery("SELECT pass_id, user_id, created, modified, expiration, group_name, site_name, login_name, login_pass, site_url, totp_name, totp_time, totp_lengh, comments FROM senpa_passwd WHERE pass_id='$item_id'");
$stmt->execute(array());
$lines = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['btn-signup'])){
	//Value 1 = delete / Value 0 = ignore
	$check_value = isset($_POST['delete_checkbox']) ? 1 : 0;
	$pass_id = $_POST['pass_id'];
	$udate = $_POST['txt_date'];
	$ugroup = $_POST['txt_group'];
	$uname = $_POST['txt_uname'];
	$umail = $_POST['txt_umail'];
	$upass = $_POST['txt_upass'];
	$uurl = $_POST['txt_url'];
	$utotp = $_POST['txt_totp'];
	$ttotp = $_POST['otp_time'];
	$ltotp = $_POST['otp_lengh'];
	$ucomm = $_POST['txt_comment'];

	if ($ttotp==="") $ttotp = 0;
	if ($ltotp==="") $ltotp = 0;

	$passwd_e = openssl_encrypt($upass,"AES-256-ECB",$user->getUserToken($user_id));
	$name_e = openssl_encrypt($uname,"AES-256-ECB",$user->getUserToken($user_id));
	$user_e = openssl_encrypt($umail,"AES-256-ECB",$user->getUserToken($user_id));
	$url_e = openssl_encrypt($uurl,"AES-256-ECB",$user->getUserToken($user_id));
	$totp_e = openssl_encrypt($utotp,"AES-256-ECB",$user->getUserToken($user_id));
	$comm_e = openssl_encrypt($ucomm,"AES-256-ECB",$user->getUserToken($user_id));

	if ($check_value == "1" ){
			try{
				$delete = $user->runQuery("DELETE FROM senpa_passwd WHERE pass_id='$pass_id' AND user_id='$user_id'");
				$delete->execute();
				$user->redirect('../m/');
			} catch(PDOException $e) {echo $e->getMessage(); }
		} else {
			try {
				$update = $user->runQuery("UPDATE senpa_passwd SET
					expiration='$udate',
					group_name='$ugroup',
					site_name='$name_e',
					login_name='$user_e',
					login_pass='$passwd_e',
					site_url='$url_e',
					totp_name='$totp_e',
					totp_time='$ttotp',
					totp_lengh='$ltotp',
					comments='$comm_e'
					WHERE pass_id='$pass_id' AND user_id='$user_id'");
				$update->execute();
				$user->redirect('../m/');
			} catch(PDOException $e) { echo $e->getMessage(); }
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
					<div class="imgcontainer">
					<header class="banner" style="width:80%">
						<h1><?php echo $title ?></h1>
						<h3><?php echo $name ?></h3>
					</header>
					</div>
				</tr>
				<tr>
					<td class="png">
						<a href="../m/"><img src="../png/close.png"></a>
					</td>
				</tr>
			</table>
<div class="container">

<?php
	foreach ($lines as $keys){
		echo "<input type=\"hidden\" name=\"item_id\" value=\"".$keys['pass_id']."\">";
		echo "<input type=\"hidden\" name=\"pass_id\" value=\"".$keys['pass_id']."\">";
		echo "<table style=\"width:86%;\" align=\"center\" border=\"1px\">";
		echo "<tr style=\"border:none;\">";
		echo "<th style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($record_entry).":  </b></label></th>";
		echo "<th colspan=\"2\" style=\"border:none;\" align=\"left\"><label style=\"color: #CD1212; font-weight:bold;\"><input type=\"checkbox\" name=\"delete_checkbox\" value=\"".$keys['pass_id']."\">".strtoupper($record_delete)."</label></th>";
		echo "</tr>";

		echo "<tr>";
		echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($modified).":  </b></label> </td>";
		echo "<td style=\"border:none;\" colspan=\"2\">";
		echo "<input style=\"text-align:center; width:100%;\" type=\"text\" name=\"txt_umail\" value=\"".($keys['modified'])."\" readonly>";
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<th style=\"border:none;\" align=\"right\" ><label> <b> ".ucwords($record_expiration).":  </b></label></th>";
		echo "<th colspan=\"2\" style=\"border:none; width:100%;\" align=\"center\"><input style=\"text-align:center; width:100%;\" type=\"date\" name=\"txt_date\" value=\"".$keys['expiration']."\" /></th>";
		echo "</tr>";
		echo "<tr>";
				echo "<td style=\"border:none;\" align=\"right\"><b> ".ucwords($record_groups).":  </b></td>";
				echo "<td colspan=\"2\" style=\"border:none;\" align=\"center\">";
				echo "<select style=\"text-align:center; width:100%;\" name=\"txt_group\" id=\"wgtmsr\" size=\"1\">";
				$current_group = $keys['group_name'];
				$real_name = '';
				if($current_group === "GROUP_1"){
					$real_name = $GROUP_1;
				} elseif($current_group === "GROUP_2"){
					$real_name = $GROUP_2;
				} elseif($current_group === "GROUP_3"){
					$real_name = $GROUP_3;
				} elseif($current_group === "GROUP_4"){
					$real_name = $GROUP_4;
				} elseif($current_group === "GROUP_5"){
					$real_name = $GROUP_5;
				} elseif($current_group === "GROUP_6"){
					$real_name = $GROUP_6;
				} elseif($current_group === "GROUP_7"){
					$real_name = $GROUP_7;
				} elseif($current_group === "GROUP_8"){
					$real_name = $GROUP_8;
				} elseif($current_group === "GROUP_9"){
					$real_name = $GROUP_9;
				} elseif($current_group === "GROUP_10"){
					$real_name = $GROUP_10;
				} elseif($current_group === "GROUP_11"){
					$real_name = $GROUP_11;
				} elseif($current_group === "GROUP_12"){
					$real_name = $GROUP_12;
				} elseif($current_group === "GROUP_13"){
					$real_name = $GROUP_13;
				} elseif($current_group === "GROUP_14"){
					$real_name = $GROUP_14;
				} elseif($current_group === "GROUP_15"){
					$real_name = $GROUP_15;
				} else {
					$real_name = $row['group_name'];
				}
					echo "<option value=\"".$keys['group_name']."\">".mb_strtoupper($real_name, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_1\">".mb_strtoupper($GROUP_1, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_1\">------</option>";
					echo "<option value=\"GROUP_2\">".mb_strtoupper($GROUP_2, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_3\">".mb_strtoupper($GROUP_3, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_4\">".mb_strtoupper($GROUP_4, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_5\">".mb_strtoupper($GROUP_5, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_6\">".mb_strtoupper($GROUP_6, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_7\">".mb_strtoupper($GROUP_7, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_8\">".mb_strtoupper($GROUP_8, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_9\">".mb_strtoupper($GROUP_9, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_10\">".mb_strtoupper($GROUP_10, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_11\">".mb_strtoupper($GROUP_11, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_12\">".mb_strtoupper($GROUP_12, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_13\">".mb_strtoupper($GROUP_13, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_14\">".mb_strtoupper($GROUP_14, 'UTF-8')."</option>";
					echo "<option value=\"GROUP_15\">".mb_strtoupper($GROUP_15, 'UTF-8')."</option>";
					echo "</select><br>";
				echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($field_name).":  </b></label> </td>";
				echo "<td style=\"border:none;\" colspan=\"2\" align=\"center\">";
					echo "<input style=\"text-align:center; width:100%;\" type=\"text\" size=\"700\" name=\"txt_uname\" value=\"".openssl_decrypt($keys['site_name'],"AES-256-ECB",$user->getUserToken($user_id))."\" />";
				echo "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($newTOTP).":  </b></label></td>";
				echo "<td style=\"border:none;\" align=\"center\" colspan=\"2\">";
					echo "<input style=\"text-align:center; width:100%;\" type=\"text\" name=\"txt_totp\" value=\"".openssl_decrypt($keys['totp_name'],"AES-256-ECB",$user->getUserToken($user_id))."\">";
				echo "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td style=\"border:none;\" align=\"right\"><b> ".ucwords("")."  </b></td>";
				echo "<td colspan=\"1\" style=\"border:none;\" align=\"left\">";
				echo "<select style=\"text-align:center; width:50%;\" name=\"otp_time\" id=\"wgtmsr\" size=\"1\">";
				echo "<option value=\"".$keys['totp_time']."\">".$keys['totp_time']." Seconds</option>"; //TODO
					echo "<option value=\"30\">30 Seconds</option>";
					echo "<option value=\"60\">60 Seconds</option>";
					echo "</select>";
				echo "</td>";
				echo "<td colspan=\"1\" style=\"border:none;\" align=\"left\">";
				echo "<select style=\"text-align:center; width:50%;\" name=\"otp_lengh\" id=\"wgtmsr\" size=\"1\">";
				echo "<option value=\"".$keys['totp_lengh']."\">".$keys['totp_lengh']." Characters</option>";
					echo "<option value=\"6\">6 Characters</option>";
					echo "<option value=\"8\">8 Characters</option>";
					echo "</select>";
				echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($item_user).":  </b></label> </td>";
				echo "<td style=\"border:none;\" colspan=\"2\">";
					echo "<input style=\"text-align:center; width:100%;\" type=\"text\" name=\"txt_umail\" value=\"".openssl_decrypt($keys['login_name'],"AES-256-ECB",$user->getUserToken($user_id))."\" />";
				echo "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td style=\"border:none;\" align=\"right\">";
					echo "<input style=\"text-align:center;\" type=\"checkbox\" onclick=\"showPass()\"><b>".ucwords($show_pass)."</b>";
				echo "</td>";
				echo "<td colspan=\"2\" style=\"border:none;\" align=\"center\">";
				echo "<input style=\"text-align:center; width:100%;\" id=\"myInput\" type=\"password\" name=\"txt_upass\" value=\"".openssl_decrypt($keys['login_pass'],"AES-256-ECB",$user->getUserToken($user_id))."\" />";
			echo "</tr>";
			echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($item_url).":  </b></label> </td>";
				echo "<td style=\"border:none;\" colspan=\"2\">";
					echo "<input style=\"text-align:center; width:100%;\" type=\"text\" class=\"Input\" name=\"txt_url\" value=\"".openssl_decrypt($keys['site_url'],"AES-256-ECB",$user->getUserToken($user_id))."\" /></br>";
				echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td style=\"border:none;\" align=\"right\"><label> <b> ".ucwords($comm_ref).":  </b></label> </td>";
				echo "<td style=\"border:none;\" colspan=\"2\">";
					echo "<textarea name=\"txt_comment\" rows=\"5\">".openssl_decrypt($keys['comments'],"AES-256-ECB",$user->getUserToken($user_id))."</textarea></br>";
				echo "</td>";
			echo "</tr>";
		echo "</table>";
	}
 ?>
		<!-- END of loop -->
            <div>
            	<button type="submit" name="btn-signup">
                	<B><?php echo ucwords($submit) ?></B>
                </button>
            </div>
        </form>
       </div>
            <br />
    <footer style="width:58%">
      <?php include("../a/footer.php"); ?>
    </footer>

</body>
</html>
