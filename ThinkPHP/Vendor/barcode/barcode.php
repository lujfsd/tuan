<?php
// Including all required classes

require('./class/BCGFont.php');
require('./class/BCGColor.php');
require('./class/BCGDrawing.php'); 
/*'BCGcodabar','BCGcode11','BCGcode39','BCGcode39extended','BCGcode93',
'BCGcode128','BCGean8','BCGean13','BCGisbn','BCGi25','BCGs25','BCGmsi',
'BCGupca','BCGupce','BCGupcext2','BCGupcext5','BCGpostnet','BCGothercode'*/
$codebar = $_REQUEST['codebar']; //该软件支持的所有编码，只需调整$codebar参数即可。
if (empty($codebar)){
	$codebar = 'BCGcode39';
}

$resolution = intval($_REQUEST['resolution']);
if ($resolution == 0){
	$resolution = 2;
}

if(isset($_REQUEST['thickness']))
{
	$thickness = intval($_REQUEST['thickness']);
}
else
{
	$thickness = 30;
}

// The arguments are R, G, B for color.
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255); 
/*
if (file_exists (VENDOR_PATH.'barcode/class/'.$codebar.'.barcode.php')) {
	echo $codebar."_file_exists<br>";
}else{
	echo $codebar."_file_not_exists<br>";
}
*/
// Including the barcode technology
include('./class/'.$codebar.'.barcode.php'); 

// Loading Font
//$font = new BCGFont('./class/font/Arial.ttf', 10);

$code = new $codebar();

$code->setScale($resolution); // Resolution
$code->setThickness($thickness); // Thickness
$code->setForegroundColor($color_black); // Color of bars
$code->setBackgroundColor($color_white); // Color of spaces
//$code->setFont($font); // Font (or 0)
$text = $_REQUEST['text']; //条形码将要数据的内容
$code->parse($text); 

/* Here is the list of the arguments
1 - Filename (empty : display on screen)
2 - Background color */
$drawing = new BCGDrawing('', $color_white);
$drawing->setBarcode($code);
$drawing->draw();

// Header that says it is an image (remove it if you save the barcode to a file)
header('Content-Type: image/png');

// Draw (or save) the image into PNG format.
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>