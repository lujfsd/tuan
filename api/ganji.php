<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/ganji.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = 'ganji';
include_once(ROOT_PATH."api.php");
?>