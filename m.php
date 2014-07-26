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


// 定义ThinkPHP框架路径
define('BASE_PATH','./');
//define('THINK_PATH', './ThinkPHP');
//定义项目名称和路径
define('APP_NAME', 'mobile');
//require_once('./services/system_init.php');
//require_once('./services/com_function.php');

error_reporting(E_ALL ^ E_NOTICE);

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('m.php', '', str_replace('\\', '/', __FILE__)));

require ROOT_PATH.'mobile/source/index.php';
exit();
// 加载框架入口文件 
//require(THINK_PATH."/ThinkPHP.php");

//实例化一个网站应用实例
//$AppWeb = new App(); 
//应用程序初始化
//$AppWeb->run();
?>