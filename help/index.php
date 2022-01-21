<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); /* Starts the session */

if (isset($_GET['lang'])) {
    $_SESSION['user_lang'] = $_GET['lang'];
} else {
  $_SESSION['user_lang'] = 'en';
}
# /var/www/html/senpa/s/cnf/class.user.php
require_once(__DIR__ . '/../s/cnf/class.user.php');
$LANG = $_SESSION['user_lang'];
include(__DIR__ . "/../s/cnf/lang_$LANG.php");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include(__DIR__ . "/header.php"); ?>
  </head>

  <main style="width:80%">
	<form action="" method="post" name="FRAME">
    <header class="banner" style="margin-top: 10px;">
			<h1><?php echo $title ?></h1>
			<h3><?php echo $name ?></h3>
    </header>
		<body>
        <td class="noBorder">
          <hr width="92%">
          <td class="png">
            <label><?php echo ucfirst($return); ?></labe><br>
            <a href="../"><img src="../s/png/close.png"></a>
          </td>
          <BR>
          <hr width="92%">
        </td>
          <article> <?php echo mb_strtoupper($make_account, 'utf-8')?> </article>
          <section style="text-align: left;"> <?php echo ucfirst($how_make_account)?></section>
          <br>
          <article> <?php echo mb_strtoupper($reset_subject, 'utf-8')?> </article>
          <section style="text-align: left;"> <?php echo ucfirst($how_reset_account)?></section>
          <br>
          <article> <?php echo mb_strtoupper($unlock, 'utf-8')?> </article>
          <section style="text-align: left;"> <?php echo ucfirst($how_unlock_account)?></section>
          <br>
          <article> <?php echo mb_strtoupper($support, 'utf-8')?> </article>
          <section style="text-align: left;"> <?php echo ucfirst($how_support_account)?></section>
          <br>
          <article> <?php echo mb_strtoupper($profile, 'utf-8')?> </article>
          <section style="text-align: left;"> <?php echo ucfirst($how_profile_account)?></section>
          <br>
          <article> <?php echo mb_strtoupper($record_entry, 'utf-8')?> </article>
          <section style="text-align: left;"> <?php echo ucfirst($how_record_entry)?></section>
          <br>
          <article> <?php echo ucwords($main_article_title_2)?> </article>
          <section style="text-align: left;"> <?php echo ucfirst($main_article_msg_2)?></section>
          <br>

		</body>
    <footer>
      <?php include(__DIR__ . "/../s/a/footer.php"); ?>
    </footer>
	</form>
</html>
