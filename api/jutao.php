<?php


if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/jutao.php', '', str_replace('\\', '/', __FILE__)));
	
require ROOT_PATH.'app/source/db_init.php';
require ROOT_PATH.'app/source/comm_init.php';
require ROOT_PATH.'app/source/func/com_func.php';

define('API_ROOT', str_replace('/api', '', __ROOT__));

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

	header('Content-type: text/xml; charset=utf-8');
	$now = a_gmtTime();
		
		
	$sql = "SELECT s.name as supplier_name,s.brief as supplier_biref,sp.tel as sp_tel,sp.address as sp_address,g.id,g.city_id,g.name_1 as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.brief_1 as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count,gc.py ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sp on s.id = sp.supplier_id '.
					"where sp.is_main=1 and g.status = 1 and g.promote_begin_time <= $now and g.promote_end_time >= $now group by g.id order by g.sort desc,g.id desc";
		
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
	
?>