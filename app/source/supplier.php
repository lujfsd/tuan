<?php

	$ma= $_REQUEST ['m']."_".$_REQUEST ['a'];
	$ma();
	
	function Supplier_index()
	{
		$page = intval($_REQUEST['p']);
		if($page==0)
		    $page = 1;
	
		$cate_id = intval($_REQUEST['id']);
		$show_cate = intval($_REQUEST['sc']);
		
		//$cache_id = C_CITY_ID."_Supplier_index#".md5("c-{$cate_id}-sc{$show_cate}-p-{$page}");
		//if(!$GLOBALS['tpl']->is_cached("Page/supplier_index.moban",$cache_id)){
			//开始处理分页
			//$page_size = $page;
			//$page_count = a_fanweC("GOODS_PAGE_LISTROWS");

			$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".a_fanweC("PAGE_LISTROWS");
		
			if ($cate_id > 0){
				$sql = "SELECT a.*,b.tel,b.operating FROM ".DB_PREFIX."suppliers as a left outer join ".DB_PREFIX."suppliers_depart as b on b.supplier_id = a.id and b.is_main =1 where a.cate_id =".$cate_id ."  order by sort desc  ,id asc limit {$limit}";
			}else{
				$sql = "SELECT a.*,b.tel,b.operating FROM ".DB_PREFIX."suppliers as a left outer join ".DB_PREFIX."suppliers_depart as b on b.supplier_id = a.id and b.is_main =1  order by sort desc  ,id asc limit {$limit}";
			}
			
			$suppliers_list = $GLOBALS['db']->getAllCached($sql); //getAllCached
			
			if ($cate_id > 0)
				$sql = "SELECT  count(*) FROM ".DB_PREFIX."suppliers as a left outer join ".DB_PREFIX."suppliers_depart as b on b.supplier_id = a.id and b.is_main =1 where a.cate_id =".$cate_id;
			else
				$sql = "SELECT  count(*) FROM ".DB_PREFIX."suppliers as a left outer join ".DB_PREFIX."suppliers_depart as b on b.supplier_id = a.id and b.is_main =1";
			$total = $GLOBALS['db']->getOneCached($sql);
			//$total = count($suppliers_list);
			
			foreach($suppliers_list as $k=>$v)
			{
				$suppliers_list[$k]['url'] = a_u("Supplier/show","id-".$v['id']);
			}
					
			$GLOBALS['tpl']->assign('suppliers_list',$suppliers_list);
			//分页
			$page = new Pager($total,a_fanweC("PAGE_LISTROWS"));   //初始化分页对象 		
			$p  =  $page->show();
			
			$GLOBALS['tpl']->assign('pages',$p);
				
			if ($show_cate == 1){
				
				$sql = "select * from ".DB_PREFIX."suppliers_cate order by sort desc";
				$cate_list = $GLOBALS['db']->getAllCached($sql); //getAllCached
		        
		        foreach($cate_list as $k=>$v)
		        {
		        	if($show_cate==1)
		        	$cate_list[$k]['url'] = a_u($_REQUEST['m']."/".$_REQUEST['a'], "id-".$v['id']."|sc-1");
		        	else
		        	$cate_list[$k]['url'] = a_u($_REQUEST['m']."/".$_REQUEST['a'],"id-".$v['id']);
		        }
		        $GLOBALS['tpl']->assign("allurl", a_u($_REQUEST['m']."/".$_REQUEST['a'],"sc-1"));
		        $GLOBALS['tpl']->assign("cate_list",$cate_list);
		        $GLOBALS['tpl']->assign("cate_id",$cate_id);
		        $GLOBALS['tpl']->assign("show_cate",$show_cate);
			}
			//输出当前页seo内容
		    $data = array(
		    	'navs' => array(
		    		array(
						'name'=>a_L("HC_SUPPLIER_LIST"),
						'url' =>''
					)
		    	),
		    );
			assignSeo ( $data );
			
			//输出主菜单
			$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
			//输出城市
			$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
			//输出帮助
			$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
		//}
		$GLOBALS['tpl']->display ( "Page/supplier_index.moban",$cache_id);
	}
	function Supplier_show(){
		$id = intval ( $_REQUEST ['id'] );
		//初始化分页
		$page = intval ( $_REQUEST ["p"] );
		if ($page == 0)
			$page = 1;
		
		$supplier_info = getSupplierItem ( $id );
		if ($supplier_info) {
			$result = searchGoodsList ( $page, 1, -1, 0 ,0, 0 ,$supplier_info ['id'] );
		
			$GLOBALS['tpl']->assign ( "supplier_info", $supplier_info );
			
			//查询当前页商品数据
			$GLOBALS['tpl']->assign ( 'goods_list', $result ['list'] );
			
			//分页
			$page = new Pager ( $result ['goods_total'], a_fanweC ( "GOODS_PAGE_LISTROWS" ) ); //初始化分页对象 		
			$p = $page->show ();
			$GLOBALS['tpl']->assign ( 'pages', $p );
			//end 分页  
			
		
			//输出当前页seo内容
			$data = array ('navs' => array (array ('name' => $supplier_info ['name'], 'url' => '' ) ) );
			$GLOBALS['tpl']->assign ( "page_title", $supplier_info ['name'] );
			assignSeo ( $data );
			
			//开始输出 商户点评的百分比条数
			$h_pin = $GLOBALS['db']->getOne("select count(*) as countx from ".DB_PREFIX."message where rec_module='Suppliers' and rec_id=" . $supplier_info ['id'] . " and status = 1 and score = 3" );
			$z_pin = $GLOBALS['db']->getOne("select count(*) as countx from ".DB_PREFIX."message where rec_module='Suppliers' and rec_id=" . $supplier_info ['id'] . " and status = 1 and score = 2" );
			$c_pin = $GLOBALS['db']->getOne("select count(*) as countx from ".DB_PREFIX."message where rec_module='Suppliers' and rec_id=" . $supplier_info ['id'] . " and status = 1 and score = 1" );
			
			$full_count = $h_pin + $c_pin + $z_pin;
			if ($z_pin > $full_count) {
				$full_count = $z_pin;
			}
			if ($c_pin > $full_count) {
				$full_count = $c_pin;
			}
			if($h_pin)
				$h_percent = intval ( $h_pin / $full_count * 100 );
			else
				$h_percent = 0;
	
			if($z_pin)
				$z_percent = intval ( $z_pin / $full_count * 100 );
			else
				$z_percent=0;
				
			if($z_pin)
				$c_percent = intval ( $c_pin / $full_count * 100 );
			else
				$c_percent=0;
			
			$GLOBALS['tpl']->assign ( "h_percent", $h_percent );
			$GLOBALS['tpl']->assign ( "z_percent", $z_percent );
			$GLOBALS['tpl']->assign ( "c_percent", $c_percent );
			
			$GLOBALS['tpl']->assign ( "h_pin", $h_pin );
			$GLOBALS['tpl']->assign ( "z_pin", $z_pin );
			$GLOBALS['tpl']->assign ( "c_pin", $c_pin );
			
			
			//输出主菜单
			$GLOBALS['tpl']->assign("main_navs",assignNav(2));
			//输出城市
			$GLOBALS['tpl']->assign("city_list",getGroupCityList());
			//输出帮助
			$GLOBALS['tpl']->assign("help_center",assignHelp());
			
			$GLOBALS['tpl']->display ( "Page/supplier_show.moban" );
		} else {
			a_error ( a_L ( "NO_SUPPLIER" ) );
		}
	}
	
	function Supplier_comment()
	{
		//初始化分页
		$page = intval ( $_REQUEST ["p"] );
		if ($page == 0)
			$page = 1;
		$GLOBALS['tpl']->assign ( "page", $page );
		$supplier_id = intval ( $_REQUEST ['supplier_id'] );
		$score = intval ( $_REQUEST ['score'] ) == 0 ? 3 : intval ( $_REQUEST ['score'] );
		
		$supplier_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers where id=".$supplier_id );
		
		if ($supplier_info) {
			$GLOBALS['tpl']->assign ( "supplier_info", $supplier_info );
		} else {
			a_error ( a_L( "NO_SUPPLIER" ) );
		}
		
		//查询当前页商品数据
		$result = searchCommentList ( $page, $supplier_id, $score );
		
		$GLOBALS['tpl']->assign ( 'comment_list', $result ['list'] );
		
		//分页
		$page = new Pager ( $result ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象 		
		$p = $page->show ();
		$GLOBALS['tpl']->assign ( 'pages', $p );
		//end 分页  
		
		
		$GLOBALS['tpl']->assign ( "score", $score );
		
		//输出当前页seo内容
		$data = array ('navs' => array (array ('name' => a_L ( "HC_SUPPLIER_COMMENT_LIST" ), 'url' => '' ) ) );
		$GLOBALS['tpl']->assign ( "page_title", a_L ( "HC_SUPPLIER_COMMENT_LIST" ) );
		
		assignSeo ( $data );
		
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		
		$GLOBALS['tpl']->display ( "Page/supplier_comment.moban" );
	}
	
	function searchCommentList($page,$supplier_id,$score)
	{
		//开始处理分页
		$page_size = $page;
		$page_count = a_fanweC("PAGE_LISTROWS");
		$limit = ($page_size-1)*$page_count.",".$page_count;

		$result['list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."message where rec_module='Suppliers' and rec_id=".$supplier_id." and score=".$score." and status = 1 order by is_top desc,create_time desc limit {$limit}");
		$result['total'] = $GLOBALS['db']->getOne("select count(*) as countx from ".DB_PREFIX."message where rec_module='Suppliers' and rec_id=".$supplier_id." and score=".$score." and status = 1");
    	
		return $result;		
	}
?>