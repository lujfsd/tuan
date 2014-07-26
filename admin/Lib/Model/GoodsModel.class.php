<?php
// +----------------------------------------------------------------------
// | Fanwe 多语商城建站系统 (Build on ThinkPHP)
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

//商品
class GoodsModel extends MultiLangModel {
	protected $_validate = array(
			array('name','require',GOODS_NAME_REQUIRE), 
			array('market_price','is_numeric',MARKET_PRICE_MUST_BE_NUM,2,'function'), 
			array('shop_price','is_numeric',SHOP_PRICE_MUST_BE_NUM,2,'function'), 
			array('sort','is_numeric',SORT_MUST_BE_NUM,2,'function'), 
			array('stock','is_numeric',STOCK_MUST_BE_NUM,2,'function'), 
			array('promote_begin_time','checkDateFormat',PROMOTE_BEGIN_TIME_ERROR,2,'function'),
			array('promote_end_time','checkDateFormat',PROMOTE_END_TIME_ERROR,2,'function'),
			array('group_bond_end_time','checkDateFormat',"团购券过期时间格式错误",2,'function'),
			array('u_name','checkGoodsUName','别名不能重复',2,'function'),
		);
		
	protected $_auto = array ( 		
		//array('status','1'),  // 新增的时候把status字段设置为1
		array('create_time','gmtTime',1,'function'), // 对create_time字段在插入的时候写入当前时间戳	
		array('update_time','gmtTime',3,'function'), 
		array('sn','genGoodsSn',3,'function'), 	   //未填写时自动生成货号
		array('promote_price','shop_price',3,'field'), 
		array('promote_begin_time','localStrToTimeMin',3,'function'), 	   
		array('promote_end_time','localStrToTimeMax',3,'function'), 	   
		array('group_bond_end_time','localStrToTimeMax',3,'function'),
	);
	
	/**
	 * 查询团购商品
	 */
	public function getGoodsItem($goodsID = 0,$cityID = 0)
	{
		$curr_lang_id = D("LangConf")->where("lang_name ='".FANWE_LANG_SET."'")->getField('id');
		$time = gmtTime();
		if($goodsID == 0)
		{			   
			$where = " status = 1 AND promote_begin_time <= $time AND promote_end_time >= $time ";
			
			if($cityID == 0)
			{
				$city = D("GroupCity")->where("status=1")->order("is_defalut desc,id asc")->find();
				$where .= " AND city_id = $city[id]";
			}
			else
			{
				$where .= " AND city_id = $cityID";
			}
			
			$item = $this->where($where)->order("sort desc,id desc")->find();
		}
		else
			$item = $this->where("id = $goodsID and status = 1")->find();
			
		//dump(fanweC("URL_ROUTE"));	
		
		if($item)
		{
			if(fanweC("URL_ROUTE")==1)
				$item['url'] = U("tg/".$item['id']);
			elseif(fanweC("URL_ROUTE")==2)
			{
				$arr = explode(" ",$item['name_'.FANWE_LANG_ID]);
				$url = '';
				$py=new Pinyin();
				foreach($arr as $idx => $ar)
				{
					$ar = $py->complie($ar);
					$prefix = "";
					$prefix = preg_replace("/\d/","",$ar);
					$prefix = preg_replace("/[^a-zA-Z-]/","",$prefix);
					$url.=$prefix.C("URL_PATHINFO_DEPR");
				}
				$url=strtolower($url);
				$item['url'] = 'g'.C("URL_PATHINFO_DEPR").$url.$item['id'].C("URL_HTML_SUFFIX"); 
			}
			else
				$item['url'] = U("Goods/show",array('id'=>$item['id']));
				
			$item['update_time_format']  = toDate($item['update_time']);
			$item['create_time_format']  = toDate($item['create_time']);
			$item['promote_begin_time_format']  = toDate($item['promote_begin_time']);
			$item['promote_end_time_format']  = toDate($item['promote_end_time']);			
			$item['promote_price_format'] = formatPrice($item['promote_price']);
			$item['market_price_format'] = '￥'.floatval($item['market_price']);
			$item['shop_price_format'] = '￥'.floatval($item['shop_price']);
			$item['weight_format'] = formatWeight($item['weight'],$item['id']);
			$item['score_format'] = formatScore($item['score']);
			$item['max_score_format'] = formatScore($item['max_score']);
			$item['urlname'] = urlencode($item['name_'.$curr_lang_id]);
			$item['urlbrief'] = urlencode($item['brief_'.$curr_lang_id]);
			$item['brief'] = nl2br($item['brief_'.$curr_lang_id]);
			$item['earnest_money_format'] = (floatval($item['earnest_money']) == 0) ? "免费" :'￥'.floatval($item['earnest_money']);
			
			
			
			if(intval($item['promote_end_time']) <  $time)
				$item['is_end'] = true;
			
			$mail = D("MailTemplate")->where("name = 'share'")->find();
			$mail['mail_title'] = str_replace('{$title}',$item['name_'.$curr_lang_id], $mail['mail_title']);
			$mail['mail_content'] = str_replace('{$title}',$item['name_'.$curr_lang_id], $mail['mail_content']);
			
			$item['urlgbname'] = urlencode(utf8ToGB($mail['mail_title']));
			$item['urlgbbody'] = urlencode(utf8ToGB($mail['mail_content']));
			
			if($item['small_img']=='')
				$item['small_img'] = fanweC("NO_PIC");
			if($item['big_img']=='')
				$item['big_img'] = fanweC("NO_PIC");
			if($item['origin_img']=='')
				$item['origin_img'] = fanweC("NO_PIC");
				
			$item['discountfb'] = round(($item['shop_price'] / $item['market_price']) * 10,2);
			$item['save'] = '￥'.floatval($item['market_price'] - $item['shop_price']);
			$item['endtime'] = $item['promote_end_time'] - gmtTime();
			$item['user_count'] = intval($item['user_count']);
			$item['userBuyCount'] = 0;
			
			//return $item; exit;
			
			if(intval($_SESSION['user_id']) > 0)
			{
				//$item['userBuyCount'] = intval(D("OrderGoods")->where("rec_id=".intval($item['id'])." and user_id=".intval($_SESSION['user_id']))->sum("number"));
				//modify by chenfq 2010-05-02
				$sql = "select sum(number) as num from ".C("DB_PREFIX")."order_goods where rec_id = ".intval($item['id'])." and user_id=".intval($_SESSION['user_id']);
				$num = M()->query($sql);
				$item['userBuyCount'] = intval($num[0]['num']);
			}
			
			/*$userCount = M()->query("select o.user_id as userCount from ".M("OrderGoods")->getTableName()." as og left join ".M("Order")->getTableName()." as o on o.id = og.order_id AND o.money_status = 2 where og.rec_id = '$item[id]' AND o.user_id >0 group by o.user_id");
			
			$item['userCount'] = count($userCount);
			
			$orderGoodsCountArr = M()->query("select COALESCE(sum(og.number),0) as orderGoodsCount from ".M("OrderGoods")->getTableName()." as og left join ".M("Order")->getTableName()." as o on o.id = og.order_id AND o.money_status = 2 where og.rec_id = '$item[id]' and og.id is not null");
			
			$orderGoodsCount = $orderGoodsCountArr[0]['orderGoodsCount'];*/
			
			$item['surplusCount'] = 0;
			
			if(intval($item['stock']) > 0)
			{
				$item['surplusCount'] = intval($item['stock']) - intval($item['buy_count']);
				if($item['surplusCount'] <= 0)
					$item['is_none'] = true;
					
				$item['stockbfb'] = ($item['surplusCount'] / intval($item['stock'])) * 100;
			}
			
			
			
			if($item['complete_time'] > 0)
				$item['complete_time'] = toDate($item['complete_time'],'H点i分');
			else
				$item['complete_time'] = "";
			
			$item['progress_pointer'] = round(($item['buy_count'] / intval($item['group_user'])) * 186);
			$item['progress_left'] = $item['progress_pointer'] -1;
			
			$item['progress_left'] = $item['progress_pointer'] -1;
			
			$item['reviews_list'] = D("GoodsReviews")->where("goods_id='$item[id]'")->order("id asc")->findAll();
			$item['suppliers'] = D("Suppliers")->where("id='$item[suppliers_id]'")->find();
			
			$gallery = D("GoodsGallery")->where('goods_id='.$item['id'])->order("is_default desc")->findAll();
			$item['gallery'] = $gallery;
		}
		
		//print_r($item);
		//exit;
		return $item;
	}
		
}
?>