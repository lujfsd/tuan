<?php

define("MB_RETURN_FORMAT", 'json');
define("MB_API_HOST", 'open.t.qq.com');
include_once (VENDOR_PATH . 'user_login/qq/oauth.php');
include_once (VENDOR_PATH . 'user_login/qq/opent.php');
include_once (VENDOR_PATH . 'user_login/qq/api_client.php');

if ($_REQUEST['oauth_qq'] == '1') {
    $o = new MBOpenTOAuth(a_fanweC('QQ_KEY'), a_fanweC('QQ_SECRET'));

    $keys = $o->getRequestToken('http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . "/index.php?m=user&a=login_qq&goods_id=" . intval($_REQUEST ['id']));
    $_SESSION['qq_keys'] = $keys;

    $aurl = $o->getAuthorizeURL($keys['oauth_token'], false, '');
    redirect2($aurl);
} else {
    $o = new MBOpenTOAuth(a_fanweC('QQ_KEY'), a_fanweC('QQ_SECRET'), $_SESSION['qq_keys']['oauth_token'], $_SESSION['qq_keys']['oauth_token_secret']);
    $last_key = $o->getAccessToken($_REQUEST['oauth_verifier']);
    $u_id = $last_key['name'];


    $c = new MBApiClient(a_fanweC('QQ_KEY'),
                    a_fanweC('QQ_SECRET'),
                    $last_key['oauth_token'],
                    $last_key['oauth_token_secret']);


    $qqwb = $c->getUserInfo(array('n' => $u_id));
    if ($qqwb === false || $qqwb === null || $qqwb['msg'] != 'ok') {
        a_error('Error: ' . $qqwb['msg'], __ROOT__ . "/index.php");
    }

    $_SESSION['api_user_info'] = $qqwb;

    $user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where qq_id = '" . $qqwb['data']['name'] . "' and qq_id <> ''");
    $names = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where user_name = '" . trim($qqwb['data']['name']) . "' ");
    $has_name = empty($names) ? 0 : 1;
    if (!$user_data && !$has_name) {
        $data ['id'] = '';
        $data ['user_name'] = trim($qqwb['data']['name']);
        $data ['user_pwd'] = md5(trim($qqwb['data']['name']));
        $data ['email'] = trim($qqwb['data']['name'] . '@qq.com');
        $data ['qq_id'] = trim($qqwb['data']['name']);
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
        $user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where qq_id = '" . $_SESSION['qid'] . "' and qq_id <> 0");
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

        $GLOBALS ['tpl']->assign('user_api_field_name', 'qq_id');
        $GLOBALS ['tpl']->assign('user_api_field_value', $qqwb['data']['name']);

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