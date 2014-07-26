<?php
require ROOT_PATH.'app/source/db_init.php';
require ROOT_PATH.'app/source/comm_init.php';
require ROOT_PATH.'app/source/func/com_func.php';
require ROOT_PATH.'app/source/user_init.php';
require ROOT_PATH.'app/source/insert_comm.php';
//检查是否关闭
check_closed();

	require ROOT_PATH.'app/source/class/Pager.php';
if(empty($_SESSION ['user_id'])&&!empty($_REQUEST['qid'])&&(!empty($_REQUEST['qname'])||!empty($_REQUEST['qmail']))&&!empty($_REQUEST['sign'])&&($_REQUEST['from']=='hao360'))
{
	require ROOT_PATH.'app/source/user_login_360.php';
	exit;
}
if((!empty($_REQUEST['qid'])||!empty($_REQUEST['qname'])||!empty($_REQUEST['qmail']))&&!empty($_REQUEST['sign'])&&($_REQUEST['from']=='tuan800'))
		{
			require ROOT_PATH.'app/source/user_login_800.php';
	        exit;
		}

    if ($_REQUEST['m']=='User'&&$_REQUEST['a']=='doLogin'){
      	require ROOT_PATH.'app/source/func/com_user_func.php';
		user_do_login();
		exit;
	}
	if ($_REQUEST['m']=='User'&&$_REQUEST['a']=='doRegister'){
		require ROOT_PATH."app/source/func/com_send_sms_func.php";
		require ROOT_PATH."app/source/func/com_order_pay_func.php";
      	require ROOT_PATH.'app/source/func/com_user_func.php';
		user_do_register();
		exit;
	}

	if ($_REQUEST['m']=='User'&&$_REQUEST['a']=='ajax_register'){
		require ROOT_PATH."app/source/func/com_send_sms_func.php";
		require ROOT_PATH."app/source/func/com_order_pay_func.php";
      	require ROOT_PATH.'app/source/func/com_user_func.php';
		user_ajax_register();
		exit;
	}

	if ($_REQUEST['m']=='User'&&$_REQUEST['a']=='sendVerifySn'){
		require ROOT_PATH."app/source/func/com_send_sms_func.php";
      	require ROOT_PATH.'app/source/func/com_user_func.php';
		user_sendVerifySn();
		exit;
	}

	if ($_REQUEST['m']=='User'&&$_REQUEST['a']=='verify'){
      	require ROOT_PATH.'app/source/func/com_user_func.php';
		user_verify();
		exit;
	}
	if ($_REQUEST['m']=='User'&&$_REQUEST['a']=='doResetreq'){
      	require ROOT_PATH.'app/source/func/com_user_func.php';
		user_doResetreq();
		exit;
	}
	if ($_REQUEST['m']=='User'&&$_REQUEST['a']=='doReset'){
		require ROOT_PATH.'app/source/func/com_user_func.php';
		user_doReset();
		exit;
	}

		
	//会员登陆退出
    if ($_REQUEST['m']=='User'&&$_REQUEST['a']=='logout'){
      	require ROOT_PATH.'app/source/func/com_user_func.php';
		user_logout();
		exit;
	}

	if ($_REQUEST['m']=='Payment'&& isset($_REQUEST['payment_name'])&& isset($_REQUEST['md'])){
		$payment_name = $_REQUEST['payment_name']."Payment";
		$pay_file = VENDOR_PATH.'payment3/'.$payment_name.'.class.php';
		if(file_exists($pay_file) ){
			require_once($pay_file);
			$pay_method = $_REQUEST['md'];
			$payment_model = new $payment_name;	
			 if (method_exists($payment_model,$pay_method)){
			 	require_once ROOT_PATH."app/source/func/com_send_sms_func.php";
			 	require_once ROOT_PATH."app/source/func/com_order_pay_func.php";			 	
			  	$res = $payment_model->$pay_method();
			  	exit;
			}
		}	
	}
	
   	//处理网银在线2.3接口
	if ($_REQUEST['m']=='Chinabank'&&$_REQUEST['a']=='index'){
		require ROOT_PATH."app/source/func/com_send_sms_func.php";
      	require ROOT_PATH."app/source/func/com_order_pay_func.php";
		chinaBankIndex();
		exit;
	}
	//处理支付接口
  	if ($_REQUEST['m']=='Payment'&&$_REQUEST['a']=='response'){
  		require ROOT_PATH."app/source/func/com_send_sms_func.php";
      	require ROOT_PATH."app/source/func/com_order_pay_func.php";
		payment_response();
		exit;
	}

	if ($_REQUEST['m']=='Payment'&&$_REQUEST['a']=='Kuaiqian'){
  		require ROOT_PATH."app/source/func/com_send_sms_func.php";
      	require ROOT_PATH."app/source/func/com_order_pay_func.php";
		KuaiqianIndex();
		exit;
	}
	//盛付通  add by chenfq 2011-05-18
	if ($_REQUEST['m']=='Payment'&&$_REQUEST['a']=='Sdo'){
  		require ROOT_PATH."app/source/func/com_send_sms_func.php";
      	require ROOT_PATH."app/source/func/com_order_pay_func.php";
		SdoIndex();
		exit;
	}	

	//支付宝 add by chenfq 2011-05-30
	if ($_REQUEST['m']=='Payment'&&$_REQUEST['a']=='Alipay'){
  		require ROOT_PATH."app/source/func/com_send_sms_func.php";
      	require ROOT_PATH."app/source/func/com_order_pay_func.php";
		AlipayIndex();
		exit;
	}
		
   	$user_id =  intval($_SESSION['user_id']);
   	$session_id = session_id();

    $currentCity = $GLOBALS['db']->getRowCached("SELECT id,py,`desc`,tip,name,notice,qq_1,qq_2,qq_3,qq_4,qq_5,qq_6 FROM ".DB_PREFIX."group_city where id = ".intval(C_CITY_ID));
    if(!isset($currentCity) || intval(C_CITY_ID)==0)
    {
    	a_error(a_l("PLASE_SET_DEFAULT_CITY"),"","back");
    }
    $default_city = $GLOBALS['db']->getRowCached("SELECT id,py,name,notice FROM ".DB_PREFIX."group_city where verify = 1 and is_defalut =1");
   	$GLOBALS['tpl']->assign("currentCity",$currentCity);
   	$GLOBALS['tpl']->assign("default_city",$default_city);
  
   	//搜索次级城市
   	$sub_citys = $GLOBALS['db']->getAllCached("select id,pid,py,name from ".DB_PREFIX."group_city WHERE pid in (select pid from ".DB_PREFIX."group_city where id=".C_CITY_ID." and pid<>0) order by sort asc");
   	if(!$sub_citys)
   	{
   		$sub_citys = $GLOBALS['db']->getAllCached("select id,pid,py,name from ".DB_PREFIX."group_city WHERE pid = ".C_CITY_ID." order by sort asc");
   	}
   	foreach($sub_citys as $idx => $v)
    {
   		$sub_citys[$idx]['url'] = a_u("Index/index","cityname-{$v['py']}");
    }
    $GLOBALS['tpl']->assign("sub_citys",$sub_citys);
	$GLOBALS['tpl']->assign("lang",$GLOBALS['Ln']);
	if (isset($_SESSION['error'])){
		$err = $_SESSION['error'];
		unset($_SESSION['error']);
		//echo $err; exit;
		$GLOBALS['tpl']->assign('error',$err);
	}
	$tpl->assign('SHOP_NAME',SHOP_NAME);
	if(intval(a_fanweC('REFERRAL_TYPE')) == 0 )
		$referralsMoney = a_formatPrice(a_fanweC('REFERRALS_MONEY'));
	else
		$referralsMoney = a_fanweC('REFERRALS_MONEY').a_L('SCORE_UNIT');

	$tpl->assign("referralsMoney",$referralsMoney);
	global $defautIdx,$def_idx;
	$def_idx = 0;
	if (!isset($_REQUEST['m']) || empty($_REQUEST['m'])){
		$defautIdx = $GLOBALS['db']->getRowCached("SELECT rec_module,rec_action,rec_id,show_cate FROM ".DB_PREFIX."nav where (`url`='' or `url` is null) and status=1 and is_default =1");
		if($defautIdx)
		{
			if(isset($defautIdx['rec_module'])&&!empty($defautIdx['rec_module']))
			{
				$_REQUEST['m'] = $defautIdx['rec_module'];
				$def_idx = 1;
			}
			else
			{
				$_REQUEST['m'] = "Index";
			}

			if(isset($defautIdx['rec_action'])&&!empty($defautIdx['rec_action']))
			{
				$_REQUEST['a'] = $defautIdx['rec_action'];
			}
			else
			{
				$_REQUEST['a'] = "index";
			}

			if(!empty($defautIdx['rec_id']))
			{
				$_REQUEST['id'] = $defautIdx['rec_id'];
			}
			if(!empty($defautIdx['show_cate']))
			{
				$_REQUEST['sc'] = $defautIdx['show_cate'];
			}
		}
		else {
				$_REQUEST['m'] = "Index";
				$_REQUEST['a'] = "index";
		}
	}
	if (!isset($_REQUEST['m']) || empty($_REQUEST['m'])){
		$_REQUEST['m'] = "Index";
	}

	if (!isset($_REQUEST['a']) || empty($_REQUEST['a'])){
		$_REQUEST['a'] = "index";
	}

	$tpl->assign ( "module_name", $_REQUEST ['m'] );
	$tpl->assign ( "action_name", strtolower ( $_REQUEST ['a'] ) );
   	//装载菜单
   	global $user_menu;
   	$user_menu = com_userMenu();
	$tpl->assign("user_menu",$user_menu);
	//装载生成JS语言包
	com_jsLang();

	/*增加优惠券列表*/
	if (strtolower($_REQUEST['m'])=='youhui'){
		$is_youhui = $GLOBALS['db']->getOne("select youhui from ".DB_PREFIX."group_city where id=".intval($currentCity['id']));
		if(intval($is_youhui)==0)
		a_error(a_L("CITY_NOT_SUPPORT_YOUHUI"));
      	require ROOT_PATH.'app/source/func/com_youhui_func.php';
		exit;
	}



   	//readHTMLCache();
	$ma = strtolower($_REQUEST['m'].'_'.$_REQUEST['a']);
	switch($ma){
		case 'index_index':
		case 'goods_show':
		case 'goods_showcate':
		case 'goods_showbyuname':
		{
			require ROOT_PATH.'app/source/goods_list.php';
			break;
		}

		case 'goods_index':
		{
			$_REQUEST['is_advance'] = 0;
			$_REQUEST['is_other'] = 1;
			require ROOT_PATH.'app/source/goods_index.php';
			break;
		}
		case 'goods_other':
		{
			$_REQUEST['is_other'] = 0;
			require ROOT_PATH.'app/source/goods_index.php';
			break;
		}
		case 'goods_score':
		{
			$_REQUEST['is_score'] = 1;
			require ROOT_PATH.'app/source/goods_index.php';
			break;
		}
		case 'advance_index':
		{
			$_REQUEST['is_score'] = 0;
    		$_REQUEST['is_advance'] = 1;
			require ROOT_PATH.'app/source/goods_index.php';
			break;
		}
		case 'belowline_index':{
			$_REQUEST['is_score'] = 0;
    		$_REQUEST['type_id'] = 2;
			require ROOT_PATH.'app/source/goods_index.php';
			break;
		}
		case 'message_index':
		case 'message_add':
		case 'message_comment':
		case 'message_addcomment':
		case 'message_commentlist':
		case 'message_buycomment':	// lin 15:05 2011-4-28
		case 'message_groupmessage':
		case 'message_addgroupmessage':
		case 'message_insertgroupmessage':
		case 'message_followgroupmessage':
		case 'message_showgroupmessage':
		case 'message_addgroupmessagecomment':
		case 'message_feedback':
		case 'message_addfeedback':
		case 'message_add':
		case 'message_sellermsg':
		case 'message_addsellermsg':
		case 'message_addsuppliercomment':{
			require ROOT_PATH.'app/source/message.php';
			break;
		}

		case 'cart_index':{
			require ROOT_PATH.'app/source/cart_index.php';
			break;
		}
		case 'cart_cartlogin':{
			require ROOT_PATH.'app/source/cart_cartlogin.php';
			break;
		}
		case 'cart_check':{
			require ROOT_PATH.'app/source/cart_check.php';
			break;
		}
		case 'order_pay':{
			require ROOT_PATH.'app/source/func/com_send_sms_func.php';
			require ROOT_PATH."app/source/func/com_order_pay_func.php";
			require ROOT_PATH."app/source/order_pay.php";
			//require ROOT_PATH.'app/source/order_pay_success.php';
			break;
		}
		case 'order_pay_success':{
			require ROOT_PATH.'app/source/func/com_send_sms_func.php';
			//require ROOT_PATH."app/source/func/com_order_pay_func.php";
			s_autoRun();//支付成功后，运行自动发放团购券
			require ROOT_PATH.'app/source/order_pay_success.php';
			break;
		}
		case 'user_no_verify':
			require ROOT_PATH."app/source/user_no_verify.php";
			break;
		case "user_rse_success":
			require ROOT_PATH."app/source/user_rse_success.php";
			break;
		case "user_reset":
			require ROOT_PATH."app/source/user_reset.php";
			break;
		case "supplier_index":
		case "supplier_show":
		case "supplier_comment":
			require ROOT_PATH."app/source/supplier.php";
			break;
		case "suppliers_index":
		case "suppliers_login":
		case "suppliers_dologin":
		case "suppliers_groupbond":
		case "suppliers_reset":
		case "suppliers_doreset":
		case "suppliers_logout":
		case "suppliers_usetime":
		case "suppliers_dousetime":
		//增加商家优惠券的处理
		case "suppliers_coupon":
		case "suppliers_addcoupon":
		case "suppliers_editcoupon":
		case "suppliers_doaddcoupon":
		case "suppliers_doeditcoupon":
		case "suppliers_delcoupon":
		case "suppliers_load_region":
		//增加关于自助团购的处理
		case "suppliers_grouplist":
		case "suppliers_addgroup":
		case "suppliers_editgroup":
		case "suppliers_delgroup":
		case "suppliers_doaddgroup":
		case "suppliers_doeditgroup":
		case "suppliers_delgroup":
		case "suppliers_uploadimg":
		case "suppliers_uploadeditor":
		case "suppliers_orderlist":
		case "suppliers_dealorder":
		case "suppliers_dodealorder":
                 case "suppliers_balance":
		case "suppliers_goodslist":
		case "suppliers_exportcsv":
		case "suppliers_couponbus":	
			require ROOT_PATH."app/source/suppliers.php";
			break;
		case 'ajax_smssubscribe':
		case 'ajax_smssubscribecode':
		case 'ajax_unsmssubscribe':
		case 'ajax_unsmssubscribecode':
		case 'ajax_showmap':
		case 'ajax_verify':
		case 'ajax_ecvverify':
		case 'ajax_close_top_adv':
		case 'ajax_getcartinfo':
		case 'ajax_getsubcitys':
		case 'ajax_gettypeattr':
			require ROOT_PATH.'app/source/ajax.php';
			break;
		//去掉底部的团购券验证接口
		//case 'coupon_index':
		//	require ROOT_PATH.'app/source/coupon.php';
		//	break;
		case 'ucmodify_index':
		case 'ucgroupbond_index':
		case 'ucorder_index':
		case 'uclog_index':
		case 'uclog_logindex':
		case 'ucreferrals_index':
		case 'ucreferrals_payindex':
		case 'ucecv_index':
		case 'ucecv_add':
		case 'ucecv_exchange':
		case 'ucincharge_index':
		case 'ucuncharge_index':
		case 'ucmodify_consignee':
		case 'ucuncharge_modify':
		case 'ucincharge_modify':
		case 'ucorder_view':
		case 'order_check':
		case 'ucmodify_domodify':
		case 'ucmodify_doconsignee':
		case 'ucincharge_ecv':
		case 'usercenter_subscribe':
		case 'usercenter_unsubscribe':
		case 'ucuncharge_update':
		case 'ucuncharge_insert':
		case 'ucincharge_update':
		case 'ucincharge_insert':
		case 'ucincharge_ecvincharge':
		case 'ucecv_doexchange':
		case 'ucorder_del':
		case 'ucbeloworder_index':
		case 'ucbeloworder_view':
		case 'ucbeloworder_del':
		case 'ucscore_exchange':
		case 'ucscore_exchange_do':
		case 'ucmodify_avatar':
		case 'ucmodify_camera':
		case 'ucmodify_avatar_upload':
		case 'ucmodify_save_avatar':
			require ROOT_PATH.'app/source/func/com_user_center_func.php';
			require ROOT_PATH."app/source/user_center.php";
			break;
		case 'ucgroupbond_order':
		case 'ucgroupbond_sms':
		case 'ucgroupbond_printbond':
		case 'ucgroupbond_download':
			require ROOT_PATH.'app/source/func/com_user_center_func.php';
			require ROOT_PATH."app/source/user_center_groupbond.php";
			break;
		case 'rss_index':
			require ROOT_PATH."app/source/rss.php";
			break;
		case 'adv_show':
			require ROOT_PATH."app/source/adv.php";
			break;
		case 'vote_index':
		case 'vote_add':
			require ROOT_PATH."app/source/vote.php";
			break;
		case 'lottery_step1':
		case 'lottery_step2':
		case 'lottery_step3':
		case 'lottery_view':
			require ROOT_PATH."app/source/lottery.php";
			break;
		case "referrals_index":
		case "referrals_money":
		case "referrals_score":
			require ROOT_PATH."app/source/referrals_index.php";
			break;
		case 'article_show':
		case 'article_showbyuname':
		{
			require ROOT_PATH.'app/source/article_show.php';
			break;
		}
		case 'index_unsubscribe':{
			require ROOT_PATH.'app/source/index_malllist.php';
			break;
		}
		case 'forum_index':
		case 'forum_view':
		case 'forum_add':
		case 'forum_insert':
		{
			require ROOT_PATH.'app/source/forum.php';
			break;
		}
		default:{
			if (is_file(ROOT_PATH.'app/source/'.$ma.'.php')){
				require ROOT_PATH.'app/source/'.$ma.'.php';
			}else{
				echo 'App "'.$ma.'" not exists!';
				//a_error(a_L("_OPERATION_FAIL_"));
			}
			break;
		}
	}
	unset($GLOBALS);
    exit;

?>