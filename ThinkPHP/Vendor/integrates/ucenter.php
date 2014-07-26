<?php

/**
 * UCenter 会员数据处理类
 */


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = 'ucenter';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'UCenter';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '1.5.x';

    /* 插件的作者 */
    $modules[$i]['author']  = 'FANWE R&D TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.fanwe.com';

    /* 插件的初始的默认值 */
    $modules[$i]['default']['db_host'] = 'localhost';
    $modules[$i]['default']['db_user'] = 'root';
    $modules[$i]['default']['prefix'] = 'uc_';
    $modules[$i]['default']['cookie_prefix'] = 'xnW_';

    return;
}

require_once(VENDOR_PATH.'integrates/integrate.php');
class ucenter extends integrate
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function __construct($cfg)
    {
        /* 使用默认数据库连接 */
        //$this->ucenter($cfg);
        parent::integrate(array());
        //$cfg['quiet'] = 1;
        //$cfg['prefix'] = $cfg['db_pre'];
        //parent::integrate($cfg);
        
        $this->user_table = 'members';
        $this->field_id = 'uid';
        $this->field_name = 'username';
        $this->field_pass = 'password';
        $this->field_email = 'email';
        $this->field_gender = 'NULL';
        $this->field_bday = 'NULL';
        $this->field_reg_date = 'regdate';
        $this->need_sync = false;
        $this->is_fanwe = 0;
       	$this->dbcfg = $cfg;
        $this->prefix = $cfg['db_pre'];
        $this->db_name = $cfg['db_name'];
        $this->charset = strtolower(isset($cfg['db_charset'])?$cfg['db_charset']:'utf8');
        
        /* 初始化UC需要常量 */
        if (!defined('UC_CONNECT') && isset($cfg['uc_id']) && isset($cfg['db_host']) && isset($cfg['db_user']) && isset($cfg['db_name']))
        {
            if(strpos($cfg['db_pre'], '`' . $cfg['db_name'] . '`') === 0)
            {
                $db_pre = $cfg['db_pre'];
            }
            else
            {
                $db_pre = '`' . $cfg['db_name'] . '`.' . $cfg['db_pre'];
            }
            
			//dump($cfg);
            define('UC_CONNECT', isset($cfg['uc_connect'])?$cfg['uc_connect']:'');
            define('UC_DBHOST', isset($cfg['db_host'])?$cfg['db_host']:'');
            define('UC_DBUSER', isset($cfg['db_user'])?$cfg['db_user']:'');
            define('UC_DBPW', isset($cfg['db_pass'])?$cfg['db_pass']:'');
            define('UC_DBNAME', isset($cfg['db_name'])?$cfg['db_name']:'');
            define('UC_DBCHARSET', isset($cfg['db_charset'])?$cfg['db_charset']:'utf8');
            define('UC_DBTABLEPRE', $db_pre);
            define('UC_DBCONNECT', '0');
            define('UC_KEY', isset($cfg['uc_key'])?$cfg['uc_key']:'');
            define('UC_API', isset($cfg['uc_url'])?$cfg['uc_url']:'');
            define('UC_CHARSET', isset($cfg['uc_charset'])?$cfg['uc_charset']:'');
            define('UC_IP', isset($cfg['uc_ip'])?$cfg['uc_ip']:'');
            define('UC_APPID', isset($cfg['uc_id'])?$cfg['uc_id']:'');
            define('UC_PPP', '20');
        }        
    }

    /**
     *  用户登录函数
     *
     * @access  public
     * @param   string  $username
     * @param   string  $password
     *
     * @return void
     */
    function login($username, $password, $email = '')
    {
    	return false;
    }

    /**
     * 用户退出
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function logout()
    {
        //$this->set_cookie();  //清除cookie
        //$this->set_session(); //清除session
        $this->ucdata = uc_call("uc_user_synlogout");   //同步退出
        //dump($this->ucdata);
        return true;
    }

    /*添加用户*/
    function add_user($username, $password, $email)
    {
        // 检测用户名
        //dump('add_user');
        
        if ($this->check_user($username))
        {
            $this->error = ERR_USERNAME_EXISTS;
            return false;
        }

        if ($this->charset == 'gbk'){
        	$username = addslashes(utf8ToGB($username));
        	$password = addslashes(utf8ToGB($password));
        	$email = addslashes(utf8ToGB($email));
	    }else{
        	$username = addslashes($username);
        	$password = addslashes($password);
        	$email = addslashes($email);
	    }
	            
        $uid = uc_call("uc_user_register", array($username, $password, $email));
        
        //dump($uid);
        if ($uid <= 0)
        {
            if($uid == -1)
            {
                $this->error = ERR_INVALID_USERNAME;
                return false;
            }
            elseif($uid == -2)
            {
                $this->error = ERR_USERNAME_NOT_ALLOW;
                return false;
            }
            elseif($uid == -3)
            {
                $this->error = ERR_USERNAME_EXISTS;
                return false;
            }
            elseif($uid == -4)
            {
                $this->error = ERR_INVALID_EMAIL;
                return false;
            }
            elseif($uid == -5)
            {
                $this->error = ERR_EMAIL_NOT_ALLOW;
                return false;
            }
            elseif($uid == -6)
            {
                $this->error = ERR_EMAIL_EXISTS;
                return false;
            }
            else
            {
                return false;
            }
        }
        else
        {   return true;
        }
    }
    /**
     *  检查指定用户是否存在及密码是否正确
     *
     * @access  public
     * @param   string  $username   用户名
     *
     * @return  int
     */
    function check_user($username, $password = null)
    {
    	//dump($username);
    	if ($this->charset == 'gbk'){
        	$username = addslashes(utf8ToGB($username));
	    }else{
        	$username = addslashes($username);
	    } 
	        	
        $userdata = uc_call("uc_user_checkname", array($username));
        //dump($userdata);
        if ($userdata == 1)
        {
            return false;
        }
        else
        {
            return  true;
        }
    }

    /**
     * 检测Email是否合法
     *
     * @access  public
     * @param   string  $email   邮箱
     *
     * @return  blob
     */
    function check_email($email)
    {
    	if ($this->charset == 'gbk'){
        	$email = addslashes(utf8ToGB($email));
	    }else{
        	$email = addslashes($email);
	    }     	
        if (!empty($email))
        {
            $email_exist = uc_call('uc_user_checkemail', array($email));
            if ($email_exist == 1)
            {
                return false;
            }
            else
            {
                $this->error = ERR_EMAIL_EXISTS;
                return true;
            }
        }
        return true;
    }

    /* 编辑用户信息($password, $email, $gender, $bday) */
    function edit_user($cfg, $forget_pwd = '0')
    {
    	//dump($cfg);
    	if ($this->charset == 'gbk'){
        	$real_username = addslashes(utf8ToGB($cfg['username']));
        	$cfg['username'] = addslashes(utf8ToGB($cfg['username']));	 
        	$cfg['email'] = addslashes(utf8ToGB($cfg['email']));
        	$cfg['password'] = addslashes(utf8ToGB($cfg['password']));       
	    }else{
        	$real_username = addslashes($cfg['username']);
        	$cfg['username'] = addslashes($cfg['username']);
        	$cfg['email'] = addslashes($cfg['email']);
        	$cfg['password'] = addslashes($cfg['password']);
	    }    	


        if (!empty($cfg['email']))
        {
            $ucresult = uc_call("uc_user_edit", array($cfg['username'], '', '', $cfg['email'], 1));
            if ($ucresult > 0 )
            {
                $flag = true;
            }
            elseif($ucresult == -4)
            {
                //echo 'Email 格式有误';
                $this->error = ERR_INVALID_EMAIL;

                return false;
            }
            elseif($ucresult == -5)
            {
                //echo 'Email 不允许注册';
                $this->error = ERR_INVALID_EMAIL;

                return false;
            }
            elseif($ucresult == -6)
            {
                //echo '该 Email 已经被注册';
                $this->error = ERR_EMAIL_EXISTS;

                return false;
            }
            elseif ($ucresult < 0 )
            {
                return false;
            }
        }
        if (!empty($cfg['password']))
        {
            $ucresult = uc_call("uc_user_edit", array($real_username, '', $cfg['password'], '', '1'));
            if ($ucresult > 0 )
            {
                return true;
            }else{
            	return false;
            }
        }

        return true;
    }

    /**
     *  获取指定用户的信息
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function get_profile_by_name($username)
    {
    	if ($this->charset == 'gbk'){
        	$username = addslashes(utf8ToGB($username));
	    }else{
        	$username = addslashes($username);
	    } 
		list($uid, $username, $email) = uc_call("uc_get_user", array($username));
        
        if ($this->charset == 'gbk'){
        	$username = addslashes(gbToUTF8($username));
        	$email = addslashes(gbToUTF8($email));
	    }else{
        	$username = addslashes($username);
	    }		
		
        return array('id'=>$uid, 'user_name'=>$username, 'email'=>$email);
    }

    function remove_user_by_names($user_names)
    {
    	//$user_names = 'test9@11.com2';
    	if (!is_array($user_names)){
    		$user_names = explode (',', $user_names);
    	}	
    	if ($this->charset == 'gbk'){
        	$user_names = addslashes(utf8ToGB($user_names));
	    }else{
        	$user_names = addslashes($user_names);
	    } 
    	$res = uc_call("uc_user_delete", $user_names);
    	//dump($res);
    	return $res;  
    }
        
    /**
     *  检查cookie是正确，返回用户名
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function check_cookie()
    {
        return '';
    }

    /**
     *  根据登录状态设置cookie
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function get_cookie()
    {
        $id = $this->check_cookie();
        if ($id)
        {
            $this->set_session($id);

            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     *  设置cookie
     *
     * @access  public
     * @param
     *
     * @return void
     
    function set_cookie($username='')
    {
    }
*/
    /**
     *  设置指定用户SESSION
     *
     * @access  public
     * @param
     *
     * @return void
     
    function set_session ($username='')
    {
        if (empty($username))
        {
            $GLOBALS['sess']->destroy_session();
        }
        else
        {
            $sql = "SELECT user_id, password, email FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name='$username' LIMIT 1";
            $row = $GLOBALS['idb']->getRow($sql);

            if ($row)
            {
                $_SESSION['user_id']   = $row['user_id'];
                $_SESSION['user_name'] = $username;
                $_SESSION['email']     = $row['email'];
            }
        }
    }
*/
    /**
     *  获取指定用户的信息
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function get_profile_by_id($id)
    {
        //$sql = "SELECT user_id, user_name, email, sex, birthday, reg_time FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id='$id'";
        //$row = $this->idb->getRow($sql);

        //return $row;
    }

    function get_user_info($username)
    {
        return $this->get_profile_by_name($username);
    }

    /**
     *  获取论坛有效积分及单位
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function get_points_name ()
    {
        return 'ucenter';
    }
}

?>