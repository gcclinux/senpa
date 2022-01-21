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

require_once(__DIR__ . '/s/cnf/class.user.php');
$LANG = $_SESSION['user_lang'];
include(__DIR__ . "/s/cnf/lang_$LANG.php");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include(__DIR__ . "/s/a/header.php"); ?>
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
          <label style="font-size: x-large; font-weight: bold;"><a style="color:black;" href="s/"><?php echo mb_strtoupper($sign_in, 'utf-8')?></a></label>
          <BR>
          <hr width="92%">
          <label style="font-size: medium;"> <?php $_SESSION['new_created'] = ""; $_SESSION['reset_sent'] = ""; ?><a style="color:black;" href="s/n/"><?php echo mb_strtoupper($make_account, 'utf-8') ?></a></label>
          <font style="font-size: x-large;">&#8700;</font>
          <label style="font-size: medium;"> <?php $_SESSION['new_created'] = ""; $_SESION['reset_sent'] = "";?><a style="color:black;" href="s/r/"><?php echo mb_strtoupper($reset, 'utf-8')?></a></label>
          <font style="font-size: x-large;">&#8700;</font>
          <label style="font-size: medium;"> <?php $_SESSION['new_created'] = ""; $_SESION['reset_sent'] = "";?><a style="color:black;" href="s/z/"><?php echo mb_strtoupper($unlock, 'utf-8')?></a></label>
          <font style="font-size: x-large;">&#8700;</font>
          <label style="font-size: medium;"><a style="color:black;" href="mailto:support@senpa.co.uk"><?php echo mb_strtoupper($support, 'utf-8')?></a></label>
          <font style="font-size: x-large;">&#8700;</font>
          <label style="font-size: medium;"><a style="color:black;" href="help/"><?php echo mb_strtoupper($help, 'utf-8')?></a></label>
          <hr width="92%">
        </td>
              <!-- <?php include(__DIR__ . "/s/a/amazon.php"); ?> -->
          <br>
          <article> <?php echo mb_strtoupper($main_news_title, 'utf-8')?> </article>
          <section > <?php echo ucfirst($main_news_story)?></section>
          <br>
          <article> <?php echo mb_strtoupper($main_article_title_0, 'utf-8')?> </article>
          <section> <?php echo ucfirst($main_article_msg_0)?></section>
          <br>
          <article> <?php echo ucwords($main_article_title_1)?> </article>
          <section> <?php echo ucfirst($main_article_msg_1)?></section>
          <br>
          <article> <?php echo ucwords($main_article_title_3)?> </article>
          <section> <?php echo ucfirst($main_article_msg_3)?></section>
		</body>
    <footer>
      <?php include(__DIR__ . "/s/a/footer.php"); ?>
    </footer>
	</form>
</html>
