<?php
//
// require php7.2-gd extension
//
session_start();
$string = $_SESSION['captcha'];

$my_img = imagecreate( 180, 45 );
$background = imagecolorallocate( $my_img, 255,255,255 );
$text_colour = imagecolorallocate( $my_img, 17, 35, 59 );
$line_colour = imagecolorallocate( $my_img, 255, 255, 255 );
imagesetthickness ( $my_img, 30 ); // background hight
imageline( $my_img, 10, 25, 170, 25, $line_colour ); // left margine, left top margine, lenght, right top margine
imagestring( $my_img, 25, 63, 17, $string, $text_colour ); // font size, left space, upper space

header( "Content-type: image/png" );
imagepng( $my_img );
imagecolordeallocate( $my_img, $line_colour );
imagecolordeallocate( $my_img, $background );
imagecolordeallocate( $my_img, $text_colour );
imagedestroy( $my_img );
?>
