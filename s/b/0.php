<?php

        require_once("../session.php");
        require_once(__DIR__ . '/../cnf/class.user.php');
        include("../cnf/lang_en.php");

        $auth_user = new SENPA();
        $user_id = $_SESSION['user_session'];

        $stmt2 = $auth_user->runQuery("SELECT created,modified,expiration,group_name,site_name,login_name,login_pass,site_url,totp_name,totp_time,totp_lengh,comments FROM senpa_passwd WHERE user_id='$user_id'");
        $stmt2->execute(array());
        $lines = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $filename = "senpa-export_" . date('Y-m-d') . ".csv";

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        $delimiter = ",";
        $fields = array("CREATED","MODIFIED", "EXPIRES", "GROUP", "NAME", "LOGIN", "PASSWD", "URL", "OTP", "TIME", "LENGHT", "COMMENTS");
        $fp = fopen('php://output', 'w');
        fputcsv($fp, $fields, $delimiter);

        foreach ($lines as $row){
                $lineData = array(
                        $row['created'],
                        $row['modified'],
                        $row['expiration'],
                        $row['group_name'],
                        openssl_decrypt($row['site_name'],"AES-256-ECB",$auth_user->getUserToken($user_id)),
                        openssl_decrypt($row['login_name'],"AES-256-ECB",$auth_user->getUserToken($user_id)),
                        openssl_decrypt($row['login_pass'],"AES-256-ECB",$auth_user->getUserToken($user_id)),
                        openssl_decrypt($row['site_url'],"AES-256-ECB",$auth_user->getUserToken($user_id)),
                        openssl_decrypt($row['totp_name'],"AES-256-ECB",$auth_user->getUserToken($user_id)),
                        $row['totp_time'],
                        $row['totp_lengh'],
                        openssl_decrypt($row['comments'],"AES-256-ECB",$auth_user->getUserToken($user_id)),
                        );
                fputcsv($fp, $lineData, $delimiter);
        }
?>
