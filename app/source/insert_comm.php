<?php
/**
 * 输出会员信息
 *
 * @return 会员信息
 */
function insert_member_info() {
	return $GLOBALS ['tpl']->fetch ( "Inc/common/member_info.moban" );
}

function insert_UserMaill() {
	return $_SESSION ['user_email'];
}

/**
 * 输出留言信息
 *
 * @param 数组 $arr
 * @return 信息
 */
function insert_getMessageList($arr) {
	$filename = md5(implode(".",$arr).C_CITY_ID).".php";
	if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,60)){
		$limit = ! empty ( $arr ["limit"] ) ? intval ( $arr ["limit"] ) : 3;
		if(intval(a_fanweC("MSG_ALL_CITY_VIEW")) == 1)
		{
			if(intval(C_CITY_ID) > 0)
			{
				$childIdsUtil = new ChildIds("group_city");
				$city_ids = $childIdsUtil->getChildIds(C_CITY_ID);
				array_push($city_ids,C_CITY_ID);			
				$where.=" and (city_id in (".implode(",",$city_ids).") or city_id=0 or city_id ='')";
			}
		}
		$sql = "SELECT id,content,create_time FROM " . DB_PREFIX . "message WHERE rec_module='Message' AND rec_id = 0 AND pid = 0 AND reply_type = 0 AND status = 1 {$where} order by is_top desc,create_time desc LIMIT 0, {$limit}";
		$return['messages'] = $GLOBALS ['db']->getAll( $sql );
		$sql = "SELECT count(*) FROM " . DB_PREFIX . "message WHERE rec_module='Message' AND rec_id = 0 AND pid = 0 AND reply_type = 0 AND status = 1 {$where}";
		$return['count'] = intval ( $GLOBALS ['db']->getOne( $sql ) );
		setCaches($filename,$return,substr($filename,0,1)."/");
	}
	else{
		$return  = getCaches($filename,substr($filename,0,1)."/");
	}
	$GLOBALS ['tpl']->assign ( 'messageUrl', a_U ( 'Message/index' ) );
	$GLOBALS ['tpl']->assign ( 'message_list', $return['messages'] );
	$GLOBALS ['tpl']->assign ( 'message_count', $return['count'] );
	return $GLOBALS ['tpl']->fetch ( "Inc/common/right_msg_list.moban" );
}
/**
 * 用户是否支付
 *
 * @param 参数 $arr
 * @return unknown
 */
function insert_getTooltipStatus($arr) {
	if (($_REQUEST ['m'] == "Index" && $_REQUEST ['a'] == "index") || ($_REQUEST ['m'] == "Goods" && $_REQUEST ['a'] == "show")) {
		if (intval ( $_SESSION ['user_id'] ) > 0) {
			$id = intval ( $arr ['id'] );
			
			$sql = "select o.id from " . DB_PREFIX . "order as o left join " . DB_PREFIX . "order_goods as og on og.order_id = o.id left join " . DB_PREFIX . "goods as g on g.id = og.rec_id where g.id = $id and o.money_status < 2 and o.status<>2 and o.user_id = '" . $_SESSION ['user_id'] . "' and g.is_group_fail <> 1 and g.promote_end_time >= " . a_gmtTime () . " and o.id is not null group by o.id order by o.create_time desc,o.update_time desc LIMIT 1";
			
			$orderID = intval ( $GLOBALS ['db']->getOne ( $sql ) );
			
			$sql = "select id,is_lookat from " . DB_PREFIX . "group_bond where goods_id=$id and is_valid = 1 and user_id = '" . $_SESSION ['user_id'] . "' GROUP BY goods_id HAVING is_lookat = 0 ORDER BY id desc LIMIT 0,1";
			
			if ($orderID > 0) {
				$GLOBALS ['tpl']->assign ( 'orderCheckUrl', a_U ( "Order/check", "id-" . $orderID ) );
				;
				$GLOBALS ['tpl']->assign ( 'orderID', $orderID );
			}
			
			$groupBondID = intval ( $GLOBALS ['db']->getOne ( $sql ) );
			
			if ($groupBondID > 0) {
				$GROUPBOTH = a_fanweC ( "GROUPBOTH" );
				$GLOBALS ['tpl']->assign ( 'GROUPBOTH', $GROUPBOTH );
				$GLOBALS ['tpl']->assign ( 'groupBondUrl', a_u ( "UcGroupBond/printbond", "id-" . $groupBondID ) );
				$GLOBALS ['tpl']->assign ( 'groupBondID', $groupBondID );
			}
			
			//增加by hc, 当订单未付款时，且当前团购卖光时不再提示
			$goods_id = $GLOBALS ['db']->getOne ( "select rec_id from " . DB_PREFIX . "order_goods where order_id=" . $orderID );
			$goods_info = $GLOBALS ['db']->getRow ( "select `id`,`name_1`,`sn`,`cate_id`,`city_id`,`suppliers_id`,`click_count`,`cost_price`,`shop_price`,`market_price`,`promote_price`,`promote_begin_time`,`promote_end_time`,`create_time`,`update_time`,`type_id`,`goods_type`,`stock`,`brief_1`,`brand_id`,`is_best`,`is_hot`,`is_new`,`status`,`sort`,`seokeyword_1`,`seocontent_1`,`goods_desc_1`,`small_img`,`big_img`,`origin_img`,`define_small_img`,`is_define_small_img`,`is_inquiry`,`weight`,`spec_type`,`weight_unit`,`score`,`web_reviews`,`goods_reviews`,`min_user_time`,`special_note`,`max_bought`,`is_group_fail`,`complete_time`,`buy_count`,`group_user`,`user_count`,`earnest_money`,`group_bond_end_time`,`expand1`,`expand2`,`expand3`,`expand4`,`u_name`,`referrals`,`close_referrals`,`goods_short_name`,`fail_buy_count`,`free_delivery_amount`,`allow_combine_delivery`,`allow_sms` from " . DB_PREFIX . "goods where id =" . intval ( $goods_id ) );
			
			//增加by awfigq, 当库存设置为0时显示
			if (($groupBondID > 0 || $orderID > 0) && ($goods_info ['stock'] == 0 || $goods_info ['buy_count'] < $goods_info ['stock']))
				return $GLOBALS ['tpl']->fetch ( 'Inc/others/head_tooltip.moban' );
		}
	}
}
/**
 * 获取商品状态提示
 *
 * @param unknown_type $arr
 * @return unknown
 */
function insert_getGoodsStatus($arr) {
	if ((($_REQUEST ['m'] == "Index" && $_REQUEST ['a'] == "index") || ($_REQUEST ['m'] == "Goods" && $_REQUEST ['a'] == "show")) && intval ( $arr ['id'] ) > 0) {
		$id = intval ( $arr ['id'] );
		$sql = "SELECT `id`,`stock`,`buy_count`,`promote_end_time` FROM " . DB_PREFIX . "goods WHERE id={$id}";
		
		$goods = $GLOBALS ['db']->getRow ( $sql );
		
		if (intval ( $goods ['promote_end_time'] ) < a_gmtTime ())
			$goods ['is_end'] = true;
		
		if (intval ( $goods ['stock'] ) > 0) {
			$surplusCount = intval ( $goods ['stock'] ) - intval ( $goods ['buy_count'] );
			if ($surplusCount <= 0)
				$goods ['is_none'] = true;
		}
		$GLOBALS ['tpl']->assign ( 'cityid', C_CITY_ID );
		$GLOBALS ['tpl']->assign ( 'goods', $goods );
		
		return $GLOBALS ['tpl']->fetch ( 'Inc/goods/goods_tooltip.moban' );
	}
	return "";

}

function insert_Cart_Info(){
	$goods_num = 0;
	$totalprice = 0 ;
	if(session_id())
	{
		$sql = "SELECT sum(`number`) AS tp_count, sum(`data_total_price`) as totalprice FROM `".DB_PREFIX."cart` WHERE session_id = '".session_id()."' ";
		$res = $GLOBALS['db']->getRow($sql);
		$goods_num = intval($res['tp_count']);
		$totalprice = a_formatPrice($res['totalprice']);
	}
	
	$GLOBALS['tpl']->assign("CARTNUM",$goods_num);
	$GLOBALS['tpl']->assign("CARTTOTAL",$totalprice);
	return $GLOBALS['tpl']->fetch("Inc/cart/cartinfo.moban");
}

/**
 * 广告布局列表
 *
 * @param 页面传入的数组 $arr
 * @return unknown
 */
function insert_advLayout($arr) {
	$filename = md5($_REQUEST["m"].$_REQUEST["a"].md5(implode("",$arr)).C_CITY_ID).".php";
	if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
		$layout_id = $arr ['id'];
		$file = $arr ['file'];
		$tmpl = $GLOBALS ['langItem'] ['tmpl'];
		$page = strtolower ( $_REQUEST ['m'] ) . "_" . strtolower ( $_REQUEST ['a'] );
		if($page == 'goods_showcate')
			$layinfo = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "layout where `tmpl`='{$tmpl}' and (`page`= '{$page}' or `page`= '') and (cate_id=".intval($_REQUEST['id'])." or cate_id='' or cate_id=0) and layout_id='{$layout_id}' order by id desc" );
		else
			$layinfo = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "layout where `tmpl`='{$tmpl}' and (`page`= '{$page}' or `page`= '') and layout_id='{$layout_id}' order by id desc" );
	
		if(!$layinfo)
		{
			return '';
		}
		
		$layout_item = "";
		foreach ( $layinfo as $l_item ) {
			$arr = explode ( ",", $l_item ['target_id'] );
			if (in_array ( C_CITY_ID, $arr )||$l_item ['target_id']==''||$l_item ['target_id']==0) {
				$layout_item = $l_item;
				break;
			}
		}
		
		if (! $layout_item) {
			if($page == 'goods_showcate')
				$layout_item = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "layout where `tmpl`='{$tmpl}' and (`page`= '{$page}' or `page`= '') and cate_id=".intval($_REQUEST['id'])." and layout_id='{$layout_id}' and target_id='' order by id desc" );
			else
				$layout_item = $GLOBALS ['db']->getRow( "select * from " . DB_PREFIX . "layout where `tmpl`='{$tmpl}' and (`page`= '{$page}' or `page`= '') and layout_id='{$layout_id}' and target_id='' order by id desc" );
		}
		
		$rec_id = $layout_item ['rec_id']; //操作的ID
		
		$item_limit = intval ( $layout_item ['item_limit'] ) > 0 ? intval ( $layout_item ['item_limit'] ) : 0 ; //元素个数   
		
		$target_id_str = $layout_item ['target_id'];
		
		
		$parseStr = "";
		if ($target_id_str != '') {
			$target_id = explode(",",$target_id_str);	
			if(!in_array(C_CITY_ID,$target_id))	
			{
				break;
			}	
		}
		
		$ap = showAdvPosition ( $rec_id, $item_limit);
		if ($ap ['is_flash'] == 1 && ! empty ( $ap ['flash_style'] )) {
			$adv_path =  CND_URL."/Public/adflash/" . $ap ['flash_style'] . ".swf";
			$adv_pics = "";
			$adv_texts = "";
			$adv_links = "";
			
			
			foreach ( $ap ['adv_list'] as $adv ) {
				if (empty ( $adv_pics ))
					$jg = "";
				else
					$jg = "|";
				
				$adv_pics .= $jg . __ROOT__ . $adv ['code'];
				$adv_texts .= $jg . $adv ['desc'];
				$adv_links .= $jg . $adv ['url'];
			}
			unset ( $ap ['adv_list'] );
			$GLOBALS['tpl']->assign("adv_position",$ap);
			$GLOBALS['tpl']->assign("adv_path",$adv_path);
			$GLOBALS['tpl']->assign("adv_pics",$adv_pics);
			$GLOBALS['tpl']->assign("adv_links", $adv_links);
			$GLOBALS['tpl']->assign("adv_texts", $adv_texts);
			return $GLOBALS['tpl']->fetch("str:".$ap ['style']);
		
		} else {
			$ap_adv_list = $ap ['adv_list'];
		}
		if($ap_adv_list)
		{
			$GLOBALS['tpl']->assign("adv_list",$ap_adv_list);
			if ($file)
			{
				$parseStr = $GLOBALS['tpl']->fetch($file);
			}
			else
				$parseStr = $GLOBALS['tpl']->fetch("str:".$ap ['style']);
		}
		setCaches($filename,$parseStr,substr($filename,0,1)."/");
	}
	else{
		$parseStr = getCaches($filename,substr($filename,0,1)."/");
	}
	return $parseStr;
}

/**
 * 显示指定ID的广告位
 */
function showAdvPosition($pid = 0,$num = 1)
{
	if(!$pid)
	{
		return '';
	}
	$ap = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."adv_position where id=".$pid);
	
	$where = " and status = 1 and position_id=".$pid;
	$where .= " and ((adv_start_time <='".a_gmtTime()."' and adv_end_time >='".a_gmtTime()."') or (adv_start_time =0 and adv_end_time = 0 ) or (adv_start_time <='".a_gmtTime()."' and adv_end_time = 0 ) or (adv_start_time =0 and adv_end_time >='".a_gmtTime()."' ))";
	
	//2011-10-03 去掉缓存 chenfq 有时间参数，不能使用数据缓存
	if($num == 0)
		$adv_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."adv where 1=1 $where order by sort desc,id asc");
	else
		$adv_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."adv where 1=1 $where order by sort desc,id asc limit 0,{$num}");
	
	foreach($adv_list as $key => $adv)
	{
		$adv_list[$key]['html']= getAdvHTML($adv,$ap);
		$adv_list[$key]['url'] = urlencode(a_u("Adv/show","id-".$adv['id']));
	}
	
	$ap['adv_list'] = $adv_list;
	
	return $ap;
}

function getAdvHTML($adv,$ap)
{
	if($adv['width'] > 0)
		$ap['width'] = $adv['width'];
		
	if($adv['height'] > 0)
		$ap['height'] = $adv['height'];
		
	if($ap['width'] == 0)
		$ap['width']="";
	else
		$ap['width']=" width='".$ap['width']."'";
		
	if($ap['height'] == 0)
		$ap['height']="";
	else
		$ap['height']=" height='".$ap['height']."'";
		
	switch($adv['type'])
	{
		case '1':
			if($adv['url']=='')
				$adv_str = "<img src='".CND_URL."/".$adv['code']."'".$ap['width'].$ap['height']."/>";
			elseif(intval($adv['is_vote']) ==1)
				$adv_str = "<a href='".$adv['url']."' target='_blank' title='".$adv['desc']."'><img src='".__ROOT__.$adv['code']."'".$ap['width'].$ap['height']."/></a>";
			else
				$adv_str = "<a href='".a_u("Adv/show","id-".$adv['id'])."' target='_blank' title='".$adv['desc']."'><img src='".__ROOT__.$adv['code']."'".$ap['width'].$ap['height']."/></a>";
			break;
		case '2':
			$adv_str = "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0'".$ap['width'].$ap['height'].">".
					   "<param name='movie' value='".CND_URL."/".$adv['code']."' />".
    				   "<param name='quality' value='high' />".
					   "<param name='menu' value='false' />".
					   "<embed src='".CND_URL."/".$adv['code']."' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'".$ap['width'].$ap['height']."></embed>".
					   "</object>";
			break;
		case '3':
			$adv_str = $adv['code'];
			break;
	}
	return $adv_str;
}
?>