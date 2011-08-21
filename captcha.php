<?php
session_start ();

$cyr = array(
	'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
	'j', 'k', 'l', 'm', 'n',      'p', 'q', 'r',
	's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 
	'1', '2', '3', '4', '5', '6', '7', '8', '9'
);

$width = 80;
$height = 20;

$char = $cyr[rand(0,33)].$cyr[rand(0,33)].$cyr[rand(0,33)].$cyr[rand(0,33)];
$_SESSION['code'.( isset($_GET['rnd']) ? '_'.$_GET['rnd'] : '' )] = $char;

$img = @imagecreate ($width, $height) or die ("Cannot initialize new GD image stream!");
$bg = imagecolorallocate ($img, 255, 255, 255);

//выводим символы кода
for ($i = 0; $i < strlen($char); $i++) {
	$color = imagecolorallocate ($img, rand(0,200), rand(0,128), rand(0,240)); //задаём цвет
	imagechar ($img, 5, (5 + $i * 20), rand(1, 6), $char{$i}, $color);
}

//создаём шум
for ($i=0; $i<=128; $i++) {
	$color = imagecolorallocate ($img, rand(0,255), rand(0,255), rand(0,255)); //задаём цвет
	imagesetpixel($img, rand(2,78), rand(2,18), $color); //рисуем пиксель
}

//антикеширование
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//создание рисунка в зависимости от доступного формата
if (function_exists("imagepng")) {
   header("Content-type: image/png");
   imagepng($img);
} elseif (function_exists("imagegif")) {
   header("Content-type: image/gif");
   imagegif($img);
} elseif (function_exists("imagejpeg")) {
   header("Content-type: image/jpeg");
   imagejpeg($img);
} else {
   die("No image support in this PHP server!");
}
imagedestroy ($img);