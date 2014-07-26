<?php
    $data = array(
    	'navs' => array(
    			array('name'=>a_L("HC_RESET_PASSWORD"),'url'=>a_u("User/reset"))
    	),
    	'keyword'=>	'',
    	'content'=>	'',
    );
    assignSeo($data);
	$sn = $_REQUEST['sn'];
	$tpl->assign("sn",$sn);
    //输出主菜单
	$GLOBALS['tpl']->assign("main_navs",assignNav(2));
	//输出城市
	$GLOBALS['tpl']->assign("city_list",getGroupCityList());
	//输出帮助
	$GLOBALS['tpl']->assign("help_center",assignHelp());
	$tpl->display("Inc/user/reset_password.moban");
   
?>