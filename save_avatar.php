<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('save_avatar.php', '', str_replace('\\', '/', __FILE__)));
	if(file_exists(ROOT_PATH."Public/install.lock"))
	{
		$_REQUEST['m']="UcModify";
		$_REQUEST['a']="save_avatar";
		include ROOT_PATH."app/source/index.php";
	}
	else
	{
		header("Location:install.php");
	}
?>