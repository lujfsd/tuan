<?php
		$title = base64_decode(base64_decode($_REQUEST['title']));
		if (empty($title))
			$title = a_L("_OPERATION_FAIL_");
				
	    $msg = base64_decode(base64_decode($_REQUEST['msg']));
	    $jumpUrl = base64_decode(base64_decode($_REQUEST['jumpUrl']));
	    if (empty($jumpUrl)){
	    	$jumpUrl = $_SERVER['HTTP_REFERER'];
	    }
	    	
		$GLOBALS['tpl']->assign("jumpUrl",$jumpUrl);
		$GLOBALS['tpl']->assign("fail_title",$title);
		$GLOBALS['tpl']->assign("error_msg",$msg);
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		$GLOBALS['tpl']->display("Page/error_index.moban");
?>