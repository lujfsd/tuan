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
if(!is_file(ROOT_PATH."app/Runtime/lang.php"))
{
	$Ln_common = require (ROOT_PATH.'app/Lang/'.$langname.'/common.php');
	$Ln_xy = require ROOT_PATH.'app/Lang/'.$langname.'/xy_lang.php';
	$Ln_js = require ROOT_PATH.'app/Lang/'.$langname.'/js_lang.php';
	
	if ($langname == 'en-us'){
		$Ln_gc = require ROOT_PATH.'global/common_lang_en.php';	            	
	}elseif ($langname == 'zh-tw'){
		$Ln_gc = require ROOT_PATH.'global/common_lang_tw.php';
	}else{
		$Ln_gc = require ROOT_PATH.'global/common_lang.php';			        	
	}	
	
	$templang = array_merge($Ln_common,$Ln_xy,$Ln_gc,$Ln_js);
	
	$files = scandir(ROOT_PATH.'app/Lang/'.$langname.'/payment/');
	foreach($files as $file)
	{
		if($file!='.'&&$file!='..'&&strpos($file,".php"))
		{
			 $templangs = require ROOT_PATH.'app/Lang/'.$langname.'/payment/'.$file;
			 $templang =array_merge($templang,$templangs);
		}
	}
	
	$config_str = "<?php\n";
	$config_str .= "return array(\n";
	foreach($templang as $k=>$v)
	{
		$config_str.="'".$k."'=>'".str_replace("'","\\'",$v)."',";
	}
	$config_str.=");\n ?>";
	error_reporting(0);
	file_put_contents(ROOT_PATH."app/Runtime/lang.php",$config_str);
	error_reporting(1);
}
else
{
	$templang = require ROOT_PATH."app/Runtime/lang.php";
}

$Ln = $lang = $templang;

//end 语言包
if (! defined ( 'IS_CGI' ))
	define ( 'IS_CGI', substr ( PHP_SAPI, 0, 3 ) == 'cgi' ? 1 : 0 );
	//定义__ROOT__常量
if (! defined ( '_PHP_FILE_' )) {
	if (IS_CGI) {
		//CGI/FASTCGI模式下
		$_temp = explode ( '.php', $_SERVER ["PHP_SELF"] );
		define ( '_PHP_FILE_', rtrim ( str_replace ( $_SERVER ["HTTP_HOST"], '', $_temp [0] . '.php' ), '/' ) );
	} else {
		define ( '_PHP_FILE_', rtrim ( $_SERVER ["SCRIPT_NAME"], '/' ) );
	}
}

if (! defined ( '__ROOT__' )) {
	// 网站URL根目录
	$_root = dirname ( _PHP_FILE_ );
	$_root = (($_root == '/' || $_root == '\\') ? '' : $_root);
	define ( '__ROOT__', $_root );
}

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

if (!function_exists('json_encode')) {
     function format_json_value(&$value)
    {
        if(is_bool($value)) {
            $value = $value?'true':'false';
        }elseif(is_int($value)) {
            $value = intval($value);
        }elseif(is_float($value)) {
            $value = floatval($value);
        }elseif(defined($value) && $value === null) {
            $value = strval(constant($value));
        }elseif(is_string($value)) {
            $value = '"'.addslashes($value).'"';
        }
        return $value;
    }

    function json_encode($data)
    {
        if(is_object($data)) {
            //对象转换成数组
            $data = get_object_vars($data);
        }else if(!is_array($data)) {
            // 普通格式直接输出
            return format_json_value($data);
        }
        // 判断是否关联数组
        if(empty($data) || is_numeric(implode('',array_keys($data)))) {
            $assoc  =  false;
        }else {
            $assoc  =  true;
        }
        // 组装 Json字符串
        $json = $assoc ? '{' : '[' ;
        foreach($data as $key=>$val) {
            if(!is_null($val)) {
                if($assoc) {
                    $json .= "\"$key\":".json_encode($val).",";
                }else {
                    $json .= json_encode($val).",";
                }
            }
        }
        if(strlen($json)>1) {// 加上判断 防止空数组
            $json  = substr($json,0,-1);
        }
        $json .= $assoc ? '}' : ']' ;
        return $json;
    }
}

if (!function_exists('json_decode')) {
    function json_decode($json,$assoc=false)
    {
        // 目前不支持二维数组或对象
        $begin  =  substr($json,0,1) ;
        if(!in_array($begin,array('{','[')))
            // 不是对象或者数组直接返回
            return $json;
        $parse = substr($json,1,-1);
        $data  = explode(',',$parse);
        if($begin =='{' ) {
            // 转换成PHP对象
            $result   = new stdClass();
            foreach($data as $val) {
                $item    = explode(':',$val);
                $key =  substr($item[0],1,-1);
                $result->$key = json_decode($item[1],$assoc);
            }
            if($assoc)
                $result   = get_object_vars($result);
        }else {
            // 转换成PHP数组
            $result   = array();
            foreach($data as $val)
                $result[]  =  json_decode($val,$assoc);
        }
        return $result;
    }
}

?>