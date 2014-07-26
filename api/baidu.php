<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/baidu.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = 'hao123';
include_once(ROOT_PATH."api.php");
	
?>