<?php
	$goods = getGoodsItem(0,C_CITY_ID);
	if($goods)
	{
		$goods['less_day'] = floor(($goods['promote_end_time'] - a_gmtTime())/(3600*24));
		$goods['less_hour'] = floor(($goods['promote_end_time'] - a_gmtTime())%(3600*24)/3600);
		$goods['less_min'] = floor((($goods['promote_end_time'] - a_gmtTime())%(3600*24)%3600)/60);
		$goods['less_sec'] = (((($goods['promote_end_time'] - a_gmtTime())%(3600*24))%3600)%60);
	}
    $tpl->assign("today_goods",$goods);
    $tpl->display("Page/index_index.html");
?>