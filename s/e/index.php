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
	$ucomm = strip_tags($_POST['txt_comment']);
	$user_id = "unknow";

	try {
		$stmt = $user->runQuery("SELECT user_id, user_email FROM senpa_admins WHERE user_email= '$real_user'");
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		if($row['user_id']!="") {
			$user_id = $row['user_id'];
		} else {
			$stmt = $user->runQuery("SELECT user_id, user_name FROM senpa_admins WHERE user_name= '$real_user'");
			$stmt->execute();
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
				if($user->newentry($user_id,$exdate,$ugroup,$uname,$umail,$upass,$uurl,$utotp,$ucomm)){
					$user->redirect('../m/');
				}
			}
		} catch(PDOException $e) { echo $e->getMessage(); }
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
		<div id="error">
			<?php if(isset($error)) { ?>
				<div class="alert alert-danger">
					<?php echo "".mb_strtoupper($error, 'UTF-8')."!<br>"; ?>
				</div>
			<?php } ?>
			</div>
	    <div class="container">
			<?php
			echo "<table style=\"width:90%;\" align=\"center\" border=\"1px\">";
			echo "<tr>";
		    	echo "<th style=\"border:none; width:20%;\" align=\"right\"><label> <b> ".ucwords($record_expiration).":  </b></label></th>";
		    	echo "<th style=\"border:none; width:80%;\" colspan=\"2\" align=\"center\"><input style=\"text-align:center; width:100%;\" type=\"date\" name=\"txt_date\" value=".date('d-m-Y')." /></th>";
				  echo "</tr>";

					echo "<tr>";
						echo "<td style=\"border:none;\" align=\"right\"><b> ".ucwords($record_groups).":  </b></td>";
						echo "<td colspan=\"2\" style=\"border:none;\" align=\"left\">";
						echo "<select style=\"text-align:center; padding-left:20em; width:100%;\" name=\"txt_group\" id=\"wgtmsr\" size=\"1\">";
							echo "<option value=\"GROUP_1\">".mb_strtoupper($GROUP_1, 'UTF-8')."</option>";
							echo "<option value=\"GROUP_1\">------</option>";
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
      <?php include("../a/footer.php"); ?>
    </footer>

</body>
</html>
