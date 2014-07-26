<?php

$aliapy_config = array(
    //合作身份者id，以2088开头的16位纯数字
    "partner" => a_fanweC('ALIAPY_PARTNER'),
    //安全检验码，以数字和字母组成的32位字符
    "key" => a_fanweC('ALIAPY_KEY'),
    //安全检验码，以数字和字母组成的32位字符
    //页面跳转同步通知路径 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
    //return_url的域名不能写成http://localhost/alipay.auth.authorize_php_utf8/return_url.php ，否则会导致return_url执行无效
    "return_url" => 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . "/api/alipay_login.php",
    //签名方式 不需修改
    "sign_type" => 'MD5',
    //字符编码格式 目前支持 gbk 或 utf-8
    "input_charset" => 'utf-8',
    //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
    "transport" => 'http',
);

if ($_REQUEST['oauth_alipay'] == '1') {
    //构造要请求的参数数组，无需改动
    $parameter = array(
        //扩展功能参数——防钓鱼
        "anti_phishing_key" => '',
        "exter_invoke_ip" => '',
    );
    include_once (VENDOR_PATH . 'user_login/alipay/alipay_service.class.php');

    //构造快捷登录接口
    $alipayService = new AlipayService($aliapy_config);
    $html_text = $alipayService->alipay_auth_authorize($parameter);
    //print_r($html_text);
    redirect2($html_text);
} else {
    //计算得出通知验证结果
    include_once (VENDOR_PATH . 'user_login/alipay/alipay_notify.class.php');
    $alipayNotify = new AlipayNotify($aliapy_config);
    $verify_result = $alipayNotify->verifyReturn();
    if ($verify_result) {//验证成功
        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
        $u_id = $_GET['user_id']; //支付宝用户id
        //print_r($_GET);
        $token = $_GET['token']; //授权令牌
        $user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where alipay_id = '" . $u_id . "' and alipay_id <> 0");
        $names = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where user_name = '" . trim($_GET['real_name']) . "' ");
         $_SESSION['token']=trim($_GET['token']);
        $has_name = empty($names) ? 0 : 1;
        if (!$user_data && !$has_name) {
            $data ['user_name'] = trim($_GET['real_name']);
            $data ['user_pwd'] = trim($_GET['real_name']);
            $data ['email'] = trim($_GET['real_name'] . "@alipay.com");
            $data ['alipay_id'] = trim($_GET['user_id']);
            $data ['status'] = 1;
           
            $data ['last_ip'] = $_SESSION['CLIENT_IP'];
            $data ['score'] = intval(a_fanweC("DEFAULT_SCORE"));
            $data ['create_time'] = a_gmtTime();
            $data ['update_time'] = a_gmtTime();
            $data ['group_id'] = a_fanweC("DEFAULT_USER_GROUP");

            $code = a_fanweC('INTEGRATE_CODE');
            if (empty($code))
                $code = 'fanwe';
			$is_add = false;
            if ($code == 'ucenter') {
                $users = & init_users3_1();
                $users->need_sync = false;
                $is_add = $users->add_user(trim($data['user_name']), trim($data['user_pwd']), trim($data ['email']));
                if ($is_add) {
                    $user_id_arr = $users->get_profile_by_name($data ['user_name']);
                    $data ['ucenter_id'] = $user_id_arr ['id'];
                }
            }
			if(!$is_add && $code == 'ucenter'){
				unset($user_data);
			}
			else{
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
				$user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where alipay_id = '" . $_GET['user_id'] . "' and alipay_id <> 0");
			}
        }

        if ($user_data) {

            include_once(ROOT_PATH . 'app/source/func/com_user_func.php');
            user_do_login($user_data);
            if($_GET['target_url'] != "") {
            echo "<script>window.location =\"".$_GET['target_url']."\";</script>";
            }
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
            $GLOBALS ['tpl']->assign('redirect', '');
            $GLOBALS ['tpl']->assign('goods_id', 0);

            $GLOBALS ['tpl']->assign('user_api_field_name', 'alipay_id');
            $GLOBALS ['tpl']->assign('user_api_field_value', $u_id);

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
    } else {
        a_error('验证失败', '', 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__);
        exit;
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