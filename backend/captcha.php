<?php

define("ALLOW_INCLUDE", true);
require "functions.php";

$c1 = [
    "1" => "тысяча",
    "2" => "дветысячи",
    "3" => "тритысячи",
    "4" => "четыретысячи",
    "5" => "пятьтысяч",
    "6" => "шестьтысяч",
    "7" => "семьтысяч",
    "8" => "восемьтысяч",
    "9" => "девятьтысяч"
];

$c2 = [
    "1" => "сто",
    "2" => "двести",
    "3" => "триста",
    "4" => "четыреста",
    "5" => "пятьсот",
    "6" => "шестьсот",
    "7" => "семьсот",
    "8" => "восемьсот",
    "9" => "девятьсот"
];

$c3 = [
    "2" => "двадцать",
    "3" => "тридцать",
    "4" => "сорок",
    "5" => "пятьдесят",
    "6" => "шестьдесят",
    "7" => "семьдесят",
    "8" => "восемьдесят",
    "9" => "девяносто"
];

$c3_1 = [
    "10" => "десять",
    "11" => "одиннадцать",
    "12" => "двенадцать",
    "13" => "тринадцать",
    "14" => "четырнадцать",
    "15" => "пятнадцать",
    "16" => "шестнадцать",
    "17" => "семнадцать",
    "18" => "восемнадцать",
    "19" => "девятнадцать"
];

$c4 = [
    "1" => "один",
    "2" => "два",
    "3" => "три",
    "4" => "четыре",
    "5" => "пять",
    "6" => "шесть",
    "7" => "семь",
    "8" => "восемь",
    "9" => "девять"
];

$number = (string)rand(1000, 9999);

$n1 = $c1[$number[0]];
if ((int)$number[1] == 0)
{
    $n2 = "";
}
else
{
    $n2 = $c2[$number[1]];
}

$tens = intval($number) - intval($n1 . $n2 . "00");

$n3 = "";
$n4 = "";
if ($tens < 20 && $tens >= 10)
{
    $n3 = $c3_1[(string)$tens];
}
else
{
    if ((int)$number[2] == 0 && (int)$number[3] == 0)
    {
        $number[2] = rand(1, 9);
        $number[3] = rand(1, 9);
    }
    if ((int)$number[2] > 1)
    {
        $n3 = $c3[$number[2]];
    }
    if ((int)$number[3] != 0)
    {
        $n4 = $c4[$number[3]];
    }
}

$text = $n1.$n2.$n3.$n4;

$text_lines = [];

$k = 0;
for ($i = 0; $i < mb_strlen($text); $i++)
{
    if ($k == 14)
    {
        $k = 0;
    }
    $k++;
    if ($k == 1)
    {
        $text_lines[] = "";
    }
    $text_lines[count($text_lines) - 1] .= mb_substr($text, $i, 1);
}

session_start();

$_SESSION["captcha"] = $number;

header("Content-Type: image/png");

$fonts = "overdozesans.ttf";

$fontSize = 24;
$fontSizeFooter = 12;

$width = 400;
$height = 200;

$countLine = rand(0, 10);
$countPixel = rand(200, 1000);

$imgPng = imagecreatetruecolor($width, $height);

$imgColor = randColor($imgPng, 255, 181, 181);
$lineColor = randColor($imgPng);
$pixelColor = randColor($imgPng);
$textColor = randColor($imgPng);
$bgColor = randColor($imgPng, 0, 0, 0);
$redColor = randColor($imgPng, 255, 0, 0);

imagefilledrectangle($imgPng, 0, 0, $width, $height, $imgColor);

for ($i = 0; $i < $countLine; $i++)
{
    imageline($imgPng, 0, rand(0, $height), $width, rand(0, $height), $lineColor);
}

for ($i = 0; $i < $countPixel; $i++) {
    imagesetpixel($imgPng, rand(0, $width) , rand(0, $height), $pixelColor);
}

$l = 0;
foreach ($text_lines as $text_line)
{
    $l++;
    $angle = rand(-25, 50);
    for ($i = 0; $i < mb_strlen($text_line); $i++)
    {
        $x = ($width - 20) / mb_strlen($text_line) * $i + 20;
        $y = $l / 2 * 100;
        $letterColor = randColor($imgPng);

        imagettftext($imgPng, $fontSize, $angle, $x, $y, $letterColor, $fonts, mb_substr($text_line, $i, 1));
    }
}

imagepng($imgPng);

imagedestroy($imgPng);