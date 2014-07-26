<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/360.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = 'hao360';
include_once(ROOT_PATH."api.php");	
?>