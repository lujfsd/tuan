<?php

/**
 * 调用日历 JS
*/
$actions = array('zh-cn','zh-tw','en-us');
$lang = (!empty($_GET['lang'])) ? strtolower(trim($_GET['lang'])) : 'zh-cn';

if(!in_array($lang,$actions))
	die("ACCESS ERROR!");

header('Content-type: application/x-javascript; charset=utf-8');
include_once('../../../../Lang/' . $lang . '/calendar.php');

foreach ($_LANG['calendar_lang'] AS $cal_key => $cal_data)
{
    echo 'var ' . $cal_key . " = \"" . $cal_data . "\";\r\n";
}

include_once('./calendar/calendar.js');

?>