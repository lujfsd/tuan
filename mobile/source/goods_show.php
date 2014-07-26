<?php
	if($goods_id == 0)
    	$goods_id = intval($_REQUEST['id']);
    	
    if(check_ip_operation($_SESSION['CLIENT_IP'],"Goods",1200,$goods_id))
    	$GLOBALS['db']->query("update ".DB_PREFIX."goods  set click_count=click_count+1 where id='{$goods_id}' and status=1");   		

   $goods_item  = getGoodsItem($goods_id, 0);
   	if($goods_item)
	{
		$goods_item['less_day'] = floor(($goods_item['promote_end_time'] - a_gmtTime())/(3600*24));
		$goods_item['less_hour'] = floor(($goods_item['promote_end_time'] - a_gmtTime())%(3600*24)/3600);
		$goods_item['less_min'] = floor((($goods_item['promote_end_time'] - a_gmtTime())%(3600*24)%3600)/60);
		$goods_item['less_sec'] = (((($goods_item['promote_end_time'] - a_gmtTime())%(3600*24))%3600)%60);
	}		
   $tpl->assign("today_goods",$goods_item);    	
   $tpl->display("Page/goods_show.html");
?>