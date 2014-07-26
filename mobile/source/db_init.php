<?php
//定义DB
error_reporting(0);
session_start();
error_reporting(1);

require ROOT_PATH.'app/source/class/mysql_db.php';

$db_config = require ROOT_PATH.'Public/db_config.php';
//print_r($db_config);
define('DB_PREFIX', $db_config['DB_PREFIX']);
if(isset($db_config['DB_PCONNECT']) && intval($db_config['DB_PCONNECT'])==1)	
	$pconnect = true;
else
	$pconnect = false;
	
$db = new mysql_db($db_config['DB_HOST'].":".$db_config['DB_PORT'], $db_config['DB_USER'],$db_config['DB_PWD'],$db_config['DB_NAME'],'utf8',$pconnect);
//end 定义DB
global $sys_config,$langItem;
if(!is_file(ROOT_PATH."/Public/sys_config.php"))
{
	//开始写入配置文件
		$sys_configs = $GLOBALS['db']->getAll("select name,val from ".DB_PREFIX."sys_conf");
		$config_str = "<?php\n";
		$config_str .= "return array(\n";
		foreach($sys_configs as $k=>$v)
		{
			$config_str.="'".$v['name']."'=>'".str_replace("'","\\'",$v['val'])."',";
		}
		$config_str.=");\n ?>";
		error_reporting(0);
		file_put_contents(ROOT_PATH."/Public/sys_config.php",$config_str);
		error_reporting(1);
}
$sys_config = require ROOT_PATH.'Public/sys_config.php';

//引入时区配置及定义时间函数
$time_conf = require ROOT_PATH.'Public/global_config.php';
if(function_exists('date_default_timezone_set'))
	date_default_timezone_set($time_conf['DEFAULT_TIMEZONE']);

//end 引入时区配置及定义时间函数
$langItem = $GLOBALS['db']->getRowCached('SELECT `id`,`lang_name`,`show_name`,`time_zone`,`tmpl`,`seokeyword`,`seocontent`,`shop_title`,`shop_name`,`default`,`currency` FROM '.DB_PREFIX.'lang_conf WHERE id = 1');
define('FANWE_LANG_ID', $langItem['id']);
define('SHOP_NAME',$langItem['shop_name']);
define('FANWE_TMPL',$langItem['tmpl']);
define('FANWE_TIME_ZONE', intval($langItem['time_zone']));


//语言包
$langname = a_fanweC("DEFAULT_LANG");
if (empty($langname)) $langname = 'zh-cn';
define('LANG',$langname);
$Ln = $lang = array();

$Ln_common = require (ROOT_PATH.'mobile/Lang/'.$langname.'/common.php');
$Ln_gc = require ROOT_PATH.'global/common_lang.php';
$templang = array_merge($Ln_common,$Ln_gc);

$Ln = $lang = $templang;

//end 语言包

//services 用的fanweC
function a_fanweC($name)
{
	if($name == 'INTEGRATE_CONFIG'){
		return $GLOBALS['db']->getOneCached("select val from ".DB_PREFIX."sys_conf where name='INTEGRATE_CONFIG'");
	}		
	if($name == 'SHOP_URL')
		return "http://".$_SERVER['HTTP_HOST'].__ROOT__;
	else
		return $GLOBALS['sys_config'][$name];
}

function a_gmtTime()
{
	return (time() - date('Z'));
}

function a_L($name){
	return $GLOBALS['Ln'][$name];
}
?>