<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/bendi.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = 'bendi';
include_once(ROOT_PATH."api.php");
?>