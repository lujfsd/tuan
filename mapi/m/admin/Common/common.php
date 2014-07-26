<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

if (!defined('THINK_PATH')) exit();

//过滤请求
filter_request($_REQUEST);
filter_request($_GET);
filter_request($_POST);
define("AUTH_NOT_LOGIN", 1); //未登录的常量
define("AUTH_NOT_AUTH", 2);  //未授权常量

// 全站公共函数库
// 更改系统配置, 当更改数据库配置时为永久性修改， 修改配置文档中配置为临时修改
function conf($name,$value = false)
{
	if($value === false)
	{
		return C($name);
	}
	else
	{

	}
}



function write_timezone($zone='')
{
	if($zone=='')
	$zone = conf('TIME_ZONE');
		$var = array(
			'0'	=>	'UTC',
			'8'	=>	'PRC',
		);
		
		//开始将$db_config写入配置
	    $timezone_config_str 	 = 	"<?php\r\n";
	    $timezone_config_str	.=	"return array(\r\n";
	    $timezone_config_str.="'DEFAULT_TIMEZONE'=>'".$var[$zone]."',\r\n";
	    
	    $timezone_config_str.=");\r\n";
	    $timezone_config_str.="?>";
	   
	    @file_put_contents(get_real_path()."public/timezone_config.php",$timezone_config_str);
}




//状态的显示
function get_toogle_status($tag,$id,$field)
{
	if($tag)
	{
		return "<span class='is_effect' onclick=\"toogle_status(".$id.",this,'".$field."');\">".l("YES")."</span>";
	}
	else
	{
		return "<span class='is_effect' onclick=\"toogle_status(".$id.",this,'".$field."');\">".l("NO")."</span>";
	}
}

//状态的显示
function get_is_effect($tag,$id)
{
	if($tag)
	{
		return "<span class='is_effect' onclick='set_effect(".$id.",this);'>".l("IS_EFFECT_1")."</span>";
	}
	else
	{
		return "<span class='is_effect' onclick='set_effect(".$id.",this);'>".l("IS_EFFECT_0")."</span>";
	}
}


//排序显示
function get_sort($sort,$id)
{
	if($tag)
	{
		return "<span class='sort_span' onclick='set_sort(".$id.",".$sort.",this);'>".$sort."</span>";
	}
	else
	{
		return "<span class='sort_span' onclick='set_sort(".$id.",".$sort.",this);'>".$sort."</span>";
	}
}

function getMPageName($page)
{
	return L('MPAGE_'.strtoupper($page));
}

function getMTypeName($type)
{
	return L('MTYPE_'.strtoupper($type));
}

function check_empty($data)
{
	if(trim($data)=='')
	{
		return false;
	}
	return true;
}
?>