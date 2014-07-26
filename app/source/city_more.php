<?php
	$pylist  = range('a','z');
	$citylist = getGroupCityList();
	$list = "";
	foreach($pylist as $idx => $k)
	{
		$list[$idx]["py"] = strtoupper($k);
		foreach($citylist as $sidx  => $v)
		{
			if(strtolower(substr($v['py'],0,1)) == $k )
			{
				$list[$idx]['list'][] = $v;
			}
		}
		if(count($list[$idx]['list'])==0)
		{
			unset($list[$idx]);
		}
	}
	
	
	//输出当前页seo内容
    $data = array(
    	'navs' => array(
    		array(
				'name'=>a_L("SELECT_WHERE_YOU_CITY"),
				'url' =>''
			)
    	),
    );
	assignSeo ( $data );
	
	//输出主菜单
	$GLOBALS['tpl']->assign("main_navs",assignNav(2));
	//输出城市
	$GLOBALS['tpl']->assign("city_list",getGroupCityList());
	//输出帮助
	$GLOBALS['tpl']->assign("help_center",assignHelp());
	$GLOBALS['tpl']->assign("citylist",$list);
	
	$GLOBALS['tpl']->display("Page/citylist.moban");
	
?>