<?php
		$user_id = intval($_REQUEST['user_id']);		
		$user = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."user where id = ".$user_id);
			
		$GLOBALS['tpl']->assign("user",$user);
		$GLOBALS['tpl']->assign("REPLY_ADDRESS",a_fanweC('REPLY_ADDRESS'));
		
		$data = array(
			'navs' => array(
				array('name'=>a_L("HC_EMAIL_NOT_VERIFY"),'url'=>'')
			),
			'keyword'=>	'',
			'content'=>	'',
		);
				
		assignSeo($data);
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		
		$tpl->display("Page/no_verify.moban");   	
?>