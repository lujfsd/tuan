<?php
include_once (VENDOR_PATH . 'user_login/baidu/Baidu.php');
define("APP_KEY_baidu", a_fanweC("APP_KEY_baidu"));       // input your key
define("APP_SECRET_baidu", a_fanweC("APP_SECRET_baidu")); //input you secret

if ($_REQUEST['oauth_baidu'] == '1') {
	$baidu = new Baidu(APP_KEY_baidu, APP_SECRET_baidu,
		new BaiduCookieStore(APP_KEY_baidu));
   $baidu->useHttps();
   $callback = 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . "/index.php?m=user&a=login_baidu&goods_id=" . intval($_REQUEST ['id']);
   $url = $baidu->getLoginUrl(array('response_type' => 'code',
  									'redirect_uri' => $callback));
   $access_token = $baidu->getAccessToken();
	if ($access_token){
		header("Location:$callback");
	}
	else
	{
		header("Location:$url");
	}
}
 else {
	 $baidu = new Baidu(APP_KEY_baidu, APP_SECRET_baidu,
		 new BaiduCookieStore(APP_KEY_baidu));
    $baidu->useHttps();
    $access_token = $baidu->getAccessToken();
    if ($access_token) {
			$user_profile = $baidu->api('passport/users/getInfo', 
										array('fields' => 'userid,username,sex,birthday'));
			
			$_SESSION['baidu_id'] = $user_profile["userid"];
			$_SESSION['qname'] = $user_profile["username"];
			$_SESSION['qmail'] = "";
    }
	else{
		//错误码
		$error_code = $baidu->errcode()."<br>";
		 
		//详细的错误信息
		$error_msg  = $baidu->errmsg()."<br>";
		 
		//查看具体的内容
		var_dump($error_code);
		var_dump($error_msg);
		echo 'You are not Connected.';
		die();
	}
	
    $u_id = $_SESSION['baidu_id'];
    $user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where baidu_id = '" . $_SESSION['baidu_id'] . "' and baidu_id <> 0");
    $names = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where user_name = '" . trim($_SESSION['qname']) . "' ");
    $has_name = empty($names) ? 0 : 1;
    if (!$user_data && !$has_name) {
        $data ['user_name'] = trim($_SESSION ['qname']);
        $data ['user_pwd'] = "";
        $data ['email'] = trim($_SESSION ['qname'])."@hegou.cn";
        $data ['baidu_id'] = trim($_SESSION ['baidu_id']);
        $data ['status'] = 1;
        $data ['last_ip'] = $_SESSION['CLIENT_IP'];
        $data ['score'] = intval(a_fanweC("DEFAULT_SCORE"));
        $data ['create_time'] = a_gmtTime();
        $data ['update_time'] = a_gmtTime();
        $data ['group_id'] = a_fanweC("DEFAULT_USER_GROUP");

        $code = a_fanweC('INTEGRATE_CODE');
        if (empty($code))
            $code = 'fanwe';
        if ($code == 'ucenter') {
            $users = & init_users3_1();
            $users->need_sync = false;
            $is_add = $users->add_user(trim($data['user_name']), trim($data['user_pwd']), trim($data ['email']));
            if ($is_add) {
                $user_id_arr = $users->get_profile_by_name($_REQUEST ['user_name']);
                $data ['ucenter_id'] = $user_id_arr ['id'];
            }
        }
        $GLOBALS ['db']->autoExecute(DB_PREFIX . "user", addslashes_deep($data), 'INSERT');
        $rs = intval($GLOBALS ['db']->insert_id());
        $user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where baidu_id = '" . $_SESSION['baidu_id'] . "' and baidu_id <> 0");
    }

    if ($user_data) {
        include_once(ROOT_PATH . 'app/source/func/com_user_func.php');
        user_do_login($user_data);
        exit;
    } else {
        $extend_fields = $GLOBALS ['db']->getAllCached("SELECT * FROM " . DB_PREFIX . "user_field where is_show = 1 order by sort desc");
        foreach ($extend_fields as $k => $v) {
            $extend_fields [$k] ['val_scope'] = explode(",", $v ['val_scope']);
        }
        $GLOBALS ['tpl']->assign("extend_fields", $extend_fields);

        $sql = "select id from " . DB_PREFIX . "article where type=1 and status=1";
        $id = $GLOBALS ['db']->getOneCached($sql);
        $agreement_url = a_u("Article/show", 'id-' . $id);

        $city_list = getGroupCityList();
        $GLOBALS ['tpl']->assign("city_list", $city_list);


        $GLOBALS ['tpl']->assign("agreement_url", $agreement_url);
        $GLOBALS ['tpl']->assign('redirect', $_REQUEST ['redirect']);
        $GLOBALS ['tpl']->assign('goods_id', $_REQUEST ['id']);

        $GLOBALS ['tpl']->assign('user_api_field_name', 'baidu_id');
        $GLOBALS ['tpl']->assign('user_api_field_value', intval($_SESSION['baidu_id']));

        //输出主菜单
        $GLOBALS ['tpl']->assign("main_navs", assignNav(2));
        //输出城市
        $GLOBALS ['tpl']->assign("city_list", getGroupCityList());
        //输出帮助
        $GLOBALS ['tpl']->assign("help_center", assignHelp());


        //$user=array('qid'=> $_SESSION['qid'],'qname'=> $_SESSION['qname'],'qmail'=> $_SESSION['qmail']);
        //print_r($user);
        //$GLOBALS ['tpl']->assign ( "user", $user );
        $GLOBALS ['tpl']->assign("help_center", assignHelp());

        $data = array('navs' => array(array('name' => a_L("HC_PLEASE_REG_OR_REGISTER"), 'url' => '')), 'keyword' => '', 'content' => '');



        assignSeo($data);
        $GLOBALS['tpl']->display('Inc/user/login_sync.moban');
    }
}

function &init_users3_1() {
    $set_modules = false;
    static $cls = null;
    if ($cls != null) {
        return $cls;
    }
    $code = a_fanweC('INTEGRATE_CODE');
    if (empty($code))
        $code = 'fanwe';
    $code = $code . '3';
    include_once (VENDOR_PATH . 'integrates3/' . $code . '.php');
    $cfg = unserialize(a_fanweC('INTEGRATE_CONFIG'));
    $cls = new $code($cfg);

    return $cls;
}

?>