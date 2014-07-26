<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/tuanp.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = 'tuanp';
include_once(ROOT_PATH."api.php");
	
?>