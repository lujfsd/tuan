<?php
	$goods_id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : 0;
	if($goods_id==0)
	{
		header("Location:".a_u("Index/index"));
		exit();
	}
	$preview = isset ( $_REQUEST ['preview'] ) ? ( bool ) $_REQUEST ['preview'] : false;
	$goods = getGoodsItem ( $goods_id, C_CITY_ID, $preview, 0, false );
	if(!$goods)
	{
		header("Location:".a_u("Index/index"));
		exit();
	}
	require ROOT_PATH.'app/source/func/brand_func.php';
	$brand_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."brand where id={$goods['brand_id']}");
	$GLOBALS ['tpl']->assign ( "brand_info", $brand_info );
	
	$todaygoodslist = getTodayBrandGoodsList($goods_id,$goods['brand_id']);
	
	$GLOBALS ['tpl']->assign ( "todaygoods",$todaygoodslist);
	
	$data = array ('navs' => array (array ('name' => $goods ['name_1'], 'url' => $goods ['url'] ) ), "keyword" => $goods ['seokeyword_1'], "content" => $goods ['seocontent_1'] );
	assignSeo ( $data );

	$GLOBALS ['tpl']->assign ( "goods", $goods );
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$GLOBALS ['tpl']->display ( 'Page/brand_show.moban' );
?>