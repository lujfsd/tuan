<?php

class fanwe3 
{
    /*------------------------------------------------------ */
    //-- PUBLIC ATTRIBUTEs
    /*------------------------------------------------------ */

    /* 是否需要同步数据到商城 */
    var $need_sync = true;

    var $error          = 0;

    var $dbcfg = null;
    /*------------------------------------------------------ */
    //-- PRIVATE ATTRIBUTEs
    /*------------------------------------------------------ */
    
    var $is_fanwe = 0;
    	
	function __construct($cfg){
		
	}
    
    /*添加用户*/
    function add_user($username, $password, $email)
    {
         return true;
    }
    	
    /**
     *  检查指定用户是否存在及密码是否正确
     *
     * @access  public
     * @param   string  $username   用户名
     *
     * @return  int
     */
    function check_user($username, $password = '')
    {
        /* 如果没有定义密码则只检查用户名 */
    	$user_id = 0;
        if ($password == '')
        {
            $sql = "SELECT id FROM ".DB_PREFIX."user WHERE user_name ='" . $username ."'";
            $user_id = intval($GLOBALS['db']->getOne($sql));
        }
        else
        {
            if (preg_match('/^\w{32}$/', $password)){
        		$sql = "SELECT id FROM ".DB_PREFIX."user WHERE user_name ='" . $username . "' and user_pwd ='" . $password. "'";
        	}else{
        		$sql = "SELECT id FROM ".DB_PREFIX."user WHERE user_name ='" . $username . "' and user_pwd ='" . md5($password) . "'";
        	}
             
            if ($user_id == 0){//判断是否是最土过来用户
             	$SECRET_KEY = '@4!@#$%@';
            	$sql = "SELECT id FROM ".DB_PREFIX."user WHERE user_name ='" . $username . "' and user_pwd ='" . md5($password.$SECRET_KEY) . "'";
             	$user_id = intval($GLOBALS['db']->getOne($sql));   
            }
             
        	if ($user_id == 0) { //
				$sql = "SELECT id FROM " . DB_PREFIX . "user WHERE user_name ='" . $user_name . "' and user_pwd ='" . md5(md5($password)) . "'";
				$user_id = intval ( $GLOBALS ['db']->getOne ( $sql ) );
			}            
        }
        return $user_id;
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
    function login($username, $password, $email='')
    {
        if ($this->check_user($username, $password) > 0)
        {
            //$this->set_session($username);
            $this->set_cookie($username);
            return true;
        }
        else
        {
            $sql = "SELECT id FROM ".DB_PREFIX."user WHERE user_name ='" . $username ."'";
            $user_id = intval($GLOBALS['db']->getOne($sql));            
            if ($user_id > 0){
            	$this->error = a_L('PWD_IS_WRONG');//'口令不对，请重新录入';
            }else{
            	$this->error = a_L('USER_IS_WRONG');//'用户不存在，请重新录入';
            }
            return false;
        }
    } 
   	
	/**
     * 编辑用户
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function edit_user()
    {
        return true;
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
        $this->set_cookie();  //清除cookie
        return true;
    }
        
    /**
     *  设置cookie
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function set_cookie($username='')
    {
        if (empty($username))
        {
            /* 摧毁cookie */
		  	 unset($_SESSION['user_name']);
			 unset($_SESSION['user_id']);
			 unset($_SESSION['group_id']);
			 unset($_SESSION['user_email']);
			 unset($_SESSION['other_sys']);
			setcookie("email",null);
			setcookie("password",null);
			setcookie("fanwe_user_id",null);		
        }
        else
        {
            /* 设置cookie */
            $userinfo = $GLOBALS['db']->getRow("select id,user_name,email,user_pwd,status,group_id,score from ".DB_PREFIX."user where user_name='".$username."' limit 1");
            if ($userinfo)
            {
				$_SESSION['user_name'] = $userinfo['user_name'];
				$_SESSION['user_id'] = $userinfo['id'];
				$_SESSION['group_id'] = $userinfo['group_id'];
				$_SESSION['user_email'] = $userinfo['email'];
				$_SESSION['score'] = $userinfo['score'];	

				setcookie('fanwe_user_id',base64_encode(serialize($userinfo['id'])), time() + 365*60*60*24);
            }
        }
    }

    function get_profile_by_name($username)
    {
        return array();
    } 
		/*太平洋*/
	   /**
     *  用户登录函数
     *
     * @access  public
     * @param   string  $username
     * @param   string  $password
     *
     * @return void
     */
    function login_tpy($username, $password, $email='')
    {
        if ($this->check_user_tpy($username, $password) > 0)
        {
            //$this->set_session($username);
            $this->set_cookie($username);
            return true;
        }
        else
        {
            $sql = "SELECT id FROM ".DB_PREFIX."user WHERE user_name ='" . $username ."'";
            $user_id = intval($GLOBALS['db']->getOne($sql));            
            if ($user_id > 0){
            	$this->error = a_L('PWD_IS_WRONG');//'口令不对，请重新录入';
            }else{
            	$this->error = a_L('USER_IS_WRONG');//'用户不存在，请重新录入';
            }
            return false;
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
    function check_user_tpy($username, $password = '')
    {
        /* 如果没有定义密码则只检查用户名 */
    	$user_id = 0;
        if ($password == '')
        {
            $sql = "SELECT id FROM ".DB_PREFIX."user WHERE user_name ='" . $username ."'";
            $user_id = intval($GLOBALS['db']->getOne($sql));
        }
        else
        {
		    $sql = "SELECT id FROM ".DB_PREFIX."user WHERE user_name ='" . $username . "' and user_pwd ='" . $password . "'";
            $user_id = intval($GLOBALS['db']->getOne($sql));
             
            if ($user_id == 0){//判断是否是最土过来用户
             	$SECRET_KEY = '@4!@#$%@';
            	$sql = "SELECT id FROM ".DB_PREFIX."user WHERE user_name ='" . $username . "' and user_pwd ='" . md5($password.$SECRET_KEY) . "'";
             	$user_id = intval($GLOBALS['db']->getOne($sql));   
            }
             
        }
        return $user_id;
    }
}

?>