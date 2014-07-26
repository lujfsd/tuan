<?php
   	$GLOBALS['tpl']->assign('redirect',$_SERVER['HTTP_REFERER']); 
   	$GLOBALS['tpl']->assign('goods_id',$_REQUEST['id']);
   	//输出主菜单
	$GLOBALS['tpl']->assign("main_navs",assignNav(2));
	//输出城市
	$GLOBALS['tpl']->assign("city_list",getGroupCityList());
	//输出帮助
	$GLOBALS['tpl']->assign("help_center",assignHelp());
	//输出当前页seo内容
   	$data = array(
   		'navs' => array(
   			array('name'=>a_L('USER_LOGIN'),'url'=>a_u("User/login"))
   		),
   		'keyword'=>	'',
   		'content'=>	'',
   	);
	assignSeo($data);
	$GLOBALS['tpl']->display('Inc/user/login.moban');
?>