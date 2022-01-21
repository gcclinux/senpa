<?php

require_once(__DIR__ . '/dbconfig.php');

class SENPA
{

	private $conn;

	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
  }

	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public function get_client_ip() {
	       	$ipaddress = '';
	       	if (getenv('HTTP_CLIENT_IP'))
	       		$ipaddress = getenv('HTTP_CLIENT_IP');
	                	else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			        else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
			        else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
			        else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
			        else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
			        else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public function delete_user($token_id){
		try{
			$stmt = $this->runQuery("DELETE senpa_admins, senpa_passwd FROM senpa_admins INNER JOIN senpa_passwd ON senpa_admins.user_id = senpa_passwd.user_id
			WHERE senpa_admins.crypt_key = '$token_id'");
			$stmt->execute();
			return $stmt;
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function getUserName($user_id){
		try {
			$userName = '';
			$stmt = $this->runQuery("SELECT user_name FROM senpa_admins WHERE user_id='$user_id'");
			$stmt->execute();
			$userName=$stmt->fetch(PDO::FETCH_ASSOC);
			return $userName['user_name'];
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function getUserAttempt($u_name){
		try {
			$failed = '';
			$stmt = $this->runQuery("SELECT failed FROM senpa_admins WHERE user_name='$u_name' OR user_email='$u_name'");
			$stmt->execute();
			$failed=$stmt->fetch(PDO::FETCH_ASSOC);
			return $failed['failed'];
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function setUserAttempt($u_name,$attempts){
		try {
			$stmt = $this->conn->prepare("UPDATE senpa_admins SET failed='$attempts' WHERE user_name='$u_name' OR user_email='$u_name'");
			$stmt->execute();
			return $stmt;
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function setUserLock($u_name,$failed){
		try {
			$stmt = $this->conn->prepare("UPDATE senpa_admins SET user_status='$failed' WHERE user_name='$u_name' OR user_email='$u_name'");
			$stmt->execute();
			return $stmt;
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function setUserUnlocked($email,$status,$count){
		try {
			$stmt = $this->conn->prepare("UPDATE senpa_admins a INNER JOIN senpa_activation b ON a.user_email = b.user_email
				SET
					a.user_status='$status', a.failed='$count', b.status='done'
					WHERE a.user_email='$email' AND b.user_email='$email' AND b.status IS NULL");
			$stmt->execute();
			return $stmt;
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function getUserToken($user_id){
		try {
			$userToken = '';
			$stmt = $this->runQuery("SELECT crypt_key FROM senpa_admins WHERE user_id='$user_id'");
			$stmt->execute();
			$userToken=$stmt->fetch(PDO::FETCH_ASSOC);
			return $userToken['crypt_key'];
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function getAllpasswd($user_id){
		try {
			$allPasswd = '';
			$stmt2 = $this->runQuery("SELECT pass_id,user_id,group_name,site_name,login_name,login_pass,site_url,totp_name,totp_time,totp_lengh
				FROM senpa_passwd WHERE user_id='$user_id' ORDER BY group_name");
			$stmt2->execute(array());
			return $allPasswd = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getFilterpasswd($user_id,$selected){
		try {
			$filterPasswd = '';
			$stmt2 = $this->runQuery("SELECT pass_id,user_id,group_name,site_name,login_name,login_pass,site_url,totp_name,totp_time,totp_lengh
				FROM senpa_passwd WHERE user_id='$user_id' AND group_name ='$selected'");
			$stmt2->execute(array());
			return $filterPasswd = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	// search all results for user, decrypt results & $search_string and find entries LIKE and add to array.
	public function getSearchDetails($user_id,$search_string){
		try {
			$searchString = openssl_decrypt($search_string,"AES-256-ECB",$this->getUserToken($user_id));
			$searchResult = '';
			$submitResult = array();
			$searchStart = array();
			$stmt = $this->runQuery("SELECT pass_id,user_id,group_name,site_name,login_name,login_pass,site_url,totp_name,totp_time,totp_lengh, comments
				FROM senpa_passwd WHERE user_id='$user_id'");
				$stmt->execute(array());
				$searchResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($searchResult as $row) {
					$pass_id = $row['pass_id'];
					$site_name = openssl_decrypt($row['site_name'],"AES-256-ECB",$this->getUserToken($user_id));
					$login_name = openssl_decrypt($row['login_name'],"AES-256-ECB",$this->getUserToken($user_id));
					$site_url = openssl_decrypt($row['site_url'],"AES-256-ECB",$this->getUserToken($user_id));
					$comments = openssl_decrypt($row['comments'],"AES-256-ECB",$this->getUserToken($user_id));

					if (stripos($site_name, $searchString) !== false || stripos($site_url, $searchString) !== false
					|| stripos($login_name, $searchString) !== false || stripos($comments, $searchString) !== false) {
						$stmt2 = $this->runQuery("SELECT pass_id,user_id,group_name,site_name,login_name,login_pass,site_url,totp_name,totp_time,totp_lengh, comments
							FROM senpa_passwd WHERE user_id='$user_id' AND pass_id='$pass_id'");
							$stmt2->execute(array());
							$searchStart = $stmt2->fetchAll(PDO::FETCH_ASSOC);
					}
					$submitResult = array_merge($submitResult, $searchStart);
				}
				$result = array();
				foreach ($submitResult as $key => $value){
				  if(!in_array($value, $result))
				    $result[$key]=$value;
				}
				$submitResult = $result;

				return $submitResult;
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getPasswdGroups($user_id){
		try {
			$grpPasswd = '';
			$stmt1 = $this->runQuery("SELECT user_id, group_name FROM senpa_passwd WHERE user_id='$user_id' GROUP BY group_name,user_id");
			$stmt1->execute(array());
			return $grpPasswd = $stmt1->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getOtcTime($totp_name){
		try {
			$time_otc = '';
			$stmt = $this->runQuery("SELECT totp_time FROM senpa_passwd WHERE totp_name ='$totp_name'");
			$stmt->execute();
			$time_otc = $stmt->fetch(PDO::FETCH_ASSOC);
			return $time_otc['totp_time'];
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function getOtcLengh($totp_name){
		try {
			$length_otc = '';
			$stmt = $this->runQuery("SELECT totp_lengh FROM senpa_passwd WHERE totp_name ='$totp_name'");
			$stmt->execute();
			$length_otc = $stmt->fetch(PDO::FETCH_ASSOC);
			return $length_otc['totp_lengh'];
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function getActivationToken($user_id,$user_email){
		try {
			$activationToken = '';
			$stmt = $this->runQuery("SELECT token FROM senpa_activation WHERE user_id='$user_id' AND user_email='$user_email'");
			$stmt->execute();
			$activationToken=$stmt->fetch(PDO::FETCH_ASSOC);
			return $activationToken['token'];
		} catch(PDOException $e) { echo $e->getMessage();}
	}

	function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

public function personalKey($length){
	$personalKey = '';
	do {
			$personalKey = $this->generateRandomString(24);
	} while ($this->generateRandomString($personalKey) === "true");

	return $personalKey;
}

public function checkpersonalKey($personalKey)
{
	try
	{
		$result = '';
		$stmt = $this->runQuery("SELECT crypt_key FROM senpa_admins");
		$stmt->execute(array());
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		if($row['crypt_key'] === $personalKey) {
			$result = "true";
		} else {
			$result = "false";
		}
		return $result;
	} catch(PDOException $e) { echo $e->getMessage();	}
}

public function doUnlock($email,$lang,$type){
	try {
			$LANG = $_SESSION['user_lang'];
			include(__DIR__."/../cnf/lang_$LANG.php");
			$_SESSION['new_created'] = "";

			$resetLang = $LANG;
			$resetEmail = $email;
			$resetID = $this->getUserID($resetEmail);

			do {
			    $resetKey = $this->generateResetKey(64);
			} while ($this->generateResetKey($resetKey) === "true");

			$stmt = $this->conn->prepare("INSERT INTO senpa_activation(user_id,token,type,user_email) VALUES('$resetID', '$resetKey', '$type', '$resetEmail')");
			$stmt->execute();

			$_SESSION['reset_email'] = $resetEmail;
			$_SESSION['reset_token'] = $resetKey;
			$_SESSION['reset_lang'] = $resetLang;

			if ($type === "unlock"){
				$_SESSION['reset_sent'] = $unlock_mail."<br><br>";
				$_SESSION['reset_type'] = "unlock";
				require_once("../../../../senpa-cnf/activateuser.php");
				$this->redirect("./s/");
			}

			return $stmt;

			} catch(PDOException $e) {
					echo "HERE: ".$e->getMessage();
	 }
}

public function doReset($email,$lang,$type){
	try {
			$LANG = $_SESSION['user_lang'];
			include(__DIR__."/../cnf/lang_$LANG.php");
			$_SESSION['new_created'] = "";

			$resetLang = $LANG;
			$resetEmail = $email;
			$resetID = $this->getUserID($resetEmail);

			do {
			    $resetKey = $this->generateResetKey(64);
			} while ($this->generateResetKey($resetKey) === "true");

			$stmt = $this->conn->prepare("INSERT INTO senpa_activation(user_id,token,type,user_email) VALUES('$resetID', '$resetKey', '$type', '$resetEmail')");
			$stmt->execute();

			$_SESSION['reset_email'] = $resetEmail;
			$_SESSION['reset_token'] = $resetKey;
			$_SESSION['reset_lang'] = $resetLang;

			if ($type === "reset"){
				$_SESSION['reset_sent'] = $reset_mail."<br><br>";
				$_SESSION['reset_type'] = "reset";
				require_once(__DIR__."/activateuser.php");
				$this->redirect("./s/");
			} else if ($type === "new"){
				$_SESSION['reset_sent'] = $activate_email."<br><br>";
				$_SESSION['reset_type'] = "new";
				require_once(__DIR__."/activateuser.php");
			}

			return $stmt;

			} catch(PDOException $e) {
					echo "HERE: ".$e->getMessage();
	 }
}

public function generateResetKey($length) {
		$characters = 'abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$resetKey = '';
		for ($i = 0; $i < $length; $i++) {
				$resetKey .= $characters[rand(0, $charactersLength - 1)];
		}
		return $resetKey;
}

public function activateUser($email,$type,$status)
{
	try {
		$update = '';
		$update = $this->conn->prepare("UPDATE senpa_admins SET user_status='$status' WHERE user_email='$email' AND user_status='$type'");
		$update->execute();
		return $update;
	} catch(PDOException $e) { echo $e->getMessage();	}
}

public function disableRestLink($token)
{
	try {
		$status = "done";
		$update = '';
		$update = $this->conn->prepare("UPDATE senpa_activation SET status='$status' WHERE token='$token'");
		$update->execute();
		return $update;
	} catch(PDOException $e) { echo $e->getMessage();	}
}

public function checkvalidToken ($key,$type,$email){
	try
	{
		$result = '';
		$stmt = $this->runQuery("SELECT token,type,user_email,status FROM senpa_activation WHERE token='$key' AND type='$type' AND user_email='$email' AND status IS NULL");
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		if($row['token'] == $key) {
			$result = "true";
		} else {
			$result = "false";
		}
		return $result;
	} catch(PDOException $e) { echo $e->getMessage();	}
}

public function checkDuplicateReset ($key){
	try
	{
		$result = '';
		$stmt = $this->runQuery("SELECT token FROM senpa_activation WHERE token='$key'");
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

		if($row['token'] ?? '$key') {
			$result = "false";
		} else {
			$result = "true";
		}
		return $result;
	} catch(PDOException $e) { echo $e->getMessage();	}
}

public function getUserID($user_email){
	try {
		$userID = '';
		$stmt = $this->runQuery("SELECT user_id FROM senpa_admins WHERE user_email='$user_email'");
		$stmt->execute();
		$userID=$stmt->fetch(PDO::FETCH_ASSOC);
		return $userID['user_id'];

	} catch(PDOException $e) { echo $e->getMessage();}

}

	public function getUserEmail($user_id){
		try {
			$userEmail = '';
			$stmt = $this->runQuery("SELECT user_email FROM senpa_admins WHERE user_id='$user_id'");
			$stmt->execute();
			$userEmail=$stmt->fetch(PDO::FETCH_ASSOC);
			return $userEmail['user_email'];

		} catch(PDOException $e) { echo $e->getMessage();}

	}

	public function generateRandomString($length) {
	    $characters = '@$*#?!0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function generateCaptcha($length) {
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$captchaString = '';
			for ($i = 0; $i < $length; $i++) {
					$captchaString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $captchaString;
	}

	public function updateLang($user_id, $lang)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE senpa_admins SET user_lang='$lang' WHERE user_id='$user_id'");
			$stmt->execute();
			$_SESSION['user_lang'] = $lang;
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function updateLogin($user_id, $currentTime)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE senpa_admins SET last_login='$currentTime' WHERE user_id='$user_id'");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function updateModified($user_id, $currentTime)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE senpa_admins SET modified='$currentTime' WHERE user_id='$user_id'");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function checkDuplicateUser ($uuser){
		try
		{
			$result = '';
			$stmt = $this->runQuery("SELECT user_name FROM senpa_admins WHERE user_name='$uuser'");
			$stmt->execute();
			$row=$stmt->fetch(PDO::FETCH_ASSOC);

			if($row['user_name'] ?? '$uuser') {
				$result = "false";
			} else {
				$result = "true";
			}
			return $result;
		} catch(PDOException $e) { echo $e->getMessage();	}
	}

	public function checkNotNew ($umail){
		try
		{
			$result = '';
			$stmt = $this->runQuery("SELECT user_email, user_status FROM senpa_admins WHERE user_email='$umail'");
			$stmt->execute();
			$row=$stmt->fetch(PDO::FETCH_ASSOC);

			if(($row['user_email'] === $umail) && ($row['user_status'] !== "new")) {
				$result = "true";
			} else {
				$result = "false";
			}
			return $result;
		} catch(PDOException $e) { echo $e->getMessage();	}
	}

	public function checkDuplicateEmail ($umail){
		try
		{
			$result = '';
			$stmt = $this->runQuery("SELECT user_email FROM senpa_admins WHERE user_email='$umail'");
			$stmt->execute();
			$row=$stmt->fetch(PDO::FETCH_ASSOC);

			if($row['user_email'] ?? '$umail') {
				$result = "false";
			} else {
				$result = "true";
			}
			return $result;
		} catch(PDOException $e) { echo $e->getMessage();	}
	}

	public function resetDetails($new_1,$email)
	{
		try {
			$new_pass = password_hash($new_1, PASSWORD_DEFAULT);
			$update = '';
			$update = $this->conn->prepare("UPDATE admins SET user_pass='$new_pass' WHERE user_email='$email'");
			$update->execute();
			return $update;
		} catch(PDOException $e) { echo $e->getMessage();	}
	}

	public function updateDetails($user_id,$current,$new_1,$new_2,$uemail)
	{
		try {
			$stmt = $this->runQuery("SELECT user_id, user_name, user_email, user_pass FROM senpa_admins WHERE user_id='$user_id'");
			$stmt->execute(array());
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 1) {
				if(password_verify($current, $userRow['user_pass']))
				{
					$new_pass = password_hash($new_1, PASSWORD_DEFAULT);
					$update = '';
					if ($uemail!==""){
						$update = $this->runQuery("UPDATE senpa_admins SET user_pass='$new_pass', user_email='$uemail' WHERE user_id='$user_id'");
					} else {
						$update = $this->runQuery("UPDATE senpa_admins SET user_pass='$new_pass' WHERE user_id='$user_id'");
					}
					$update->execute();
					$this->redirect('../m/');
				} else {
					$error[] = "$incorrect_pass";
				}
			} else {
				$error[] = "$incorrect_user";
			}

		} catch(PDOException $e) { echo $e->getMessage();}
	}

	public function importentry($user_id,$created,$modified,$edate,$ugroup,$uname,$umail,$upass,$uurl,$utotp,$otpt,$otpl,$ucomm)
	{
		try
		{

			if ($otpt==="")
			$otpt = 0;
			if ($otpl==="")
			$otpl = 0;

			$e_pass = openssl_encrypt($upass,"AES-256-ECB",$this->getUserToken($user_id));
			$e_name = openssl_encrypt($uname,"AES-256-ECB",$this->getUserToken($user_id));
			$e_mail = openssl_encrypt($umail,"AES-256-ECB",$this->getUserToken($user_id));
			$e_url = openssl_encrypt($uurl,"AES-256-ECB",$this->getUserToken($user_id));
			$e_com = openssl_encrypt($ucomm,"AES-256-ECB",$this->getUserToken($user_id));
			$e_totp = openssl_encrypt($utotp,"AES-256-ECB",$this->getUserToken($user_id));

			$stmt = $this->conn->prepare("INSERT INTO senpa_passwd(user_id,created,modified,expiration,group_name,site_name,login_name,login_pass,site_url,totp_name,totp_time,totp_lengh,comments)
					VALUES('$user_id', '$created', '$modified', '$edate', '$ugroup', '$e_name', '$e_mail', '$e_pass', '$e_url', '$e_totp', '$otpt', '$otpl', '$e_com')");

			if ($user_id == "unknow"){
				header("Location: ../logout.php");
			}

			$stmt->execute();
			return $stmt;

		} catch(PDOException $e) {
			echo "IMPORTED: ".$e->getMessage();
		}
	}

	public function newotpentry($user_id,$udate,$ugroup,$uname,$umail,$upass,$uurl,$utotp,$ttotp,$ltotp,$ucomm)
	{
		try
		{
			$e_pass = openssl_encrypt($upass,"AES-256-ECB",$this->getUserToken($user_id));
			$e_name = openssl_encrypt($uname,"AES-256-ECB",$this->getUserToken($user_id));
			$e_mail = openssl_encrypt($umail,"AES-256-ECB",$this->getUserToken($user_id));
			$e_url = openssl_encrypt($uurl,"AES-256-ECB",$this->getUserToken($user_id));
			$e_com = openssl_encrypt($ucomm,"AES-256-ECB",$this->getUserToken($user_id));
			$e_totp = openssl_encrypt($utotp,"AES-256-ECB",$this->getUserToken($user_id));

			$stmt = $this->conn->prepare("INSERT INTO senpa_passwd(user_id,expiration,group_name,site_name,login_name,login_pass,site_url,totp_name,totp_time,totp_lengh,comments)
					VALUES('$user_id', $udate', '$ugroup', '$e_name', '$e_mail', '$e_pass', '$e_url', '$e_totp', '$ttotp', '$ltotp', '$e_com')");

			if ($user_id == "unknow"){
				header("Location: ../logout.php");
			}

			$stmt->execute();
			return $stmt;

		} catch(PDOException $e) {
			echo "HERE: ".$e->getMessage();
		}
	}

	public function newentry($user_id,$udate,$ugroup,$uname,$umail,$upass,$uurl,$utotp,$ucomm)
	{
		try
		{
			$e_pass = openssl_encrypt($upass,"AES-256-ECB",$this->getUserToken($user_id));
			$e_name = openssl_encrypt($uname,"AES-256-ECB",$this->getUserToken($user_id));
			$e_mail = openssl_encrypt($umail,"AES-256-ECB",$this->getUserToken($user_id));
			$e_url = openssl_encrypt($uurl,"AES-256-ECB",$this->getUserToken($user_id));
			$e_com = openssl_encrypt($ucomm,"AES-256-ECB",$this->getUserToken($user_id));
			$e_totp = openssl_encrypt($utotp,"AES-256-ECB",$this->getUserToken($user_id));

			$stmt = $this->conn->prepare("INSERT INTO senpa_passwd(user_id,expiration,group_name,site_name,login_name,login_pass,site_url,totp_name,comments)
		                       VALUES('$user_id', '$udate', '$ugroup', '$e_name', '$e_mail', '$e_pass', '$e_url', '$e_totp', '$e_com')");

			if ($user_id == "unknow"){
				header("Location: ../logout.php");
			}

			$stmt->execute();
			return $stmt;

		} catch(PDOException $e) {
			echo "NEW ENTRY: ".$e->getMessage();
		}
	}

	public function register($uname,$umail,$upass,$status,$lang,$personalKey)
	{
		try
		{
			$new_password = password_hash($upass, PASSWORD_DEFAULT);

			$stmt = $this->conn->prepare("INSERT INTO senpa_admins(user_name,user_email,user_pass,user_status,user_lang,crypt_key)
		                 VALUES('$uname', '$umail', '$upass', '$status', '$lang', '$personalKey')");

			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}


	public function doLogin($uname,$umail,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT user_id, user_name, user_email, user_pass, user_status, user_lang FROM senpa_admins WHERE user_name=:uname OR user_email=:umail ");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($upass, $userRow['user_pass']) && ($userRow['user_status']==="active"))
				{
					$_SESSION['user_session'] = $userRow['user_id'];
					$_SESSION['user_lang'] = $userRow['user_lang'];
					return true;
				} else if (password_verify($upass, $userRow['user_pass']) && ($userRow['user_status']==="new")) {
					$_SESSION['reset_email'] = $userRow['user_email'];
					$_SESSION['reset_token'] = $this->getActivationToken($userRow['user_id'],$userRow['user_email']);
					$_SESSION['reset_lang'] = $userRow['user_lang'];
					$_SESSION['reset_type'] = $userRow['user_status'];
					$_SESSION['user_status'] = $userRow['user_status'];
					return false;
				} else {
					$_SESSION['user_status'] = $userRow['user_status'];
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}

	public function redirect($url)
	{
		header("Location: $url");
	}

	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
}
?>
