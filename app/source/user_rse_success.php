<?php
	$user_id = intval ( $_REQUEST ['user_id'] );
	$user = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "user where id = " . $user_id );
	
	$user_email = $user ['email'];
	$domain = explode ( "@", $user_email );
	$domain = $domain [1];
	$gocheck_url = '';
	switch ($domain) {
		case '163.com' :
			$gocheck_url = 'http://mail.163.com';
			break;
		case '126.com' :
			$gocheck_url = 'http://www.126.com';
			break;
		case 'sina.com' :
			$gocheck_url = 'http://mail.sina.com';
			break;
		case 'sina.com.cn' :
			$gocheck_url = 'http://mail.sina.com.cn';
			break;
		case 'sina.cn' :
			$gocheck_url = 'http://mail.sina.cn';
			break;
		case 'qq.com' :
			$gocheck_url = 'http://mail.qq.com';
			break;
		case 'foxmail.com' :
			$gocheck_url = 'http://mail.foxmail.com';
			break;
		case 'gmail.com' :
			$gocheck_url = 'http://www.gmail.com';
			break;
		case 'yahoo.com' :
			$gocheck_url = 'http://mail.yahoo.com';
			break;
		case 'yahoo.com.cn' :
			$gocheck_url = 'http://mail.cn.yahoo.com';
			break;
		case 'hotmail.com' :
			$gocheck_url = 'http://www.hotmail.com';
			break;
		case 'msn.cn' :
			$gocheck_url = 'http://mail.live.com';
			break;
		case 'msn.com' :
			$gocheck_url = 'http://mail.live.com';
			break;
		default :
			$gocheck_url = 'http://mail.' . $domain;
			break;
	}
	
	$data = array ('navs' => array (array ('name' => a_L ( 'REG_SUCCESS_WAIT' ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
	
	assignSeo ( $data );
	
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	
	$GLOBALS ['tpl']->assign ( "user", $user );
	$GLOBALS ['tpl']->assign ( "REPLY_ADDRESS", a_fanweC ( 'REPLY_ADDRESS' ) );
	$GLOBALS ['tpl']->assign ( "gocheck_url", $gocheck_url );
	$tpl->display ( "Page/verify_success.moban" );
?>