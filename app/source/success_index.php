<?php
		$title = base64_decode(base64_decode($_REQUEST['title']));
		if (empty($title))
			$title = a_L("_OPERATION_SUCCESS_");
				
	    $msg = base64_decode(base64_decode($_REQUEST['msg']));
	    $jumpUrl = base64_decode(base64_decode($_REQUEST['jumpUrl']));
	    if (empty($jumpUrl)){
	    	$jumpUrl = $_SERVER['HTTP_REFERER'];
	    }		
		if (isset($_SESSION['ucdata'])){
			$ucdata = base64_decode($_SESSION['ucdata']);
			$_SESSION['ucdata'] = '';
			unset($_SESSION['ucdata']);
			if (empty($msg)){
				$msg = $ucdata;
			}else{
				$msg = $msg.$ucdata;
			}
		}
		
		$GLOBALS['tpl']->assign("jumpUrl",$jumpUrl);
		$GLOBALS['tpl']->assign("success_title",$title);
		$GLOBALS['tpl']->assign("success_msg",$msg);
		
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		$GLOBALS['tpl']->display("Page/success_index.moban");
?>