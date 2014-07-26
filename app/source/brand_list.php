<?php
	require ROOT_PATH.'app/source/func/brand_func.php';
	
	$page = isset ( $_REQUEST ['p'] ) ? intval ( $_REQUEST ['p'] ) > 0 ? intval ( $_REQUEST ['p'] ) : 1 : 1;
	$id=intval($_REQUEST['id']);
	$cate_id=intval($_REQUEST['cid']);
	$brand_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."brand where id={$id}");
	
	if(!$brand_info)
	{
		header("Location:".a_u("Index/index"));
	}
	
	$kwd = $_REQUEST['keywords'];
	
	$extw ="";
	
	if(intval($_REQUEST['s_order'])==0)
		$order="order by sort desc,promote_end_time desc,id desc";
	elseif(intval($_REQUEST['s_order'])==1)
		$order="order by shop_price asc,sort desc,promote_end_time desc,id desc";
	elseif(intval($_REQUEST['s_order'])==2)
	{
		$oby= " (shop_price/market_price) asc ";
		$order="order by $oby,sort desc,promote_end_time desc,id desc";
	}
	
	$result = searchBrandGoods($page,$id,$cate_id,$kwd,$extw,$order);
	
	//分页
	$page = new Pager ( $result ['total'], a_fanweC ( "GOODS_PAGE_LISTROWS" )); //初始化分页对象 		
	$p = $page->show ();
	
	$GLOBALS ['tpl']->assign ( 'goods_list', $result ['list'] );
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$GLOBALS ['tpl']->assign ( 'goods_total', $result ['total'] );
	
	$brandgcate = getBrandGoodsCate($id);
	
	$GLOBALS ['tpl']->assign ( 'brandgcate', $brandgcate );
	
	$GLOBALS ['tpl']->assign ( 'brand_info', $brand_info );
	
	$data = array ('navs' => array (array ('name' =>$brand_info['name_1'], 'url' => '' ,"keyword" => $brand_info ['seokeyword_1'], "content" => $brand_info ['seocontent_1']) ) );
	assignSeo($data);
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$GLOBALS['tpl']->display("Page/brand_list.moban");
?>