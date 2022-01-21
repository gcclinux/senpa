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

if (isset($_POST["import"])) {

	$real_user = $_SESSION['real_user'];
	echo $real_user;

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

		if (isset($_POST["import"])) {

		    $fileName = $_FILES["file"]["tmp_name"];

		    if ($_FILES["file"]["size"] > 0) {

		        $file = fopen($fileName, "r");

		        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
				if($user->importentry($user_id,$column[0],$column[1],$column[2],$column[3],$column[4],$column[5],$column[6],$column[7],$column[8],$column[9],$column[10],$column[11])){
					$user->redirect('../m/');
				} else {
					$type = "error";
					$message = "Problem in Importing CSV Data";
				}
		        }
		    }
		}

}
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $title ?></title>
<link rel="stylesheet" href="../cnf/style.css" type="text/css"  />
<script src="jquery-3.2.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#frmCSVImport").on("submit", function () {

	    $("#response").attr("class", "");
        $("#response").html("");
        var fileType = ".csv";
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
        if (!regex.test($("#file").val().toLowerCase())) {
        	    $("#response").addClass("error");
        	    $("#response").addClass("display-block");
            $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
            return false;
        }
        return true;
    });
});
</script>
</head>
<body>
	<center>
    <form class="imgcontainer" enctype="multipart/form-data" action="" method="post" name="frmCSVImport" id="frmCSVImport" style="width:60%;" >
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
				<label><?php echo ucfirst($return); ?></labe><br>
				<a href="../m/"><img src="../png/close.png"></a>
			</td>
			</tr>
			</table>

					<br>
					<table style="width:85%" align="center" border="5" >
						<tr>
							<div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
						</tr>
					<tr>
					<td colspan="2" align="center"><br><strong><?php echo strtoupper($important).":</strong> ".$csv_import_notice."<br>"?><br></td>
					</tr>
					<tr>
					<td align="center">&nbsp;&nbsp;CSV <?php echo $file ?>:&nbsp;&nbsp;</td><td>&nbsp;<input type="file" name="file" id="file" accept=".csv"></td></tr>
					</table>
					<tr>
						<button type="submit" id="submit" name="import" class="btn-submit"><?php echo $import ?></button>
					</tr>
				</form>
				<br>
<footer style="width:78%">
	<p>Copyright Ricardo Wagemaker © 2009 - 2018  </p>
</footer>

</body>
</html>
