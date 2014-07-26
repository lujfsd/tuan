<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/360api.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = '360api';
include_once(ROOT_PATH."api.php");
	
?>