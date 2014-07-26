<?php
	function get_brand_cate($id=0)
	{
		$filename = md5("get_brand_cate".$id.C_CITY_ID).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			if($id!=0)
			{
				$ext = " and id = $id ";
			}
			$sql = "select *, name_1 as name from " . DB_PREFIX . "brand where 1=1 {$ext} order by sort desc";
			$cate_list = $GLOBALS ['db']->getAllCached ( $sql ); //getAllCached
			$now = a_gmtTime();
			
			foreach ($cate_list as $k=>$v)
			{
				$cate_list[$k]['endtime'] = $GLOBALS['db']->getOne("select g.promote_end_time from ".DB_PREFIX."goods as g LEFT JOIN ".DB_PREFIX."goods_cate as gc ON g.cate_id = gc.id where g.status = 1 and gc.is_brand<>0 and g.promote_begin_time <={$now} and g.promote_end_time>={$now} and g.brand_id={$v['id']} order by g.promote_end_time asc");
				$cate_list[$k]['url'] = a_u("Brand/list","id-{$v['id']}");
			}
			setCaches($filename,$cate_list,substr($filename,0,1)."/");
		}
		else{
			$cate_list = getCaches($filename,substr($filename,0,1)."/");
		}
		return $cate_list;
	}
	
	function get_adv_brand_cate($id=0)
	{
		$filename = md5("get_adv_brand_cate".$id.C_CITY_ID).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			if($id!=0)
			{
				$ext = " and b.id = $id ";
			}
			$now = a_gmtTime();
			$sql = "select b.*,g.promote_begin_time, b.name_1 as name from " . DB_PREFIX . "brand as b left join " . DB_PREFIX . "goods as g on g.brand_id= b.id where g.promote_begin_time >{$now} {$ext} group by b.id order by g.promote_begin_time asc,b.sort desc";
			$cate_list = $GLOBALS ['db']->getAll ( $sql ); //getAllCached
			
			foreach ($cate_list as $k=>$v)
			{
				$cate_list[$k]['starttime'] = a_toDate($v['promote_begin_time'],"Y-m-d");
				$cate_list[$k]['url'] = a_u("Brand/list","id-{$v['id']}");
			}
			setCaches($filename,$cate_list,substr($filename,0,1)."/");
		}
		else{
			$cate_list = getCaches($filename,substr($filename,0,1)."/");
		}
		return $cate_list;
	}
	
	function get_adv_time_list($limit = 30)
	{
		$now = a_gmtTime();
		for($i=1;$i<=$limit;$i++)
		{
			$idx = $i-1;
			$t = $now+ $i*24*3600;
			$advtilelist[$idx]['time'] = a_toDate($t,"Y-m-d");
			$advtilelist[$idx]['week'] = a_toDate($t,"l");
			$advtilelist[$idx]['week_short'] =a_msubstr($advtilelist[$idx]['week'],0,3);
			$advtilelist[$idx]['y'] =  a_toDate($t,"Y");
			$advtilelist[$idx]['m'] =  a_toDate($t,"m");
			$advtilelist[$idx]['d'] =  a_toDate($t,"d");
		}
		return $advtilelist;
	}
	
	function searchBrandGoods($page=1,$brand_id = 0 ,$cate_id = 0, $kwd ='',$extwhere='',$order='order by sort desc,promote_end_time desc,id desc'){
		$filename = md5($_REQUEST["m"].$_REQUEST["a"].$page.$brand_id.$cate_id.$kwd.$extwhere.$order.C_CITY_ID).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			$now = a_gmtTime();//取整，要做缓存一分钟
		
			$where = " status = 1 "; 
			$where .= " and (type_id = 0 or type_id = 1 or type_id = 3)";
			$where .= " and score_goods <> 1 ";
			$where .= " and promote_begin_time <= ".$now." and promote_end_time >= ".$now;
			if(intval($brand_id)>0)
				$where .=" and brand_id =".$brand_id;
		
			
			if ($cate_id > 0){
				$childCateUtil = new ChildIds("goods_cate");
				$cate_ids = $childCateUtil->getChildIds($cate_id);
				array_push($cate_ids,$cate_id);
				$where .= " and (cate_id in (".implode(",",$cate_ids).") or extend_cate_id in (".implode(",",$cate_ids)."))";
			}
			
			if($kwd!="")
			{
				$where .= " and ( `name_1` like '%".addslashes($kwd) ."%' or `web_reviews` like '%".addslashes($kwd) ."%') ";
			}
			
			if($extwhere!="")
			{
				$where .= $extwhere;
			}
			
			$childIdsUtil = new ChildIds("group_city");
		
			$city_ids = $childIdsUtil->getChildIds(C_CITY_ID);
			array_push($city_ids,C_CITY_ID);
			
			$where.=" and (city_id in (".implode(",",$city_ids).") or all_show = 1)";
			
			//开始处理分页
			$page_size = $page;
			$page_count = a_fanweC("GOODS_PAGE_LISTROWS");
			$limit = ($page_size-1)*$page_count.",".$page_count;	
			
			$sql = "SELECT `id`,`name_1`,`sn`,`cate_id`,`city_id`,`suppliers_id`,`score_goods`,`click_count`,`cost_price`,`shop_price`,`market_price`,`promote_price`,`promote_begin_time`,`promote_end_time`,`create_time`,`update_time`,`type_id`,`goods_type`,`stock`,`brief_1`,`brand_id`,`is_best`,".
				   "`is_hot`,`is_new`,`status`,`sort`,`seokeyword_1`,`seocontent_1`,`goods_desc_1`,`small_img`,`big_img`,`origin_img`,`define_small_img`,`is_define_small_img`,`is_inquiry`,`weight`,`spec_type`,`weight_unit`,`score`,`web_reviews`,`goods_reviews`,`min_user_time`,".
				   "`special_note`,`max_bought`,`is_group_fail`,`complete_time`,`buy_count`,`group_user`,`user_count`,`earnest_money`,`group_bond_end_time`,`expand1`,`expand2`,`expand3`,`expand4`,`u_name`,`referrals`,`close_referrals`,`goods_short_name`,`fail_buy_count`,`free_delivery_amount`,".
				   "`allow_combine_delivery`,`allow_sms`, (select count(*) from ".DB_PREFIX."message m where m.rec_module = 'Goods' and m.status = 1 and m.rec_id = a.id) as messageCount FROM ".
					DB_PREFIX."goods as a where 1 = 1 and ".$where." {$order} limit ".$limit;
			//echo $sql; exit;
			$goods_list = $GLOBALS['db']->getAll($sql); //getAllCached
			
			$sql = "SELECT count(*) FROM ".DB_PREFIX."goods as a where 1 = 1 and ".$where;
			$result['total'] = $GLOBALS['db']->getOne($sql);
			
			foreach($goods_list as $k => $v)
			{
				$goods_list[$k]['url'] = a_u("Brand/show","id-".$v['id']);
				$goods_list[$k]['save'] = a_formatPrice($v['market_price']-$v['shop_price']);
				$goods_list[$k]['name'] = $v['name_1'];
				$goods_list[$k]['market_price'] = floatval($v['market_price']);
				$goods_list[$k]['shop_price'] = floatval($v['shop_price']);
				$goods_list[$k]['earnest_money'] = floatval($v['earnest_money']);
				$goods_list[$k]['market_price_format'] = a_formatPrice(floatval($v['market_price']));
				$goods_list[$k]['shop_price_format'] = a_formatPrice(floatval($v['shop_price']));
				if(floatval($v['shop_price'])>0)
					$goods_list[$k]['discountfb'] = round(($v['shop_price'] / $v['market_price']) * 10,2);
				else
					$goods_list[$k]['discountfb'] = 0;
					
				if($v['small_img']=='')
					$goods_list[$k]['small_img'] = a_fanweC("NO_PIC");
				if($v['big_img']=='')
					$goods_list[$k]['big_img'] = a_fanweC("NO_PIC");
				if($v['origin_img']=='')
					$goods_list[$k]['origin_img'] = a_fanweC("NO_PIC");		
			}
			
			$result['list'] = $goods_list;
			setCaches($filename,$result,substr($filename,0,1)."/");
		}
		else{
			$result = getCaches($filename,substr($filename,0,1)."/");
		}
		return $result;
	}
	
	function getBrandGoodsCate($id=0,$cid=0)
	{
		$filename = md5("getBrandGoodsCate".$id.$cid.C_CITY_ID).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			if($cid >0)
			{
				$ext = " and gc.pid = {$cid} ";
			}
			$now= a_gmtTime();
			$rs = $GLOBALS['db']->getAll("select gc.* from ".DB_PREFIX."goods as g LEFT JOIN ".DB_PREFIX."goods_cate as gc ON g.cate_id = gc.id where g.status = 1 and gc.is_brand<>0 and g.promote_begin_time <={$now} and g.promote_end_time>={$now} and g.brand_id={$id} $ext group by gc.id order by g.promote_end_time asc");
			foreach($rs as $k=>$v)
			{
				$rs[$k]['url'] = a_u("Brand/list","id-".$id."|cid-".$v['id']);
				$rs[$k]['list'] = getBrandGoodsCate($id,$v['id']);
			}
			setCaches($filename,$rs,substr($filename,0,1)."/");
		}
		else{
			$rs = getCaches($filename,substr($filename,0,1)."/");
		}
		return $rs;
	}
	
	function getTodayBrandGoodsList($id,$brand_id)
	{
		$filename = md5("getTodayBrandGoodsList".$id.$brand_id.C_CITY_ID).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			$id = intval($id);
			if(intval ( a_fanweC ( "VIEW_GOODS_LIST" ) ) == 1 && $id ==0)
				$limit = a_fanweC ( "GOODS_LIST_NUM" );
			else
				$limit = a_fanweC ( "TODAY_OTHER_GROUP" );			
	
				
			$limit = intval($limit) > 0 ? intval($limit) : 8;
			//0:普通商品;1:积分商品;2:抽奖商品; add by chenfq 2011-01-05
			$where = 'status = 1 and score_goods <> 1';
				
			if($id != "0")
				 $where .= " and id not in (".$id.")";
				 
			if ($brand_id > 0)
				$where .= " and brand_id =".$brand_id;
				
			$now = a_gmtTime();	
			$where .= " and ((promote_begin_time <=".$now." and promote_end_time>=".$now.") or (is_preview=1 and promote_end_time>=".$now."))";
			
			$childIdsUtil = new ChildIds("group_city");
			$city_ids = $childIdsUtil->getChildIds(C_CITY_ID);
			array_push($city_ids,C_CITY_ID);			
			$where .= " and (city_id in (".implode(",",$city_ids).") or all_show = 1)";
	
			$sort = 'sort desc,id desc';
			$list =  getGoodsList($where,$limit,$sort);
			setCaches($filename,$list,substr($filename,0,1)."/");
		}
		else{
			$list = getCaches($filename,substr($filename,0,1)."/");
		}
		return $list;
	}
?>