<?php
$ma = $_REQUEST['m']."_". strtolower ( $_REQUEST ['a'] );
$ma();
function referrals_index()
{
	//输出当前页seo内容
	$uid=intval($_SESSION['user_id']);
	$data = array ('navs' => array (array ('name' => a_L("HC_INVITE_FRIEND"), 'url' => a_u ( "Referrals/Index" ) ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$goodsID =intval($_REQUEST['id']);
	$time = a_gmtTime ();
	if ($goodsID == 0) {
		$where = " status = 1 AND promote_begin_time <= $time AND promote_end_time >= $time ";
		
		if (C_CITY_ID == 0) {
			$sql = "select id from " . DB_PREFIX . "group_city where status = 1 order by is_defalut desc,id asc limit 1";
			$cityID = $GLOBALS ['db']->getOneCacehd ( $sql );
			$where .= " AND city_id = $cityID";
		} else {
			$where .= " AND city_id = ".C_CITY_ID;
		}
		$item = $GLOBALS ['db']->getRow ( "select name_1,small_img,goods_short_name,u_name,id,brief_1 from " . DB_PREFIX . "goods where " . $where . " order by sort desc,id desc limit 1" );
	} else {
		$item = $GLOBALS ['db']->getRow ( "select name_1,small_img,goods_short_name,u_name,id,brief_1 from " . DB_PREFIX . "goods where id=$goodsID and status = 1" );
	}
	$url_route = a_fanweC ( "URL_ROUTE" );

	if ($item) {
		if ($url_route == 1) {
				$item ['url'] = "tg-" . $item ['id'] . "-ru-" . intval ( $uid ) . ".html";
				$item ['share_url'] = "tg-" . $item ['id'] . "-ru-" . intval ( $uid ) . ".html";
		} else {
			$item ['url'] = a_u( "Goods/show","id-" . $item ['id'] . "|ru-" . intval ( $uid ) );
			$item ['share_url'] = a_u("Goods/show","id-" . $item ['id'] . "|ru-" . intval ( $uid ));
		}
		
		$mail = $GLOBALS ['db']->getRow ( "select `id`,`name`,`mail_title`,`mail_content`,`is_html` from " . DB_PREFIX . "mail_template where name ='share'" );
		$mail ['mail_title'] = str_replace ( '{$title}', $item ['name_1'], $mail ['mail_title'] );
		$mail ['mail_content'] = str_replace ( '{$title}', $item ['name_1'], $mail ['mail_content'] );
		$item ['urlgbname'] = urlencode ( a_utf8ToGB ( $mail ['mail_title'] ) );
		//$item ['urlgbbody'] = urlencode ( a_utf8ToGB ( $mail ['mail_content'] ) );
		//$item ['urlname'] = urlencode ( $item ['name_1'] );
		$item ['urlbrief'] = urlencode ( $item ['brief_1'] );
		$item ['urllink'] = a_getDomain ()."/".$item ['url'];
		$item ['ref_urllink'] = a_getDomain () ."/". $item ['share_url'];
		
		if (a_fanweC('DEFAULT_LANG') == 'en-us'){
			$item['urlgbname'] = $mail['mail_title'];
			$item['urlgbbody'] = $mail['mail_content'];
		}else{
			$item['urlgbname'] = urlencode(a_utf8ToGB($mail['mail_title']));
			$item['urlgbbody'] = urlencode(a_utf8ToGB($mail['mail_content']));
		}
		$GLOBALS ['tpl']->assign ( 'goods', $item );
	}
	else {
		if ($url_route == 1) {
			$item ['ref_urllink'] = a_getDomain () ."/". intval ( $uid ) . ".html";
		}
		else {
			$item ['share_url'] = a_getDomain ()."/".a_u("Index/index","ru-" . intval ( $uid ));
		}
	}
	
	$GLOBALS ['tpl']->assign ( 'ref_urllink', a_getDomain().__ROOT__."/?ru=".intval($uid));
	
	$GLOBALS ['tpl']->assign ( 'is_referrals_page', 1 );
	
	
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );

	$GLOBALS ['tpl']->display ( "Page/referrals.moban" );
}

function referrals_money()
{
	$data = array ('navs' => array (array ('name' => a_L("REFERRALS_RANK"), 'url' => a_u ( "Referrals/money" ) ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$min = isset($_REQUEST['min']) ? intval($_REQUEST['min']) : 0 ;
	$count = $GLOBALS['db']->getAllCached("select r.* from ".DB_PREFIX."referrals as r left join ".DB_PREFIX."user as u on u.id = r.parent_id  where r.is_pay = 1  group by r.parent_id  having sum(r.money) >0");
	$count = count($count);
	$refulist = $GLOBALS['db']->getAllCached("select sum(r.money) as sum_res,u.create_time as reg_time,u.user_name from ".DB_PREFIX."referrals as r left join ".DB_PREFIX."user as u on u.id = r.parent_id  where r.is_pay = 1  group by r.parent_id having sum_res >0 order by sum_res desc, r.pay_time desc limit {$min},50");
	$GLOBALS['tpl']->assign("refulist", $refulist);
	$GLOBALS['tpl']->assign("min", $min);
	$GLOBALS['tpl']->assign("page_title",a_L("REFERRALS_RANK"));
	
	$GLOBALS ['tpl']->assign ( "count", $count );
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$GLOBALS ['tpl']->display ( "Page/referrals_show.moban" );
}

function referrals_score()
{
	$data = array ('navs' => array (array ('name' => a_L("REFERRALS_RANK"), 'url' => a_u ( "Referrals/score" ) ) ), 'keyword' => '', 'content' => '' );
	assignSeo ( $data );
	$min = isset($_REQUEST['min']) ? intval($_REQUEST['min']) : 0 ;
	$count = $GLOBALS['db']->getOneCached("select count(*) from ".DB_PREFIX."user where score>0");
	$refulist = $GLOBALS['db']->getAllCached("select score,create_time as reg_time,user_name from ".DB_PREFIX."user where score>0  order by score desc limit {$min},50");
	$GLOBALS['tpl']->assign("refulist", $refulist);
	$GLOBALS['tpl']->assign("page_title","积分排行");
	$GLOBALS['tpl']->assign("count",$count);
	$GLOBALS['tpl']->assign("min", $min);
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$GLOBALS ['tpl']->display ( "Page/referrals_show.moban" );
}
?>