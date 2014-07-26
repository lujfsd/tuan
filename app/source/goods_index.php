<?php
	$page = isset ( $_REQUEST ['p'] ) ? intval ( $_REQUEST ['p'] ) > 0 ? intval ( $_REQUEST ['p'] ) : 1 : 1;
	if(intval(a_fanweC("OPEN_PY_ROUTE"))===1)
		$cate_id = $GLOBALS['db']->getOneCached("select id from ".DB_PREFIX."goods_cate where py ='{$_REQUEST ['py']}'");
	else
		$cate_id = intval ( $_REQUEST ['id'] );
	
	$is_other = intval ( $_REQUEST ['is_other'] );
	$is_score = intval ( $_REQUEST ['is_score'] ); //0:非积分商品；1:积分商品
	$type_id = intval ( $_REQUEST ['type_id'] ); //2:线下团购 
	$is_advance = intval ( $_REQUEST ['is_advance'] ); //1:团购预告
	$suppliers_id = intval ( $_REQUEST ['suppliers_id'] );
	
	$keywords = isset($_REQUEST ['keywords']) ? $_REQUEST['keywords'] : "";
	$key_begin_time = isset($_REQUEST ['key_begin_time']) ? a_strtotime($_REQUEST['key_begin_time']) : "";
	$key_end_time = isset($_REQUEST ['key_end_time']) ? a_strtotime($_REQUEST['key_end_time']) : "";
	
	if($key_begin_time && $key_end_time)
	{
		$extwhere =" and promote_begin_time between $key_begin_time and $key_end_time ";
	}
	elseif($key_begin_time){
		$extwhere = " and promote_begin_time >= $key_begin_time ";
	}
	elseif($key_end_time )
	{
		$extwhere = " and promote_begin_time <= $key_end_time ";
	}
	
	
	//die();
	$result = searchGoodsList ( $page, $type_id, $is_score, $is_advance, $is_other, $cate_id, $suppliers_id , $keywords , $extwhere);
	//分页
	$page = new Pager ( $result ['total'], a_fanweC ( "GOODS_PAGE_LISTROWS" )); //初始化分页对象 		
	$p = $page->show ();
	
	$data = array ();
	if ($is_advance == 0) {
		if ($type_id == 2) {
			$inc_page = "goods_belowline_list";
			$tpl->assign ( 'info_title', $GLOBALS['Ln']['HC_BELOW_GROUPON'] );
			$data = array ('navs' => array (array ('name' => $GLOBALS['Ln']['HC_BELOW_GROUPON'], 'url' => '' ) ) );
		} else {
			if ($is_score == 0) {
				$inc_page = "goods_index_list";
				if ($is_other == 0) {
					$page_title = $GLOBALS['Ln']['XY_TODAY_OTHER'];
					$tpl->assign ( 'info_title', $GLOBALS['Ln']['XY_TODAY_OTHER'] );
				} else {
					$goods_list = getTodayGoodsList (-1, $cate_id );
					$tpl->assign ( 'today_list', $goods_list);
					$page_title = $GLOBALS['Ln']['JJ_BEFORE_GB'];
					$tpl->assign ( 'info_title', $GLOBALS['Ln']['JJ_BEFORE_GB'] );
				}
				$data = array ('navs' => array (array ('name' => $page_title, 'url' => '' ) ) );
			} else {
				$inc_page = "goods_score_list";
				$tpl->assign ( 'info_title', $GLOBALS['Ln']['SCORE_GOODS'] );
				$data = array ('navs' => array (array ('name' => $GLOBALS['Ln']['SCORE_GOODS'], 'url' => '' ) ) );
			}
		}
	} else {
		$inc_page = "goods_advance_list";
		$tpl->assign ( 'info_title', $GLOBALS['Ln']['HC_GROUPON_FORENOTICE'] );
		$data = array ('navs' => array (array ('name' => $GLOBALS['Ln']['HC_GROUPON_FORENOTICE'], 'url' => '' ) ) );
	}
	
	$show_cate = isset ( $_REQUEST ['sc'] ) ? intval ( $_REQUEST ['sc'] ) : 0;
	if ($show_cate == 1) {
		$cate_list = getGoodsCate();
		foreach ( $cate_list as $k => $v ) {
			if ($show_cate == 1)
			{
				if(intval(a_fanweC("OPEN_PY_ROUTE"))===1)
				{
					$ma = strtolower($_REQUEST ['m']."_".$_REQUEST ['a']);
					switch ($ma)
					{
						case  "goods_index":
							$cate_list [$k] ['url'] = a_u("gi/". $v ['py'],"sc-1");
							break;
						case  "goods_other":
							$cate_list [$k] ['url'] = a_u("go/". $v ['py'],"sc-1");
							break;
						case  "advance_index":
							$cate_list [$k] ['url'] = a_u("ai/". $v ['py'],"sc-1");
							break;
					}
				}
				else
					$cate_list [$k] ['url'] = a_u ( $_REQUEST ['m'] . "/" . $_REQUEST ['a'], "id-" . $v ['id'] . "|sc-1" );
			
			}
			else
			{
				if(intval(a_fanweC("OPEN_PY_ROUTE"))===1)
				{
					$ma = strtolower($_REQUEST ['m']."_".$_REQUEST ['a']);
					switch ($ma)
					{
						case  "goods_index":
							$cate_list [$k] ['url'] = a_u("gi/". $v ['py']);
							break;
						case  "goods_other":
							$cate_list [$k] ['url'] = a_u("go/". $v ['py']);
							break;
						case  "advance_index":
							$cate_list [$k] ['url'] = a_u("ai/". $v ['py']);
							break;
					}
				}
				else
					$cate_list [$k] ['url'] = a_u ( $_REQUEST ['m'] . "/" . $_REQUEST ['a'], "id-" . $v ['id'] );
			}
		}
		$GLOBALS ['tpl']->assign ( "allurl", a_u ( $_REQUEST ['m'] . "/" . $_REQUEST ['a'], "sc-1" ) );
		$GLOBALS ['tpl']->assign ( "cate_list", $cate_list );
		$GLOBALS ['tpl']->assign ( "cate_id", $cate_id );
		$GLOBALS ['tpl']->assign ( "show_cate", $show_cate );
	}
	assignSeo ( $data );
	$GLOBALS ['tpl']->assign ( 'goods_list', $result ['list'] );
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$GLOBALS ['tpl']->assign ( 'inc_page', $inc_page );
	
	unset($result,$show_cate,$data);
	//输出函数
	$GLOBALS ['tpl']->assign ( "keywords", $keywords );
	$GLOBALS ['tpl']->assign ( "key_begin_time", $key_begin_time );
	$GLOBALS ['tpl']->assign ( "key_end_time", $key_end_time );
	$GLOBALS ['tpl']->assign ( "rec_module", $_REQUEST['m'] );
	$GLOBALS ['tpl']->assign ( "rec_action", $_REQUEST['a'] );
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$tpl->display ( "Page/goods_index.moban" );
?>