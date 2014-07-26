<?php
	$GLOBALS['tpl']->assign('sn',$_REQUEST['sn']);
	//输出当前页seo内容
   	$data = array(
   		'navs' => array(
   			array('name'=>a_L('GET_PASSWORD'),'url'=>a_u("User/resetreq"))
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
	$GLOBALS['tpl']->display('Inc/user/get_password.moban');	    
?>