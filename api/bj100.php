<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/bj100.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = 'bj100';
include_once(ROOT_PATH."api.php");
	
?>