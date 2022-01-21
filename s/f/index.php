<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	require_once("../session.php");
	require_once(__DIR__ . '/../cnf/class.user.php');

	$_SESSION['item_id'] = "unknown";

	$LANG = $_SESSION['user_lang'];
  include("../cnf/lang_$LANG.php");

	$auth_user = new SENPA();
	$user_id = $_SESSION['user_session'];
	$search_string = null;

	if ($user_id == ""){
		$user_id->redirect('../');
	}

	$selected = $_GET['filter'];

	$groups = $auth_user->getPasswdGroups($user_id);
	$lines = $auth_user->getFilterpasswd($user_id,$selected);

	if(isset($_POST['btn-signup']))
	{
		$search_string = strip_tags($_POST['s_string']);
		$encrypt_search = openssl_encrypt($search_string,"AES-256-ECB",$auth_user->getUserToken($user_id));
		$search_result = $auth_user->getSearchDetails($user_id,$encrypt_search);
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" type="text/css" href="../cnf/style.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="../js/popmenu.js"></script>
		<script src="../js/autoclose.js"></script>
  </head>
	<main>
	<center>
	    <header class="banner" style="width:98%;">
				<h1><?php echo $title ?></h1>
				<h3><?php echo $name ?></h3>
	    </header>
	<body>
    <nav>
      <ul>
        <div class="dropdown">
					<button onclick="myMenu()" class="dropbtn"><?php echo $entries ?></button>
  				<div id="myDropdown" class="dropdown-content">
			    		<a href="../m/"><?php echo ucwords($overview) ?></a>
			    		<a href="../e/"><?php echo ucwords($newEntry) ?></a>
							<a href="../o/"><?php echo ucwords($new_otc) ?></a>
							<a href="../b/"><?php echo ucfirst($exp_csv) ?></a>
							<a href="../i/"><?php echo ucfirst($imp_csv) ?></a>
							<a>
								<form method="post" action="#showmsg">
									<input type="hidden" name="gen_pass" value="gen_pass">
									<input type="submit" value="<?php echo ucwords($generate) ?>">
								</form>
							</a>
					</div>
  			</div>

				<div class="dropdown">
					<button onclick="nextMenu()" class="dropbtn"><?php echo $filter ?></button>
					<div id="nextDropdown" class="dropdown-content">
						<a href="./index.php?filter=search">&rarr; <?php echo ucwords($search) ?></a>
							<?php
								foreach ($groups as $row){
									$current_group = $row["group_name"];
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
									echo "<a href=\"./index.php?filter=".$row["group_name"]."\">&rarr; ".ucwords($real_name)."</a>";
							 		}
							 ?>
					</div>
				</div>
				&nbsp;&nbsp;
        <li class="li"><a style="text-decoration: none;" href="../p/"><?php echo " ".$profile ?></a></li>
				&nbsp;&nbsp;
        <li class="li"><a style="text-decoration: none;" href="../logout.php"><?php echo $logout ?></a></li>
      </ul>
    </nav>
<br>
	<?php
		$current_group = null;

		if ($selected==='search' && $search_string===null){

			echo "<form autocomplete=\"off\" method=\"post\" class=\"imgcontainer\" style=\"width:742px;\">";
                	echo "<td class=\"png\">";
									echo "<br>";
									include(__DIR__ . "/../a/amazon.php");
									echo "</td>";
			echo "<input autocomplete=\"off\" name=\"hidden\" type=\"text\" style=\"display:none;\">";
			echo "<article style=\"width:95%;margin-bottom: 5px;\">";
			echo "<table style=\"width:100%\" class=\"password-list\">";
			echo "<tr>";
			echo "<td style=\"border:none;width:15%\" align=\"left\"><label> <b> ".ucwords($search).":  </b></label></td>";
			echo "<td colspan=\"3\" style=\"border:none;width:70%\" align=\"center\">";
			echo "<input style=\"text-align:center; width:100%;\" type=\"text\" name=\"s_string\" placeholder=\"$search\" value=\"$search_string\" autofocus>";
			echo "</td>";
			echo "<td colspan=\"3\" style=\"border:none;width:15%\" align=\"center\">";
			echo "<button type=\"submit\" name=\"btn-signup\">";
			echo "<B></B>";
			echo "</button>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "</article>";
			echo "</form>";

		} else if ($selected==='search' && $search_string!=null){
			if (empty($search_result)){
                		echo "<td class=\"png\">";
				echo "".ucwords($null_result)."!<br>";
			} else {
                	echo "<td class=\"png\">";
				foreach ($search_result as $row) {
					if ($row["group_name"] != $current_group) {
						$current_group = $row["group_name"];
						$real_name = '';
						if($current_group === "GROUP_1"){ $real_name = $GROUP_1;
							} elseif($current_group === "GROUP_2"){ $real_name = $GROUP_2;
							} elseif($current_group === "GROUP_3"){ $real_name = $GROUP_3;
							} elseif($current_group === "GROUP_4"){ $real_name = $GROUP_4;
							} elseif($current_group === "GROUP_5"){ $real_name = $GROUP_5;
							} elseif($current_group === "GROUP_6"){ $real_name = $GROUP_6;
							} elseif($current_group === "GROUP_7"){ $real_name = $GROUP_7;
							} elseif($current_group === "GROUP_8"){ $real_name = $GROUP_8;
							} elseif($current_group === "GROUP_9"){ $real_name = $GROUP_9;
							} elseif($current_group === "GROUP_10"){ $real_name = $GROUP_10;
							} elseif($current_group === "GROUP_11"){ $real_name = $GROUP_11;
							} elseif($current_group === "GROUP_12"){ $real_name = $GROUP_12;
							} elseif($current_group === "GROUP_13"){ $real_name = $GROUP_13;
							} elseif($current_group === "GROUP_14"){ $real_name = $GROUP_14;
							} elseif($current_group === "GROUP_15"){ $real_name = $GROUP_15;
						} else {
							$real_name = $row['group_name'];
						}
					echo "<h2>".mb_strtoupper($real_name, 'UTF-8')."</h2>";
					}

					echo "<article style=\"width:100%\">";
					echo "<table style=\"width:100%\" class=\"password-list\">";
					echo "<tr>";
						echo "<th style=\"width:30%\">".ucfirst($field_name)." </th>";
						echo "<th style=\"width:40%\">".ucfirst($item_user)."</th>";
						echo "<th style=\"width:10%\">".ucfirst($item_show)."</th>";
						echo "<th style=\"width:10%\">".ucfirst($newTOTP)."</th>";
						echo "<th style=\"width:10%\">".ucfirst($item_edit)."</th>";
						echo "<th style=\"width:10%\">".ucfirst($item_url)."</th>";
					echo "</tr>";
					echo "<td>".openssl_decrypt($row['site_name'],"AES-256-ECB",$auth_user->getUserToken($user_id))."</td>";
					echo "<td>".openssl_decrypt($row['login_name'],"AES-256-ECB",$auth_user->getUserToken($user_id))."</td>";

					echo "<td>";
					echo "<form method=\"post\" action=\"#showmsg\">";
					if (openssl_decrypt($row['login_pass'],"AES-256-ECB",$auth_user->getUserToken($user_id))===""){
						echo "<a title=\"\" class=\"show-pass\">";

					} else {
						echo "<input type=\"hidden\" name=\"login_pass\" value=\"".openssl_decrypt($row['login_pass'],"AES-256-ECB",$auth_user->getUserToken($user_id))."\">";
						echo "<input type=\"image\" src=\"../png/show.png\" height=\"32\" width=\"32\" value=\"submit\">";
					}
					echo "</form>";
					echo "</td>";

					echo "<td>";
						if(openssl_decrypt($row['totp_name'],"AES-256-ECB",$auth_user->getUserToken($user_id))===""){
							echo "<a title=\"\" class=\"show-pass\">";
							echo "<img src=\"../png/link-none.png\" height=\"32\" width=\"32\"></img>";
						} else {
							echo "<form method=\"post\" action=\"#showmsg\">";
							echo "<input type=\"hidden\" name=\"totp_id\" value=\"".openssl_decrypt($row['totp_name'],"AES-256-ECB",$auth_user->getUserToken($user_id))."\">";
							echo "<input type=\"hidden\" name=\"totp_lengh\" value=\"".($row['totp_lengh'])."\">";
							echo "<input type=\"hidden\" name=\"totp_time\" value=\"".($row['totp_time'])."\">";
							echo "<input type=\"image\" src=\"../png/timer.png\" height=\"32\" width=\"32\" value=\"submit\">";

							echo "</form>";
						}
					echo "</td>";

					echo "<td>";
					echo "<form method=\"post\" action=\"../u/\">";
					echo "<input type=\"hidden\" name=\"item_id\" value=\"".$row['pass_id']."\">";
					echo "<input type=\"image\" src=\"../png/edit.png\" height=\"32\" width=\"32\" value=\"submit\">";
					echo "</form>";
					echo "</td>";

					echo "<td>";
					if (filter_var(openssl_decrypt($row['site_url'],"AES-256-ECB",$auth_user->getUserToken($user_id)), FILTER_VALIDATE_URL)){
						echo "<a href=\"".openssl_decrypt($row['site_url'],"AES-256-ECB",$auth_user->getUserToken($user_id))."\" title=\"\" class=\"show-pass\">";
						echo "<img src=\"../png/link.png\" height=\"32\" width=\"32\"></img>";
					} else {
						echo "<a title=\"\" class=\"show-pass\">";
						echo "<img src=\"../png/link-none.png\" height=\"32\" width=\"32\"></img>";
						echo "";
					}
					echo "</a>";
					echo "</td>";
					echo "</tr>";
					echo "</table>";
					echo "</article>";

				} // END foreach loop
					}
				} else {
                		echo "<td class=\"png\">";
					foreach ($lines as $row) {
						if ($row["group_name"] != $current_group) {
							$current_group = $row["group_name"];
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
							echo "<h2>".mb_strtoupper($real_name, 'UTF-8')."</h2>";
						}

						echo "<article style=\"width:100%\">";
						echo "<table style=\"width:100%\" class=\"password-list\">";
						echo "<tr>";
							echo "<th style=\"width:30%\">".ucfirst($field_name)." </th>";
							echo "<th style=\"width:40%\">".ucfirst($item_user)."</th>";
							echo "<th style=\"width:10%\">".ucfirst($item_show)."</th>";
							echo "<th style=\"width:10%\">".ucfirst($newTOTP)."</th>";
							echo "<th style=\"width:10%\">".ucfirst($item_edit)."</th>";
							echo "<th style=\"width:10%\">".ucfirst($item_url)."</th>";
						echo "</tr>";
						echo "<td>".openssl_decrypt($row['site_name'],"AES-256-ECB",$auth_user->getUserToken($user_id))."</td>";
						echo "<td>".openssl_decrypt($row['login_name'],"AES-256-ECB",$auth_user->getUserToken($user_id))."</td>";

						echo "<td>";
						echo "<form method=\"post\" action=\"#showmsg\">";
						if (openssl_decrypt($row['login_pass'],"AES-256-ECB",$auth_user->getUserToken($user_id))===""){
							echo "<a title=\"\" class=\"show-pass\">";
							echo "<img src=\"../png/link-none.png\" height=\"32\" width=\"32\"></img>";
						} else {
							echo "<input type=\"hidden\" name=\"login_pass\" value=\"".openssl_decrypt($row['login_pass'],"AES-256-ECB",$auth_user->getUserToken($user_id))."\">";
							echo "<input type=\"image\" src=\"../png/show.png\" height=\"32\" width=\"32\" value=\"submit\">";
						}
						echo "</form>";
						echo "</td>";

						echo "<td>";
						if(openssl_decrypt($row['totp_name'],"AES-256-ECB",$auth_user->getUserToken($user_id))===""){
								echo "<a title=\"\" class=\"show-pass\">";
								echo "<img src=\"../png/link-none.png\" height=\"32\" width=\"32\"></img>";
						} else {
							echo "<form method=\"post\" action=\"#showmsg\">";
							echo "<input type=\"hidden\" name=\"totp_id\" value=\"".openssl_decrypt($row['totp_name'],"AES-256-ECB",$auth_user->getUserToken($user_id))."\">";
							echo "<input type=\"hidden\" name=\"totp_lengh\" value=\"".($row['totp_lengh'])."\">";
							echo "<input type=\"hidden\" name=\"totp_time\" value=\"".($row['totp_time'])."\">";
							echo "<input type=\"image\" src=\"../png/timer.png\" height=\"32\" width=\"32\" value=\"submit\">";

						echo "</form>";
						}
						echo "</td>";

						echo "<td>";
						echo "<form method=\"post\" action=\"../u/\">";
						echo "<input type=\"hidden\" name=\"item_id\" value=\"".$row['pass_id']."\">";
						echo "<input type=\"image\" src=\"../png/edit.png\" height=\"32\" width=\"32\" value=\"submit\">";
						echo "</form>";
						echo "</td>";

						echo "<td>";
						if (filter_var(openssl_decrypt($row['site_url'],"AES-256-ECB",$auth_user->getUserToken($user_id)), FILTER_VALIDATE_URL)){
							echo "<a href=\"".openssl_decrypt($row['site_url'],"AES-256-ECB",$auth_user->getUserToken($user_id))."\" title=\"\" class=\"show-pass\">";
							echo "<img src=\"../png/link.png\" height=\"32\" width=\"32\"></img>";
						} else {
							echo "<a title=\"\" class=\"show-pass\">";
							echo "<img src=\"../png/link-none.png\" height=\"32\" width=\"32\"></img>";
							echo "";
						}
						echo "</a>";
						echo "</td>";
						echo "</tr>";
						echo "</table>";
						echo "</article>";

					} // END foreach loop
				}
				?>
      </section>
			<style>
			.overlay {
				display: block;
			  position: absolute;
			  top: 0;
			  bottom: 0;
			  left: 0;
			  right: 0;
			  background: rgba(0, 0, 0, 0.5);
			  transition: opacity 200ms;
			  visibility: hidden;
			  opacity: 0;
			}
			.overlay.light {
			  background: rgba(255, 255, 255, 0.5);
			}
			.overlay .cancel {
			  position: absolute;
			  width: 100%;
			  height: 100%;
			  cursor: default;
			}
			.overlay:target {
			  visibility: visible;
			  opacity: 1;
			}

			.popup {
			  margin: 75px auto;
			  padding: 20px;
			  background: #fff;
			  border: 10px solid #666;
			  width: 400px;
			  box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
			  position: relative;
			}
			.light .popup {
			  border-color: #aaa;
			  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
			}
			.popup h2 {
			  margin-top: 0;
			  color: #666;
			  font-family: "Trebuchet MS", Tahoma, Arial, sans-serif;
			}
			.popup .close {
			  width: 20px;
			  height: 20px;
			  top: 20px;
			  right: 20px;
			  opacity: 0.8;
			  transition: all 200ms;
			  font-size: 24px;
			  font-weight: bold;
			  text-decoration: none;
			  color: #666;
			}
			.popup .close:hover {
			  opacity: 1;
			}
			.popup .content {
			  max-height: 400px;
			  overflow: auto;
				background: #666;
				color: #ffffff;
			}
			.popup p {
			  margin: 0 0 1em;
			}
			.popup p:last-child {
			  margin: 0;
			}
			</style>

			<div id="showmsg" class="overlay">
					<div class="popup">
							<?php
								if(isset($_POST['totp_id'])){
										echo "<h2>".$newTOTP." ".ucwords($oneTimeM)."</h2>";
								} else if(isset($_POST['login_pass'])){
									echo "<h2>".ucwords($pass_header)."</h2>";
								} else if(isset($_POST['gen_pass'])){
									echo "<h2>".ucwords($generate)."</h2>";
								}
							?>
							<div class="content">
									<p>
										<?php
										echo "
										<script type=\"text/javascript\">
										setTimeout(onUserInactivity, 30 * 1000)
										function onUserInactivity() {
											window.location.href = \"#\"
										}
										</script>
										<script type=\"text/javascript\">
										    var timeleft = 30;
										    var downloadTimer = setInterval(function(){
										    timeleft--;
										    document.getElementById(\"countdowntimer\").textContent = timeleft;
										    if(timeleft <= 0)
										        clearInterval(downloadTimer);
										    },1000);
										</script>
										";
										if(isset($_POST['totp_id'])){
											$totp_id = strip_tags($_POST['totp_id']);
											$TL = strip_tags($_POST['totp_lengh']);
											$TT = strip_tags($_POST['totp_time']);
												require_once("../cnf/ga_".$TL."_".$TT.".php");
												$OneTime = new Google2FA();

												$result = $OneTime->getOneTimePassword($totp_id);
												$valid = $OneTime->verify_key($totp_id,$result);
												echo "<br>ID: ".$totp_id."<br>";
												echo "<br>TOTP: ".$result."<br><br>";
												$_SESSION['qr_code'] = $result;
												echo "<img src=\"../qrcode/generate.php\"></img>";
												echo "<br><br>";
												echo "".ucwords($timeout).": <span id=\"countdowntimer\">30 </span> ".ucwords($seconds)."";
												echo "<br>";
										} else if(isset($_POST['login_pass'])){
												$login = strip_tags($_POST['login_pass']);
												echo "<br>Pass: ".$login."<br><br>";
												echo "".ucwords($timeout).": <span id=\"countdowntimer\">30 </span> ".ucwords($seconds)."";
												echo "<br>";
										} else if(isset($_POST['gen_pass'])){
											echo "<br>".$auth_user->generateRandomString(10)."<br><br>";
											echo "".ucwords($timeout).": <span id=\"countdowntimer\">30 </span> ".ucwords($seconds)."";
											echo "<br>";
										}
										?>
									</p>
							</div>
								<a class="close" href="#">&#9664;</a>
					</div>
			</div>

			<br>
    <footer style="width:88%">
      <?php include("../a/footer.php"); ?>
    </footer>
		</body>
  </main>
</html>
