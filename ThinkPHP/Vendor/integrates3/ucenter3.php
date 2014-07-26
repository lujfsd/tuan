<?php

/**
 * UCenter 会员数据处理类
 */


class ucenter3
{
    /*------------------------------------------------------ */
    //-- PUBLIC ATTRIBUTEs
    /*------------------------------------------------------ */

    /* 整合对象使用的数据库主机 */
    var $db_host        = '';

    /* 整合对象使用的数据库名 */
    var $db_name        = '';

    /* 整合对象使用的数据库用户名 */
    var $db_user        = '';

    /* 整合对象使用的数据库密码 */
    var $db_pass        = '';

    /* 整合对象数据表前缀 */
    var $prefix         = '';

    /* 数据库所使用编码 */
    var $charset        = '';

    /* 整合对象使用的cookie的domain */
    var $cookie_domain  = '';

    /* 整合对象使用的cookie的path */
    var $cookie_path    = '/';

    /* 整合对象会员表名 */
    var $user_table = '';

    /* 会员ID的字段名 */
    var $field_id       = '';

    /* 会员名称的字段名 */
    var $field_name     = '';

    /* 会员密码的字段名 */
    var $field_pass     = '';

    /* 会员邮箱的字段名 */
    var $field_email    = '';

    /* 会员性别 */
    var $field_gender = '';

    /* 会员生日 */
    var $field_bday = '';

    /* 注册日期的字段名 */
    var $field_reg_date = '';

    /* 是否需要同步数据到商城 */
    var $need_sync = true;

    var $error          = 0;

    var $dbcfg = null;
    /*------------------------------------------------------ */
    //-- PRIVATE ATTRIBUTEs
    /*------------------------------------------------------ */

    var $idb;
    
    var $is_fanwe = 0;
    
    var $ucdata = '';
    	
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
            
            include_once(VENDOR_PATH . 'uc_client/client.php');
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
    function login($username, $password, $email = '', $uid=0)
    {
		if ($uid > 0){
		    $this->ucdata = uc_user_synlogin($uid); 
			$_SESSION['ucdata'] = base64_encode($this->ucdata);		
			return true;	
		}

		if (empty($password) || $password == ''){
			$passwordmd5 = $password;
		}else{
			$passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);
		}	
		
    	if (empty($username) && !empty($email)){
    		
    		//zuanshi@ecshop.com
			//include_once(VENDOR_PATH."mysql.php");
			include_once(getcwd()."/app/source/class/mysql_db.php");
			
	        if (empty($this->dbcfg['is_latin1']))
	        {
	        	//dump($cfg);
	           $this->idb = new mysql_db($this->dbcfg['db_host'], $this->dbcfg['db_user'], $this->dbcfg['db_pass'], $this->dbcfg['db_name'], $this->dbcfg['db_charset'], NULL,  1);
	           //dump($this->idb);
	        }
	        else
	        {
	           $this->idb = new mysql_db($this->dbcfg['db_host'], $this->dbcfg['db_user'], $this->dbcfg['db_pass'], $this->dbcfg['db_name'], 'latin1', NULL, 1) ;
	        }
	
	        
	        if (!is_resource($this->idb->link_id))
	        {
	            $this->error = 'ucenter数据库连接出错';
	            return false;
	        }
	        else
	        {
	            $this->error = $this->idb->errno();
	        }    		
    	    if ($this->charset == 'gbk'){
        		$email = addslashes(a_utf8ToGB($email));
	    	}else{
        		$email = addslashes($email);
	    	}	        
	        
	        $sql = "SELECT " . $this->field_name .
	               " FROM " . $this->table($this->user_table).
	               " WHERE " . $this->field_email . " = '$email'";
	        

	        
	        $username = $this->idb->getOne($sql, true);
	        
    	    if ($this->charset == 'gbk'){
        		$username = addslashes(a_gbToUTF8($username));
	    	}	
    	}
    	
        if ($this->charset == 'gbk'){
        	$username = addslashes(a_utf8ToGB($username));
        	$passwordmd5 = addslashes(a_utf8ToGB($passwordmd5));
	    }else{
	    	$username = addslashes($username);
	    	$passwordmd5 = addslashes($passwordmd5);
	    }
	        	
    	if (empty($password) || $password == ''){
    		list($uid, $uname, $email) =  uc_get_user($username);// $this->uc_call("uc_get_user", array($username));
    	}else{
    		list($uid, $uname, $pwd, $email, $repeat) = uc_user_login($username, $passwordmd5); //$this->uc_call("uc_user_login", array($username, $password));
    	}
    	
        if ($this->charset == 'gbk'){
        	$uname = addslashes(a_gbToUTF8($uname));
        	$email = addslashes(a_gbToUTF8($email));
        	$passwordmd5 = addslashes(a_gbToUTF8($passwordmd5));
	    }else{
	    	$uname = addslashes($uname);
	    	$passwordmd5 = addslashes($passwordmd5);
	    }    	
        
        //dump($uid);
        if($uid > 0)
        {
            //检查用户是否存在,不存在直接放入用户表
            $user_exist = $GLOBALS['db']->getRow("SELECT id FROM ".DB_PREFIX . "user WHERE user_name='$uname' AND user_pwd = '" . $passwordmd5 ."'");
            
            $name_exist = $GLOBALS['db']->getRow("SELECT id FROM " .DB_PREFIX . "user WHERE user_name='$uname'");
            if (empty($user_exist))
            {
                if(empty($name_exist))
                {
                    $reg_date = time();
                    $ip = $_SESSION['CLIENT_IP'];
                    $GLOBALS['db']->query('INSERT INTO ' . DB_PREFIX . "user(`ucenter_id`, `email`, `user_name`, `user_pwd`, `create_time`, `last_ip`, status) VALUES ('$uid', '$email', '$uname', '$passwordmd5', '$reg_date',  '$ip', 1)");
                }
                else 
                {
                    $GLOBALS['db']->query('UPDATE ' . DB_PREFIX . "user SET `user_pwd`='".$passwordmd5."' WHERE user_name='$uname' limit 1");
                }
            }
            
            //$this->set_session($uname);
            //$this->set_cookie($uname);
            $this->ucdata = uc_user_synlogin($uid); //$this->uc_call("uc_user_synlogin", array($uid));
            //$user_id = $GLOBALS['db']->getOne("SELECT id FROM " .DB_PREFIX . "user WHERE user_name='$uname' limit 1");
            //dump($this->ucdata);
			$_SESSION['ucdata'] = base64_encode($this->ucdata);
			$this->ucdata = '';
            return true;
        }
        elseif($uid == -1)
        {
            $this->error = '无效的Email';
            return false;
        }
        elseif ($uid == -2)
        {
            $this->error = '密码错误';
            return false;
        }
        else
        {
        	$this->error = '登陆异常';
            return false;
        }
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
        $this->ucdata = uc_user_synlogout();   //同步退出
        //dump($this->ucdata);
		$_SESSION['ucdata'] = base64_encode($this->ucdata);
		$this->ucdata = '';
        return true;
    }

    /*添加用户*/
    function add_user($username, $password, $email)
    {
    	/*
        // 检测用户名
        //dump('add_user');
        //var_dump($username);
        if ($this->check_user($username))
        {
        	//var_dump($this->error); exit;
            //$this->error = ERR_USERNAME_EXISTS;
            return false;
        }

        if ($this->check_email($email))
        {
        	//var_dump($this->error); exit;
            //$this->error = ERR_USERNAME_EXISTS;
            return false;
        }        
        */
        
        if ($this->charset == 'gbk'){
        	$username = addslashes(a_utf8ToGB($username));
        	$password = addslashes(a_utf8ToGB($password));
        	$email = addslashes(a_utf8ToGB($email));
	    }else{
        	$username = addslashes($username);
        	$password = addslashes($password);
        	$email = addslashes($email);
	    }
	            
        $uid = uc_user_register($username, $password, $email);
        
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
        	$username = addslashes(a_utf8ToGB($username));
	    }else{
        	$username = addslashes($username);
	    } 
	        	
        $userdata = uc_user_checkname($username);
        //var_dump($userdata);
        
		if($userdata > 0) {
			$this->error =  '用户名可用';
		} elseif($userdata == -1) {
			$this->error =  '用户名不合法';
		} elseif($userdata == -2) {
			$this->error =  '包含要允许注册的词语';
		} elseif($userdata == -3) {
			$this->error =  '用户名已经存在';
		}

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
        	$email = addslashes(a_utf8ToGB($email));
	    }else{
        	$email = addslashes($email);
	    }     	
        if (!empty($email))
        {
            $email_exist = uc_user_checkemail($email);
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
        	$real_username = addslashes(a_utf8ToGB($cfg['username']));
        	$cfg['username'] = addslashes(a_utf8ToGB($cfg['username']));	 
        	$cfg['email'] = addslashes(a_utf8ToGB($cfg['email']));
        	$cfg['password'] = addslashes(a_utf8ToGB($cfg['password']));       
	    }else{
        	$real_username = addslashes($cfg['username']);
        	$cfg['username'] = addslashes($cfg['username']);
        	$cfg['email'] = addslashes($cfg['email']);
        	$cfg['password'] = addslashes($cfg['password']);
	    }    	


        if (!empty($cfg['email']))
        {
            $ucresult = uc_user_edit($cfg['username'], '', '', $cfg['email'], 1);
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
            $ucresult = uc_user_edit($real_username, '', $cfg['password'], '', '1');
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
        	$username = addslashes(a_utf8ToGB($username));
	    }else{
        	$username = addslashes($username);
	    } 
		list($uid, $username, $email) = uc_get_user($username);
        
        if ($this->charset == 'gbk'){
        	$username = addslashes(a_gbToUTF8($username));
        	$email = addslashes(a_gbToUTF8($email));
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
        	$user_names = addslashes(a_utf8ToGB($user_names));
	    }else{
        	$user_names = addslashes($user_names);
	    } 
    	$res = uc_user_delete($user_names);
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
    
    function table($str)
    {
        return '`' .$this->db_name. '`.`'.$this->prefix.$str.'`';
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
    function login_tpy($username, $password, $email = '')
    {
    	if (empty($username) && !empty($email)){
    		
    		//zuanshi@ecshop.com
			//include_once(VENDOR_PATH."mysql.php");
			include_once(getcwd()."/app/source/class/mysql_db.php");
			
	        if (empty($this->dbcfg['is_latin1']))
	        {
	        	//dump($cfg);
	           $this->idb = new mysql_db($this->dbcfg['db_host'], $this->dbcfg['db_user'], $this->dbcfg['db_pass'], $this->dbcfg['db_name'], $this->dbcfg['db_charset'], NULL,  1);
	           //dump($this->idb);
	        }
	        else
	        {
	           $this->idb = new mysql_db($this->dbcfg['db_host'], $this->dbcfg['db_user'], $this->dbcfg['db_pass'], $this->dbcfg['db_name'], 'latin1', NULL, 1) ;
	        }
	
	        
	        
	        if (!is_resource($this->idb->link_id))
	        {
	            $this->error = 'ucenter数据库连接出错';
	            return false;
	        }
	        else
	        {
	            $this->error = $this->idb->errno();
	        }    		
    	    if ($this->charset == 'gbk'){
        		$email = addslashes(a_utf8ToGB($email));
	    	}else{
        		$email = addslashes($email);
	    	}	        
	        
	        $sql = "SELECT " . $this->field_name .
	               " FROM " . $this->table($this->user_table).
	               " WHERE " . $this->field_email . " = '$email'";
	        

	        
	        $username = $this->idb->getOne($sql, true);
	        
    	    if ($this->charset == 'gbk'){
        		$username = addslashes(a_gbToUTF8($username));
	    	}	

	    	
    	}
    	
        if ($this->charset == 'gbk'){
        	$username = addslashes(a_utf8ToGB($username));
        	$password = addslashes(a_utf8ToGB($password));
	    }else{
	    	$username = addslashes($username);
	    	$password = addslashes($password);
	    }
	        	
    	if (empty($password) || $password == ''){
    		list($uid, $uname, $email) =  uc_get_user($username);// $this->uc_call("uc_get_user", array($username));
    	}else{
    		list($uid, $uname, $pwd, $email, $repeat) = uc_user_login($username, $password); //$this->uc_call("uc_user_login", array($username, $password));
    	}
    	
    	
    	
        if ($this->charset == 'gbk'){
        	$uname = addslashes(a_gbToUTF8($uname));
        	$email = addslashes(a_gbToUTF8($email));
        	$password = addslashes(a_gbToUTF8($password));
	    }else{
	    	$uname = addslashes($uname);
	    	$password = addslashes($password);
	    }    	
        
        //dump($uid);
        if($uid > 0)
        {
            //检查用户是否存在,不存在直接放入用户表
            $user_exist = $GLOBALS['db']->getRow("SELECT id FROM ".DB_PREFIX . "user WHERE user_name='$uname' AND user_pwd = '" . $password ."'");
            
            $name_exist = $GLOBALS['db']->getRow("SELECT id FROM " .DB_PREFIX . "user WHERE user_name='$uname'");
            if (empty($user_exist))
            {
                if(empty($name_exist))
                {
                    $reg_date = time();
                    $ip = $_SESSION['CLIENT_IP'];
                    $password = $password;
                    $GLOBALS['db']->query('INSERT INTO ' . DB_PREFIX . "user(`ucenter_id`, `email`, `user_name`, `user_pwd`, `create_time`, `last_ip`, status) VALUES ('$uid', '$email', '$uname', '$password', '$reg_date',  '$ip', 1)");
                }
                else 
                {
                    $GLOBALS['db']->query('UPDATE ' . DB_PREFIX . "user SET `user_pwd`='".$password."' WHERE user_name='$uname' limit 1");
                }
            }
            
            //$this->set_session($uname);
            //$this->set_cookie($uname);
            $this->ucdata = uc_user_synlogin($uid); //$this->uc_call("uc_user_synlogin", array($uid));
            //$user_id = $GLOBALS['db']->getOne("SELECT id FROM " .DB_PREFIX . "user WHERE user_name='$uname' limit 1");
            //dump($this->ucdata);
			$_SESSION['ucdata'] = base64_encode($this->ucdata);
			$this->ucdata = '';
            return true;
        }
        elseif($uid == -1)
        {
            $this->error = '无效的Email';
            return false;
        }
        elseif ($uid == -2)
        {
            $this->error = '密码错误';
            return false;
        }
        else
        {
        	$this->error = '登陆异常';
            return false;
        }
    }
}

?>