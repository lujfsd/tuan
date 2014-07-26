<?php
/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = 'fanwe';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'FANWE';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '2.0';

    /* 插件的作者 */
    $modules[$i]['author']  = 'FANWE R&D TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.fanwe.com';

    return;
}

require_once(VENDOR_PATH.'integrates/integrate.php');
class fanwe extends integrate
{

    function __construct($cfg)
    {
        //parent::integrate(array());
        $this->user_table = 'user';
        $this->field_id = 'id';
        $this->field_name = 'user_name';
        $this->field_pass = 'user_pwd';
        $this->field_email = 'email';
        $this->field_gender = 'sex';
        $this->field_bday = 'NULL';
        $this->field_reg_date = 'create_time';
        $this->prefix = C('DB_PREFIX');
        $this->db_name = C('DB_NAME');        
        $this->need_sync = false;
        $this->is_fanwe = 1;
    }


    /*添加用户*/
    function add_user($username, $password, $email)
    {
         return true;
    }
    
	function edit_user($cfg, $forget_pwd = '0'){
		return true;
	}
	
    function get_profile_by_name($username)
    {
        return array();
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
    	
        $post_username = $username;
        /* 如果没有定义密码则只检查用户名 */
        if ($password == '')
        {
            $sql = "SELECT " . $this->field_id .
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_name . "='" . $post_username . "'";
            $re = M()->query($sql);
        }
        else
        {
            $sql = "SELECT " . $this->field_id .
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_name . "='" . $post_username . "' AND " . $this->field_pass . " ='" . $this->compile_password(array('password'=>$password)) . "'";
             $re = M()->query($sql);
             
             if (intval($re[0][$this->field_id]) == 0){//判断是否是最土过来用户
             	$SECRET_KEY = '@4!@#$%@';
            	$sql = "SELECT " . $this->field_id .
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_name . "='" . $post_username . "' AND " . $this->field_pass . " ='" . md5($password.$SECRET_KEY) . "'";
            	//dump($sql);
            	//exit;
             	$re = M()->query($sql);    
             	//dump($re);  
             	//exit;       
             }
             
        }
        return $re[0][$this->field_id];
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
            $data['user_name'] = $username;
            if (D("User")->where($data)->find()){
            	$this->error = L('PWD_IS_WRONG');//'口令不对，请重新录入';
            }else{
            	$this->error = L('USER_IS_WRONG');//'用户不存在，请重新录入';
            }
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
			setcookie("email",null);
			setcookie("password",null);
			setcookie("fanwe_user_id",null);
        }
        else
        {
            /* 设置cookie */
            $data['user_name'] = $username;
            if ($userinfo = D("User")->where($data)->find())
            {
				Session::set("user_name",$userinfo['user_name']);
				Session::set("user_id",$userinfo['id']);
				Session::set("group_id",$userinfo['group_id']);
				Session::set("user_email",$userinfo['email']);
				Session::set("score",$userinfo['score']);   
				$_SESSION['user_id'] = $userinfo['id'];
				setcookie('fanwe_user_id',base64_encode(serialize($userinfo['id'])), time() + 365*60*60*24);     
            }
        }
    }
        
    function remove_user_by_names($user_names)
    {
    	return true;    
    }    
}

?>