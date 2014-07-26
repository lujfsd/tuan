<?php
$page = isset ( $_REQUEST ['p'] ) ? intval ( $_REQUEST ['p'] ) > 0 ? intval ( $_REQUEST ['p'] ) : 1 : 1;
$cate_id = 0;
$goods_id = 0;

if ($_REQUEST ['m'] == 'Goods' && $_REQUEST ['a'] == 'showcate') {
	$cate_id = intval ( $_REQUEST ['id'] ); //商品分类
	$GLOBALS ['tpl']->assign ( "cate_id", $cate_id );
}

if ($_REQUEST ['m'] == 'Goods' && $_REQUEST ['a'] == 'show') {
	$goods_id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : 0;
}

if ($_REQUEST ['m'] == 'Goods' && $_REQUEST ['a'] == 'showByUname') {
    $uname = $_REQUEST['uname'];
    if($uname!='')
    {
    	$uname = rawurldecode($uname);
		$sql ="select id from ".DB_PREFIX."goods where u_name='".$uname."'";
		$goods_id =intval($GLOBALS['db']->getOneCached($sql));    	
    }	
}
$qid= intval($_REQUEST['qid']);//商圈id
if($qid>0)
{
	//$where .= " and quan_id=".$qid;
	$GLOBALS ['tpl']->assign ( "qid", $qid );
	
}
$sc = $_REQUEST['sc'];//排序
	$GLOBALS['tpl']->assign('sc',$sc);
		
$gp = $_REQUEST['gp'];////价格区间筛选
	$GLOBALS['tpl']->assign('gp',$gp);


 //团购分类
      $sidegoodscatelist = getGoodsCate(" and pid=0 ");
	  $catepid = 0;//分类ID
	  $pid = 0;//顶级分类ID
	  $sub_cate_list = array();//次级分类
	  $is_top_cate = 0;//是否是顶级分类
	  if($_REQUEST['m'] =="Goods" && $_REQUEST['a'] == "showcate" && $_REQUEST['id']!="")
	  {
	  	 $catepid = $_REQUEST['id'];
		 foreach($sidegoodscatelist as $k => $v){
		    if($v['id']==$catepid)
			{
				$is_top_cate=1;
			}
		 }
		 
		 if($is_top_cate==0)
		 {
		 	 $sub_cate_list = getGoodsCate(" and pid=".$GLOBALS['db']->getOne("select pid from " . DB_PREFIX . "goods_cate where id=".$catepid));
		 	 foreach($sub_cate_list as $kk => $vv)
			 {
			 	if($vv['id']==$catepid)
				{
					$pid = $vv['pid'];
				}
			 }
		 }
		 else
		 {
		 	$sub_cate_list = getGoodsCate(" and pid=".$catepid);
		 	$pid = $_REQUEST['id'];
		 }
	  }
      $GLOBALS['tpl']->assign('sidegoodscatelist',$sidegoodscatelist);
	  $GLOBALS['tpl']->assign('catepid',$catepid);
	  $GLOBALS['tpl']->assign('sub_cate_list',$sub_cate_list);
	  $GLOBALS['tpl']->assign('is_top_cate',$is_top_cate);
	  $GLOBALS['tpl']->assign('pid',$pid);
//end团购分类   

//获得商圈
	$quan_list = getGoodsQuan(0,$cate_id,true,"pid=0 and");
	$quan_id = $qid;//商圈ID
	$top_pid = 0;//顶级商圈ID
	$sub_quan_list = array();//次级商圈
	$is_top_quan = 0;//是否是顶级商圈
	if($qid>0&&$_REQUEST ['a'] != 'show' && $_REQUEST ['a'] != 'showByUname')
	{
		foreach($quan_list as $k => $v)
		{
			if($v['id']==$quan_id)
				{
					$is_top_quan=1;
					break;
				}
		}
		if($is_top_quan==1)	
		{
			$sub_quan_list=getGoodsQuan(0,$cate_id,true,"pid=".$quan_id." and");
			//print_r($sub_quan_list);
			$top_pid = $quan_id;
		}
		else
		{
			$sub_quan_list = getGoodsQuan(0,$cate_id,true,"pid=".$GLOBALS['db']->getOne("select pid from " . DB_PREFIX . "coupon_region where id=".$quan_id)." and");
			//print_r($sub_quan_list);
			foreach($sub_quan_list as $kk => $vv)
				 {
				 	if($vv['id']==$quan_id)
					{
						$top_pid = $vv['pid'];
					}
				 }
		}
	}
	$GLOBALS['tpl']->assign("quan_list",$quan_list);
	$GLOBALS['tpl']->assign("sub_quan_list",$sub_quan_list);
	$GLOBALS['tpl']->assign("top_pid",$top_pid);
	$GLOBALS['tpl']->assign("quan_id",$quan_id);
//end商圈

	$preview = isset ( $_REQUEST ['preview'] ) ? ( bool ) $_REQUEST ['preview'] : false;
	$data = array ();
	
if ($_REQUEST ['a'] != 'show' && $_REQUEST ['a'] != 'showByUname') {
//排序
	if ($sc =="new")
			$order= "order by id desc ";
	elseif ($sc =="sell")
	        $order = "order by buy_count desc ";
	elseif ($sc =="price")
	        $order = "order by shop_price";
	elseif ($sc =="zhekou")
	        $order = "order by round((shop_price / market_price) * 10,2)";
	else
	        $order = "order by sort desc,promote_end_time desc,id desc ";
	        
//价格区间筛选
	if ($gp=="1")
		 {
			$where .= " and shop_price < '100' ";
		 }
	elseif($gp=="2")
		 {
		    $where .= " and shop_price between '100' and '200' ";
		 }
	elseif ($gp=="3")
		 {
		    $where .= " and shop_price between '200' and '300' ";
		 }
	elseif($gp=="5")
		 {
		    $where .= " and shop_price between '300' and '500' ";
		 }
	elseif($gp=="gt5")
		 {
		    $where .= " and shop_price > '500' ";
		 }
	if($gp !="" && $sc=="")
	{
		$order = "order by shop_price desc";
	}
		 
	//$goods_list = getTodayGoodsList ( $goods_id, $cate_id );
   	$result = searchGoodsList($page,$type_id=0,$is_score = 0, $is_advance = 0,$is_other=0, $cate_id,$suppliers_id = 0 ,$kwd ='',$where,$order,$qid);
   	$goods_list=$result['list'];  
   	$page = new Pager ( $result ['total'], a_fanweC("GOODS_LIST_NUM")); //初始化分页对象 		
	$p = $page->show ();
    $GLOBALS ['tpl']->assign ( 'pages', $p );
    
    //数据处理
    foreach($goods_list as $k=>$v)
		{
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($v['u_name']!='')
					$goods_list[$k]['url'] = a_u("g/".rawurlencode($v['u_name']));
				else
					$goods_list[$k]['url'] = a_u("tg/".$v['id']);

				$goods_list[$k]['ref_url'] = __ROOT__."/tg-".$v['id'].'-ru-'.intval($_SESSION['user_id']).'.html';
			}
			else{
				$goods_list[$k]['url'] = a_u("Goods/show",'id-'.$v['id']);
				$goods_list[$k]['ref_url'] = a_u("Goods/show","id-".$v['id']."|ru-".intval($_SESSION['user_id']));
			}

			$goods_list[$k]['short'] = a_msubstr($v['name_1'],0,a_fanweC("GOODS_SHORT_NAME"));
			$goods_list[$k]['update_time_format']  = a_toDate($v['update_time']);
			
			if($goods_list[$k]['complete_time'] > 0)
				$goods_list[$k]['complete_time_format'] = a_toDate($v['complete_time'],a_L('XY_TIMES_MOD_2'));
			else
				$goods_list[$k]['complete_time_format'] = "";
				
			$goods_list[$k]['promote_begin_time_format']  = a_toDate($v['promote_begin_time'],a_L('XY_TIMES_MOD_1'));
			$goods_list[$k]['promote_end_time_format']  = a_toDate($v['promote_end_time']);
			$goods_list[$k]['promote_price_format'] = a_formatPrice($v['promote_price']);
			$goods_list[$k]['complete_time_format'] = a_toDate($v['complete_time']);
			$goods_list[$k]['market_price_format'] = a_formatPrice(floatval($v['market_price']));
			$goods_list[$k]['shop_price_format'] = a_formatPrice(floatval($v['shop_price']));
			$goods_list[$k]['earnest_money_format'] = a_formatPrice(floatval($v['earnest_money']));
			if(floatval($v['market_price'])!=0)
			{
				if (a_fanweC('DEFAULT_LANG') == 'en-us')
				{
					$goods_list[$k]['discountfb'] = round((1-($v['shop_price'] / $v['market_price'])) * 100,1);
				}
				else
				{
					$goods_list[$k]['discountfb'] = round(($v['shop_price'] / $v['market_price']) * 10,2);
				}					
			}
			else
			{
				$goods_list[$k]['discountfb'] =0;
			}
			$goods_list[$k]['buy_url'] = a_u("Cart/index","id-".intval($v['id']));


			if(intval($goods_list[$k]['stock']) > 0)
			{
				$goods_list[$k]['surplusCount'] = intval($goods_list[$k]['stock']) - intval($goods_list[$k]['buy_count']);
				if($goods_list[$k]['surplusCount'] <= 0)
					$goods_list[$k]['is_none'] = true;
			}
			if(intval($goods_list[$k]['surplusCount'])!==0)
				$goods_list[$k]['stockbfb'] = ($goods_list[$k]['surplusCount'] / intval($v['stock'])) * 100;
			else
				$goods_list[$k]['stockbfb'] = 0;

			$goods_list[$k]['rest_count'] = $goods_list[$k]['group_user'] - $goods_list[$k]['buy_count'];
			$goods_list[$k]['save'] = a_formatPrice(floatval($v['market_price'] - $v['shop_price']));



			$goods_list[$k]['ref_urllink'] = a_getDomain().$goods_list[$k]['ref_url'];


			$sql = "select mail_title, mail_content from  ".DB_PREFIX."mail_template where name = 'share'";
			$mail = $GLOBALS['db']->getRowCached($sql);
			$mail['mail_title'] = str_replace('{$title}',$v['name_1'], $mail['mail_title']);
			$mail['mail_content'] = str_replace('{$title}',$v['name_1'], $mail['mail_content']);

			if (a_fanweC('DEFAULT_LANG') == 'en-us'){
				$goods_list[$k]['urlgbname'] = $mail['mail_title'];
				$goods_list[$k]['urlgbbody'] = $mail['mail_content'];
			}else{
				$goods_list[$k]['urlgbname'] = urlencode(a_utf8ToGB($mail['mail_title']));
				$goods_list[$k]['urlgbbody'] = urlencode(a_utf8ToGB($mail['mail_content']));
			}
			$goods_list[$k]['urllink'] = a_getDomain().$v['url'];
			$goods_list[$k]['urlweb'] = a_getDomain().$v['url'];
			$goods_list[$k]['urlname'] = urlencode($v['name_1']);
			$goods_list[$k]['urlbrief'] = urlencode($v['brief_1']);



			if($goods_list[$k]['is_group_fail']==1)
			{
				$goods_list[$k]['buy_count'] = $goods_list[$k]['fail_buy_count'];
			}

		}
//数据处理
	//if(($cate_id>0 || $qid>0 || $sc !='') && $goods_id==0)2010/7/5(chh)注消
	if($goods_id==0)
	{
		if(count ( $goods_list ) == 1)//当$goods_list只有一个商品的时候
		{
			$goods_id=$goods_list[0][id];
		}
		else
		{
			$goods_id=0;
		}
	}
}	
if (intval ( a_fanweC ( "VIEW_GOODS_LIST" ) ) == 1 && ((count ( $goods_list ) > 1 && $goods_id == 0) || $gp!="")) {
	$GLOBALS ['tpl']->assign ( "goods_list", $goods_list );
	$cate_info = $db->getRowCached ( "select * from " . DB_PREFIX . "goods_cate where id='{$cate_id}'" );
	
	if($_REQUEST['m'] =="Index" && $_REQUEST['a'] =="index")
		$data = array();
	else
		$data = array ('navs' => array (array ('name' => $cate_info ['name_1'], 'url' => a_u ( "Goods/showcate", "id-" . $cate_id ) ) ), "keyword" => $cate_info ['seokeyword_1'], "content" => $cate_info ['seocontent_1'] );
	
		$tpl->assign ( "cate_info", $cate_info );
} else {
	$goods = getGoodsItem ( $goods_id, C_CITY_ID, $preview, $cate_id, $qid, false );
	if($goods_id ==0 && $goods){
		foreach($goods_list as $k=>$v)
		{
			if(intval($v['id']) == $goods['id'])
			{
				unset($goods_list[$k]);
			}
		}
	}
	$GLOBALS ['tpl']->assign ( "today_list", getTodayGoodsList ( $goods_id, $cate_id ));
	$GLOBALS ['tpl']->assign ( "goods", $goods );
  	
	if($_REQUEST['m'] =="Index" && $_REQUEST['a'] =="index")
		$data = array();
	else
	{
		if($goods)
		{
			if(strtolower($_REQUEST ['m']) == 'goods' && $_REQUEST ['a'] == 'showcate')
			{
				$infos = $GLOBALS['db']->getRowCached("select `name_1`,`seokeyword_1`,`seocontent_1` from ".DB_PREFIX."goods_cate where id={$cate_id}");
				$data = array ('navs' => array (array ('name' => $goods ['name_1'], 'url' => '' ) ), "keyword" => $goods ['seokeyword_1'].$infos ['seokeyword_1'], "content" => $goods ['seocontent_1'].$infos ['seocontent_1'] );
			}
			else
			{
				if($goods['seo_title'])
					$data = array ('navs' => array (array ('name' => $goods ['seo_title'], 'url' => $goods ['url'] ) ), "keyword" => $goods ['seokeyword_1'], "content" => $goods ['seocontent_1'] );
				else
					$data = array ('navs' => array (array ('name' => $goods ['name_1'], 'url' => $goods ['url'] ) ), "keyword" => $goods ['seokeyword_1'], "content" => $goods ['seocontent_1'] );
			}
		}
		else
		{
			if(strtolower($_REQUEST ['m']) == 'goods' && $_REQUEST ['a'] == 'showcate')
			{
				$infos = $GLOBALS['db']->getRowCached("select `name_1`,`seokeyword_1`,`seocontent_1` from ".DB_PREFIX."goods_cate where id={$cate_id}");
				$data = array ('navs' => array (array ('name' => $infos ['name_1'], 'url' => '' ) ), "keyword" => $infos ['seokeyword_1'], "content" => $infos ['seocontent_1'] );
			}
			else
				$data = array();
		}
	}

}
assignSeo ( $data );
require ROOT_PATH.'app/source/func/vote_func.php';
//输出投票
$GLOBALS ['tpl']->assign ( "vote", getVote());
//输出主菜单
$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
//输出城市
$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
if(!$goods)
	$GLOBALS['tpl']->assign("city_list1",getGroupCityList(true));
//输出友情链接
//if(strtolower($_REQUEST["m"]) == 'index' && strtolower($_REQUEST["a"]) == 'index')
//	$GLOBALS['tpl']->assign("links",assignLink());
//输出帮助
$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
$GLOBALS ['tpl']->display ( 'Page/index.moban' );
?>