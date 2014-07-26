<?php
/**
 * UCenter API
 */

define('UC_CLIENT_VERSION', '1.5.1');
define('UC_CLIENT_RELEASE', '20091001');

define('API_DELETEUSER', 1);
define('API_RENAMEUSER', 1);
define('API_GETTAG', 1);
define('API_SYNLOGIN', 1);
define('API_SYNLOGOUT', 1);
define('API_UPDATEPW', 1);
define('API_UPDATEBADWORDS', 1);
define('API_UPDATEHOSTS', 1);
define('API_UPDATEAPPS', 1);
define('API_UPDATECLIENT', 1);
define('API_UPDATECREDIT', 1);
define('API_GETCREDITSETTINGS', 1);
define('API_GETCREDIT', 1);
define('API_UPDATECREDITSETTINGS', 1);

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

include './init.php';


if(!defined('IN_UC')) {

	error_reporting(0);
	set_magic_quotes_runtime(0);

	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

	$_DCACHE = $get = $post = array();

	$code = @$_GET['code'];
	parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
	if(MAGIC_QUOTES_GPC) {
		$get = _stripslashes($get);
	}

	$timestamp = time();
	if(empty($get)) {
		exit('Invalid Request');
	} elseif($timestamp - $get['time'] > 3600) {
		exit('Authracation has expiried');
	}
}

$action = $get['action'];
include(VENDOR_PATH . 'uc_client/lib/xml.class.php');
$post = xml_unserialize(file_get_contents('php://input'));

if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings')))
{
    $uc_note = new uc_note();
    exit($uc_note->$get['action']($get, $post));
}
else
{
    exit(API_RETURN_FAILED);
}

class uc_note
{
	var $db = '';
	var $tablepre = '';
	var $appdir = '';
    /* 数据库所使用编码 */
    var $charset        = '';
    		
    function _serialize($arr, $htmlon = 0)
    {
        if(!function_exists('xml_serialize'))
        {
            include($this->appdir . 'uc_client/lib/xml.class.php');
        }
        return xml_serialize($arr, $htmlon);
    }

    function uc_note()
    {
		$this->appdir = VENDOR_PATH;
		$this->db = $GLOBALS['db'];
		$this->tablepre = DB_PREFIX;
		$this->charset = UC_DBCHARSET;   	
    }

    function test($get, $post)
    {
        return API_RETURN_SUCCEED;
    }

    function deleteuser($get, $post)
    {
        $uids = $get['ids'];
        if(!API_DELETEUSER)
        {
            return API_RETURN_FORBIDDEN;
        }

        if (delete_user($uids))
        {
            return API_RETURN_SUCCEED;
        }
    }

    function renameuser($get, $post)
    {
        $uid = $get['uid'];
        $usernamenew = $get['newusername'];
    	if ($this->charset == 'gbk'){
          $usernamenew = addslashes(gbToUTF8($usernamenew));
	    };         
        if(!API_RENAMEUSER)
        {
            return API_RETURN_FORBIDDEN;
        }
        //$this->db->query("UPDATE " . DB_PREFIX . "user SET user_name='$usernamenew' WHERE id='$uid'"); //modify by chenfq 2010-09-10
        $this->db->query("UPDATE " . DB_PREFIX . "user SET user_name='$usernamenew' WHERE ucenter_id='$uid' limit 1");
        return API_RETURN_SUCCEED; 
    }

    function gettag($get, $post)
    {
        if(!API_GETTAG)
        {
            return API_RETURN_FORBIDDEN;
        }
    }

    function synlogin($get, $post)
    {
        $uid = intval($get['uid']);
        $username = $get['username'];
    	if ($this->charset == 'gbk'){
          $username = addslashes(gbToUTF8($username));
	    };        
        if(!API_SYNLOGIN)
        {
            return API_RETURN_FORBIDDEN;
        }
        //$sql = "update fanwe_user set last_ip = 'aaaa'";
        //$GLOBALS['db']->query($sql);        
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        set_login($uid, $username, $this->charset);
    }

    function synlogout($get, $post)
    {
        if(!API_SYNLOGOUT)
        {
            return API_RETURN_FORBIDDEN;
        }
        
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        set_cookie();
    }

    function updatepw($get, $post)
    {
        if(!API_UPDATEPW)
        {
            return API_RETURN_FORBIDDEN;
        }
        $username = $get['username'];  
    	if ($this->charset == 'gbk'){
          $username = addslashes(gbToUTF8($username));
	    };  	                  	
        $newpw = md5(time().rand(100000, 999999));	
        $this->db->query("UPDATE " . DB_PREFIX . "user SET user_pwd='$newpw' WHERE user_name='$username'");
        return API_RETURN_SUCCEED;
    }

    function updatebadwords($get, $post)
    {
        if(!API_UPDATEBADWORDS)
        {
            return API_RETURN_FORBIDDEN;
        }
        $cachefile = $this->appdir.'uc_client/data/cache/badwords.php';
        $fp = fopen($cachefile, 'w');
        $data = array();
        if(is_array($post)) {
            foreach($post as $k => $v) {
                $data['findpattern'][$k] = $v['findpattern'];
                $data['replace'][$k] = $v['replacement'];
            }
        }
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
        fwrite($fp, $s);
        fclose($fp);
        return API_RETURN_SUCCEED;
    }

    function updatehosts($get, $post)
    {
        if(!API_UPDATEHOSTS)
        {
            return API_RETURN_FORBIDDEN;
        }
        $cachefile = $this->appdir. 'uc_client/data/cache/hosts.php';
        $fp = fopen($cachefile, 'w');
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
        fwrite($fp, $s);
        fclose($fp);
        return API_RETURN_SUCCEED;
    }

    function updateapps($get, $post)
    {
        if(!API_UPDATEAPPS)
        {
            return API_RETURN_FORBIDDEN;
        }
        $UC_API = $post['UC_API'];

        $cachefile = $this->appdir . 'uc_client/data/cache/apps.php';
        $fp = fopen($cachefile, 'w');
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
        fwrite($fp, $s);
        fclose($fp);
        #clear_cache_files();
        return API_RETURN_SUCCEED;
    }

    function updateclient($get, $post)
    {
        if(!API_UPDATECLIENT)
        {
            return API_RETURN_FORBIDDEN;
        }
        $cachefile = $this->appdir. 'uc_client/data/cache/settings.php';
        $fp = fopen($cachefile, 'w');
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
        fwrite($fp, $s);
        fclose($fp);
        return API_RETURN_SUCCEED;
    }

    function updatecredit($get, $post)
    {
        if(!API_UPDATECREDIT)
        {
            return API_RETURN_FORBIDDEN;
        }
    }

    function getcredit($get, $post)
    {
        if(!API_GETCREDIT)
        {
            return API_RETURN_FORBIDDEN;
        }
    }

    function getcreditsettings($get, $post)
    {
        if(!API_GETCREDITSETTINGS)
        {
            return API_RETURN_FORBIDDEN;
        }
    }

    function updatecreditsettings($get, $post)
    {
        if(!API_UPDATECREDITSETTINGS)
        {
            return API_RETURN_FORBIDDEN;
        }
    }
}

/**
 *  删除用户接口函数
 *
 * @access  public
 * @param   int $uids
 * @return  void
 */
function delete_user($uids = '')
{
    if (empty($uids))
    {
        return;
    }
    else
    {
        //$sql = "DELETE FROM " . DB_PREFIX. "user WHERE id IN ($uids)";
        //return  $GLOBALS['db']->query($sql);
        return; //uc删除用户时，方维系统不进行删除
    }
}

/**
 * 设置用户登陆
 *
 * @access  public
 * @param int $uid
 * @return void
 */
function set_login($user_id = '', $user_name = '', $charset = 'utf8')
{
    if (empty($user_id))
    {
        return ;
    }
    else
    {      		    	
        //$sql = "update " . DB_PREFIX."user set last_ip = '".$user_id."'";
        //$GLOBALS['db']->query($sql);
            	
        //$sql = "SELECT * FROM " . DB_PREFIX."user WHERE id='$user_id' LIMIT 1";//modify by chenfq 2010-09-10
        $sql = "SELECT * FROM " . DB_PREFIX."user WHERE ucenter_id='$user_id' LIMIT 1";
        $row = $GLOBALS['db']->getRow($sql);
        if ($row)
        {
            set_cookie($user_id, $row['user_name'], $row['email']);
            //$sql = "update " . DB_PREFIX."user set last_ip = '".get_client_ip()."' WHERE id='$user_id' LIMIT 1";
            //$GLOBALS['db']->query($sql);
        }
        else
        {
            include_once(VENDOR_PATH . 'uc_client/client.php');
	    	if (charset == 'gbk'){
	          $user_name = addslashes(utf8ToGB($user_name));
		    };            
            if($data = uc_get_user($user_name))
            {
                list($uid, $uname, $email) = $data;
                if (charset == 'gbk'){
		        	$uname = addslashes(gbToUTF8($uname));
		        	$email = addslashes(gbToUTF8($email));
			    };                
                
                $sql = "REPLACE INTO " . DB_PREFIX ."user (ucenter_id, user_name, email, status) VALUES('$uid', '$uname', '$email', 1)";
                $GLOBALS['db']->query($sql);
                set_login($uid,'',charset);
            }
            else
            {
                return false;
            }
        }
    }
}

/**
 *  设置cookie
 *
 * @access  public
 * @param
 * @return void
 */
function set_cookie($user_id='', $user_name = '', $email = '')
{
    if (empty($user_id))
    {
        /* 摧毁cookie */
    	
        //$sql = "update " . DB_PREFIX."user set last_ip = '".$_SESSION[user_name]."'";
        //$GLOBALS['db']->query($sql);
        
    	/*
	   $sql = "update " . DB_PREFIX."user set nickname = '".unserialize(base64_decode($_COOKIE[$GLOBALS['sys_config']['COOKIE_PREFIX'].'email']))."'";
	   $GLOBALS['db']->query($sql);
	   
	   $sql = "update " . DB_PREFIX."user set last_ip = '".$_COOKIE[$GLOBALS['sys_config']['COOKIE_PREFIX'].'email']."'";
	   $GLOBALS['db']->query($sql);
	   */
		setcookie2('fanwe_user_id', '');
		setcookie2('user_id', '');
		setcookie2('group_id', '');
		setcookie2('score', '');
		setcookie2('user_name', '');
		setcookie2('user_email', '');
		setcookie2('email', '');
		setcookie2('password', '');
		//$_SESSION['user_id'] = 0;
		//session_unregister('user_id');
		//unset($_SESSION['user_id']);
    }
    else
    {
        /* 设置cookie */
    	//$sql = "SELECT * FROM " . DB_PREFIX."user WHERE id='$user_id' LIMIT 1";//modify by chenfq 2010-09-10
    	$sql = "SELECT * FROM " . DB_PREFIX."user WHERE ucenter_id='$user_id' LIMIT 1";
        $userinfo = $GLOBALS['db']->getRow($sql);
		setcookie2('fanwe_user_id', $userinfo['id']);
		setcookie2('user_id', $userinfo['id']);
		setcookie2('group_id', $userinfo['group_id']);
		setcookie2('score', $userinfo['score']);
		setcookie2('user_name', $userinfo['user_name']);
		setcookie2('user_email', $userinfo['user_email']);
		setcookie2('email', $userinfo['email']);
		setcookie2('password', $userinfo['user_pwd']);
        $sql = "update " . DB_PREFIX."user set last_ip = '".get_client_ip()."' where id =".intval($userinfo['id']);
        $GLOBALS['db']->query($sql);
        
        /*
		setcookie2('fanwe_user_id', 0);
		setcookie2('user_id', 0);
		setcookie2('group_id', 0);
		setcookie2('score', 0);
		setcookie2('user_name', 0);
		setcookie2('user_email', '');
		setcookie2('email', '');
		setcookie2('password', '');
*/
    }
}

function setcookie2($name, $value,$expire = ''){
	if(!defined('_PHP_FILE_')) {
        if(IS_CGI) {
            //CGI/FASTCGI模式下
            $_temp  = explode('.php',$_SERVER["PHP_SELF"]);
            define('_PHP_FILE_',  rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
        }else {
            define('_PHP_FILE_',    rtrim($_SERVER["SCRIPT_NAME"],'/'));
        }
    }
    if(!defined('__ROOT__')) {
        // 网站URL根目录
        if( strtoupper(APP_NAME) == strtoupper(basename(dirname(_PHP_FILE_))) ) {
            $_root = dirname(dirname(_PHP_FILE_));
        }else {
            $_root = dirname(_PHP_FILE_);
        }
        $_root = (($_root=='/' || $_root=='\\')?'':$_root);
        $_root = str_replace("/api","",$_root);
        define('__ROOT__', $_root  );
    }

   $name = $GLOBALS['sys_config']['COOKIE_PREFIX'].$name;
   $expire = $GLOBALS['sys_config']['COOKIE_EXPIRE'];
   $path = $GLOBALS['sys_config']['COOKIE_PATH'];
   $domain = $GLOBALS['sys_config']['COOKIE_DOMAIN'];
   $expire = !empty($expire)? time() + $expire   :  0;
   if (empty($value)){
   		$value = null;
   }else{
   		$value   =  base64_encode(serialize($value));
   }	
   $path = __ROOT__."/";
   setcookie($name, $value, $expire,$path,$domain);
   $_COOKIE[$name] = $value;
   if (empty($value))
   	 unset($name);
}

function _setcookie($var, $value, $life = 0, $prefix = 1) {
	global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
	setcookie(($prefix ? $cookiepre : '').$var, $value,
		$life ? $timestamp + $life : 0, $cookiepath,
		$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + gmtTime() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
				return '';
			}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}
?>