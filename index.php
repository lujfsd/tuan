<?php 
// +----------------------------------------------------------------------
// | ThinkPHP                                                             
// +----------------------------------------------------------------------
// | Copyright (c) 2008 http://thinkphp.cn All rights reserved.      
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>                                  
// +----------------------------------------------------------------------
// $Id$

error_reporting(E_ALL ^ E_NOTICE);

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('index.php', '', str_replace('\\', '/', __FILE__)));

if(strtolower($_REQUEST['m'])=='api')
{
	include "api.php";
	exit;
}

if(file_exists(ROOT_PATH."Public/install.lock"))
{
	include ROOT_PATH.'app/source/func/ismobile.php';
	if (isMobile())
		include ROOT_PATH."mobile/source/index.php";
	else
		include ROOT_PATH."app/source/index.php";
	exit;
}
else
{
	header("Location:install.php");
}
exit;
?>
