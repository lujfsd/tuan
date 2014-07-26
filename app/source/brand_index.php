<?php
	require ROOT_PATH.'app/source/func/brand_func.php';
	
	$list = get_brand_cate();
	
	foreach ($list as $k=>$v)
	{
		if(intval($v['endtime'])==0)
		{
			unset($list[$k]);
		}
	}
	
	
	$GLOBALS ['tpl']->assign ( "list",$list);
	
	$advbrandlist = get_adv_brand_cate();
	
	$GLOBALS ['tpl']->assign ( "advbrandlist",$advbrandlist);
	
	$data = array ('navs' => array (array ('name' =>"品牌折扣", 'url' => '' ) ) );
	assignSeo ( $data );
	
	
	$advtimelist = get_adv_time_list();
	
	$GLOBALS ['tpl']->assign ( "advtimelist",$advtimelist);
	
	$GLOBALS ['tpl']->assign ( "page_title", "品牌折扣" );
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$tpl->display ( "Page/brand_index.moban" );
?>