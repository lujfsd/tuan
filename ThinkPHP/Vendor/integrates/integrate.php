<?php

class integrate
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
    
    /*------------------------------------------------------ */
    //-- PUBLIC METHODs
    /*------------------------------------------------------ */

    /**
     * 会员数据整合插件类的构造函数
     *
     * @access      public
     * @param       string  $db_host    数据库主机
     * @param       string  $db_name    数据库名
     * @param       string  $db_user    数据库用户名
     * @param       string  $db_pass    数据库密码
     * @return      void
     */
    function integrate($cfg)
    {
        $this->charset = isset($cfg['db_charset']) ? $cfg['db_charset'] : 'UTF8';
        $this->prefix = isset($cfg['prefix']) ? $cfg['prefix'] : '';
        $this->db_name = isset($cfg['db_name']) ? $cfg['db_name'] : '';
        $this->cookie_domain = isset($cfg['cookie_domain']) ? $cfg['cookie_domain'] : '';
        $this->cookie_path = isset($cfg['cookie_path']) ? $cfg['cookie_path'] : '/';
        $this->need_sync = false;

        $this->charset = strtoupper((str_replace('-', '', $this->charset)));
        
        $quiet = empty($cfg['quiet']) ? 0 : 1;

        $quiet = 1;
		//include_once(VENDOR_PATH."mysql.php");
		//include_once(ROOT_PATH."app/source/class/mysql_db.php");
		if(!class_exists("mysql_db"))
			include getcwd()."/app/source/class/mysql_db.php";
        if (empty($cfg['is_latin1']))
        {
        	//dump($cfg);
           $this->idb = new mysql_db($cfg['db_host'], $cfg['db_user'], $cfg['db_pass'], $cfg['db_name'], $this->charset, NULL,  $quiet);
           //dump($this->idb);
        }
        else
       {
           $this->idb = new mysql_db($cfg['db_host'], $cfg['db_user'], $cfg['db_pass'], $cfg['db_name'], 'latin1', NULL, $quiet) ;
       }

        if (!is_resource($this->idb->link_id))
        {
            $this->error = 1; //数据库地址帐号
        }
        else
        {
            $this->error = $this->idb->errno();
        }
    }


    /**
     * 在给定的表名前加上数据库名以及前缀
     *
     * @access  private
     * @param   string      $str    表名
     *
     * @return void
     */
    function table($str)
    {
        return '`' .$this->db_name. '`.`'.$this->prefix.$str.'`';
    }

    /**
     *  编译密码函数
     *
     * @access  public
     * @param   array   $cfg 包含参数为 $password, $md5password, $salt, $type
     *
     * @return void
     */
    function compile_password ($cfg)
    {
       if (isset($cfg['password']))
       {
            $cfg['md5password'] = md5($cfg['password']);
       }
       if (empty($cfg['type']))
       {
            $cfg['type'] = PWD_MD5;//md5加密方式
       }

       switch ($cfg['type'])
       {
           case PWD_MD5://md5加密方式
               return $cfg['md5password'];

           case PWD_PRE_SALT: //前置验证串的加密方式
               if (empty($cfg['salt']))
               {
                    $cfg['salt'] = '';
               }

               return md5($cfg['salt'] . $cfg['md5password']);

           case PWD_SUF_SALT://后置验证串的加密方式
               if (empty($cfg['salt']))
               {
                    $cfg['salt'] = '';
               }

               return md5($cfg['md5password'] . $cfg['salt']);

           default:
               return '';
       }
    }

    /**
     * 检查有无重名用户，有则返回重名用户
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function test_conflict ($user_list)
    {
        if (empty($user_list))
        {
            return array();
        }


        $sql = "SELECT " . $this->field_name . " FROM " . $this->table($this->user_table) . " WHERE " . db_create_in($user_list, $this->field_name);
        //dump($sql);
        $user_list = $this->idb->getCol($sql);

        return $user_list;
    }
}
