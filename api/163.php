<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/163.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = '163';
include_once(ROOT_PATH."api.php");
	
?>