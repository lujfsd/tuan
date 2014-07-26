<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/tuan800.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = 'tuan800';
include_once(ROOT_PATH."api.php");	
?>