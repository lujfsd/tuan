<?php 
error_reporting(E_ALL ^ E_NOTICE);

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api.php', '', str_replace('\\', '/', __FILE__)));
	
require ROOT_PATH.'app/source/db_init.php';
require ROOT_PATH.'app/source/comm_init.php';
require ROOT_PATH.'app/source/func/com_func.php';

define('API_ROOT', str_replace('/api', '', __ROOT__));

function GetLang()
{
		$langItem = $GLOBALS['$langItem'];
		define('FANWE_LANG_ID',$langItem['id']);
		define('SHOP_NAME',$langItem['shop_name']);
		return $langItem;
}

function emptyTag($string)
{
		if(empty($string))
			return "";
			
		$string = strip_tags(trim($string));
		$string = preg_replace("|&.+?;|",'',$string);
		
		return $string;
}

function convertUrl($url)
{
		$url = str_replace("&","&amp;",$url);
		return $url;
}

if($_REQUEST['a'] == 'citys')
{
	header('Content-type: text/xml; charset=utf-8');
	$now = a_gmtTime();
	$sql = 'SELECT gc.id,gc.name '.
			    'FROM '.DB_PREFIX.'goods as g '.
				'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
				"where g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by gc.id order by gc.sort asc,gc.id desc";
			
	$list = $GLOBALS['db']->getAll($sql);
		
	$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
	$xml.="<response date=\"".a_toDate($now,"r")."\">\r\n";
	$xml.="<citys>\r\n";
		
	foreach($list as $item)
	{
		$xml.="<city><id>$item[id]</id><name>$item[name]</name></city>\r\n";
	}
	$xml.="</citys>\r\n";
	$xml.="</response>\r\n";
	echo $xml;
}
if($_REQUEST['a']=='goods')
{
	header('Content-type: text/xml; charset=utf-8');
	$cityID = intval($_REQUEST['city']);
	$now = a_gmtTime();
	if($cityID > 0)
			$where = " and g.city_id = $cityID";
	else
			$where = "";
			
	$lang = GetLang();
		
	$goodsname = "name_".FANWE_LANG_ID;
	$brief = "brief_".FANWE_LANG_ID;
		
	$sql = "SELECT g.id,g.city_id,gc.py,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now  $where group by g.id order by g.sort desc,g.id desc";
		
//	$list = M()->query($sql);
	$list = $GLOBALS['db']->getAll($sql);
		
	$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
	$xml.="<response date=\"".a_toDate($now,"r")."\">\r\n";

	foreach($list as $item)
	{
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];
			
			$xml.="<goods>\r\n";
			$xml.="<cityid>$item[city_id]</cityid>\r\n";
			$xml.="<cityname>$item[city_name]</cityname>\r\n";
			$xml.="<id>$item[id]</id>\r\n";
			$xml.="<title>".emptyTag($item['goods_name'])."</title>\r\n";
			$xml.="<brief><![CDATA[$item[goodsbrief]]]></brief>\r\n";
			$xml.="<url>".convertUrl(a_getDomain().$url)."</url>\r\n";
			$xml.="<groupprice>".floatval($item['shop_price'])."</groupprice>\r\n";
			$xml.="<marketprice>".floatval($item['market_price'])."</marketprice>\r\n";
			$xml.="<begintime>".a_toDate($item['promote_begin_time'],"r")."</begintime>\r\n";
			$xml.="<endtime>".a_toDate($item['promote_end_time'],"r")."</endtime>\r\n";
			$xml.="<smallimg>".a_getDomain().API_ROOT.$item['small_img']."</smallimg>\r\n";
			$xml.="<bigimg>".a_getDomain().API_ROOT.$item['big_img']."</bigimg>\r\n";
			$xml.="<suppliers>".emptyTag($item['suppliers_name'])."</suppliers>\r\n";
			$xml.="<buycount>$item[buy_count]</buycount>\r\n";
			$xml.="</goods>\r\n";
	}
	$xml.="</response>\r\n";
	echo $xml;
}

if($_REQUEST['a'] == 'hao123')
	{
		header('Content-type: text/xml; charset=utf-8');
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT g.id,g.city_id,gc.py,g.cate_id,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count,sd.address,sd.tel,gt.name_1 as cate_name ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sd on sd.supplier_id = s.id '.
					'left join '.DB_PREFIX.'goods_cate as gt on gt.id = g.cate_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now and sd.is_main=1 group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<urlset>\r\n";
		
		foreach($list as $item)
		{
			$xml.="<url>\r\n";
		
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];

				
			//商品折扣
			if ($item['market_price'] > 0)
				$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
				$rebate = 0;
			
			//确定传给百度的一级分类
			$cate_pid=$GLOBALS['db']->getOne("select pid from ".DB_PREFIX."goods_cate where id=".intval($item['cate_id']));
			if($cate_pid>0)
			{
				$cate_pid_name=$GLOBALS['db']->getOne("select name_1 from ".DB_PREFIX."goods_cate where id=".intval($cate_pid));
			}			
			$gcatename=$cate_pid_name;
			   if((strstr($gcatename,'餐饮')!=false)||(strstr($gcatename,'美食')!=false))			   
			{
				$class = 1;              
			}
			else if((strstr($gcatename,'休闲')!=false)||(strstr($gcatename,'娱乐')!=false))
			{
				$class = 2;
			}
			else if((strstr($gcatename,'生活')!=false)||(strstr($gcatename,'服务')!=false))
			{
				$class = 3;
			}
			else if((strstr($gcatename,'网上')!=false)||(strstr($gcatename,'购物')!=false))
			{
				$class = 4;
			}
			else if((strstr($gcatename,'旅游')!=false)||(strstr($gcatename,'酒店')!=false)||(strstr($gcatename,'住宿')!=false))
			{
				$class = 5;
			}
            else if((strstr($gcatename,'丽人')!=false)||(strstr($gcatename,'美容')!=false)||(strstr($gcatename,'美体')!=false)||(strstr($gcatename,'美发')!=false)||(strstr($gcatename,'美甲')!=false))
			{
				$class = 6;
			}
			//确定传给百度的一级分类end
			if($class==4)
			{
				$cate_3='其他';//三级分类
			}
			else
			{
				$cate_3='';
			}
				
			
			$xml.="<loc>".convertUrl(a_getDomain().$url)."</loc>\r\n";
			$xml.="<data>\r\n";
			$xml.="<display>\r\n";
			$xml.="<website>".SHOP_NAME."</website>\r\n";
			$xml.="<siteurl>".a_getDomain().API_ROOT."</siteurl>\r\n";
			$xml.="<city>".$item[city_name]."</city>\r\n";
			$xml.="<category>$class</category>\r\n";
			$xml.="<subcategory>".$item[cate_name]."</subcategory>\r\n";
			$xml.="<thrcategory>".$cate_3."</thrcategory>\r\n";
			$xml.="<dpshopid>0</dpshopid>\r\n";
            $xml.="<range>".$item[city_name]."</range>\r\n";
			$xml.="<address>".$item['address']."</address>\r\n";
			$xml.="<major>0</major>\r\n";
			$xml.="<title>".emptyTag($item['goods_name'])."</title>\r\n";
			$xml.="<image>".a_getDomain().API_ROOT.$item['small_img']."</image>\r\n";
			$xml.="<startTime>".(intval($item['promote_begin_time'])+(8*3600))."</startTime>\r\n";
			$xml.="<endTime>".(intval($item['promote_end_time'])+(8*3600))."</endTime>\r\n";
			$xml.="<value>".round($item['market_price'],2)."</value>\r\n";
			$xml.="<price>".round($item['shop_price'],2)."</price>\r\n";
			$xml.="<rebate>".$rebate."</rebate>\r\n";
			$xml.="<bought>".$item['buy_count']."</bought>\r\n";
			$xml.="<name>".$item['cate_name']."</name>\r\n";
			$xml.="<seller>".$item['suppliers_name']."</seller>\r\n";
			$xml.="<phone>".$item['tel']."</phone>\r\n";
			
			
			$xml.="</display>\r\n";
			$xml.="</data>\r\n";
			$xml.="</url>\r\n";
		}
		
		$xml.="</urlset>\r\n";
		echo $xml;
	}
	 
	if($_REQUEST['a']=='tuan800')
	{
		header('Content-type: text/xml; charset=utf-8');
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		$goods_desc = "goods_desc_".FANWE_LANG_ID;
		
		$sql = "SELECT g.id,g.city_id,g.all_show,gc.py,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,g.suppliers_id,g.buy_count,c.$goodsname as cate_name,g.$goods_desc as goods_desc,s.name as suppliers_name,s.address as suppliers_address,s.tel as suppliers_tel,count(g.id) as goodscount,g.stock,g.group_user ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'goods_cate as c on c.id = g.cate_id '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.$goodsname order by g.sort desc,g.id desc limit 5";
		
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<urlset>\r\n";
		
		foreach($list as $item)
		{
			$xml.="<url>\r\n";
		
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];
				
			//商品折扣
			if ($item['market_price'] > 0)
			$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
			$rebate = 0;
				
			$item_brief = $item['goodsbrief']==''?$item['goods_name']:$item['goodsbrief'];
			$stock = $item['stock'] > 0 ? $item['stock']:'';
			$group_user = $item['group_user'] > 0 ? $item['group_user']:'';
			$city = $item['city_name'];
			$citytitle = '';
			if($item['all_show'] == 1)
			{
				$city = '全国';
				$citytitle = '【全国】';
			}
			
			$xml.="<loc>".convertUrl(a_getDomain().$url)."</loc>\r\n";
			$xml.="<data>\r\n";
			$xml.="<display>\r\n";
			$xml.="<website>".SHOP_NAME."</website>\r\n";
			$xml.="<identifier>$item[id]</identifier>";
			$xml.="<siteurl>".a_getDomain().API_ROOT."</siteurl>\r\n";
			$xml.="<city>$city</city>\r\n";
			$xml.="<title>$citytitle".emptyTag($item['goods_name'])."</title>\r\n";
			$xml.="<image>".a_getDomain().API_ROOT.$item['small_img']."</image>\r\n";
			$xml.="<tag>".$item['cate_name']."</tag>\r\n";
			$xml.="<startTime>".a_toDate($item['promote_begin_time'],"Y-m-d H:i:s")."</startTime>\r\n";
			$xml.="<endTime>".a_toDate($item['promote_end_time'],"Y-m-d H:i:s")."</endTime>\r\n";
			$xml.="<value>".round($item['market_price'],2)."</value>\r\n";
			$xml.="<price>".round($item['shop_price'],2)."</price>\r\n";
			$xml.="<rebate>".$rebate."</rebate>\r\n";
			$xml.="<bought>".$item['buy_count']."</bought>\r\n";
			$xml.="<maxQuota>$stock</maxQuota>\r\n";
			$xml.="<minQuota>$group_user</minQuota>\r\n";
			$xml.="<post></post>\r\n";
			$xml.="<soldOut></soldOut>\r\n";
			$xml.="<priority>0</priority>\r\n";		
			$xml.="<tip><![CDATA[".$item_brief."]]></tip>\r\n";
			$xml.="</display>\r\n";
			$xml.="<merchantEndTime></merchantEndTime>\r\n";
			$xml.="<shops>\r\n";
			$xml.="<shop>\r\n";
			$xml.="<name>".emptyTag($item['suppliers_name'])."</name>\r\n";
			$xml.="<tel>".emptyTag($item['suppliers_tel'])."</tel>\r\n";
			$xml.="<addr>".emptyTag($item['suppliers_address'])."</addr>\r\n";
			$xml.="<area></area>\r\n";
			$xml.="<longitude></longitude>\r\n";
			$xml.="<latitude></latitude>\r\n";
			$xml.="<trafficInfo></trafficInfo>\r\n";
			$xml.="</shop>\r\n";
			$xml.="</shops>\r\n";
			$xml.="</data>\r\n";
			$xml.="</url>\r\n";
		}
		
		$xml.="</urlset>\r\n";
		echo $xml;
	}	
	
if($_REQUEST['a']=='hao360city')
	{
		$sql = 'SELECT id,name '.
			    'FROM '.DB_PREFIX.'group_city as gc '.
				"where status = 1 order by sort asc,id desc";
			
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAllCached($sql);
		
		$txt="";
		
		foreach($list as $item)
		{
			$txt.="$item[name]\n";
		}

		echo $txt;
	}

if($_REQUEST['a']=='hao360product')
	{
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT g.id,g.city_id,gc.py,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$txt = "";
		
		foreach($list as $item)
		{
			if($txt != "")
				$txt .= "\n";
				
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];
				
			$txt.="$item[city_name]\t";
			$txt.=urlencode(a_getDomain().API_ROOT.$item['small_img'])."\t";
			$txt.="$item[goods_name]\t";
			$txt.=intval(floatval($item['market_price']) * 100)."\t";
			$txt.=intval(floatval($item['shop_price']) * 100)."\t";
			$txt.=(round((floatval($item['shop_price']) / floatval($item['market_price'])) * 10,1) * 10)."\t";
			$txt.=a_toDate($item['promote_begin_time'])."\t";
			$txt.=a_toDate($item['promote_end_time'])."\t";
			$txt.=urlencode(a_getDomain().$url)."\t";
			$txt.="$item[buy_count]";
		}
		
		echo $txt;
	}
	
	
if($_REQUEST['a']=='hao360')
	{
		header('Content-type: text/xml; charset=utf-8');
		
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT g.id,g.city_id,g.cate_id,gc.py,g.$goodsname as goods_name,g.goods_short_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,g.suppliers_id,g.buy_count,c.$goodsname as cate_name ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'goods_cate as c on c.id = g.cate_id '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		//print_r($list);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<data>\r\n";
		$xml.="<apiversion>4.0</apiversion>";
		$xml.="<site_name>".SHOP_NAME."</site_name> \r\n";
		$xml.="<wap_orderurl></wap_orderurl>";
		$xml.="<goodsdata>\r\n";
		$index = 0;
		
		foreach($list as $item)
		{
			$index++;
			
			$xml.="<goods id=\"$index\">\r\n";
				
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];
				
			//商品折扣
			if ($item['market_price'] > 0)
				$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
				$rebate = "0.0";
				
			//确定传给360的分类名
			$cate_pid=$GLOBALS['db']->getOne("select pid from ".DB_PREFIX."goods_cate where id=".intval($item['cate_id']));
			if($cate_pid>0)
			{
				$cate_pid_name=$GLOBALS['db']->getOne("select name_1 from ".DB_PREFIX."goods_cate where id=".intval($cate_pid));
			}
			if((!preg_match('/^((?!休闲|娱乐).)*$/is',$item['cate_name']))||(!preg_match('/^((?!休闲|娱乐).)*$/is',$cate_pid_name)))
                        {
				$class="休闲娱乐";
                                 if(!preg_match('/^((?!影院门票|电影卡).)*$/is',$goods_name))
                                { 
                                    $end_class= 电影票;
                                }
                                 else if(!preg_match('/^((?!KTV|唱歌).)*$/is',$goods_name))
                                {
                                        $end_class = KTV;
                                }
                                else if(!preg_match('/^((?!温泉|养生会所).)*$/is',$goods_name))
                                {
                                        $end_class = 温泉养生;
                                }
                                 else if(!preg_match('/^((?!电玩城|桌游|台球|真人CS|陶艺吧).)*$/is',$goods_name))
                                {
                                        $end_class = 游玩游乐;
                                }
                                 else if(!preg_match('/^((?!健身房|瑜伽|高尔夫|跆拳道).)*$/is',$goods_name))
                                {
                                        $end_class = 运动健身;
                                }
                                 else if(!preg_match('/^((?!游泳馆|婴幼儿游泳).)*$/is',$goods_name))
                                {
                                        $end_class = 游泳;
                                }
                                  else if(!preg_match('/^((?!婚纱摄影|旅游婚纱|儿童摄影|艺术写真).)*$/is',$goods_name))
                                {
                                        $end_class = 摄影写真;
                                }
                                else if(!preg_match('/^((?!酒吧|慢摇吧|迪吧).)*$/is',$goods_name))
                                {
                                        $end_class = 酒吧;
                                }
                                else if(!preg_match('/^((?!洗浴中心|桑拿|汗蒸).)*$/is',$goods_name))
                                {
                                        $end_class = 桑拿洗浴;
                                }
                                else{$end_class= 其他娱乐;}
                        }
			else if((!preg_match('/^((?!餐|美食|饮).)*$/is',$item['cate_name']))||(!preg_match('/^((?!餐|美食|饮).)*$/is',$cate_pid_name)))
                        {
				$class="本地美食";
                                  if(!preg_match('/^((?!火锅|羊蝎子).)*$/is',$goods_name))
                                { 
                                    $end_class= 火锅;
                                }
                                 else if(!preg_match('/^((?!西点|西餐).)*$/is',$goods_name))
                                {
                                        $end_class = 西餐;
                                }
                                else if(!preg_match('/^((?!海鲜).)*$/is',$goods_name))
                                {
                                        $end_class = 海鲜;
                                }
                                 else if(!preg_match('/^((?!地方菜|北京菜|山东菜|四川菜|广东菜|淮扬菜|浙江菜|福建菜|湖北菜|徽菜|湖南菜|上海菜|天津菜).)*$/is',$goods_name))
                                {
                                        $end_class = 地方菜;
                                }
                                 else if(!preg_match('/^((?!蛋糕|甜点).)*$/is',$goods_name))
                                {
                                        $end_class = 蛋糕;
                                }
                                 else if(!preg_match('/^((?!烧烤|烤串|麻辣香锅|烤羊腿).)*$/is',$goods_name))
                                {
                                        $end_class = 香锅烧烤;
                                }
                                else if(!preg_match('/^((?!甜点|饮料|下午茶|咖啡).)*$/is',$goods_name))
                                {
                                        $end_class = 甜点饮品;
                                }
                                  else if(!preg_match('/^((?!肯德基|麦当劳|咖啡厅|冷饮).)*$/is',$goods_name))
                                {
                                        $end_class = 快餐休闲;
                                }
                                else{$end_class= 其他美食;}
                        }
			else if((!preg_match('/^((?!护肤|美体|护理|洁面|香水|面霜).)*$/is',$item['cate_name']))||(!preg_match('/^((?!护肤|美体|护理|洁面|香水|面霜).)*$/is',$cate_pid_name)))
                        {
				$class="化妆品";
                                 if(!preg_match('/^((?!美容工具|粉底|眼线|眼影|睫毛膏|假睫毛|唇彩|口红|腮红).)*$/is',$goods_name))
                                { 
                                    $classl= 彩妆;
                                }
                                else if(!preg_match('/^((?!面膜|面霜|洁面|化妆水|爽肤水|乳液|精华|卸妆).)*$/is',$goods_name))
                                {
                                        $classl = 护肤;
                                }
                                else if(!preg_match('/^((?!纤体瘦身|润体乳).)*$/is',$goods_name))
                                {
                                        $classl = 美体;
                                }
                                  else if(!preg_match('/^((?!男士洁面|男士面霜).)*$/is',$goods_name))
                                {
                                        $classl = 男士专区;
                                }
                                  else if(!preg_match('/^((?!男士香水|女士香水).)*$/is',$goods_name))
                                {
                                        $classl = 香水;
                                }
                                else{$classl= 其他化妆品;}
                        }
			else if((!preg_match('/^((?!旅游|酒店|景点门票).)*$/is',$item['cate_name']))||(!preg_match('/^((?!旅游|酒店|景点门票).)*$/is',$cate_pid_name)))
                                {
				$class="旅游酒店";
                                  if(!preg_match('/^((?!周边游|国内游|港澳台|出境游|旅游).)*$/is',$goodsname))
                                { 
                                    $classl= 旅游;
                                }
                                else if(!preg_match('/^((?!酒店|旅馆|快捷酒店|酒店代金券卡).)*$/is',$goodsname))
                                {
                                        $classl = 酒店;
                                }
                                else{$classl= 景点通票;}
                                 }
			else if((!preg_match('/^((?!精品|网上|购物).)*$/is',$item['cate_name']))||(!preg_match('/^((?!精品|网上|购物).)*$/is',$cate_pid_name)))
                                {
				$class="网上购物";
                                 if(!preg_match('/^((?!男装|女装|内衣|泳衣|童装|配饰|羽绒服|保暖内衣).)*$/is',$goods_name))
                                { 
                                    $end_class= 服装服饰;
                                }
                                 else if(!preg_match('/^((?!女鞋|童鞋|男鞋|女包|男包|功能箱包|雪地靴).)*$/is',$goods_name))
                                {
                                        $end_class = 箱包鞋靴;
                                }
                                else if(!preg_match('/^((?!食品|茶叶冲饮|饮料|保健类|酒类|水果生鲜).)*$/is',$goods_name))
                                {
                                        $end_class = 食品保健;
                                }
                                 else if(!preg_match('/^((?!床上用品|厨卫用品|灯具|个人护理|居家日用|清洁用品|计生用品|家用电器|护理电器).)*$/is',$goods_name))
                                {
                                        $end_class = 家居生活;
                                }
                                 else if(!preg_match('/^((?!手机|电脑硬件|数码配件|数码).)*$/is',$goods_name))
                                {
                                        $end_class = 手机数码;
                                }
                                 else if(!preg_match('/^((?!母婴|孕妇装|婴儿用品|玩具).)*$/is',$goods_name))
                                {
                                        $end_class = 母婴用品;
                                }
                                else if(!preg_match('/^((?!GPS导航|内饰用品|常规保养).)*$/is',$goods_name))
                                {
                                        $end_class = 汽车配件;
                                }
                                  else if(!preg_match('/^((?!运动装备|运动服|运动鞋).)*$/is',$goods_name))
                                {
                                        $end_class = 运动户外;
                                }
                                else{$end_class= 其他购物;}
                                 }
                           else 
                                {
				$class="生活服务";
                                 if(!preg_match('/^((?!美发|烫发|剪发|染发|造型等美发店).)*$/is',$goods_name))
                                { 
                                    $end_class= 美发;
                                }
                                 else if(!preg_match('/^((?!美甲).)*$/is',$goods_name))
                                {
                                        $end_class = 美甲;
                                }
                                else if(!preg_match('/^((?!美容中心|减肥中心|产后瘦身|美容美体).)*$/is',$goods_name))
                                {
                                        $end_class = 美容美体;
                                }
                                 else if(!preg_match('/^((?!体检机构|专科医院体检|体检).)*$/is',$goods_name))
                                {
                                        $end_class = 体检;
                                }
                                 else if(!preg_match('/^((?!中医保健|按摩|养生护理|养生|按摩|保健).)*$/is',$goods_name))
                                {
                                        $end_class = 养生按摩;
                                }
                                 else if(!preg_match('/^((?!日用品清洗|清洗).)*$/is',$goods_name))
                                {
                                        $end_class = 清洗服务;
                                }
                                else if(!preg_match('/^((?!配镜|镜|眼睛|眼镜).)*$/is',$goods_name))
                                {
                                        $end_class = 配镜;
                                }
                                  else if(!preg_match('/^((?!婚庆服务|鲜花快递|婚庆|鲜花).)*$/is',$goods_name))
                                {
                                        $end_class = 鲜花婚庆;
                                }
                                 else if(!preg_match('/^((?!语言学校|儿童兴趣培训|成人兴趣培训|培训|学校|教育).)*$/is',$goods_name))
                                {
                                        $end_class = 教育培训;
                                }
                                else{ $end_class = 美容美体;}
                                 }

							
//			$suppliers = D("SuppliersDepart")->where("supplier_id = ".$item['suppliers_id'])->order("is_main desc")->find();
			$suppliers = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".$item['suppliers_id']." and is_main = 1");
			
			$address = "";
			if($suppliers)
			{
				$address = emptyTag($suppliers['address']);
				$suppliers_name = emptyTag($suppliers['depart_name']);
				$suppliers_tel =$suppliers['tel'];
				$map = convertUrl($suppliers['map']);
			}
			$goods_short_name=emptyTag($item['goods_short_name']);
			$goods_short_name=a_msubstr($goods_short_name,0,20);
			
			$xml.="<pid>".intval($item[id])."</pid>\r\n";
			$xml.="<feature>".$item[cate_name]."</feature>\r\n";
			$xml.="<city_name>".$item[city_name]."</city_name>\r\n";
			$xml.="<site_url>".a_getDomain().API_ROOT."</site_url>\r\n";
			$xml.="<title>".a_msubstr(htmlspecialchars($goods_short_name),0,20)."</title>\r\n";
			$xml.="<short_title>".emptyTag(htmlspecialchars($item['short_name']))."</short_title>\r\n";
			$xml.="<bigimg_url>".a_getDomain().API_ROOT.$item['small_img']."</bigimg_url>\r\n";
			$xml.="<goods_url>".convertUrl(a_getDomain().$url)."</goods_url>\r\n";
			$xml.="<wap_buyurl></wap_buyurl>\r\n";
			$xml.="<goods_wapurl></goods_wapurl>\r\n";
			$xml.="<desc>".emptyTag(htmlspecialchars($item['goods_name']))."</desc>\r\n";
			$xml.="<class>$class</class>\r\n";
            $xml.="<end_class>$end_class</end_class>\r\n";
			$xml.="<img_url>".a_getDomain().API_ROOT.$item['small_img']."</img_url>\r\n";
			$xml.="<pins></pins>\r\n";
			$xml.="<original_price>".number_format(round($item['market_price'],2), 2, '.', '')."</original_price>\r\n";
			$xml.="<sale_price>".number_format(round($item['shop_price'],2), 2, '.', '')."</sale_price>\r\n";
			$xml.="<sale_rate>".$rebate."</sale_rate>\r\n";
			$xml.="<sales_num>".$item['buy_count']."</sales_num>\r\n";
			$xml.="<start_time>".a_toDate($item['promote_begin_time'],"YmdHis")."</start_time>\r\n";
			$xml.="<close_time>".a_toDate($item['promote_end_time'],"YmdHis")."</close_time>\r\n";
			$xml.="<merchant_name>$suppliers_name</merchant_name>\r\n";
			$xml.="<merchant_tel>$suppliers_tel</merchant_tel>\r\n";
			$xml.="<merchant_addr>$address</merchant_addr>\r\n";
			$xml.="<hot_area></hot_area>\r\n";
			$xml.="<longitude></longitude>\r\n";
			$xml.="<latitude></latitude>\r\n";
			$xml.="<reservation></reservation>\r\n";
			$xml.="<spend_start_time></spend_start_time>\r\n";
			$xml.="<spend_close_time></spend_close_time>\r\n";
			$xml.="</goods>\r\n";
		}
		
		$xml.="</goodsdata>\r\n";
		$xml.="</data>\r\n";
		echo $xml;
	}

	
if($_REQUEST['a']=='tuanp')
	{
		header('Content-type: text/xml; charset=utf-8');
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT s.name as supplier_name,s.brief as supplier_biref,sp.tel as sp_tel,sp.address as sp_address,g.id,g.city_id,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count,gc.py,gct.name_1 as cate_name ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'goods_cate as gct on gct.id = g.cate_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sp on s.id = sp.supplier_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<urlset>\r\n";
		
		foreach($list as $item)
		{
			$xml.="<url>\r\n";
						
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];

				
			//商品折扣
			if ($item['market_price'] > 0)
			$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
			$rebate = 0;
				
			$item_brief = $item['goodsbrief']==''?$item['goods_name']:$item['goodsbrief'];

			$xml.="<loc>".convertUrl(a_getDomain().$url)."</loc>\r\n";
			$xml.="<data>\r\n";
			$xml.="<display>\r\n";
			$xml.="<website>".SHOP_NAME."</website>\r\n";
			$xml.="<siteurl>".a_getDomain().API_ROOT."</siteurl>\r\n";
			$xml.="<city>".$item[city_name]."</city>\r\n";
			$xml.="<title>".emptyTag($item['goods_name'])."</title>\r\n";
			$xml.="<image>".a_getDomain().API_ROOT.$item['small_img']."</image>\r\n";
			$xml.="<startTime>".$item['promote_begin_time']."</startTime>\r\n";
			$xml.="<endTime>".$item['promote_end_time']."</endTime>\r\n";
			$xml.="<expireTime></expireTime>\r\n";
			$xml.="<value>".round($item['market_price'],2)."</value>\r\n";
			$xml.="<price>".round($item['shop_price'],2)."</price>\r\n";
			$xml.="<rebate>".$rebate."</rebate>\r\n";
			$xml.="<cate>".$item['cate_name']."</cate>\r\n";
			$xml.="<bought>".$item['buy_count']."</bought>\r\n";
			
			$xml.="<shops>\r\n";
			$xml.="<shop>\r\n";
			$xml.="<name>".emptyTag($item['supplier_name'])."</name>\r\n";	
			$xml.="<tel>".$item['sp_tel']."</tel>\r\n";	
			$xml.="<addr>".$item['sp_address']."</addr>\r\n";	
			$xml.="</shop>\r\n";
			$xml.="</shops>\r\n";
						
			$xml.="</display>\r\n";
			$xml.="</data>\r\n";
			$xml.="</url>\r\n";
		}
		
		$xml.="</urlset>\r\n";
		echo $xml;
	}
	if($_REQUEST['a']=='soso')
	{
		header('Content-type: text/xml; charset=GBK');
		$now = a_gmtTime();
		$lang = GetLang();
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
		
		$GLOBALS['db']->query("SET NAMES GBK");
		$sql = "SELECT g.id,g.city_id,gc.py,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,s.map as suppliers_map,s.img as suppliers_img,s.tel as suppliers_tel,s.address as suppliers_address,g.buy_count,gct.name_1 as cat_name,g.sort,g.max_bought,g.type_id,g.group_user,g.brief_1 as goodsbrief,g.stock,g.goods_short_name  ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'goods_cate as gct on gct.id = g.cate_id '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"GBK\"?>\r\n";
		$xml.="<sdd>\r\n";
		$xml.="<provider>".iconv('utf-8','gbk',SHOP_NAME)."</provider>\r\n";
		$xml.="<version>1.0</version>\r\n";
		$xml.="<dataServiceId>1_1</dataServiceId>\r\n";
		$xml.="<datalist>\r\n";
		foreach($list as $item)
		{
			$xml.="<item>\r\n";
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
					$url = API_ROOT."/tg-".$item['id'].".html";	
			}
			else
				$url = "/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];
			//商品折扣
			if ($item['market_price'] > 0)
				$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
			$rebate = 0;
			
			$xml.="<keyword>".$item['goods_short_name']."</keyword>\r\n";
			$xml.="<Url>".convertUrl(a_getDomain().$url)."</Url>\r\n";
			$xml.="<creator>".a_getDomain().API_ROOT."</creator>\r\n";
			$xml.="<Title>".emptyTag($item['goods_name'])."</Title>\r\n";
			$xml.="<publishdate>".date('Y-m-d',(intval($item['promote_begin_time'])+(8*3600)))."</publishdate>\r\n";
			$xml.="<imageaddress1>".a_getDomain().API_ROOT.$item['small_img']."</imageaddress1>\r\n";
			$xml.="<imagealt1>".iconv('utf-8','gbk',SEO_CONTENT)."</imagealt1>\r\n";
			$xml.="<imagelink1>".convertUrl(a_getDomain().$url)."</imagelink1>\r\n";
			$xml.="<content1>".emptyTag($item['goods_short_name'])."</content1>\r\n";
			$xml.="<content2>".emptyTag($item['goods_name'])."</content2>\r\n";
			$xml.="<content3>".round($item['market_price'],2)."</content3>\r\n";
			$xml.="<content4>".round($item['shop_price'],2)."</content4>\r\n";
			$xml.="<content5>".$rebate."</content5>\r\n";
			$xml.="<content6>".emptyTag($item['cat_name'])."</content6>\r\n";
			$xml.="<content7>".$item['city_name']."</content7>\r\n";
			$xml.="<content8>".$key."</content8>\r\n";
			$xml.="<content9>".iconv('utf-8','gbk',$item['suppliers_name'])."</content9>\r\n";
			if($item['buy_count']>=$item['group_user']){
			$xml.="<content10>".iconv('utf-8','gbk','成功')."</content10>\r\n";
			}
			if($item['stock']){
			$xml.="<content12>".$item['stock']."</content12>\r\n";
			}
			$xml.="<content11>".$item['goods_name']."</content11>\r\n";
			$xml.="<content13>".$item['suppliers_address']."</content13>\r\n";
			$xml.="<content14>".$item['suppliers_tel']."</content14>\r\n";
			$xml.="<content15>".date('Y-m-d i:m:s',(intval($item['promote_begin_time'])+(8*3600)))."</content15>\r\n";
			$xml.="<content16>".date('Y-m-d i:m:s',(intval($item['promote_begin_time'])+(8*3600)))."</content16>\r\n";
			$xml.="</item>\r\n";
		}
		$xml.="</datalist>\r\n";
		$xml.="</sdd>\r\n";
		echo $xml;
	}	
	if($_REQUEST['a']=='sohu')
	{
		header('Content-type: text/xml; charset=utf-8');
		$now = a_gmtTime();
		$lang = GetLang();
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
		$sql = "SELECT g.id,g.city_id,gc.py,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,s.map as suppliers_map,s.img as suppliers_img,s.tel as suppliers_tel,s.address as suppliers_address,g.buy_count,gct.name_1 as cat_name,g.sort,g.max_bought,g.type_id,g.group_user,g.brief_1 as goodsbrief,g.stock,g.update_time    ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'goods_cate as gct on gct.id = g.cate_id '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<ActivitySet>\r\n";
		$xml.="<Site>".SHOP_NAME."</Site>\r\n";
		$xml.="<SiteUrl>".a_getDomain().API_ROOT."</SiteUrl>\r\n";
		$xml.="<Update>".date("Y-m-d",(intval(a_gmtTime())+(8*3600)))."</Update>\r\n";
		//$xml.="<Update>".convertUrl(getDomain())."</Update>\r\n";
		foreach($list as $item)
		{
			
			$xml.="<Activity>\r\n";
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";	
			}
			else
				$url = "/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];
			//商品折扣
			if ($item['market_price'] > 0)
				$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
				$rebate = 0;
			$item_brief = $item['goodsbrief']==''?$item['goods_name']:$item['goodsbrief'];
			$xml.="<Title>".emptyTag($item['goods_name'])."</Title>\r\n";
			$xml.="<Url>".convertUrl(a_getDomain().$url)."</Url>\r\n";
			$xml.="<Description>".SEO_CONTENT."</Description>\r\n";
			$xml.="<ImageUrl>".a_getDomain().API_ROOT.$item['small_img']."</ImageUrl>\r\n";
			$xml.="<CityName>".$item[city_name]."</CityName>\r\n";
			
			$xml.="<Value>".round($item['market_price'],2)."</Value>\r\n";
			$xml.="<Price>".round($item['shop_price'],2)."</Price>\r\n";
			$xml.="<ReBate>".$rebate."</ReBate>\r\n";
			$xml.="<StartTime>".date("Ymdhis",(intval($item['promote_begin_time'])+(8*3600)))."</StartTime>\r\n";
			$xml.="<EndTime>".date("Ymdhis",(intval($item['promote_end_time'])+(8*3600)))."</EndTime>\r\n";
			
			if($item['stock']){
			$xml.="<Quantity>".$item['stock']."</Quantity>\r\n";
			}
			$xml.="<Bought>".$item['buy_count']."</Bought>\r\n";
			if($item['group_user']){
			$xml.="<MinBought>".$item['group_user']."</MinBought>\r\n";
			}
			
			if($item['max_bought']){
			$xml.="<BoughtLimit>".$item['max_bought']."</BoughtLimit>\r\n";
			}
			$xml.="<Goods>\r\n";
			$xml.="<Name>".$item['goods_name']."</Name>\r\n";
			$xml.="<ProviderName>".$item['suppliers_name']."</ProviderName>\r\n";
			
			if($item['suppliers_img']){
			$xml.="<ImageUrlSet>".a_getDomain().API_ROOT.$item['suppliers_img']."</ImageUrlSet>\r\n";
			}
			if($item['suppliers_tel']){
			$xml.="<Telephone>".$item['suppliers_tel']."</Telephone>\r\n";
			}
			if($item['suppliers_address'])
			{
			$xml.="<Address>".$item['suppliers_address']."</Address>\r\n";
			}
			if($item['suppliers_map']){
			//$xml.="<Map>".$item['suppliers_map']."</Map>\r\n";
			}
			if($item_brief){
			$xml.="<Description>".$item_brief."</Description>\r\n";
			}
			
			$xml.="</Goods>\r\n";
			$xml.="</Activity>\r\n";
		}
		
		$xml.="</ActivitySet>\r\n";
		echo $xml;
	} 
	if($_REQUEST['a'] == 'ganji')
	{
		
		header('Content-type: text/xml; charset=utf-8');
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT g.id,g.city_id,gc.py,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count,gca.name_1 as cat_name ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'goods_cate as gca on g.cate_id = gca.id '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<urlset>\r\n";
		
		foreach($list as $item)
		{
			$xml.="<url>\r\n";
		
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];

				
			//商品折扣
			if ($item['market_price'] > 0)
				$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
				$rebate = 0;
			switch($item[city_name]){}
			
			$xml.="<loc>".convertUrl(a_getDomain().$url)."</loc>\r\n";
			$xml.="<data>\r\n";
			$xml.="<display>\r\n";
			$xml.="<website>".SHOP_NAME."</website>\r\n";
			$xml.="<siteurl>".a_getDomain().API_ROOT."</siteurl>\r\n";
			$xml.="<city>".$item[city_name]."</city>\r\n";
			
			$xml.="<title>".emptyTag($item['goods_name'])."</title>\r\n";
			if((strstr($item['cat_name'],'餐饮')!=false)||strstr($item['cat_name'],'食品')!=false){
			$xml.="<class>meishi</class>\r\n";
			}
			elseif((strstr($item['cat_name'],'休闲')!=false)||strstr($item['cat_name'],'健身')!=false){
			$xml.="<class>jianshen</class>\r\n";
			}
			elseif((strstr($item['cat_name'],'美容')!=false)||strstr($item['cat_name'],'护肤')!=false){
			$xml.="<class>meirong</class>\r\n";
			}
			elseif((strstr($item['cat_name'],'精品')!=false)||strstr($item['cat_name'],'购物')!=false){
			$xml.="<class>gouwu</class>\r\n";
			}
			elseif((strstr($item['cat_name'],'优惠')!=false)||strstr($item['cat_name'],'券票')!=false){
			$xml.="<class>piaowu</class>\r\n";
			}
			else{
			$xml.="<class>others</class>\r\n";
			}
			
			$xml.="<image>".a_getDomain().API_ROOT.$item['small_img']."</image>\r\n";
			$xml.="<startTime>".(intval($item['promote_begin_time'])+(8*3600))."</startTime>\r\n";
			$xml.="<endTime>".(intval($item['promote_end_time'])+(8*3600))."</endTime>\r\n";
			$xml.="<value>".round($item['market_price'],2)."</value>\r\n";
			$xml.="<price>".round($item['shop_price'],2)."</price>\r\n";
			$xml.="<rebate>".$rebate."</rebate>\r\n";
			$xml.="<bought>".$item['buy_count']."</bought>\r\n";
			
			
			$xml.="</display>\r\n";
			$xml.="</data>\r\n";
			$xml.="</url>\r\n";
		}
		
		$xml.="</urlset>\r\n";
		echo $xml;
	}
	if($_REQUEST['a'] == 'bendi')
	{
		
		header('Content-type: text/xml; charset=utf-8');
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT g.id,g.city_id,gc.py,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count,gca.name_1 as cat_name ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'goods_cate as gca on g.cate_id = gca.id '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<urlset>\r\n";
		
		foreach($list as $item)
		{
			$xml.="<url>\r\n";
		
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];

				
			//商品折扣
			if ($item['market_price'] > 0)
				$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
				$rebate = 0;
			switch($item[city_name]){}
			
			$xml.="<loc>".convertUrl(a_getDomain().$url)."</loc>\r\n";
			$xml.="<data>\r\n";
			$xml.="<display>\r\n";
			$xml.="<website>".SHOP_NAME."</website>\r\n";
			$xml.="<siteurl>".a_getDomain().API_ROOT."</siteurl>\r\n";
			$xml.="<city>".$item[city_name]."</city>\r\n";
			
			$xml.="<title>".emptyTag($item['goods_name'])."</title>\r\n";
			$xml.="<image>".a_getDomain().API_ROOT.$item['small_img']."</image>\r\n";
			if((strstr($item['cat_name'],'餐饮')!=false)||strstr($item['cat_name'],'食品')!=false){
			$xml.="<class>meishi</class>\r\n";
			}
			elseif((strstr($item['cat_name'],'休闲')!=false)||(strstr($item['cat_name'],'娱乐')!=false)||(strstr($item['cat_name'],'旅游')!=false)){
			$xml.="<class>yule</class>\r\n";
			}
			elseif((strstr($item['cat_name'],'美容')!=false)||strstr($item['cat_name'],'时尚')!=false){
			$xml.="<class>meirong</class>\r\n";
			}
			elseif((strstr($item['cat_name'],'精品')!=false)||strstr($item['cat_name'],'购物')!=false){
			$xml.="<class>gouwu</class>\r\n";
			}
			elseif((strstr($item['cat_name'],'化妆')!=false)||strstr($item['cat_name'],'护肤')!=false){
			$xml.="<class>cosmetic</class>\r\n";
			}
			else{
			$xml.="<class>qita</class>\r\n";
			}
			
			$xml.="<startTime>".(intval($item['promote_begin_time'])+(8*3600))."</startTime>\r\n";
			$xml.="<endTime>".(intval($item['promote_end_time'])+(8*3600))."</endTime>\r\n";
			$xml.="<value>".round($item['market_price'],2)."</value>\r\n";
			$xml.="<price>".round($item['shop_price'],2)."</price>\r\n";
			$xml.="<bought>".$item['buy_count']."</bought>\r\n";
			$xml.="<rebate>".$rebate."</rebate>\r\n";
			
			
			
			$xml.="</display>\r\n";
			$xml.="</data>\r\n";
			$xml.="</url>\r\n";
		}
		
		$xml.="</urlset>\r\n";
		echo $xml;
	}
	
	if($_REQUEST['a'] == '163')
	{
		header('Content-type: text/xml; charset=utf-8');
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT g.id,g.city_id,gc.py,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,s.tel as suppliers_tel,s.address as suppliers_address,s.xpoint,s.ypoint,g.buy_count,gca.name_1 as cat_name  ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'goods_cate as gca on g.cate_id = gca.id '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<urlset>\r\n";
		
		foreach($list as $item)
		{
			$xml.="<url>\r\n";
		
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];

				
			//商品折扣
			if ($item['market_price'] > 0)
				$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
				$rebate = 0;
			
			$xml.="<loc>".convertUrl(a_getDomain().$url)."</loc>\r\n";
			$xml.="<data>\r\n";
			$xml.="<display>\r\n";
			$xml.="<website>".SHOP_NAME."</website>\r\n";
			$xml.="<siteurl>".a_getDomain().API_ROOT."</siteurl>\r\n";
			$xml.="<city>".$item[city_name]."</city>\r\n";
			$xml.="<title>".emptyTag($item['goods_name'])."</title>\r\n";
			$xml.="<image>".a_getDomain().API_ROOT.$item['small_img']."</image>\r\n";
			if((strstr($item['cat_name'],'餐饮')!=false)||strstr($item['cat_name'],'食品')!=false){
			$xml.=" <tag>美食</tag>\r\n";
			}
			elseif((strstr($item['cat_name'],'休闲')!=false)||strstr($item['cat_name'],'健身')!=false){
			$xml.=" <tag>生活</tag>\r\n";
			}
			elseif((strstr($item['cat_name'],'美容')!=false)||strstr($item['cat_name'],'护肤')!=false){
			$xml.=" <tag>美容</tag>\r\n";
			}
			elseif((strstr($item['cat_name'],'精品')!=false)||strstr($item['cat_name'],'购物')!=false){
			$xml.=" <tag>网购</tag>\r\n";
			}
			else{
			$xml.=" <tag>其他</tag>\r\n";
			}
			$xml.="<startTime>".(intval($item['promote_begin_time'])+(8*3600))."</startTime>\r\n";
			$xml.="<endTime>".(intval($item['promote_end_time'])+(8*3600))."</endTime>\r\n";
			$xml.="<value>".round($item['market_price'],2)."</value>\r\n";
			$xml.="<price>".round($item['shop_price'],2)."</price>\r\n";
			$xml.="<rebate>".$rebate."</rebate>\r\n";
			$xml.="<bought>".$item['buy_count']."</bought>\r\n";
			$xml.="<merchantEndTime></merchantEndTime>\r\n";
			
			$xml.="</display>\r\n";
			$xml.="<shops>\r\n";
			$xml.="<shop>\r\n";
			$xml.="<name>".$item['suppliers_name']."</name>\r\n";
			$xml.="<tel>".$item['suppliers_tel']."</tel>\r\n";
			$xml.="<addr>".$item['suppliers_address']."</addr>\r\n";
			$xml.="<longitude>".$item['xpoint']."</longitude>\r\n";
			$xml.="<latitude>".$item['ypoint']."</latitude>\r\n";
			$xml.="</shop>\r\n";
			$xml.="</shops>\r\n";
			
			$xml.="</data>\r\n";
			$xml.="</url>\r\n";
		}
		
		$xml.="</urlset>\r\n";
		echo $xml;
	}	
	
	if($_REQUEST['a'] == 'bj100')
	{
		header('Content-type: text/xml; charset=utf-8');
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT g.id,g.city_id,gc.py,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,s.tel as suppliers_tel,s.address as suppliers_address,s.xpoint,s.ypoint,g.buy_count,gca.name_1 as cat_name  ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'goods_cate as gca on g.cate_id = gca.id '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<urlset>\r\n";
		
		foreach($list as $item)
		{
			$xml.="<url>\r\n";
		
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];

				
			//商品折扣
			if ($item['market_price'] > 0)
				$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
				$rebate = 0;
			
			$xml.="<loc>".convertUrl(a_getDomain().$url)."</loc>\r\n";
			$xml.="<data>\r\n";
			$xml.="<display>\r\n";
			$xml.="<website>".SHOP_NAME."</website>\r\n";
			$xml.="<siteurl>".a_getDomain().API_ROOT."</siteurl>\r\n";
			$xml.="<city>".$item[city_name]."</city>\r\n";
			
			
			if((strstr($item['cat_name'],'餐饮')!=false)||strstr($item['cat_name'],'食品')!=false){
			$xml.=" <category>1</category>\r\n";
			}
			elseif((strstr($item['cat_name'],'休闲')!=false)||strstr($item['cat_name'],'摄影')!=false||strstr($item['cat_name'],'KTV')!=false||strstr($item['cat_name'],'票')!=false){
			$xml.=" <category>2</category>\r\n";
			}
			elseif((strstr($item['cat_name'],'美容')!=false)||strstr($item['cat_name'],'护肤')!=false||strstr($item['cat_name'],'化妆')!=false||strstr($item['cat_name'],'健身')!=false){
			$xml.=" <category>3</category>\r\n";
			}
			elseif((strstr($item['cat_name'],'精品')!=false)||strstr($item['cat_name'],'数码')!=false||strstr($item['cat_name'],'服饰')!=false||strstr($item['cat_name'],'玩具')!=false||strstr($item['cat_name'],'箱包')!=false){
			$xml.=" <category>4</category>\r\n";
			}
			elseif((strstr($item['cat_name'],'家具')!=false)||strstr($item['cat_name'],'家电')!=false||strstr($item['cat_name'],'床')!=false){
			$xml.=" <category>9</category>\r\n";
			}			
			else{
			$xml.=" <category>4</category>\r\n";
			}
			$xml.="<dpshopid></dpshopid>\r\n";
			$xml.="<area></area>\r\n";
			$xml.="<address>".$item['suppliers_address']."</address>\r\n";
			$xml.="<major>0</major>\r\n";
			$xml.="<title>".emptyTag($item['goods_name'])."</title>\r\n";
			$xml.="<image>".a_getDomain().API_ROOT.$item['small_img']."</image>\r\n";
			

			$xml.="<startTime>".(intval($item['promote_begin_time'])+(8*3600))."</startTime>\r\n";
			$xml.="<endTime>".(intval($item['promote_end_time'])+(8*3600))."</endTime>\r\n";
			$xml.="<value>".round($item['market_price'],2)."</value>\r\n";
			$xml.="<price>".round($item['shop_price'],2)."</price>\r\n";
			$xml.="<rebate>".$rebate."</rebate>\r\n";
			$xml.="<bought>".$item['buy_count']."</bought>\r\n";
			
			$xml.="</display>\r\n";
			$xml.="</data>\r\n";
			$xml.="</url>\r\n";
		}
		
		$xml.="</urlset>\r\n";
		echo $xml;
	}	
	
	if($_REQUEST['a']=='360api')
	{
		header('Content-type: text/xml; charset=utf-8');
		
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT g.id,g.city_id,gc.py,g.$goodsname as goods_name,g.goods_short_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,g.suppliers_id,g.buy_count,c.$goodsname as cate_name ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'goods_cate as c on c.id = g.cate_id '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					"where g.no_api = 0 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<data>\r\n";
		$xml.="<site_name>".SHOP_NAME."</site_name> \r\n";
		$xml.="<goodsdata>\r\n";
		$index = 0;
		
		foreach($list as $item)
		{
			$index++;
			
			$xml.="<goods id=\"$index\">\r\n";
				
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];
				
			//商品折扣
			if ($item['market_price'] > 0)
				$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
				$rebate = "0.0";
				
//			$suppliers = D("SuppliersDepart")->where("supplier_id = ".$item['suppliers_id'])->order("is_main desc")->find();
			$suppliers = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".$item['suppliers_id']." and is_main = 1");
			
			$address = "";
			if($suppliers)
			{
				$address = emptyTag($suppliers['address']);
				$suppliers_name = emptyTag($suppliers['depart_name']);
				$suppliers_tel =$suppliers['tel'];
				$map = convertUrl($suppliers['map']);
			}
			
			$xml.="<city_name>".$item[city_name]."</city_name>\r\n";
			$xml.="<site_url>".a_getDomain().API_ROOT."</site_url>\r\n";
			$xml.="<title>".emptyTag($item['goods_short_name'])."</title>\r\n";
			$xml.="<goods_url>".convertUrl(a_getDomain().$url)."</goods_url>\r\n";
			$xml.="<desc>".emptyTag($item['goods_name'])."</desc>\r\n";
			$xml.="<class>$item[cate_name]</class>\r\n";
			$xml.="<img_url>".a_getDomain().API_ROOT.$item['small_img']."</img_url>\r\n";
			$xml.="<original_price>".number_format(round($item['market_price'],2), 2, '.', '')."</original_price>\r\n";
			$xml.="<sale_price>".number_format(round($item['shop_price'],2), 2, '.', '')."</sale_price>\r\n";
			$xml.="<sale_rate>".$rebate."</sale_rate>\r\n";
			$xml.="<sales_num>".$item['buy_count']."</sales_num>\r\n";
			$xml.="<start_time>".a_toDate($item['promote_begin_time'],"YmdHis")."</start_time>\r\n";
			$xml.="<close_time>".a_toDate($item['promote_end_time'],"YmdHis")."</close_time>\r\n";
			$xml.="<merchant_name>$suppliers_name</merchant_name>\r\n";
			$xml.="<merchant_tel>$suppliers_tel</merchant_tel>\r\n";
			$xml.="<spend_start_time></spend_start_time>\r\n";
			$xml.="<spend_close_time></spend_close_time>\r\n";
			$xml.="<merchant_addr>$address</merchant_addr>\r\n";
			$xml.="<hot_area></hot_area>\r\n";
			$xml.="<longitude></longitude>\r\n";
			$xml.="<latitude></latitude>\r\n";
			$xml.="</goods>\r\n";
		}
		
		$xml.="</goodsdata>\r\n";
		$xml.="</data>\r\n";
		echo $xml;
	}
	
	if($_REQUEST['a']=='jutao')
	{
		header('Content-type: text/xml; charset=utf-8');
		$now = a_gmtTime();
		
		$lang = GetLang();
		
		$goodsname = "name_".FANWE_LANG_ID;
		$brief = "brief_".FANWE_LANG_ID;
		
		$sql = "SELECT s.name as supplier_name,s.brief as supplier_biref,sp.tel as sp_tel,sp.address as sp_address,g.id,g.city_id,g.$goodsname as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.$brief as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count,gc.py ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sp on s.id = sp.supplier_id '.
					"where sp.is_main=1 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
//		$list = M()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		$xml.="<urlset>\r\n";
		
		foreach($list as $item)
		{
			$xml.="<url>\r\n";
						
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($item['u_name']!='')
//					$url = U("g/".rawurlencode($item['u_name']));
					$url = API_ROOT."/g-".rawurlencode($item['u_name']).".html";
				else
//					$url = U("tg/".$item['id']);
					$url = API_ROOT."/tg-".$item['id'].".html";					
			}
			else
				$url = API_ROOT."/index.php?m=Goods&a=show&cityname=".$item['py']."&id=".$item['id'];

				
			//商品折扣
			if ($item['market_price'] > 0)
			$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
			else
			$rebate = 0;
				
			$item_brief = $item['goodsbrief']==''?$item['goods_name']:$item['goodsbrief'];

			$xml.="<loc>".convertUrl(a_getDomain().$url)."</loc>\r\n";
			$xml.="<data>\r\n";
			$xml.="<display>\r\n";
			$xml.="<website>".SHOP_NAME."</website>\r\n";
			$xml.="<siteurl>".a_getDomain().__ROOT__."</siteurl>\r\n";
			$xml.="<city>".$item[city_name]."</city>\r\n";
			$xml.="<title>".emptyTag($item['goods_name'])."</title>\r\n";
			$xml.="<image>".a_getDomain().$item['small_img']."</image>\r\n";
			$xml.="<soldout>"."no"."</soldout>";
			$xml.="<buyer>".$item['buy_count']."</buyer>\r\n";
			$xml.="<start_date>".$item['promote_begin_time']."</start_date>\r\n";
			$xml.="<end_date>".$item['promote_end_time']."</end_date>\r\n";
			$xml.="<expire_date>"."0"."</expire_date>";
			$xml.="<oriprice>".round($item['market_price'],2)."</oriprice>\r\n";
			$xml.="<curprice>".round($item['shop_price'],2)."</curprice>\r\n";
			$discount=number_format(($item['shop_price']/$item['market_price'])*10, 1);
			$xml.="<discount>".$discount."</discount>";
			$xml.="<tip><![CDATA[".$item_brief."]]></tip>\r\n";
			$xml.="<detail><![CDATA[".$item['supplier_biref']."]]></detail>\r\n";
			$xml.="</display>\r\n";
			$xml.="<companys>\r\n";
			$xml.="<company>\r\n";
			$xml.="<name>".emptyTag($item['supplier_name'])."</name>\r\n";	
			$xml.="<contact>".$item['sp_tel']."</contact>\r\n";	
			$xml.="<address>".$item['sp_address']."</address>\r\n";
			$xml.="</company>\r\n";
			$xml.="</companys>\r\n";
			$xml.="</data>\r\n";
			$xml.="</url>\r\n";
		}
		
		$xml.="</urlset>\r\n";
		echo $xml;
	}	
?>