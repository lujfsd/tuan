<?php
require(ROOT_PATH . 'app/source/class/Rss.class.php');
$langItem = $GLOBALS['langItem'];
$city = $GLOBALS['db']->getRowCached("select `name`,`seo_description` from ".DB_PREFIX."group_city where py='".C_CITY_ID."'");
$shop_description = !empty($city['seo_description'])?$city['seo_description']:$langItem['seocontent'];
$shop_title = str_replace("{\$city_name}",$city['name'],SHOP_NAME);
$shop_description = str_replace("{\$city_name}",$city['name'],$shop_description);
$CND_URL = $GLOBALS['tpl']->_var['CND_URL'];

$rss = new UniversalFeedCreator ( );
$rss->useCached (); // use cached version if age<1 hour  
$rss->title = $shop_title;
$rss->description = $shop_description;
//optional  
$rss->descriptionTruncSize = 500;
$rss->descriptionHtmlSyndicated = true;
$rss->link = a_fanweC("SHOP_URL");
$rss->syndicationURL = a_fanweC("SHOP_URL");
//optional  
$image->descriptionTruncSize = 500;
$image->descriptionHtmlSyndicated = true;


$city_id = C_CITY_ID;
$where .= " and promote_end_time >=".a_gmtTime()." and promote_begin_time <= ".a_gmtTime();
$childIdsUtil = new ChildIds("group_city");
$city_ids = $childIdsUtil->getChildIds(C_CITY_ID);
array_push($city_ids,C_CITY_ID);
$where.=" and status = 1 and score_goods <> 1 and (city_id in (".implode(",",$city_ids).") or all_show = 1)";

$sql = "SELECT `id`,`name_1`,goods_short_name,`brief_1`,`small_img`,`promote_end_time` FROM ".DB_PREFIX."goods where 1 = 1 ".$where." order by sort desc,promote_end_time desc";

$goods_list = $GLOBALS['db']->getAll($sql);

foreach ( $goods_list as $data ) {
	$item = new FeedItem ( );
	if (!empty($data ['goods_short_name'])){
		$item->title = a_msubstr($data ['goods_short_name'], 0, 30 );
	}
	else{
		$item->title = a_msubstr($data ['name_1'], 0, 30 );
	}	
	$data ['url'] = a_getDomain()."/".a_u("Goods/show","id-{$data['id']}");
	$data ['brief_1'] = str_replace("./Public/",$CND_URL."/Public/",$data ['brief_1']);
	$data ['img'] = $CND_URL.$data ['small_img'];
	$item->link = $data ['url'];
	$item->description = "<img src='" . $data ['img'] . "' /><br />" . $data ['brief_1'] . "<br /> <a href='" . $data ['url'] . "' target='_blank' >" . a_l('XY_GROUP_DESC') . "</a>";
	
	//optional  
	$item->descriptionTruncSize = 500;
	$item->descriptionHtmlSyndicated = true;
	
	if ($data ['promote_end_time'] != 0)
		$item->date = date ( 'r', $data ['promote_end_time'] );
	$item->source = $data ['url'];
	$item->author = SHOP_NAME;
	
	$rss->addItem ( $item );
}

$rss->saveFeed ( $format = "RSS0.91", $filename = ROOT_PATH . "app/Runtime/caches/rss_".C_CITY_ID.".xml" );
?>