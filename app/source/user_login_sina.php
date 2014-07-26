<?php
include_once (VENDOR_PATH . 'user_login/sina/saetv2.ex.class.php');
define("WB_CALLBACK_URL",'http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . "/index.php?m=user&a=login_sina&goods_id=" . intval($_REQUEST ['id']));
define( "WB_AKEY" , a_fanweC('SINA_KEY') );
define( "WB_SKEY" , a_fanweC('SINA_SECRET') );

if ($_REQUEST['oauth_sina'] == '1') {
    $o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);

   	$aurl = $o->getAuthorizeURL( WB_CALLBACK_URL );

    redirect2($aurl);
} else {     
	 $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
	
	 if (isset($_REQUEST['code'])) {
		$keys = array();
		$keys['code'] = $_REQUEST['code'];
		$keys['redirect_uri'] = WB_CALLBACK_URL;
		try {
			$token = $o->getAccessToken( 'code', $keys ) ;
		} catch (OAuthException $e) {
		}
	 }
	 
	 if ($token) {
		$_SESSION['token'] = $token;
		setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
	 }
	
     $c = new SaeTClientV2( WB_AKEY, WB_SKEY , $_SESSION['token']['access_token'] );
	 $ms  = $c->home_timeline(); // done
	 $uid_get = $c->get_uid();
	 $uid = $uid_get['uid'];
	 $sina = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
	 //print_r($sina);
	 //exit;              


    if ($sina === false || $sina === null) {
        a_error('Error_code: ' . $sina['error_code'] . ';  Error: ' . $sina['error'], 'Error_code: ' . $sina['error_code'] . ';  Error: ' . $sina['error'], __ROOT__ . "/index.php");
    }
    if (isset($sina['error_code']) && isset($sina['error'])) {
        a_error('Error_code: ' . $sina['error_code'] . ';  Error: ' . $sina['error'], 'Error_code: ' . $sina['error_code'] . ';  Error: ' . $sina['error'], __ROOT__ . "/index.php");
    }

    $_SESSION['api_user_info'] = $sina;
    $user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where sina_id = '" . $sina['id'] . "' and sina_id <> 0");
    $names = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where user_name = '" . trim($sina['name']) . "' ");
    $has_name = empty($names) ? 0 : 1;
    if (!$user_data && !$has_name) {
        $data ['user_name'] = trim($sina ['name']);
        $data ['user_pwd'] = md5(trim($sina ['qmail']));
        $data ['email'] = trim($sina ['name']);
        $data ['sina_id'] = trim($sina ['id']);
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
	     if($rs)
			{
				$score = intval ( $data ['score'] );
				$money = floatval ( $data ['money'] );
				if(!function_exists("s_user_money_log"))
				{
					require_once ROOT_PATH.'app/source/func/com_order_pay_func.php';
				}
				if ($money > 0)
					s_user_money_log ( $rs, $rs, 'User', $money, a_L ( "USER_ADD_USER" ), true );
			
				if ($score > 0)
					s_user_score_log ( $rs, $rs, 'User', $score, a_L ( "USER_ADD_USER" ), true );
			}
        $user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where sina_id = '" . $data['sina_id'] . "' and sina_id <> 0");
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

        $GLOBALS ['tpl']->assign('user_api_field_name', 'sina_id');
        $GLOBALS ['tpl']->assign('user_api_field_value', intval($sina['id']));

        //输出主菜单
        $GLOBALS ['tpl']->assign("main_navs", assignNav(2));
        //输出城市
        $GLOBALS ['tpl']->assign("city_list", getGroupCityList());
        //输出帮助
        $GLOBALS ['tpl']->assign("help_center", assignHelp());

        $data = array('navs' => array(array('name' => a_L("HC_PLEASE_REG_OR_REGISTER"), 'url' => '')), 'keyword' => '', 'content' => '');

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