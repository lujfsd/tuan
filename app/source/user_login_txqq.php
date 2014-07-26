<?php

//ini_set("error_reporting", E_ALL);
//ini_set("display_errors", TRUE);
//echo  "<br>user_id_txqq1:".$_SESSION['user_id_aa'];
//session_id("demo");
//请将下面信息更改成自己申请的信息
//echo "<br>user_id_aa3:".$_SESSION['user_id_aa'];
require_once(ROOT_PATH . 'app/source/func/com_user_func.php');
require_once(VENDOR_PATH . 'user_login/txqq/txqq.class.php');

$_SESSION["appid"] = a_fanweC('TXQQ_KEY'); //opensns.qq.com 申请到的appid
$_SESSION["appkey"] = a_fanweC('TXQQ_SECRET'); //opensns.qq.com 申请到的appkey
$api['app_key']=a_fanweC('TXQQ_KEY');
$api['app_secret']=a_fanweC('TXQQ_SECRET');
$txqq=new TxQq($api);

if ($_REQUEST['oauth_txqq'] == '1') {
	
    $txqq->do_qq_login();
    
} else {
	$access_token = $txqq->getAccessToken();
	$openid = $txqq->getQqOpenid($access_token);
	$user_info=$txqq->getQqUserInfo($api['app_key'],$access_token,$openid);
	$user_info['openid']=$openid;
    //将access token，openid保存!!
    //XXX 作为demo,临时存放在session中，网站应该用自己安全的存储系统来存储这些信息
    $_SESSION["token"] = $access_token;
    $_SESSION["openid"] = $openid;

    //第三方处理用户绑定逻辑
    //将openid与第三方的帐号做关联
    //bind_to_openid();
    $_SESSION['api_user_info'] = $user_info;

    $user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where txqq_id = '" . $user_info['openid'] . "' and txqq_id <> ''");
	
	
    $names = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where user_name = '" . trim($user_info['nickname']) . "' ");
    $has_name = empty($names) ? 0 : 1;
	if (!$user_data && !$has_name) {
        $data=getRandUser($user_info);
		
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
        
        $user_data = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "user where txqq_id = '" . $data['txqq_id'] . "' ");
		user_do_login($user_data);
		exit;
    }else{
        user_do_login($user_data);
        exit;
    } 
}

?>