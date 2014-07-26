<?php
// Pull in the NuSOAP code
if (!defined('IS_ADMIN_FLAG'))
{
    define('IS_ADMIN_FLAG', true);
}

define( "ASSISTANT_VERSION", '1.0');


$file = str_replace( "\\", "/", dirname(__FILE__) );
$path_arr = explode( '/', $file);
foreach ( $path_arr as $value ) {
	if (! empty ( $value )) {
		$assistant = $value;
	}
}

if (empty($assistant)) 
{
	$assistant = "assistant";
}	

define( "ASSISTANT_PATH", $assistant );

define('ROOT_PATH', str_replace(ASSISTANT_PATH.'/eca_init.php', '', str_replace('\\', '/', __FILE__)));


if (!function_exists('file_put_contents'))
{
    define('FILE_APPEND', 'FILE_APPEND');

    function file_put_contents($file, $data, $flags = '')
    {
        $contents = (is_array($data)) ? implode('', $data) : $data;

        if ($flags == 'FILE_APPEND')
        {
            $mode = 'ab+';
        }
        else
        {
            $mode = 'wb';
        }

        if (($fp = @fopen($file, $mode)) === false)
        {
            return false;
        }
        else
        {
            $bytes = fwrite($fp, $contents);
            fclose($fp);

            return $bytes;
        }
    }
}

/*   
   
   0x00 - 0x08   0 -  8
   0x0b - 0x0c  11 - 12
   0x0e - 0x1f  14 - 31
   9 tab
   10,13
*/    
function XmlSafeStr($s)    
{    
  return trim(preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$s));;    
}

//=====================================================================================
require('lib/class.base.php');


$db_config = include(ROOT_PATH . 'Public/db_config.php');

$sys_config	= require ROOT_PATH.'sys_config.php';
$config = array_merge($db_config, $sys_config);

define('DB_PREFIX', $config['DB_PREFIX']);


//require(ROOT_PATH . 'includes/classes/cache.php');
require_once('lib/query_factory.php');

$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if ('/' == substr($php_self, -1))
{
    $php_self .= 'index.php';
}
define('PHP_SELF', $php_self);

//$zc_cache = new cache();

$db = new queryFactory();
$db->connect($config['DB_HOST'], $config['DB_USER'], $config['DB_PWD'], $config['DB_NAME'], false, false);


$sql = "SELECT val FROM " . DB_PREFIX ."sys_conf WHERE name = \"SYS_VERSION\" limit 1";	
define('VERSION', $GLOBALS['db']->getOne($sql, 'val'));
    
/*
//select adm_name from fanwe_admin
$rows = $GLOBALS['db']->Execute("select * from fanwe_admin");
//print_r($rows);

//print_r($rows->fields);
echo "<br>";
	while (!$rows->EOF) {
		foreach ( $rows->fields as $v)
		{
			echo $v."|";
		}
		echo "<br>";
		$rows->MoveNext();
	}
exit;

*/
//=====================================================================================
define( "AS_LOG_DIR", ROOT_PATH.ASSISTANT_PATH."/logs/" );
define( "AS_SYNC_DELETED", -1 );
define( "AS_SYNC_UNCHANGED", 0 );
define( "AS_SYNC_ADDED", 1 );
define( "AS_SYNC_MODIFIED", 2 );
define( "AS_DEBUG", true );

?>
