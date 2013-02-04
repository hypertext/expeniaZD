<?php
session_start();
unset($_SESSION['captcha_code']);

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-type: image/jpeg');

$string = "ABCDEFGH2345689";
$random_strong1 = substr(str_shuffle($string), 0, 2);
$random_strong2 = substr(str_shuffle($string), 0, 2);
$random_strong3 = substr(str_shuffle($string), 0, 2);
$random_strong = $random_strong1.$random_strong2.$random_strong3;

$_SESSION['captcha_code'] = md5($random_strong);

$fonts = array("captcha/zachary.ttf", "captcha/mtcorsva.ttf", "captcha/gilligan.ttf");
$image = imagecreatefrompng("captcha/hintergrund.png");
$text_color1 = imagecolorallocate($image, 0, 125, 0);
$text_color2 = imagecolorallocate($image, 130, 70, 90);
$text_color3 = imagecolorallocate($image, 180, 90, 190);
imagettftext($image, 12, 15, 3, 24, $text_color1, $fonts[0], $random_strong1);
imagettftext($image, 16, 0, 26, 15, $text_color2, $fonts[1], $random_strong2);
imagettftext($image, 14, -20, 53, 18, $text_color3, $fonts[2], $random_strong3);
imagejpeg($image);

imagedestroy($image);
?>