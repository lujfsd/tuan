<?php

user_enter_init (); //会员菜单初始化
$userid = intval ( $_SESSION ['user_id'] );

$ma = $_REQUEST ['m'] . "_" . strtolower ( $_REQUEST ['a'] );
$ma ( $userid );
exit ();

function UcModify_index($userid) {
	
	$code = a_fanweC ( "INTEGRATE_CODE" );
	if (empty ( $code ))
		$code = 'fanwe';
	
	$extend_value = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "user_extend where user_id=" . $userid );
	$extend_fields = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "user_field where is_show=1 order by sort desc" );
	
	
	foreach ( $extend_fields as $k => $v ) {
		$extend_fields [$k] ['val_scope'] = explode ( ",", $v ['val_scope'] );
		foreach ( $extend_value as $kk => $vv ) {
			if ($vv ['field_id'] == $v ['id']) {
				$extend_fields [$k] ['value'] = $vv ['field_value'];
				break;
			}
		}
	}
	
	//qq登陆的用户密码没改的时候提示修改
	$flag=0;
	$user=$GLOBALS ['db']->getRow ( "select txqq_id,user_pwd from " . DB_PREFIX . "user where id=" . $userid );
	if(!empty($user['txqq_id'])&&($user['user_pwd'] == md5('123456'))){
		$flag=1;
	}
	$GLOBALS ['tpl']->assign ( "FLAG", $flag );
	$GLOBALS ['tpl']->assign ( "page_title", a_L ( "UCMODIFY_INDEX" ) );
	$GLOBALS ['tpl']->assign ( "extend_fields", $extend_fields );
	$GLOBALS ['tpl']->assign ( "user_code", $code );
	$GLOBALS ['tpl']->assign ( "current_page", "UCMODIFY_INDEX" );
	$GLOBALS ['tpl']->assign ( "MOBILE_HITS", a_fanweC ( "GROUPBOTH" ) . $GLOBALS ['Ln'] ['XY_MOBILE_NOTICE'] );
	$GLOBALS ['tpl']->assign ( "MOBILE_PHONE_MUST", a_fanweC ( "MOBILE_PHONE_MUST" ) );
	$data = array ('navs' => array (array ('name' => a_l ( "UCMODIFY_INDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucmodify_index.moban' );

}

function UcModify_consignee($userid) {
	
	$consignee_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user_consignee where user_id=" . $userid . " order by id desc" );
	if ($consignee_info) {
		//输出一级地区
		$region_lv1_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=0 order by name asc" );
		$GLOBALS ['tpl']->assign ( "region_lv1_list", $region_lv1_list );
		
		//输出二级地区				
		$region_lv2_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=" . intval ( $consignee_info ['region_lv1'] ) . " order by name asc" );
		$GLOBALS ['tpl']->assign ( "region_lv2_list", $region_lv2_list );
		
		//输出三级地区 
		$region_lv3_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=" . intval ( $consignee_info ['region_lv2'] ) . " order by name asc" );
		$GLOBALS ['tpl']->assign ( "region_lv3_list", $region_lv3_list );
		
		//输出四级地区
		$region_lv4_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=" . intval ( $consignee_info ['region_lv3'] ) . " order by name asc" );
		$GLOBALS ['tpl']->assign ( "region_lv4_list", $region_lv4_list );
	} else {
		//输出一级地区
		$region_lv1_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=0 order by name asc" );
		$GLOBALS ['tpl']->assign ( "region_lv1_list", $region_lv1_list );
	}
	$GLOBALS ['tpl']->assign ( "current_page", "UCMODIFY_CONSIGNEE" );
	$GLOBALS ['tpl']->assign ( "consignee_info", $consignee_info );
	$GLOBALS ['tpl']->assign ( "page_title", a_L ( "UCMODIFY_CONSIGNEE" ) );
	$data = array ('navs' => array (array ('name' => a_l ( "UCMODIFY_CONSIGNEE" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucmodify_consignee.moban' );
}

function UcModify_doconsignee($userid) {
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	$data ['id'] = intval ( $_POST ['id'] );
	$data ['consignee'] = trim ( $_POST ['consignee'] );
	$data ['region_lv1'] = intval ( $_POST ['region_lv1'] );
	$data ['region_lv2'] = intval ( $_POST ['region_lv2'] );
	$data ['region_lv3'] = intval ( $_POST ['region_lv3'] );
	$data ['region_lv4'] = intval ( $_POST ['region_lv4'] );
	$data ['address'] = trim ( $_POST ['address'] );
	$data ['zip'] = trim ( $_POST ['zip'] );
	$data ['fix_phone'] = trim ( $_POST ['fix_phone'] );
	$data ['mobile_phone'] = trim ( $_POST ['mobile_phone'] );
	$data ['user_id'] = $userid;
	
	if (intval ( $data ['id'] ) == 0) {
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_consignee", addslashes_deep ( $data ) );
	} else {
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_consignee", addslashes_deep ( $data ), 'UPDATE', "id = " . $data ['id'] );
	}
	
	success ( a_L ( "MODIFY_SUCCESS" ));
}
function UcModify_domodify($userid) {
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	$data ['id'] = $userid;
	$data ['email'] = trim ( $_POST ['email'] );
	$user_pwd = trim ( $_POST ['user_pwd'] );
	$cfm_password = trim ( $_POST ['user_pwd_confirm'] );
	$data ['user_name'] = trim ( $_POST ['user_name'] );
	$data ['mobile_phone'] = trim ( $_POST ['mobile_phone'] );
	$data ['city_id'] = intval ( $_POST ['city_id'] );
	$data ['is_receive_sms'] = intval ( $_POST ['is_receive_sms'] );
	$userNameLength = (strlen ( $data ['user_name'] ) + mb_strlen ( $data ['user_name'], 'UTF8' )) / 2;
	$err = "";
	
	if ($data ['email'] == '') {
		$err = a_L ( "HC_PLEASE_ENTER_EMAIL" );

	} elseif (!a_checkEmail( $data ['email'] )) {
		$err = a_L ( "HC_EMAIL_ERROR" );

	} elseif ($GLOBALS ['db']->getOne ( "select count(*) as num from " . DB_PREFIX . "user where email='" . $data ['email'] . "' and user_name<>'" . $data ['user_name'] . "'" ) > 0) {
		$err = a_L ( "THIS_EMAIL_HAD_USE" );
	}elseif ($userNameLength < 4) {
		$err = a_l ( "HC_USER_NAME_TOO_SHORT" );
	} elseif ($userNameLength > 16) {
		$err = a_l ( "HC_USER_NAME_TOO_LONG" );
	} elseif ($data ['user_name'] != $_SESSION ['user_name']) {
		$count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "user where user_name='" . $data ['user_name'] . "'" );
		if ($count > 0)
			$err = a_l ( "HC_USER_REGISTERED" );
	} elseif (! empty ( $user_pwd )) {
		if (strlen ( $user_pwd ) < 4) {
			$err = a_l ( "HC_USER_PASSWORD_TOO_SHORT" );
		} elseif ($user_pwd != $cfm_password) {
			$err = a_l ( "HC_PASSWORD_CONFIRM_FAILED" );
		}
	} elseif (! empty ( $data ['mobile_phone'] ) && ! preg_match ( "/^(\d+)$/", $data ['mobile_phone'] )) {
		$err = a_l ( "HC_MOBILE_NUMBER_ERROR" );
		//preg_match ( "/^(13\d{9}|14\d{9}|15\d{9}|18\d{9})|(0\d{9}|9\d{8})$/", $data ['mobile_phone'] )
	} elseif ($GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "user where mobile_phone='" . $data ['mobile_phone'] . "' and id<>" . $data ['id'] . " and mobile_phone<>'' and status = 1" ) > 0) {
		$err = a_l ( "HC_MOBILE_NUMBER_EXISTS" );
	}
	
	//开始验证扩展字段的数据
	$extend_fields = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "user_field where is_show=1 order by sort desc" );
	
	foreach ( $extend_fields as $kk => $vv ) {
		if ($vv ['is_must'] == 1) {
			if ($_REQUEST [$vv ['field_name']] == '') {
				$err = a_l ( "XY_PLEASE_ENTER" ) . $vv ['field_show_name'];
			}
		}
	}
	
	if ($err != '') {
		a_error ( $err, '', 'back' );
	} else {
		$origin_pwd = $GLOBALS ['db']->getRow ( "select user_pwd from " . DB_PREFIX . "user where id =" . $data ['id'] );
		//$origin_pwd = D("User")->where("id=".$data['id']." and status=1")->getField("user_pwd");
		if (! empty ( $user_pwd )) {
			require ROOT_PATH . 'app/source/func/com_user_func.php';
			if (md5 ( $user_pwd ) != $origin_pwd) {
				$data ['user_pwd'] = md5 ( $user_pwd );
				
				$cfg = array ('username' => $data ['user_name'], 'password' => $user_pwd );
				$users = &init_users3 ();
				$users->need_sync = false;
				$users->edit_user ( $cfg );
			}
		}
		
		$data ['update_time'] = a_gmtTime ();
		
		if ($GLOBALS ['db']->autoExecute ( DB_PREFIX . "user", addslashes_deep ( $data ), 'UPDATE', "id = " . $data ['id'] )) {
			//开始处理扩展字段的数据
			$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "user_extend where user_id =" . $data ['id'] );
			
			foreach ( $extend_fields as $kk => $vv ) {
				$ext_data ['field_value'] = $_REQUEST [$vv ['field_name']];
				$ext_data ['field_id'] = $vv ['id'];
				$ext_data ['user_id'] = $data ['id'];
				$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_extend", addslashes_deep ( $ext_data ) );
			}
			
			$_SESSION ['user_name'] = $data ['user_name'];
			//Cookie::set('cityID',$data['city_id'],60*60*24);
			//$this->getCurrentGroupCity();
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "mail_address_list set city_id =" . $data ['city_id'] . " where user_id =" . $data ['id'] );
			//M("MailAddressList")->where("user_id=".$data['id'])->setField("city_id",$data['city_id']);
			//更新用户数据
			//S("CACHE_USER_INFO_".$data['id'],NULL);
			success ( a_L ( "MODIFY_SUCCESS" ));
		} else {
			a_error ( a_L ( "MODIFY_FAILED" ), '', 'back' );
		}
	}
}

function UcGroupBond_index($userid) {
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	$status = intval ( $_REQUEST ['status'] );
	$GLOBALS ['tpl']->assign ( "status", $status );
	
	$result = getGroupBondList ( $status, $page );
	
	$GLOBALS ['tpl']->assign ( "groupbond_list", $result ['list'] );
	
	//分页
	$page = new Pager ( $result ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	//end 分页  ;
	

	$GLOBALS ['tpl']->assign ( 'GROUPBOND_PRINTTYPE', a_fanweC ( "GROUPBOND_PRINTTYPE" ) );
	$GLOBALS ['tpl']->assign ( 'IS_SMS', a_fanweC ( "IS_SMS" ) );
	$GLOBALS ['tpl']->assign ( 'GROUPBOTH', a_fanweC ( "GROUPBOTH" ) );
	$data = array ('navs' => array (array ('name' => sprintf ( a_L ( "MY_GROUPBOND" ), a_fanweC ( "GROUPBOTH" ) ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucgroupbond_index.moban' );
}

function UcOrder_index($userid) {
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	$res = getOrderList ( $userid, $page );
	/*
	foreach ( $res ['list'] as $k => $v ) {
		$goods_item = $GLOBALS ['db']->getRow ( "select g.* from " . DB_PREFIX . "order_goods as og left join " . DB_PREFIX . "goods as g on og.rec_id = g.id where og.order_id = " . $v ['id'] );
		if ($goods_item ['promote_end_time'] < a_gmtTime ()) {
			$res ['list'] [$k] ['stock_is_over'] = 1;
		}
	}
	*/
	
	$GLOBALS ['tpl']->assign ( 'order_list', $res ['list'] );
	
	//分页
	$page = new Pager ( $res ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$GLOBALS ['tpl']->assign ( 'page_title', a_L ( "UCORDER_INDEX" ) );
	$GLOBALS ['tpl']->assign ( 'ALLOW_TK', a_fanweC ( "ALLOW_TK" ) );
	$data = array ('navs' => array (array ('name' => a_L ( "UCORDER_INDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucorder_index.moban' );
}

function UcLog_index($userid) {
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	$res = getMoneyLogList ( $userid, $page );
	
	$GLOBALS ['tpl']->assign ( "current_page", "UCLOG_INDEX" );
	
	//var_dump($res);
	$GLOBALS ['tpl']->assign ( 'log_list', $res ['list'] );
	$GLOBALS ['tpl']->assign ( 'page_title', a_L ( "UCLOG_INDEX" ) );
	//分页
	$page = new Pager ( $res ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$data = array ('navs' => array (array ('name' => a_L ( "UCLOG_INDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/uclog_index.moban' );
}

function UcLog_logindex($userid) {
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	$GLOBALS ['tpl']->assign ( "current_page", "UCLOG_LOGINDEX" );
	$res = getScoreLogList ( $userid, $page );
	
	$GLOBALS ['tpl']->assign ( 'log_list', $res ['list'] );
	
	//分页
	$page = new Pager ( $res ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$data = array ('navs' => array (array ('name' => a_L ( "UCLOG_LOGINDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/uclog_logindex.moban' );
}

function UcReferrals_index($userid) {
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	$limit = (($page - 1) * a_fanweC ( "PAGE_LISTROWS" )) . "," . (a_fanweC ( "PAGE_LISTROWS" ));
	
	$invite_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "user where status = 1 and parent_id =" . $userid . " limit " . $limit );
	
	$invite_total = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "user where status = 1 and parent_id =" . $userid );
	
	foreach ( $invite_list as $k => $v ) {
		$invite_item = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "referrals where user_id=" . $v ['id'] . " and parent_id=" . $userid );
		if (! $invite_item) {
			if (a_gmtTime () - $v ['create_time'] > a_fanweC ( 'REFERRAL_TIME' ) * 3600) {
				$invite_list [$k] ['referrals_status'] = $GLOBALS ['Ln'] ["REFERRALS_STATUS_EXPIRED"];
			} else {
				$invite_list [$k] ['referrals_status'] = $GLOBALS ['Ln'] ["REFERRALS_STATUS_NO"];
			}
		} elseif ($invite_item ['is_pay'] == 0) {
			$invite_list [$k] ['referrals_status'] = $GLOBALS ['Ln'] ["REFERRALS_STATUS_NOPAY"];
		} else {
			$invite_list [$k] ['referrals_status'] = $GLOBALS ['Ln'] ["REFERRALS_STATUS_PAY"];
		}
		
		$invite_list [$k] ['create_time_format'] = a_toDate ( $v ['create_time'] );
	}
	
	$GLOBALS ['tpl']->assign ( 'invite_list', $invite_list );
	
	$user_group ['discount'] = floatval ( $user_group ['discount'] );
	
	$GLOBALS ['tpl']->assign ( "current_page", "UCREFERRALS_INDEX" );
	
	//分页
	$page = new Pager ( $invite_total, a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$data = array ('navs' => array (array ('name' => a_L ( "UCREFERRALS_INDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucreferrals_index.moban' );
}

function UcReferrals_payindex($userid) {
	$page = intval ( $_REQUEST ["p"] );
	if ($page == 0)
		$page = 1;
	
	$limit = (($page - 1) * a_fanweC ( "PAGE_LISTROWS" )) . "," . (a_fanweC ( "PAGE_LISTROWS" ));
	
	$referral_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "referrals where parent_id =" . $userid . " limit " . $limit );
	$referral_count = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "referrals where parent_id =" . $userid );
	
	foreach ( $referral_list as $k => $v ) {
		$referral_list [$k] ['user_name'] = $GLOBALS ['db']->getOne ( "select user_name from " . DB_PREFIX . "user where id =" . $v ['user_id'] );
		$referral_list [$k] ['order_sn'] = $GLOBALS ['db']->getOne ( "select sn from " . DB_PREFIX . "order where id =" . $v ['order_id'] );
		$referral_list [$k] ['goods_name'] = $GLOBALS ['db']->getOne ( "select name_1 from " . DB_PREFIX . "goods where id =" . $v ['goods_id'] );
		if ($v ['money'] > 0) {
			$referral_list [$k] ['pay_amount'] = a_formatPrice ( $v ['money'] );
		} else {
			$referral_list [$k] ['pay_amount'] = $v ['score'] . " " . $GLOBALS ['Ln'] ['SCORE_UNIT'];
		}
		
		if ($v ['is_pay'] == 0) {
			$referral_list [$k] ['pay_status'] = $GLOBALS ['Ln'] ["REFERRALS_STATUS_NOPAY"];
		} else {
			$referral_list [$k] ['pay_status'] = $GLOBALS ['Ln'] ["REFERRALS_STATUS_PAY"];
		}
		
		$referral_list [$k] ['create_time_format'] = a_toDate ( $v ['create_time'] );
		$referral_list [$k] ['pay_time_format'] = a_toDate ( $v ['pay_time'] );
	}
	
	//当前余额
	

	$GLOBALS ['tpl']->assign ( 'referral_list', $referral_list );
	
	$GLOBALS ['tpl']->assign ( "current_page", "UCREFERRALS_PAYINDEX" );
	//分页
	$page = new Pager ( $referral_count, a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$GLOBALS ['tpl']->assign ( 'page_title', a_L ( "UCREFERRALS_PAYINDEX" ) );
	$data = array ('navs' => array (array ('name' => a_L ( "UCREFERRALS_PAYINDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucreferrals_payindex.moban' );
}

function UcEcv_add($userid) {
	$sn = trim($_REQUEST['ecvSn']);
	$password = trim($_REQUEST['ecvPassword']);
	
	$ecv = $GLOBALS ['db']->getRow( "select * from " . DB_PREFIX . "ecv where use_count = 1 and sn='" . $sn . "' and password='" . $password."'" );
	$result = array("type"=>0,"msg"=>"");
		
		
	if($ecv)
	{
		$sql = "select * from " . DB_PREFIX . "ecv_type where id = " . intval ( $ecv ['ecv_type'] );
		$ecv['ecvType'] = $GLOBALS ['db']->getRow ( $sql );	

		$exchanged = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "ecv where ecv_type=" . intval ( $ecv ['ecv_type'] ) . " and user_id=" . $userid );
		if ($exchanged >= $ecv['ecvType']['use_count']) {
			$result['msg'] = sprintf ( a_L ( "EVC_EXCHANGE_LIMIT" ), $ecv['ecvType']['name'], a_l ( "ECV_TYPE_" . $ecv['ecvType']['type'] ), $ecv['ecvType']['use_count'] );
		}else{		
			$time = a_gmtTime();
			if(intval($ecv['user_id']) > 0 and intval($ecv['user_id']) <> intval($_SESSION['user_id']))
				$result['msg'] = a_L("HC_ECV_HAS_DELIVERY_TO_OTHER_USER");		
			elseif(intval($ecv['user_id']) == intval($userid))
				$result['msg'] = a_L("HC_ECV_HAS_IN_YOUR_ACCOUNT");
			elseif(intval($ecv['use_date_time']) > 0)
				$result['msg'] = sprintf(a_L("HC_ECV_HAS_USE_STR"),$ecv['useUser']['user_name'],a_toDate($ecv['use_date_time'],a_L("HC_DATETIME_FORMAT")));
			elseif(intval($ecv['ecvType']['status']) == 0)
				$result['msg'] = a_L("HC_ECV_HAS_FORBID");
			elseif($time < intval($ecv['ecvType']['use_start_date']))
				$result['msg'] = sprintf(a_L("HC_ECV_NOT_BEGIN_STR"),a_toDate($ecv['ecvType']['use_start_date'],a_L("HC_DATETIME_SHORT_FORMAT")));
			elseif($time > intval($ecv['ecvType']['use_end_date']) &&  intval($ecv['ecvType']['use_end_date']) > 0)				
				$result['msg'] = sprintf(a_L("HC_ECV_EXPIRED_STR"),a_toDate($ecv['ecvType']['use_end_date'],a_L("HC_DATETIME_SHORT_FORMAT")));
			else
			{
				$result['type'] = 1;
				$GLOBALS ['db']->query( "update " . DB_PREFIX . "ecv set user_id='" . intval($userid)."' where id = $ecv[id] limit 1");
			}
		}
	}
	else
	{
		$result['msg'] = a_L("HC_ECV_NOT_EXIST");
	}
	
	if($result['type'] == 0)
	{
		$GLOBALS ['tpl']->assign("error",$result['msg']); 
	}
	else
	{
		$GLOBALS ['tpl']->assign("error",'');
		$GLOBALS ['tpl']->assign("success",a_l("HC_ECV_ADD_SUCCESS"));	
	}	
			
	UcEcv_index($userid);
}

function UcEcv_index($userid) {
	
	if (a_fanweC ( "OPEN_ECV" ) == 0) {
		a_error ( $GLOBALS ['Ln'] ['HC_ECV_CLOSED'], '', 'back' );
		exit ();
	}
	
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	$status = intval ( $_REQUEST ['status'] );
	
	//$sql = "select count(*) from ".DB_PREFIX."ecv_type where exchange= 1 and status = 1";
	//$is_exchange = $count = $GLOBALS['db']->getOneCached($sql);
	

	//$GLOBALS['tpl']->assign("is_exchange",$is_exchange);
	

	$GLOBALS ['tpl']->assign ( 'status', $status );
	
	$result = getUserEcvList ( $status, $page );
	
	$GLOBALS ['tpl']->assign ( "evc_list", $result ['list'] );
	
	//分页
	$page = new Pager ( $result ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$GLOBALS ['tpl']->assign ( 'page_title', a_L ( "UCECV_INDEX" ) );
	$data = array ('navs' => array (array ('name' => a_L ( "UCECV_INDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	//end 分页  ;
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucecv_index.moban' );
}

function UcEcv_doexchange($userid) {
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	$result = array ();
	$result ['status'] = 0;
	$result ['info'] = '';
	$id = intval ( $_REQUEST ['id'] );
	$ecv = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "ecv_type where id=" . $id . " and status=1" );
	if ($ecv) {
		if ($ecv ['exchange_limit'] != 0) //有兑换限掉
{
			//$exchanged = M("Ecv")->where("ecv_type=".$id." and user_id=".intval($_SESSION['user_id']))->count();
			$exchanged = $GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "ecv where ecv_type=" . $id . " and user_id=" . $userid );
			if ($exchanged >= $ecv ['exchange_limit']) {
				$result ['info'] = sprintf ( a_L ( "EVC_EXCHANGE_LIMIT" ), $ecv ['name'], a_l ( "ECV_TYPE_" . $ecv ['type'] ), $ecv ['exchange_limit'] );
				echo json_encode ( $result );
				exit ();
			}
		}
		//开始验证会员积分
		$score = $GLOBALS ['db']->getOne ( "select score from " . DB_PREFIX . "user where id=" . $userid );
		if ($ecv ['exchange_score'] > $score) {
			$result ['info'] = a_L ( "SCORE_NOT_ENOUGHT" );
			echo json_encode ( $result );
			exit ();
		}
		//开发兑换流程
		$tempsn = unpack ( 'H8', str_shuffle ( sha1 ( uniqid () ) ) );
		$temppwd = unpack ( 'H8', str_shuffle ( md5 ( uniqid () ) ) );
		$ecv_data ['ecv_type'] = $ecv ['id'];
		$ecv_data ['sn'] = $tempsn [1];
		$ecv_data ['password'] = $temppwd [1];
		$ecv_data ['user_id'] = $userid;
		$ecv_data ['type'] = $ecv ['type'];
		$ecv_data ['use_count'] = $ecv ['use_count'];
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "ecv", addslashes_deep ( $ecv_data ) );
		//$ecv_id = M("Ecv")->add($ecv_data);
		$ecv_id = intval ( $GLOBALS ['db']->insert_id () );
		if ($ecv_id > 0) {
			//M("EcvType")->setInc('gen_count',"id=".$ecv['id']);
			$sql = "update " . DB_PREFIX . "ecv_type set gen_count = gen_count + 1 where id = " . $ecv ['id'];
			$GLOBALS ['db']->query ( $sql );
			require ROOT_PATH . "app/source/func/com_order_pay_func.php";
			s_user_score_log ( $userid, $ecv_id, "Ecv", "-" . $ecv ['exchange_score'], sprintf ( a_L ( "EXCHANGE_USER_SCORE_LOG" ), $ecv ['name'], a_l ( "ECV_TYPE_" . $ecv ['type'] ), $ecv ['exchange_score'] ) );
			$result ['status'] = 1;
			$result ['info'] = a_L ( "EXCHANGE_SUCCESS" );
			echo json_encode ( $result );
			exit ();
		} else {
			$result ['info'] = a_l ( "EXCHANGE_FAILED" );
			echo json_encode ( $result );
			exit ();
		}
	
	} else {
		$result ['info'] = a_L ( "EXCHANGE_FAILED" );
		echo json_encode ( $result );
		exit ();
	}
}

function UcEcv_exchange($userid) {
	if (a_fanweC ( "OPEN_ECV" ) == 0) {
		a_error ( $GLOBALS ['Ln'] ['HC_ECV_CLOSED'], '', 'back' );
		exit ();
	}
	
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	$limit = (($page - 1) * a_fanweC ( "PAGE_LISTROWS" )) . "," . a_fanweC ( "PAGE_LISTROWS" );
	
	$ecv_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "ecv_type where exchange=1 and status = 1 limit " . $limit );
	$ecv_total = $GLOBALS ['db']->getOneCached ( "select count(*) from " . DB_PREFIX . "ecv_type where exchange=1 and status = 1" );
	
	$GLOBALS ['tpl']->assign ( "is_exchange", 1 );
	$GLOBALS ['tpl']->assign ( "ecv_list", $ecv_list );
	//分页
	$page = new Pager ( $ecv_total, a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	//end 分页  ;
	$GLOBALS ['tpl']->assign ( 'page_title', a_L ( "UCECV_EXCHANGE" ) );
	$data = array ('navs' => array (array ('name' => a_L ( "UCECV_EXCHANGE" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucecv_exchange.moban' );
}

function UcIncharge_index($userid) {
	if (a_fanweC ( "CLOSE_USERMONEY" ) == 1) {
		a_error ( $GLOBALS ['Ln'] ['HC_USER_INCHARGE_CLOSE'], '', 'back' );
		exit ();
	}
	
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
		
	//输出支付方式
	$payment_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "payment where status=1 and class_name<>'Accountpay' and class_name<>'Cod'" );
	$Bank_list = '';
	foreach($payment_list as $kk=>$vv)
	{
		$currency_item = array('id'=>1,'unit'=>a_fanweC("BASE_CURRENCY_UNIT"),'radio'=>1);
		if($vv['fee_type']==0)
			$payment_list[$kk]['fee_format'] = a_formatPrice($vv['fee']);
		else 
			$payment_list[$kk]['fee_format'] = floatval($vv['fee'])."%";
			/*    			
		if($vv['class_name'] == "TenpayBank"||$vv['class_name'] == "Sdo"){//财付通银行直接接口 add by chenfq 2010-12-30
			$payment_name = $vv['class_name']."Payment";
			require_once (VENDOR_PATH.'payment3/'.$payment_name.'.class.php');
			$payment_model = new $payment_name;
			$res = $payment_model->getBackList($vv['id']);
			$Bank_list = $Bank_list.$res;
			unset($payment_list[$kk]);	//不在前台显示,财付通,只显示各银行 add by chenfq 2011-02-22		    			
		}
		*/
			$payment_name = $vv['class_name']."Payment";
			$pay_file = VENDOR_PATH.'payment3/'.$payment_name.'.class.php';
			if(file_exists($pay_file)){
				require_once($pay_file);
				$payment_model = new $payment_name;
				if (method_exists($payment_model,'getBackList')){
	    			$res = $payment_model->getBackList($vv['id']);
	    			$Bank_list = $Bank_list.$res;
			    	unset($payment_list[$kk]);
				}
				
				if (method_exists($payment_model,'selection')){
					$payment_list[$kk]['selection'] = $payment_model->selection($vv['id']);
				};			    	    			
			}		
	}	
	$GLOBALS['tpl']->assign("Bank_list",$Bank_list);
	$GLOBALS ['tpl']->assign ( "payment_list", $payment_list );
	
	$result = getInchargeList ( $userid, $page );
	$GLOBALS ['tpl']->assign ( "incharge_list", $result ['list'] );
	//分页
	$page = new Pager ( $result ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	//end 分页  
	

	$GLOBALS ['tpl']->assign ( "page_title", a_L ( "UCINCHARGE_INDEX" ) );
	$data = array ('navs' => array (array ('name' => a_L ( "UCINCHARGE_INDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucincharge_index.moban' );
}

function UcIncharge_update($userid) {
	
	if (! $_REQUEST ['payment']) {
		a_error ( a_L ( "SELECT_PAYMENT" ), a_L ( "SELECT_PAYMENT" ), 'back' );
	}
	if (floatval ( $_REQUEST ['money'] ) <= 0) {
		a_error ( a_L ( "MONEY_ERROR" ), a_L ( "MONEY_ERROR" ), 'back' );
	}
	
	$id = intval ( $_REQUEST ['id'] );
	$data = $GLOBALS ['db']->getRow ( "select id,sn,money,user_id,payment,payment_fee,payment_money,status from " . DB_PREFIX . "user_incharge where id = " . $id );
	if (! $data || $userid != $data ['user_id'] || $data ['status'] == 1) {
		a_error ( a_L ( "VITIATION_DATA" ), a_L ( "VITIATION_DATA" ), 'back' );
	}
	
	$_REQUEST['payment'] = trim($_REQUEST['payment']);		
	$ilen = strpos($_REQUEST['payment'],'-');
	$bank_id = '';
	if ($ilen > 0){
		$bank_id = substr($_REQUEST['payment'],0,$ilen);
		$payment_id = substr($_REQUEST['payment'],$ilen + 1, strlen($_REQUEST['payment']) - $ilen);
	}else{
		$payment_id = intval($_REQUEST['payment']);
	}	
	
		//=======================add by chenfq 2011-06-29 begin========================
		$payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id=".$payment_id);
		require_once(VENDOR_PATH.'payment3/'.$payment_info['class_name'].'Payment.class.php');
		$payment_class = $payment_info['class_name']."Payment";
		$payment_model = new $payment_class;
		if (method_exists($payment_model,'pre_confirmation_check')){
			$card_info = $payment_model->pre_confirmation_check();
			if ($card_info['result'] == false){
				a_error ($card_info['error'], '', 'back' );				
			}else{
				$data['card_info'] = serialize($card_info['card_info']);			
			}
		}	
		//=======================add by chenfq 2011-06-29 end========================
			
	$data ['payment'] = $payment_id;
	$data ['bank_id'] = $bank_id;	
	$data ['money'] = floatval ( $_REQUEST ['money'] );
	$data ['update_time'] = a_gmtTime ();
	
	$payment_info = $GLOBALS ['db']->getRow ( "select fee_type,fee from " . DB_PREFIX . "payment where id = " . $data ['payment'] );
	
	if ($payment_info ['fee_type'] == 0) {
		//定额
		$data ['payment_fee'] = $payment_info ['fee'];
	} else {
		$data ['payment_fee'] = $data ['money'] * $payment_info ['fee'] / 100;
	}
	
	$data ['payment_money'] = $data ['money'] + $data ['payment_fee'];
	
	if ($GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_incharge", addslashes_deep ( $data ), 'UPDATE', "id = " . $id )) {
		//生成支付按钮
		require ROOT_PATH . "app/source/func/com_send_sms_func.php";
		require ROOT_PATH . "app/source/func/com_order_pay_func.php";
		$payment_str = getPayment ( $id, 1 );
		$order_info = a_L ( "MONEY_SN" ) . " [&nbsp;&nbsp;<span style='color:#f30; font-weight:bold;'>" . $data ['sn'] . "</span>&nbsp;&nbsp;]";
		$GLOBALS ['tpl']->assign ( "payment_str", $payment_str );
		$GLOBALS ['tpl']->assign ( "order_info", $order_info );
		$GLOBALS ['tpl']->display ( 'Inc/user_center/ucincharge_done.moban' );
	} else {
		a_error ( aL ( "SUBMIT_ERROR", '', 'back' ) );
	}
}

function UcIncharge_insert($userid) {
	
	if (! $_REQUEST ['payment']) {
		a_error ( a_L ( "SELECT_PAYMENT" ), a_L ( "SELECT_PAYMENT" ), 'back' );
	}
	if (floatval ( $_REQUEST ['money'] ) <= 0) {
		a_error ( a_L ( "MONEY_ERROR" ), a_L ( "MONEY_ERROR" ), 'back' );
	}
	   	
	$_REQUEST['payment'] = trim($_REQUEST['payment']);		
	$ilen = strpos($_REQUEST['payment'],'-');
	$bank_id = '';
	if ($ilen > 0){
		$bank_id = substr($_REQUEST['payment'],0,$ilen);
		$payment_id = substr($_REQUEST['payment'],$ilen + 1, strlen($_REQUEST['payment']) - $ilen);
	}else{
		$payment_id = intval($_REQUEST['payment']);
	}
		
	$data = array ();		
		//=======================add by chenfq 2011-06-29 begin========================
		$payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id=".$payment_id);
		require_once(VENDOR_PATH.'payment3/'.$payment_info['class_name'].'Payment.class.php');
		$payment_class = $payment_info['class_name']."Payment";
		$payment_model = new $payment_class;
		if (method_exists($payment_model,'pre_confirmation_check')){
			$card_info = $payment_model->pre_confirmation_check();
			if ($card_info['result'] == false){
				a_error ($card_info['error'], '', 'back' );				
			}else{
				$data['card_info'] = serialize($card_info['card_info']);			
			}
		}	
		//=======================add by chenfq 2011-06-29 end========================			
	
	$data ['user_id'] = $userid;
	$data ['money'] = floatval ( $_REQUEST ['money'] );
	$data ['payment'] = $payment_id;
	$data ['bank_id'] = $bank_id;
	$data ['create_time'] = a_gmtTime ();
	$data ['update_time'] = a_gmtTime ();
	
	$payment_info = $GLOBALS ['db']->getRow ( "select fee_type,fee from " . DB_PREFIX . "payment where id = " . $data ['payment'] );
	
	if ($payment_info ['fee_type'] == 0) {
		//定额
		$data ['payment_fee'] = $payment_info ['fee'];
	} else {
		$data ['payment_fee'] = $data ['money'] * $payment_info ['fee'] / 100;
	}
	
	$data ['payment_money'] = $data ['money'] + $data ['payment_fee'];
	
	/* 插入订单表 */
	$do_count = 0;
	do {
		$data ['sn'] = a_toDate ( a_gmtTime (), 'ymdHis' ) . rand ( 0, 9 );
		if ($GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_incharge", addslashes_deep ( $data ), 'INSERT' )) {
			break;
		} else {
			if ($GLOBALS ['db']->errno () != 1062) {
				$result ['error'] = $GLOBALS ['db']->errorMsg ();
				a_error ( $result, '', 'back' );
			}
		}
		$do_count = $do_count + 1;
	} while ( $do_count < 10 ); // 防止订单号重复
	

	if ($do_count >= 10) {
		a_error ( a_L ( 'DATABASE_ERR_1' ), '', 'back' );
	}
	$id = intval ( $GLOBALS ['db']->insert_id () );
	if ($id > 0) {
		//生成支付按钮
		require ROOT_PATH . "app/source/func/com_send_sms_func.php";
		require ROOT_PATH . "app/source/func/com_order_pay_func.php";
		$payment_str = getPayment ( $id, 1 );
		$order_info = a_L ( "MONEY_SN" ) . " [&nbsp;&nbsp;<span style='color:#f30; font-weight:bold;'>" . $data ['sn'] . "</span>&nbsp;&nbsp;]";
		$GLOBALS ['tpl']->assign ( "payment_str", $payment_str );
		$GLOBALS ['tpl']->assign ( "order_info", $order_info );
		$result = $GLOBALS ['tpl']->display ( 'Inc/user_center/ucincharge_done.moban' );
	} else {
		a_error ( aL ( "SUBMIT_ERROR", '', 'back' ) );
	}
}

function UcIncharge_ecv($userid) {
	if (a_fanweC ( "OPEN_ECV" ) == 0) {
		a_error ( a_l ( "ECV_CLOSE" ), '', 'back' );
	}
	$id = intval ( $_REQUEST ['id'] );
	$sql = "select * from " . DB_PREFIX . "ecv where id =" . $id;
	$ecvdata = $GLOBALS ['db']->getRow ( $sql );
	$GLOBALS ['tpl']->assign ( "ecvdata", $ecvdata );
	$GLOBALS ['tpl']->assign ( "page_title", a_L ( "UCINCHARGE_ECV" ) );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucincharge_ecv.moban' );
}

function UcIncharge_ecvincharge($userid) {
	
	$sn = $_POST ['sn'];
	$password = trim ( $_POST ['password'] );
	if (empty ( $password )) {
		$password = '';
	}
	/*
		$ecvdata['user_id']	= array("in",array(intval($_SESSION['user_id']),0));
		$ecvdata['sn'] = $sn;
		$ecvdata['password'] = $password;
		$ecvdata['status'] = 0;
		$ecvdata['use_count'] = array("gt",0);
		//$ecvdata['sn'] = array("neq","FTGX2010");
		*/
	
	$where = " status = 0 and use_count > 0 and sn ='" . addslashes ( $sn ) . "'";
	$where .= " and password ='" . addslashes ( $password ) . "'";
	$where .= " and (user_id =" . $userid . " or user_id =0)";
	$sql = "select * from " . DB_PREFIX . "ecv where " . $where;
	//echo $sql; exit;
	$ecvdata = $GLOBALS ['db']->getRow ( $sql );
	if ($ecvdata) {
		//$ecvtype = M("EcvType")->getById($ecvdata['ecv_type']);
		$sql = "select * from " . DB_PREFIX . "ecv_type where id = " . intval ( $ecvdata ['ecv_type'] );
		$ecvtype = $GLOBALS ['db']->getRow ( $sql );
		if ($ecvtype ['type'] == 0) {
			a_error ( a_L ( "INVALID_ECV" ), '', 'back' );
		} else {
			if ($ecvtype ['use_start_date'] > 0 && $ecvtype ['use_start_date'] > a_gmtTime ()) {
				a_error ( a_L ( "INVALID_ECV_START_DATE" ), '', 'back' );
			}
			if ($ecvtype ['use_end_date'] > 0 && $ecvtype ['use_end_date'] < a_gmtTime ()) {
				a_error ( a_L ( "INVALID_ECV_END_DATE" ), '', 'back' );
			}
			
			//计算会员，已经获得的同类冲值券数量 add by chenfq 2011-03-09
			$sql = "select count(*) from " . DB_PREFIX . "ecv where use_user_id = ".$userid." and ecv_type =" .intval ( $ecvdata ['ecv_type'] );
			$use_count = $GLOBALS ['db']->getOne( $sql );
			if ($ecvtype ['use_count'] <= $use_count){
				a_error ( a_L ( "INVALID_ECV" ), '', 'back' );
			}
			
			require ROOT_PATH . "app/source/func/com_order_pay_func.php";
			s_user_money_log ( $userid, $ecvdata ['id'], 'Ecv', $ecvtype ['money'], $ecvdata ['sn'] . sprintf ( a_l ( "ECV_INCHARGE_MEMO" ), a_formatPrice ( $ecvtype ['money'] ) ) );
			if ($ecvdata ['use_count'] == 1)
				$ecvdata ['status'] = 1;
			$ecvdata ['use_user_id'] = $userid;
			$ecvdata ['use_date_time'] = a_gmtTime ();
			$ecvdata ['use_count'] = $ecvdata ['use_count'] - 1;
			//M("Ecv")->save($ecvdata);
			

			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "ecv", addslashes_deep ( $ecvdata ), 'UPDATE', "id = " . $ecvdata ['id'] );
			success ( a_L ( "INCHARGE_SUCCESS" ), '', a_u ( 'UcIncharge/ecv' ) );
		}
	} else {
		a_error ( a_L ( "INVALID_ECV" ), '', 'back' );
	}
}

function UcUncharge_index($userid) {
	if (a_fanweC ( "CLOSE_USERUNCHARGE" ) == 1) {
		a_error ( a_L ( "HC_USER_UNCHARGE_CLOSED" ), '', 'back' );
	}
	
	//初始化分页
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	$result = getUnchargeList ( $userid, $page );
	//var_dump();
	$GLOBALS ['tpl']->assign ( "uncharge_list", $result ['list'] );
	//分页
	$page = new Pager ( $result ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	//end 分页  
	

	$GLOBALS ['tpl']->assign ( "page_title", a_L ( "UCINCHARGE_INDEX" ) );
	$data = array ('navs' => array (array ('name' => a_L ( "UCINCHARGE_INDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucuncharge_index.moban' );
}

function UcUncharge_modify($userid) {
	
	$id = intval ( $_REQUEST ['id'] );
	$uncharge_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user_uncharge where status = 0 and user_id = " . $userid . " and id=" . $id );
	if (! $uncharge_info) {
		a_error ( aL ( "SUBMIT_ERROR", '', 'back' ) );
	}
	
	$GLOBALS ['tpl']->assign ( "page_title", a_L ( "UCINCHARGE_INDEX" ) );
	
	$GLOBALS ['tpl']->assign ( 'uncharge_info', $uncharge_info );
	
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucuncharge_modify.moban' );
}

function UcUncharge_update($userid) {
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	if (floatval ( $_POST ['money'] ) <= 0) {
		a_error ( a_L ( "MONEY_ERROR" ), '', 'back' );
	}
	
	$id = intval ( $_POST ['id'] );
	$_POST ['money'] = floatval ( $_POST ['money'] );
	
	$user_money = $GLOBALS ['db']->getOne ( "select money from " . DB_PREFIX . "user where id = " . $userid );
	
	$total_uncharge_money = $GLOBALS ['db']->getOne ( "select sum(money) from " . DB_PREFIX . "user_uncharge where user_id = " . $userid . " and status=0 and id<>" . $id );
	
	if ($_POST ['money'] + $total_uncharge_money > $user_money) {
		a_error ( a_L ( "OVER_UNCHARGE" ), '', 'back' );
	}
	
	$data ['id'] = $id;
	$data ['money'] = $_POST ['money'];
	$data ['memo'] = trim ( $_POST ['memo'] );
	$data ['update_time'] = a_gmtTime ();
	if ($GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_uncharge", addslashes_deep ( $data ), 'UPDATE', "id = " . $data ['id'] )) {
		success ( a_L ( "MODIFY_SUCCESS" ), '', 'back' );
	} else {
		a_error ( a_L ( "MODIFY_FAILED" ), '', 'back' );
	}
}

function UcUncharge_insert($userid) {
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	if (floatval ( $_POST ['money'] ) <= 0) {
		a_error ( a_L ( "MONEY_ERROR" ), '', 'back' );
	}
	
	$_POST ['money'] = floatval ( $_POST ['money'] );
	
	$user_money = $GLOBALS ['db']->getOne ( "select money from " . DB_PREFIX . "user where id = " . $userid );
	
	$total_uncharge_money = $GLOBALS ['db']->getOne ( "select sum(money) from " . DB_PREFIX . "user_uncharge where user_id = " . $userid . " and status=0" );
	
	if ($_POST ['money'] + $total_uncharge_money > $user_money) {
		a_error ( a_L ( "OVER_UNCHARGE" ), '', 'back' );
	}
	
	$data ['user_id'] = $userid;
	$data ['sn'] = a_toDate ( a_gmtTime (), 'ymdHis' ) . rand ( 0, 9 );
	$data ['money'] = $_POST ['money'];
	$data ['memo'] = trim ( $_POST ['memo'] );
        $data ['create_time'] = a_gmtTime (); //申请提现时间 
	if ($GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_uncharge", addslashes_deep ( $data ) )) {
		success ( a_L ( "SUBMIT_SUCCESS" ), '', a_u ( 'UcUncharge/index' ) );
	} else {
		a_error ( a_L ( "SUBMIT_ERROR" ), '', 'back' );
	}
}

function UcIncharge_modify($userid) {
	
	$id = intval ( $_REQUEST ['id'] );
	$incharge_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user_incharge where status = 0 and user_id = " . $userid . " and id=" . $id );
	if (! $incharge_info) {
		a_error ( aL ( "SUBMIT_ERROR" ) );
	}
	
	$GLOBALS ['tpl']->assign ( 'incharge_info', $incharge_info );
	
	//输出支付方式
	$payment_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "payment where status=1 and class_name<>'Accountpay' and class_name<>'Cod'" );
	foreach($payment_list as $kk=>$vv)
	{
		$currency_item = array('id'=>1,'unit'=>a_fanweC("BASE_CURRENCY_UNIT"),'radio'=>1);
		if($vv['fee_type']==0)
			$payment_list[$kk]['fee_format'] = a_formatPrice($vv['fee']);
		else 
			$payment_list[$kk]['fee_format'] = floatval($vv['fee'])."%";
		/*	    			
		if($vv['class_name'] == "TenpayBank"||$vv['class_name'] == "Sdo"){//财付通银行直接接口 add by chenfq 2010-12-30
			$payment_name = $vv['class_name']."Payment";
			require VENDOR_PATH.'payment3/'.$payment_name.'.class.php';
			$payment_model = new $payment_name;
			$res = $payment_model->getBackList($vv['id']);
			$GLOBALS['tpl']->assign("Bank_list",$res);
			unset($payment_list[$kk]);	//不在前台显示,财付通,只显示各银行 add by chenfq 2011-02-22		    			
		}*/
		
			$payment_name = $vv['class_name']."Payment";
			$pay_file = VENDOR_PATH.'payment3/'.$payment_name.'.class.php';
			if(file_exists($pay_file)){
				require_once($pay_file);
				$payment_model = new $payment_name;
				if (method_exists($payment_model,'getBackList')){
	    			$res = $payment_model->getBackList($vv['id']);
	    			$Bank_list = $Bank_list.$res;
			    	unset($payment_list[$kk]);
				}
				
				if (method_exists($payment_model,'selection')){
					$payment_list[$kk]['selection'] = $payment_model->selection($vv['id']);
				};			    	    			
			}		
	}
	$GLOBALS ['tpl']->assign ( "payment_list", $payment_list );
	$GLOBALS ['tpl']->assign ( "page_title", a_L ( "UCINCHARGE_MODIFY" ) );
	$data = array ('navs' => array (array ('name' => a_L ( "UCINCHARGE_MODIFY" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucincharge_modify.moban' );
}

function UcOrder_view($userid) {
	
	$order_id = intval ( $_REQUEST ['id'] );
	
	$order_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "order where status <> 2 and user_id = " . $userid . " and id=" . $order_id );
	if ($order_info) {
		$order_info ['total_price_format'] = a_formatPrice ( $order_info ['total_price'] );
		$order_info ['create_time_format'] = a_toDate ( $order_info ['create_time'] );
		$order_info ['update_time_format'] = a_toDate ( $order_info ['update_time'] );
		$order_info ['region_lv1_info'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "region_conf where id = " . intval ( $order_info ['region_lv1'] ) );
		$order_info ['region_lv2_info'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "region_conf where id = " . intval ( $order_info ['region_lv2'] ) );
		$order_info ['region_lv3_info'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "region_conf where id = " . intval ( $order_info ['region_lv3'] ) );
		$order_info ['region_lv4_info'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "region_conf where id = " . intval ( $order_info ['region_lv4'] ) );
		$order_info ['discount_price_format'] = a_formatPrice ( $order_info ['discount'] );
		$order_info ['ecv_money_format'] = a_formatPrice ( $order_info ['ecv_money'] );
		$order_info ['order_total_price_format'] = a_formatPrice ( $order_info ['order_total_price'] );
		$order_info ['order_all_price_format'] = a_formatPrice ( $order_info ['order_total_price'] + $order_info ['discount'] );
		$order_info ['order_all_price'] = $order_info ['order_total_price'] + $order_info ['discount'];
		$order_info ['delivery_fee_format'] = a_formatPrice ( $order_info ['delivery_fee'] );
		$order_info ['payment_fee_format'] = a_formatPrice ( $order_info ['payment_fee'] );
		$order_info ['protect_fee_format'] = a_formatPrice ( $order_info ['protect_fee'] );
		$order_info ['tax_money_format'] = a_formatPrice ( $order_info ['tax_money'] );
		$order_info ['promote_money_format'] = a_formatPrice ( $order_info ['promote_money'] );
		$order_info ['delivery'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "delivery where id = " . intval ( $order_info ['delivery'] ) );
		$order_info ['order_incharge_format'] = a_formatPrice ( $order_info ['order_incharge'] - $order_info ['ecv_money'] );
		$order_info ['order_less_format'] = a_formatPrice ( $order_info ['order_total_price'] - $order_info ['order_incharge'] );
		$order_info ['total_price_pay_format'] = a_formatPrice ( $order_info ['order_total_price'] - $order_info ['order_incharge'] );
		$order_info ['total_price_less_format'] = a_formatPrice ( $order_info ['order_total_price'] - $order_info ['order_incharge'] );
		$order_info ['total_price_less'] = ($order_info ['order_total_price'] - $order_info ['order_incharge']);
		$order_info ['payment'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "payment where id = " . intval ( $order_info ['payment'] ) );
		
		$time = a_gmtTime ();
		
		//$order_goods_list = D("Order")->getGoodsList($order_info['id']);
		

		$order_info ['order_status_format'] = '';
		
		
		$order_info ['stock_is_over'] = 0; //1：已经卖光了；0：未卖光
		$order_info ['is_delivery'] = 0;
		
		$sql = "select a.*,g.id,g.small_img,g.stock,g.buy_count,g.promote_end_time,g.is_group_fail,g.type_id from " . DB_PREFIX . "order_goods as a left outer join " . DB_PREFIX . "goods as g on g.id = a.rec_id where order_id = " . $order_id;
		$order_goods_list = $GLOBALS ['db']->getAll ( $sql );
		foreach ( $order_goods_list as $k => $goods ) {
			
			$order_goods_list [$k] ['url'] = a_u ( "Goods/show", "id-" . intval ( $goods ['rec_id'] ) );
			;
			//$order_goods_list[$k]['small_img'] = $order_goods['small_img'];
			$order_goods_list [$k] ['data_price_format'] = a_formatPrice ( $goods ['data_price'] );
			$order_goods_list [$k] ['data_score_format'] = a_formatPrice ( $goods ['data_score'] );
			$order_goods_list [$k] ['data_total_price_format'] = a_formatPrice ( $goods ['data_total_price'] );
			$order_goods_list [$k] ['data_total_score_format'] = a_formatPrice ( $goods ['data_total_score'] );
				
			if ($goods ['stock'] > 0) {
				if ($goods ['buy_count'] >= $goods ['stock']) {
					$order_info ['stock_is_over'] = 1; //团购结束，团购商品已经卖光了
				} elseif ($goods ['buy_count'] + $order_info ['orderGoods'] ['number'] > $goods ['stock']) {
					$order_info ['stock_is_over'] = 1; //购买数量大于商品数量
				}
			}
			//0:团购券，序列号+密码; 1:实体商品，需要配送;2:线下订购商品
			if (intval ( $goods ['type_id'] ) == 1 || intval($goods ['type_id']) == 3)
				$order_info ['is_delivery'] = 1;
			
			if ($goods ['promote_end_time'] < a_gmtTime ()) {
				$order_info ['stock_is_over'] = 1;
			}
		}
		
		
		$order_info ['money_status_format'] = a_L ( "ORDER_MONEY_STATUS_" . $order_info ['money_status'] );
		
		$order_info ['goods_status_format'] = a_L ( "ORDER_GOODS_STATUS_" . $order_info ['goods_status'] );
		
		$order_info['order_status_format'] = $order_info['money_status_format']."|".$order_info['money_status_format'];
		
		$GLOBALS ['tpl']->assign ( "order_info", $order_info );
		$GLOBALS ['tpl']->assign ( "order_goods_list", $order_goods_list );
		
		$GLOBALS ['tpl']->assign ( "module_name", 'Order' );
		
		//输出订单留言
		//初始化分页
		$page = intval ( $_REQUEST ['p'] );
		if ($page == 0)
			$page = 1;
		
		$result = getMessageList2 ( '', $order_id, $page,0,0 ," and (rec_module = 'Order' or rec_module='OrderReConsignment'  or rec_module='OrderUncharge' ) and user_id=".$userid );
		$GLOBALS ['tpl']->assign ( "message_list", $result ['list'] );
		//分页
		$page = new Pager ( $result ['total'], a_fanweC ( "ARTICLE_PAGE_LISTROWS" ) ); //初始化分页对象 		
		$p = $page->show ();
		$GLOBALS ['tpl']->assign ( 'pages', $p );
		//end 分页  
		$data = array ('navs' => array (array ('name' => a_L ( "XY_ORDER_INFOS" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
		assignSeo ( $data );
		$GLOBALS ['tpl']->display ( 'Inc/user_center/ucorder_view.moban' );
	} else {
		a_error ( a_L ( "NO_ORDER" ) );
	}
}

function Order_check($userid) {
	
	$id = intval ( $_REQUEST ['id'] ); //订单的ID
	

	$order = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "order where money_status in(0,1,4)  and goods_status in(0,4,5) and status < 2 and user_id = " . $userid . " and id=" . $id );
	
	if (! $order) {
		a_error ( a_L ( "NO_ORDER" ) );
		exit ();
	}
	
	$data = array ('navs' => array (array ('name' => a_l ( "HC_ORDER_EDIT" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	
	$sql = "select a.*,g.small_img,g.stock,g.buy_count,g.promote_end_time,g.allow_combine_delivery from " . DB_PREFIX . "order_goods as a left outer join " . DB_PREFIX . "goods as g on g.id = a.rec_id where order_id = " . $id;
	$order_goods_list = $GLOBALS ['db']->getAll ( $sql );
	//goods_type
	$consignee_id = intval ( $_REQUEST ['consignee_id'] );
	if ($consignee_id <= 0 && $order ['user_id'] > 0) {
		$sql = "select max(id) as maxid from " . DB_PREFIX . "user_consignee where consignee <> '' and user_id = " . $userid;
		$consignee_id = intval ( $GLOBALS ['db']->getOne ( $sql ) );
	}
	
	if ($order ['order_total_price'] < 0) {
		$isAccountpay = 1;
		$payment_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "payment where class_name='Accountpay'" );
		foreach ( $payment_list as $kk => $vv ) {
			if ($vv ['fee_type'] == 0)
				$payment_list [$kk] ['fee_format'] = formatPrice ( $vv ['fee'] );
			else
				$payment_list [$kk] ['fee_format'] = floatval ( $vv ['fee'] ) . "%";
		}
		$GLOBALS ['tpl']->assign ( "payment_list", $payment_list );
		$GLOBALS ['tpl']->assign ( "isAccountpay", $isAccountpay );
	} else {
		$isAccountpay = 0;
		$Bank_list = '';
		$payment_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "payment where status=1" );
		foreach ( $payment_list as $kk => $vv ) {
			if ($vv ['class_name'] == "Accountpay")
				$isAccountpay = 1;
			if ($vv ['fee_type'] == 0)
				$payment_list [$kk] ['fee_format'] = a_formatPrice ( $vv ['fee'] );
			else
				$payment_list [$kk] ['fee_format'] = floatval ( $vv ['fee'] ) . "%";
			
			$payment_name = $vv['class_name']."Payment";
			$pay_file = VENDOR_PATH.'payment3/'.$payment_name.'.class.php';
			if(file_exists($pay_file)){
				require_once($pay_file);
				$payment_model = new $payment_name;
				if (method_exists($payment_model,'getBackList')){
	    			$res = $payment_model->getBackList($vv['id']);
	    			$Bank_list = $Bank_list.$res;
			    	unset($payment_list[$kk]);
				}
				
				if (method_exists($payment_model,'selection')){
					$payment_list[$kk]['selection'] = $payment_model->selection($vv['id']);
				};			    	    			
			}	
					
		}
		 $GLOBALS['tpl']->assign("Bank_list",$Bank_list);
		$GLOBALS ['tpl']->assign ( "payment_list", $payment_list );
		$GLOBALS ['tpl']->assign ( "isAccountpay", $isAccountpay );
	}
	
	$user_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user where id = " . $userid );
	$GLOBALS ['tpl']->assign ( "user_info", $user_info); 
	if ($order ['delivery'] > 0 || $order ['delivery_refer_order_id'] > 0) {
		$consignee_info ['region_lv1'] = $order ['region_lv1'];
		$consignee_info ['region_lv2'] = $order ['region_lv2'];
		$consignee_info ['region_lv3'] = $order ['region_lv3'];
		$consignee_info ['region_lv4'] = $order ['region_lv4'];
		$GLOBALS ['tpl']->assign ( "goods_type", 1 ); 
		if ($consignee_info) {
			$consignee_info ['qq'] = $user_info ['qq'];
			$consignee_info ['msn'] = $user_info ['msn'];
			$consignee_info ['alim'] = $user_info ['alim'];
			$consignee_info ['email'] = $user_info ['email'];
			
			//输出一级地区
			$region_lv1_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=0 order by name asc" );
			$GLOBALS ['tpl']->assign ( "region_lv1_list", $region_lv1_list );
			
			//输出二级地区				
			$region_lv2_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=" . intval ( $consignee_info ['region_lv1'] ) . " order by name asc" );
			$GLOBALS ['tpl']->assign ( "region_lv2_list", $region_lv2_list );
			
			//输出三级地区 
			$region_lv3_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=" . intval ( $consignee_info ['region_lv2'] ) . " order by name asc" );
			$GLOBALS ['tpl']->assign ( "region_lv3_list", $region_lv3_list );
			
			//输出四级地区
			$region_lv4_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=" . intval ( $consignee_info ['region_lv3'] ) . " order by name asc" );
			$GLOBALS ['tpl']->assign ( "region_lv4_list", $region_lv4_list );
		
		} else {
			$user_info ['consignee'] = $user_info ['nickname'];
			//输出一级地区
			$region_lv1_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "region_conf where pid=0 order by name asc" );
			$GLOBALS ['tpl']->assign ( "region_lv1_list", $region_lv1_list );
		}
		
		$GLOBALS ['tpl']->assign ( "consignee_info", $consignee_info );
		
		if ($consignee_info) {
			if ($consignee_info ['region_lv4'] > 0) {
				$end_region_id = $consignee_info ['region_lv4'];
			} elseif ($consignee_info ['region_lv3'] > 0) {
				$end_region_id = $consignee_info ['region_lv3'];
			} elseif ($consignee_info ['region_lv2'] > 0) {
				$end_region_id = $consignee_info ['region_lv2'];
			} elseif ($consignee_info ['region_lv1'] > 0) {
				$end_region_id = $consignee_info ['region_lv1'];
			}
		} else {
			$end_region_id = 0;
		}
		
		require ROOT_PATH . "app/source/func/com_order_pay_func.php";
		$delivery_ids = loadDelivery ( $end_region_id );
		$delivery_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "delivery  where status = 1 order by sort" );
		foreach ( $delivery_list as $k => $v ) {
			if (! in_array ( $v ['id'], $delivery_ids )) {
				unset ( $delivery_list [$k] );
			} else
				$delivery_list [$k] ['protect_radio'] = $v ['protect_radio'] . "%";
		}
		$GLOBALS ['tpl']->assign ( 'delivery_list', $delivery_list );
		
		$is_combine = 0;
		foreach ( $order_goods_list as $order_goods ) {
			if ($is_combine == 1)
				break;
		}
		if ($is_combine == 1) {
			$sql = "select o.*,g.allow_combine_delivery as acd from " . DB_PREFIX . "order as o left join " . DB_PREFIX . "order_goods as og on o.id = og.order_id left join " . DB_PREFIX . "goods as g on og.rec_id = g.id " . " where o.delivery > 0 and o.money_status = 2 and o.goods_status = 0 and o.user_id = " . $u . " and g.allow_combine_delivery = 1";
			$order_deliverys = $GLOBALS ['db']->getAll ( $sql );
			foreach ( $order_deliverys as $k => $v ) {
				$order_deliverys [$k] ['region_lv1_name'] = $GLOBALS ['db']->getOneCached ( "select name from " . DB_PREFIX . "region_conf where id=" . intval ( $v ['region_lv1'] ) );
				$order_deliverys [$k] ['region_lv2_name'] = $GLOBALS ['db']->getOneCached ( "select name from " . DB_PREFIX . "region_conf where id=" . intval ( $v ['region_lv2'] ) );
				$order_deliverys [$k] ['region_lv3_name'] = $GLOBALS ['db']->getOneCached ( "select name from " . DB_PREFIX . "region_conf where id=" . intval ( $v ['region_lv3'] ) );
				$order_deliverys [$k] ['region_lv4_name'] = $GLOBALS ['db']->getOneCached ( "select name from " . DB_PREFIX . "region_conf where id=" . intval ( $v ['region_lv4'] ) );
				$order_deliverys [$k] ['delivery_name'] = $GLOBALS ['db']->getOneCached ( "select name_1 from " . DB_PREFIX . "eelivery where id=" . intval ( $v ['delivery'] ) );
			}
			$GLOBALS ['tpl']->assign ( "order_deliverys", $order_deliverys );
		}
	}
	
	$GLOBALS ['tpl']->assign ( 'order_goods_list', $order_goods_list );
	$GLOBALS ['tpl']->assign ( 'order', $order );
	
	$GLOBALS ['tpl']->display ( 'Page/order_check.moban' );
}

function UcOrder_del($userid) {
	$order_id = intval ( $_REQUEST ['id'] );
	
	$order_info = $GLOBALS ['db']->getRow ( "select id,money_status,goods_status,user_id from " . DB_PREFIX . "order where user_id = " . $userid . " and id=" . $order_id );
	if ($order_info ['money_status'] > 0 || $order_info ['status'] > 0 || ($order_info ['goods_status'] > 0 && $order_info ['goods_status'] != 5)) {
		a_error ( a_L ( "ORDER_STATUS_CANT_DELETE" ), '', 'back' ); //非未确认订单不能删除
	}
	
	//$msg_list = D("Message")->where("rec_module='Order' and rec_id=".$order_id)->findAll();
	/*$msg_list = $GLOBALS ['db']->getAll ( "select id from " . DB_PREFIX . "message where rec_module='Order' and rec_id=" . $order_id );
	foreach ( $msg_list as $item ) {
		//D("Message")->where("pid=".$item['id'])->delete();
		$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "message where pid=" . intval ( $item ['id'] ) );
	}
	//D("Message")->where("rec_module='Order' and rec_id=".$order_id)->delete();
	$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "message where rec_module='Order' and rec_id=" . $order_id );
	
	$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "order_log where order_id=" . $order_id );
	$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "order_promote where order_id=" . $order_id );
	$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "order_goods where order_id=" . $order_id );
	$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "order where id=" . $order_id );*/
	//修正订单不直接删除
	$GLOBALS ['db']->query ( "update " . DB_PREFIX . "order set status=2 where user_id={$userid} and id=" . $order_id );
	//D("OrderLog")->where("order_id=".$order_id)->delete();
	//D("OrderPromote")->where("order_id=".$order_id)->delete();
	//$goods_id = M("OrderGoods")->where("order_id=".$order_id)->getField("rec_id");
	//D("OrderGoods")->where("order_id=".$order_id)->delete();
	//D("Order")->where("id=".$order_id)->delete();
	//clear_user_order_cache($order_id);
	//S("CACHE_USER_BUY_COUNT_".intval($_SESSION['user_id'])."_".$goods_id,NULL);
	

	success ( a_L ( "DEL_SUCCESS" ) );

}

function UserCenter_subscribe($userid) {
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	//订阅
	//$id = intval($_REQUEST['id']);
	$user_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user where id = " . $userid );
	$data ['mail_address'] = $user_info ['email'];
	$data ['status'] = 1;
	$data ['user_id'] = $userid;
	$data ['city_id'] = $user_info ['city_id'];
	if ($GLOBALS ['db']->autoExecute ( DB_PREFIX . "mail_address_list", addslashes_deep ( $data ) )) {
		success ( a_L ( "SUBSCRIBE_SUCCESS" ), '', 'back' );
	} else {
		a_error ( a_L ( "SUBSCRIBE_FAILED" ), '', 'back' );
	}
}

function UserCenter_unsubscribe($userid) {
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	//退订
	$user_info = $GLOBALS ['db']->getRow ( "select email from " . DB_PREFIX . "user where id = " . $userid );
	$sql = "delete from " . DB_PREFIX . "mail_address_list where user_id = ".$userid." or mail_address ='".$user_info['email']."'";
	if ($GLOBALS ['db']->query ( $sql )) {
		success ( a_L ( "SUBSCRIBEBACK_SUCCESS" ), '', 'back' );
	} else {
		a_error ( a_L ( "SUBSCRIBEBACK_FAILED" ), '', 'back' );
	}
}

function UcBelowOrder_index($userid) {
	//初始化分页
	$page = intval ( $_REQUEST ["p"] );
	if ($page == 0)
		$page = 1;
	
	$res = getOrderList ( intval ( $userid ), $page, 1 );
	$GLOBALS ['tpl']->assign ( 'order_list', $res ['list'] );
	$data = array ('navs' => array (array ('name' => a_l ( "UCBELOWORDER_INDEX" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	
	//分页
	$page = new Pager ( $res ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucbeloworder_index.moban' );
}

function UcBelowOrder_view($userid) {
	$order_id = intval ( $_REQUEST ['id'] );
	$order_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "order where status = 0 and user_id = " . $userid . " and id=" . $order_id );
	if ($order_info) {
		$order_info ['total_price_format'] = a_formatPrice ( $order_info ['total_price'] );
		$order_info ['create_time_format'] = a_toDate ( $order_info ['create_time'] );
		$order_info ['update_time_format'] = a_toDate ( $order_info ['update_time'] );
		$order_info ['region_lv1_info'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "region_conf where id = " . intval ( $order_info ['region_lv1'] ) );
		$order_info ['region_lv2_info'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "region_conf where id = " . intval ( $order_info ['region_lv2'] ) );
		$order_info ['region_lv3_info'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "region_conf where id = " . intval ( $order_info ['region_lv3'] ) );
		$order_info ['region_lv4_info'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "region_conf where id = " . intval ( $order_info ['region_lv4'] ) );
		$order_info ['discount_price_format'] = a_formatPrice ( $order_info ['discount'] );
		$order_info ['ecv_money_format'] = a_formatPrice ( $order_info ['ecv_money'] );
		$order_info ['order_total_price_format'] = a_formatPrice ( $order_info ['order_total_price'] );
		$order_info ['order_all_price_format'] = a_formatPrice ( $order_info ['order_total_price'] + $order_info ['discount'] );
		$order_info ['order_all_price'] = $order_info ['order_total_price'] + $order_info ['discount'];
		$order_info ['delivery_fee_format'] = a_formatPrice ( $order_info ['delivery_fee'] );
		$order_info ['payment_fee_format'] = a_formatPrice ( $order_info ['payment_fee'] );
		$order_info ['protect_fee_format'] = a_formatPrice ( $order_info ['protect_fee'] );
		$order_info ['tax_money_format'] = a_formatPrice ( $order_info ['tax_money'] );
		$order_info ['promote_money_format'] = a_formatPrice ( $order_info ['promote_money'] );
		$order_info ['delivery'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "delivery where id = " . intval ( $order_info ['delivery'] ) );
		$order_info ['order_incharge_format'] = a_formatPrice ( $order_info ['order_incharge'] - $order_info ['ecv_money'] );
		$order_info ['order_less_format'] = a_formatPrice ( $order_info ['order_total_price'] - $order_info ['order_incharge'] );
		$order_info ['total_price_pay_format'] = a_formatPrice ( $order_info ['order_total_price'] - $order_info ['order_incharge'] );
		$order_info ['total_price_less_format'] = a_formatPrice ( $order_info ['order_total_price'] - $order_info ['order_incharge'] );
		$order_info ['total_price_less'] = ($order_info ['order_total_price'] - $order_info ['order_incharge']);
		$order_info ['payment'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "payment where id = " . intval ( $order_info ['payment'] ) );
		
		$time = a_gmtTime ();
		
		//$order_goods_list = D("Order")->getGoodsList($order_info['id']);
		

		//$order_info ['order_status_format'] = a_L ( "ORDER_STATUS_" . $order_info ['status'] );
		$order_info ['stock_is_over'] = 0; //1：已经卖光了；0：未卖光
		$order_info ['is_delivery'] = 0;
		
		$sql = "select a.*,g.small_img,g.stock,g.buy_count,g.promote_end_time,g.type_id from " . DB_PREFIX . "order_goods as a left outer join " . DB_PREFIX . "goods as g on g.id = a.rec_id where order_id = " . $order_id;
		$order_goods_list = $GLOBALS ['db']->getAll ( $sql );
		foreach ( $order_goods_list as $k => $goods ) {
			
			$order_goods_list [$k] ['url'] = a_u ( "Goods/show", "id-" . intval ( $goods ['rec_id'] ) );
			;
			//$order_goods_list[$k]['small_img'] = $order_goods['small_img'];
			$order_goods_list [$k] ['data_price_format'] = a_formatPrice ( $goods ['data_price'] );
			$order_goods_list [$k] ['data_score_format'] = a_formatPrice ( $goods ['data_score'] );
			$order_goods_list [$k] ['data_total_price_format'] = a_formatPrice ( $goods ['data_total_price'] );
			$order_goods_list [$k] ['data_total_score_format'] = a_formatPrice ( $goods ['data_total_score'] );
			
			if ($goods ['stock'] > 0) {
				if ($goods ['buy_count'] >= $goods ['stock']) {
					$order_info ['stock_is_over'] = 1; //团购结束，团购商品已经卖光了
				} elseif ($goods ['buy_count'] + $order_info ['orderGoods'] ['number'] > $goods ['stock']) {
					$order_info ['stock_is_over'] = 1; //购买数量大于商品数量
				}
			}
			//0:团购券，序列号+密码; 1:实体商品，需要配送;2:线下订购商品
			if (intval ( $goods ['type_id'] ) == 1 || intval($goods ['type_id'] ) == 3 )
				$order_info ['is_delivery'] = 1;
			
			if ($goods ['promote_end_time'] < $time) {
				$order_info ['stock_is_over'] = 1;
			}
		}
		
		$order_info ['money_status_format'] = a_L ( "ORDER_MONEY_STATUS_" . $order_info ['money_status'] );
		
		$order_info['goods_status_format'] = a_L ( "ORDER_GOODS_STATUS_" . $order_info ['goods_status'] );
		
		$order_info['order_status_format'] = $order_info['money_status_format']."|".$order_info['money_status_format'];
		
		$GLOBALS ['tpl']->assign ( "order_info", $order_info );
		
		$GLOBALS ['tpl']->assign ( "order_goods_list", $order_goods_list );
		
		$GLOBALS ['tpl']->assign ( "module_name", 'Order' );
		$GLOBALS ['tpl']->assign ( "rec_id", $order_info['id'] );
		
		//输出订单总价
		$total_price = ($order_info ['total_price'] + $delivery_fee + ($payment_fee));
		
		$GLOBALS ['tpl']->assign ( 'total_price', $total_price );
		//输出订单留言
		//初始化分页
		$page = intval ( "p" );
		if ($page == 0)
			$page = 1;
		
		$data = array ('navs' => array (array ('name' => a_l ( "XY_ORDER_INFO" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
		assignSeo ( $data );
		$result = getMessageList2 ( '', $order_id, $page );
		
		$GLOBALS ['tpl']->assign ( "message_list", $result ['message_list'] );
		//分页
		$page = new Pager ( $result ['message_total'], a_fanweC ( "ARTICLE_PAGE_LISTROWS" ) ); //初始化分页对象 		
		$p = $page->show ();
		$GLOBALS ['tpl']->assign ( 'pages', $p );
		//end 分页  
		$GLOBALS ['tpl']->display ( "Inc/user_center/ucbeloworder_view.moban" );
	} else {
		a_error ( a_L ( "NO_ORDER" ) );
	}
}

function UcBelowOrder_del(){
	$order_id = intval($_REQUEST['id']);
	$order_info = $GLOBALS['db']->getOne("select * from ".DB_PREFIX."order where id=".$order_id);
	
	if($order_info['money_status'] > 0 || $order_info['status'] >0)
	{
		a_error(a_L("ORDER_STATUS_CANT_DELETE"));  //非未确认订单不能删除
	}
		

	$msg_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."message where rec_module='Order' and rec_id=".$order_id);
	
	foreach($msg_list as $item)
	{
		$GLOBALS['db']->query("delete from ".DB_PREFIX."message where pid=".$item['id']);
	}
	
	
	$GLOBALS['db']->query("delete from ".DB_PREFIX."message where rec_module='Order' and rec_id=".$order_id);
	$GLOBALS['db']->query("delete from ".DB_PREFIX."order_log where order_id=".$order_id);
	$GLOBALS['db']->query("delete from ".DB_PREFIX."order_promote where order_id=".$order_id);
	$GLOBALS['db']->query("delete from ".DB_PREFIX."order_goods where order_id=".$order_id);
	$GLOBALS['db']->query("delete from ".DB_PREFIX."order where id=".$order_id);
	success(a_L("DEL_SUCCESS"));
}

function UcScore_exchange($userid)
{
	$GLOBALS ['tpl']->assign ( "page_title", a_L ( "UCSCORE_EXCHANGE" ) );
	$data = array ('navs' => array (array ('name' => a_L ( "UCSCORE_EXCHANGE" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	//开始处理分页
	$page_size = $page;
	$page_count = a_fanweC("PAGE_LISTROWS");
	$limit = ($page_size-1)*$page_count.",".$page_count;
		
	$log_list = $GLOBALS['db']->getAll("select rec_id,create_time,memo_1 from ".DB_PREFIX."user_score_log where user_id={$userid} and rec_module = 'UcScore' limit {$limit}");
	$total = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_score_log where user_id={$userid} and rec_module = 'UcScore'");
	//分页
	$page = new Pager ( $total, a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
	$p = $page->show ();
	
	$GLOBALS ['tpl']->assign ( "log_list", $log_list );
	$GLOBALS ['tpl']->assign ( "pages", $p );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucscore_exchange.moban' );
}

function UcScore_exchange_do($userid)
{
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	$input_val = $_REQUEST['score_val'];
	$user_score=$GLOBALS['tpl']->_var['user_info']['score'];
	if($user_score < $input_val)
	{
		a_error(a_L("SCORE_GT_EXCHANGE_SC"),"","back");
		exit();
	}
	if($input_val < a_fanweC('EX_SCORE_SCALE'))
	{
		a_error(a_L("NOT_LT_EXCHANGE_SC").a_fanweC('EX_SCORE_SCALE'),"","back");
		exit();
	}
	if($input_val%a_fanweC('EX_SCORE_SCALE')!==0)
	{
		a_error(sprintf(a_L("ENT_SCORE_MUSTBE"),a_fanweC('EX_SCORE_SCALE')),"","back");
		exit();
	}
	
	$money = ($input_val/a_fanweC('EX_SCORE_SCALE'));
	require ROOT_PATH . "app/source/func/com_order_pay_func.php";
	if(s_user_money_log($userid,0,"UcScore",$money,a_L("SCORE_EXC_EVC")))
	{
		s_user_score_log($userid,$money,"UcScore","-{$input_val}",a_L("SCORE_EXC_EVC"));
		success(sprintf(a_L("SUCCESS_EXC_SCORE"),$money));
		exit();
	}
}

function UcModify_avatar($userid)
{
	$user_face = "";
	if(is_file(ROOT_PATH."Public/upload/avatar/avatar_small/{$userid}.jpg"))
		$user_face= "Public/upload/avatar/avatar_small/{$userid}.jpg";

	$data = array ('navs' => array (array ('name' => a_l ( "UCMODIFY_AVATAR" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$GLOBALS ['tpl']->assign ( "user_face", "$user_face" );
	$GLOBALS ['tpl']->assign ( "current_page", "UCMODIFY_AVATAR" );
	$GLOBALS ['tpl']->assign ( "page_title", a_L ( "UCMODIFY_AVATAR" ) );
	$GLOBALS ['tpl']->display ( 'Inc/user_center/ucmodify_avatar.moban' );
}
function UcModify_camera($userid)
{
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	//保存报像头上传的图片.
	//Download by http://www.codefans.net
	@header("Expires: 0");
	@header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	if (! is_dir ( ROOT_PATH . 'Public/upload/avatar/avatar_origin'))
		mkdir ( ROOT_PATH . 'Public/upload/avatar/avatar_origin');
	//生成图片存放路径
	$new_avatar_path = 'avatar_origin/'.$userid.'.jpg';
	
	//将POST过来的二进制数据直接写入图片文件.
	
	//$len = file_put_contents(ROOT_PATH.'Public/upload/avatar/'.$new_avatar_path,file_get_contents("php://input"));
	@file_put_contents(ROOT_PATH.'Public/upload/avatar/'.$new_avatar_path,file_get_contents("php://input"));
	
	//原始图片比较大，压缩一下. 效果还是很明显的, 使用80%的压缩率肉眼基本没有什么区别
	$avtar_img = imagecreatefromjpeg(ROOT_PATH.'Public/upload/avatar/'.$new_avatar_path);
	imagejpeg($avtar_img,ROOT_PATH.'Public/upload/avatar/'.$new_avatar_path,80);
	//nix系统下有必要时可以使用 chmod($filename,$permissions);
	
	//log_result('图片大小: '.$len);
	
	
	//输出新保存的图片位置, 测试时注意改一下域名路径, 后面的statusText是成功提示信息.
	//status 为1 是成功上传，否则为失败.
	$d = new pic_data();
	$d->data->photoId = $userid;
	//$d->data->urls[0] = 'http://sns.com/avatar_test/'.$new_avatar_path;
	$d->data->urls[0] = 'Public/upload/avatar/'.$new_avatar_path;
	$d->status = 1;
	$d->statusText = a_L('UPLOAD_SUCCESS');
	
	$msg = json_encode($d);
	
	echo $msg;
	/*
	log_result($msg);
	function  log_result($word) {
		@$fp = fopen("log.txt","a");	
		@flock($fp, LOCK_EX) ;
		@fwrite($fp,$word."：执行日期：".strftime("%Y%m%d%H%I%S",time())."\r\n");
		@flock($fp, LOCK_UN); 
		@fclose($fp);
	}*/
	
}


function Ucmodify_avatar_upload($userid)
{
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	@header("Expires: 0");
	@header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	if (! is_dir ( ROOT_PATH . 'Public/upload/avatar/avatar_origin'))
		mkdir ( ROOT_PATH . 'Public/upload/avatar/avatar_origin');
	$pic_path = ROOT_PATH.'Public/upload/avatar/avatar_origin/'.$userid.'.jpg';
	//上传后图片的绝对地址
	//$pic_abs_path = 'http://sns.com/avatar_test/avatar_origin/'.$pic_id.'.jpg';
	$pic_abs_path = 'Public/upload/avatar/avatar_origin/'.$userid.'.jpg';
	//保存上传图片.
	if(empty($_FILES['Filedata'])) {
		echo '<script type="text/javascript">alert("'.a_L('UPLOAD_PIC_ERR').'");</script>';
		exit();
	}
	
	$file = @$_FILES['Filedata']['tmp_name'];
	
	//Download by http://www.codefans.net
	if(file_exists($pic_path))
		 @unlink($pic_path);
	
	if(@copy($file, $pic_path) || @move_uploaded_file($file, $pic_path)) 
	{
		@unlink($_FILES['Filedata']['tmp_name']);
		/*list($width, $height, $type, $attr) = getimagesize($pic_path);
		if($width < 10 || $height < 10 || $width > 3000 || $height > 3000 || $type == 4) {
			@unlink($pic_path);
			return -2;
		}*/
	} else {
		@unlink($_FILES['Filedata']['tmp_name']);
		echo '<script type="text/javascript">alert("'.a_L('UPLOAD_ERR').'");</script>';
	}
	
	//写新上传照片的ID.
	echo '<script type="text/javascript">window.parent.hideLoading();window.parent.buildAvatarEditor("'.$userid.'","'.$pic_abs_path.'","photo");</script>';

}
function UcModify_save_avatar($userid)
{
	//if(!check_referer())
	//{
		//a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	//}
	@header("Expires: 0");
	@header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	
	//这里传过来会有两种类型，一先一后, big和small, 保存成功后返回一个json字串，客户端会再次post下一个.
	$type = isset($_GET['type'])?trim($_GET['type']):'small';
	$pic_id = $userid;
	//$orgin_pic_path = $_GET['photoServer']; //原始图片地址，备用.
	//$from = $_GET['from']; //原始图片地址，备用.
	if (! is_dir ( ROOT_PATH . 'Public/upload/avatar/avatar_'.$type ))
		mkdir ( ROOT_PATH . 'Public/upload/avatar/avatar_'.$type);
	//生成图片存放路径
	$new_avatar_path = 'avatar_'.$type.'/'.$pic_id.'.jpg';
	
	//将POST过来的二进制数据直接写入图片文件.
	@file_put_contents(ROOT_PATH.'Public/upload/avatar/'.$new_avatar_path,file_get_contents("php://input"));
	
	//原始图片比较大，压缩一下. 效果还是很明显的, 使用80%的压缩率肉眼基本没有什么区别
	//小图片 不压缩约6K, 压缩后 2K, 大图片约 50K, 压缩后 10K
	$avtar_img = imagecreatefromjpeg(ROOT_PATH.'Public/upload/avatar/'.$new_avatar_path);
	imagejpeg($avtar_img,ROOT_PATH.'Public/upload/avatar/'.$new_avatar_path,80);
	//nix系统下有必要时可以使用 chmod($filename,$permissions);

	//输出新保存的图片位置, 测试时注意改一下域名路径, 后面的statusText是成功提示信息.
	//status 为1 是成功上传，否则为失败.
	$d = new pic_data();
	//$d->data->urls[0] = 'http://sns.com/avatar_test/'.$new_avatar_path;
	$d->data->urls[0] = '/Public/upload/avatar/'.$new_avatar_path;
	$d->status = 1;
	$d->statusText = a_L('UPLOAD_SUCCESS');
	
	$msg = json_encode($d);
	
	echo $msg;
}
class pic_data
{
	 public $data;
	 public $status;
	 public $statusText;
	public function __construct()
	{
		$this->data->urls = array();
	}
}

?>