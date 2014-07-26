<?php
//获取所有子集的类
class ChildIds
{
	public function __construct($tb_name)
	{
		$this->tb_name = $tb_name;
	}
	private $tb_name;
	private $childIds;
	private function _getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid')
	{
		$childItem_arr = $GLOBALS['db']->getAllCached("select id from ".DB_PREFIX.$this->tb_name." where ".$pid_str."=".intval($pid));
		if($childItem_arr)
		{
			foreach($childItem_arr as $childItem)
			{
				$this->childIds[] = $childItem[$pk_str];
				$this->_getChildIds($childItem[$pk_str],$pk_str,$pid_str);
			}
		}
	}
	public function getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid')
	{
		$this->childIds = array();
		$this->_getChildIds($pid,$pk_str,$pid_str);
		return $this->childIds;
	}
}
/**
 * 检查是否关闭
 *
 */
function check_closed()
{
	if(a_fanweC('SHOP_CLOSED'))
	{
		//输出当前页seo内容
		$data = array(
			    	'navs' => array()
		 );
		assignSeo ( $data );
		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		//输出帮助
		$currentCity = $GLOBALS['db']->getRowCached("SELECT name FROM ".DB_PREFIX."group_city where id = ".C_CITY_ID);
	   	$GLOBALS['tpl']->assign("currentCity",$currentCity);
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
		$GLOBALS['tpl']->assign("CLOSE_NOTICE",a_fanweC("CLOSE_NOTICE"));
		$GLOBALS['tpl']->display("Page/close_index.moban");
		exit();
	}
}
/**
 * 错误提示页面
 * @param string $msg 提示内容
 * @param string $title 标题
 * @param uri $jumpUrl 跳转页面
 */
function a_error($msg, $title='', $jumpUrl=''){
	if (empty($title))
		$title = a_L("_OPERATION_FAIL_");

	if (empty($jumpUrl))
		$jumpUrl = $_SERVER['HTTP_REFERER'];
	elseif($jumpUrl=='back')
		$jumpUrl ="javascript:history.back()";

	$GLOBALS['tpl']->assign("error_msg",$msg);
	$GLOBALS['tpl']->assign("title",$title);
	$GLOBALS['tpl']->assign("fail_title",$title);
	$GLOBALS['tpl']->assign("jumpUrl",$jumpUrl);
	$navs = array('name'=>$msg,'url'=>$jumpUrl);
	$data = array(
		    		'navs' => array(
		    			$navs,
		    		),
		    		'keyword'=>	"",
		    		'content'=>	"",
		    	);
	assignSeo($data);
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$GLOBALS['tpl']->display("Page/error_index.moban");
	//redirect2(__ROOT__."/index.php?m=Error&msg=".base64_encode(base64_encode($msg))."&title=".base64_encode(base64_encode($title))."&jumpUrl=".base64_encode(base64_encode($jumpUrl)));
	exit;
}

/**
 * 成功提示页面
 * @param $msg 提示内容
 * @param $title 标题
 * @param $jumpUrl 跳转页面
 */
function success($msg, $title, $jumpUrl){
	if (empty($title))
		$title = a_L("_OPERATION_SUCCESS_");
	if (empty($jumpUrl))
		$jumpUrl = $_SERVER['HTTP_REFERER'];
	elseif($jumpUrl=='back')
		$jumpUrl ="javascript:history.back()";

	//ucenter整合登陆，退出接口调用
	if (isset($_SESSION['ucdata'])){
		$ucdata = base64_decode($_SESSION['ucdata']);
		$_SESSION['ucdata'] = '';
		unset($_SESSION['ucdata']);
		if (empty($msg)){
			$msg = $ucdata;
		}else{
			$msg = $msg.$ucdata;
		}
	}
	$GLOBALS['tpl']->assign("success_msg",$msg);
	$GLOBALS['tpl']->assign("success_title",$title);
	$GLOBALS['tpl']->assign("title",$title);
	$GLOBALS['tpl']->assign("jumpUrl",$jumpUrl);
	$navs = array('name'=>$msg,'url'=>$jumpUrl);
	$data = array(
		    		'navs' => array(
		    			$navs,
		    		),
		    		'keyword'=>	"",
		    		'content'=>	"",
		    	);
	assignSeo($data);
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$GLOBALS['tpl']->display("Page/success_index.moban");
	//redirect2(__ROOT__."/index.php?m=Success&msg=".base64_encode(base64_encode($msg))."&title=".base64_encode(base64_encode($title))."&jumpUrl=".base64_encode(base64_encode($jumpUrl)));
	exit;
}
/**
 * 获取当前域名前缀
 */
function a_getHttp()
{
	return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
}
/**
 * 获取当前域名
 */
function a_getDomain()
{
	/* 协议 */
	$protocol = a_getHttp();

	/* 域名或IP地址 */
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
	{
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	elseif (isset($_SERVER['HTTP_HOST']))
	{
		$host = $_SERVER['HTTP_HOST'];
	}
	else
	{
		/* 端口 */
		if (isset($_SERVER['SERVER_PORT']))
		{
			$port = ':' . $_SERVER['SERVER_PORT'];

			if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
			{
				$port = '';
			}
		}
		else
		{
			$port = '';
		}

		if (isset($_SERVER['SERVER_NAME']))
		{
			$host = $_SERVER['SERVER_NAME'] . $port;
		}
		elseif (isset($_SERVER['SERVER_ADDR']))
		{
			$host = $_SERVER['SERVER_ADDR'] . $port;
		}
	}

	return $protocol . $host;
}

/**
 * 创建像这样的查询: "IN('a','b')";
 *
 * @access   public
 * @param    mix      $item_list      列表数组或字符串
 * @param    string   $field_name     字段名称
 *
 * @return   void
 */
function a_db_create_in($item_list, $field_name = '')
{
	if (empty($item_list))
	{
		return $field_name . " IN ('') ";
	}
	else
	{
		if (!is_array($item_list))
		{
			$item_list = explode(',', $item_list);
		}
		$item_list = array_unique($item_list);
		$item_list_tmp = '';
		foreach ($item_list AS $item)
		{
			if ($item !== '')
			{
				$item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
			}
		}
		if (empty($item_list_tmp))
		{
			return $field_name . " IN ('') ";
		}
		else
		{
			return $field_name . ' IN (' . $item_list_tmp . ') ';
		}
	}
}

/**
 * 获取星期几
 * @param $time
 */
function a_getDateWeek($time)
{
	if (empty ( $time )) {
		return '';
	}
	$time = $time+(FANWE_TIME_ZONE * 3600);
	$week = date ("w",$time);
	$chWeek = array(a_L("HC_DAY"),a_L("HC_ONE"),a_L("HC_TWO"),a_L("HC_THREE"),a_L("HC_FOUR"),a_L("HC_FIVE"),a_L("HC_SIX"));
	return $chWeek[$week];
}

/**
 * 截取
 * @param $str 内容
 * @param $start 开始位置
 * @param $length 结束位置
 * @param $charset 编码
 * @param $suffix 显示...
 */
function a_msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
	if(function_exists("mb_substr"))
	{
		if($suffix)
			return mb_substr($str, $start, $length, $charset)."…";
		else
			return mb_substr($str, $start, $length, $charset);
	}
	elseif(function_exists('iconv_substr')) {
		if($suffix)
			return iconv_substr($str,$start,$length,$charset."…");
		else
			return iconv_substr($str, $start, $length, $charset);
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."…";
	return $slice;
}

/**
 * UTF8 转 GBK
 * @param $str
 */
function a_utf8ToGB($str)
{
	include_once(ROOT_PATH."ThinkPHP/Vendor/iconv.php");
	$chinese = new Chinese();
	return $chinese->Convert("UTF-8","GBK",$str);
}

/**
 * GBK 转 UTF8
 * @param $str
 */
function a_gbToUTF8($str)
{
	include_once(ROOT_PATH."ThinkPHP/Vendor/iconv.php");
	$chinese = new Chinese();
	return $chinese->Convert("GBK","UTF-8",$str);
}


function U2($module, $action){
	$url = 'index.php?m='.$module.'&a='.$action;
	return $url;
}

/**
 * url重写  
 * @param $url Index/index
 * @param $param 数组
 */
function a_u($url,$param='')
{
	if ($url == 'UcBelowOrder/pay'){
		$url = 'Order/pay';
	}
	if ($url == 'UcOrder/modifyPayment'){
		$url = 'Order/check';
	}
	$u_mode = explode("/",$url);
	if(a_fanweC("URL_ROUTE") == 0)
	{
		$url = __ROOT__."/index.php?m=".$u_mode[0]."&a=".$u_mode[1];
		//var_dump($param);
		if($param!='')
		{
			$param = explode("|",$param);
			//var_dump($param);
			foreach($param as $v)
			{

				$v = explode("-",$v);
				//var_dump($v);
				$url.="&".$v[0]."=".$v[1];
			}
		}
		//echo $url; exit;
		return $url;
	}
	else
	{
		if(strtolower($u_mode[0]) == 'rss')
		{
			$url =__ROOT__."/".$u_mode[0];
			$v = explode("-",$param);
			$url.="-".$v[1];
			$url.=".html";
		}
		else {
			$url = __ROOT__."/".$u_mode[0]."-".$u_mode[1];
			if($param!='')
			{
				$param = explode("|",$param);
				foreach($param as $v)
				{
					$v = explode("-",$v);
					$url.="-".$v[0]."-".$v[1];
				}
			}
			//modfy by chenfq 2011-05-20 修正url重写时，多出 - 错误
			$url = str_replace('---','-',$url);
			$url = str_replace('--','-',$url);			
			$url.=".html";
		}
		return $url;
	}
}
/**
 * 格式价格
 * @param unknown_type $price
 */
function a_formatPrice($price)
{
	$unit = a_fanweC("BASE_CURRENCY_UNIT");
	return sprintf($unit,round(floatval($price),2));
}
/**
 * 格式时间
 * @param unknown_type $time
 * @param unknown_type $format
 */
function a_toDate($time, $format = 'Y-m-d H:i:s')
{
	if (empty ($time))
		return '';

	$time = $time + $GLOBALS['timezone'] * 3600;
	$format = str_replace ('#',':',$format );
	return date ($format,$time );
}

/**
 * 转为时间戳
 * @param $time
 */
function a_strtotime($time)
{
	if (empty ($time))
		return '';

	$time = strtotime($time) - $GLOBALS['timezone'] * 3600;
	return $time;
}

/**
 * 检查邮箱
 * @param $email
 */
function a_checkEmail($email){
       if (empty($email) || !ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$email))
       {
           return false;
       }else{
           return true;
       }
 }


// URL重定向
function redirect2($url,$time=0,$msg='')
{
	//多行URL地址支持
	$url = str_replace(array("\n", "\r"), '', $url);
	if(empty($msg))
	  $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
	if (!headers_sent()) {
	   if(0===$time) {
	      header("Location: ".$url);
	   }else {
	      header("refresh:{$time};url={$url}");
	      echo($msg);
	   }
	   exit();
	}else {
	   $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
	   if($time!=0)
	      $str .= $msg;
	   exit($str);
	}
}

//截取
function sub_str($str, $length = 0, $append = true)
{
    $str = trim($str);
    $strlength = strlen($str);

    if ($length == 0 || $length >= $strlength)
    {
        return $str;
    }
    elseif ($length < 0)
    {
        $length = $strlength + $length;
        if ($length < 0)
        {
            $length = $strlength;
        }
    }

    if (function_exists('mb_substr'))
    {
        $newstr = mb_substr($str, 0, $length, "UTF-8");
    }
    elseif (function_exists('iconv_substr'))
    {
        $newstr = iconv_substr($str, 0, $length, "UTF-8");
    }
    else
    {
        //$newstr = trim_right(substr($str, 0, $length));
        $newstr = substr($str, 0, $length);
    }

    if ($append && $str != $newstr)
    {
        $newstr .= '...';
    }

    return $newstr;
}

/**
 * 检查是不是从同一个域名跳转过来的
 */
function check_referer()
{
	$str =strtolower($_SERVER['HTTP_REFERER']);
	if($str=='' || strpos($str,a_getDomain())===false)
	{
		return false;
	}
	return true;
}

function getGoodsData($id)
{
	$sql = "SELECT `id`,`name_1`,`sn`,`cate_id`,`city_id`,`suppliers_id`,`score_goods`,`click_count`,`cost_price`,`shop_price`,`market_price`,`promote_price`,`promote_begin_time`,`promote_end_time`,`create_time`,`update_time`,`type_id`,`goods_type`,`stock`,`brief_1`,`brand_id`,`is_best`,`is_hot`,`is_new`,`status`,`sort`,`seokeyword_1`,`seocontent_1`,`goods_desc_1`,`small_img`,`big_img`,`origin_img`,`define_small_img`,`is_define_small_img`,`is_inquiry`,`weight`,`spec_type`,`weight_unit`,`score`,`web_reviews`,`goods_reviews`,`min_user_time`,`special_note`,`max_bought`,`is_group_fail`,`complete_time`,`buy_count`,`group_user`,`user_count`,`earnest_money`,`group_bond_end_time`,`expand1`,`expand2`,`expand3`,`expand4`,`u_name`,`referrals`,`close_referrals`,`goods_short_name`,`fail_buy_count`,`free_delivery_amount`,`allow_combine_delivery`,`allow_sms` FROM ".$GLOBALS['db_config']['DB_PREFIX']."goods WHERE id=$id";
	$goods = $GLOBALS['db']->getRow($sql);
	$goods['name'] = $goods['name_1'];
	$goods['market_price'] = floatval($goods['market_price']);
	$goods['shop_price'] = floatval($goods['shop_price']);
	$goods['earnest_money'] = floatval($goods['earnest_money']);
	$goods['market_price_format'] = a_formatPrice(floatval($goods['market_price']));
	$goods['shop_price_format'] = a_formatPrice(floatval($goods['shop_price']));
	$goods['earnest_money_format'] = (floatval($goods['earnest_money']) == 0) ? a_L("COMMON_INFO_1") :a_formatPrice(floatval($goods['earnest_money']));

	if(intval($goods['promote_end_time']) < a_gmtTime())
		$goods['is_end'] = true;

	if(intval($goods['stock']) > 0)
	{
		$goods['surplusCount'] = intval($goods['stock']) - intval($goods['buy_count']);
		if($goods['surplusCount'] <= 0)
			$goods['is_none'] = true;

		$goods['stockbfb'] = ($goods['surplusCount'] / intval($goods['stock'])) * 100;
	}


	if($goods['promote_end_time'] < a_gmtTime())
	{
		if (($goods['group_user'] >= 0 && $goods['group_user'] > $goods['buy_count']))
		{
			$goods['is_group_fail'] = 1;
			$goods['complete_time'] = a_gmtTime();
		}
		else
		{
			$goods['is_group_fail'] = 2;
			$goods['complete_time'] = a_gmtTime();
		}
	}

	if($goods['complete_time'] > 0)
		$goods['complete_time_format'] = a_toDate($goods['complete_time'], a_L('XY_TIMES_MOD_2'));
	else
		$goods['complete_time_format'] = "";

	$goods['rest_count'] = $goods['group_user'] - $goods['buy_count'];


	if(a_fanweC("URL_ROUTE")==1)
	{
		if($goods['u_name']!='')
			$goods['url'] = a_u("g/".rawurlencode($goods['u_name']));
		else
			$goods['url'] = a_u("tg/".$goods['id']);
		$goods['ref_url'] = __ROOT__."/tg-".$goods['id'].'-ru-'.intval($_SESSION['user_id']).'.html';
	}else
	{
		$goods['url'] = __ROOT__.'index.php?m=Goods&a=show&id='.$goods['id'];
		$goods['ref_url'] = __ROOT__.'/index.php?m=Goods&a=show&id='.$goods['id'].'&ru='.intval($_SESSION['user_id']);
	}

	$goods['ref_urllink'] = a_getDomain().$goods['ref_url'];
	$goods['ref_urllink']=urlencode($goods['ref_urllink']);


	$sql = "select mail_title, mail_content from  ".DB_PREFIX."mail_template where name = 'share'";
	$mail = $GLOBALS['db']->getRowCached($sql);
	$mail['mail_title'] = str_replace('{$title}',$goods['name_1'], $mail['mail_title']);
	$mail['mail_content'] = str_replace('{$title}',$goods['name_1'], $mail['mail_content']);



	if (a_fanweC('DEFAULT_LANG') == 'en-us'){
		$goods['urlgbname'] = $mail['mail_title'];
		$goods['urlgbbody'] = $mail['mail_content'];
	}else{
		$goods['urlgbname'] = urlencode(a_utf8ToGB($mail['mail_title']));
		$goods['urlgbbody'] = urlencode(a_utf8ToGB($mail['mail_content']));
	}
	$goods['urllink'] = a_getDomain().$goods['url'];
	$goods['urlweb'] = a_getDomain().$goods['url'];
	$goods['urlname'] = urlencode($goods['name_1']);
	$goods['urlbrief'] = urlencode($goods['brief_1']);

	return $goods;
}

	function getGoodsItem($id = 0,$cityID = 0,$Preview = false,$cate_id=0 ,$qid=0,$is_list=false)
	{
		$time = a_gmtTime();
		$childIdsUtil = new ChildIds("group_city");
		if($id === 0)
		{
			$sql = "SELECT `id`,`name_1`,`sn`,`cate_id`,`quan_id`,`city_id`,"
				."`suppliers_id`,`click_count`,`cost_price`,`shop_price`,"
				."`market_price`,`promote_price`,`promote_begin_time`,`promote_end_time`,"
				."`create_time`,`update_time`,`type_id`,`goods_type`,`score_goods`,`stock`,`brief_1`,"
				."`brand_id`,`is_best`,`is_hot`,`is_new`,`status`,`sort`,`seokeyword_1`,"
				."`seocontent_1`,`goods_desc_1`,`small_img`,`big_img`,`origin_img`,"
				."`define_small_img`,`is_define_small_img`,`is_inquiry`,`weight`,`spec_type`,"
				."`weight_unit`,`score`,`web_reviews`,`goods_reviews`,`min_user_time`,`special_note`,"
				."`max_bought`,`is_group_fail`,`complete_time`,`buy_count`,`group_user`,`user_count`,"
				."`earnest_money`,`group_bond_end_time`,`expand1`,`expand2`,`expand3`,`expand4`,`u_name`,"
				."`referrals`,`close_referrals`,`goods_short_name`,`fail_buy_count`,`free_delivery_amount`,"
				."`allow_combine_delivery`,`allow_sms`,`referral_money`,`fix_delivery_money`,`seo_title` FROM ".$GLOBALS['db_config']['DB_PREFIX']."goods "
				."where " //`referral_money`,`fix_delivery_money` add by chenfq 2011-03-05添加这：购买返现，固定运费字段
				."(status = 1 AND ((promote_begin_time <= $time and promote_end_time >= $time) or (is_preview = 1 and promote_end_time >= $time))) "; //(score_goods = 0 or score_goods = 1) and //0:普通商品;1:积分商品;2:抽奖商品; add by chenfq 2011-01-05
			if($cityID == 0 )
			{
				$cityID = $GLOBALS['db']->getOneCached("SELECT id FROM ".DB_PREFIX."group_city WHERE status=1 ORDER BY is_defalut desc,id asc");
				$city_ids = $childIdsUtil->getChildIds($cityID);
				array_push($city_ids,$cityID);
			}
			else {
				$city_ids = $childIdsUtil->getChildIds(C_CITY_ID);
				array_push($city_ids,C_CITY_ID);
			}

			$sql .= " AND (city_id in (".implode(",",$city_ids).") or all_show = 1)";

			if($cate_id>0)
			{
				$cate_ids = $childIdsUtil->getChildIds($cate_id);
				array_push($cate_ids,$cate_id);
				$sql .= " AND (cate_id in (".implode(",",$cate_ids).") or extend_cate_id in (".implode(",",$cate_ids)."))";
			}
			if($qid>0)
			{
				$sql .= " AND quan_id=".$qid;
			}
		}
		else
		{
			$sql = "SELECT `id`,`name_1`,`sn`,`cate_id`,`quan_id`,`city_id`,"
			."`suppliers_id`,`click_count`,`cost_price`,`shop_price`,"
			."`market_price`,`promote_price`,`promote_begin_time`,"
			."`promote_end_time`,`create_time`,`update_time`,`type_id`,"
			."`goods_type`,`stock`,`brief_1`,`brand_id`,`is_best`,`is_hot`,"
			."`is_new`,`status`,`sort`,`seokeyword_1`,`seocontent_1`,`goods_desc_1`,"
			."`small_img`,`big_img`,`origin_img`,`define_small_img`,"
			."`is_define_small_img`,`is_inquiry`,`weight`,`spec_type`,"
			."`weight_unit`,`score`,`score_goods`,`web_reviews`,`goods_reviews`,"
			."`min_user_time`,`special_note`,`max_bought`,`is_group_fail`,"
			."`complete_time`,`buy_count`,`group_user`,`user_count`,`earnest_money`,"
			."`group_bond_end_time`,`expand1`,`expand2`,`expand3`,`expand4`,"
			."`u_name`,`referrals`,`close_referrals`,`goods_short_name`,"
			."`fail_buy_count`,`free_delivery_amount`,`allow_combine_delivery`,"
			."`allow_sms`,`referral_money`,`fix_delivery_money`,`seo_title` FROM ".$GLOBALS['db_config']['DB_PREFIX']."goods WHERE id=$id";
		}
		if($_REQUEST['m']=='Goods' && $_REQUEST['a']=='showcate')
		{
			$sql .=" AND `score_goods`<>1 and ((promote_begin_time <= $time and promote_end_time >= $time) or promote_end_time >= $time) ";
		}
		else
		{
			if(!$Preview && $id == 0)
			{
				$sql .=" AND ((promote_begin_time <= $time and promote_end_time >= $time) or (is_preview = 1 and promote_end_time >= $time)) AND `score_goods`<>1 ";
			}
		}

		if($_REQUEST['m'] == 'Index' && $_REQUEST['a'] =="index")
		{
			$sql .= ' AND no_show_index =0 ';
		}
		if($_REQUEST['m']=='Goods' && $_REQUEST['a']=='showcate')
			$sql .=" order by promote_begin_time desc,sort desc,id desc";
		else
			$sql .=" order by sort desc,promote_begin_time desc,id desc";

		$item = $GLOBALS['db']->getRow($sql);
		//echo $sql;
		if($item)
		{
			$item['update_time_format']  = a_toDate($item['update_time']);
			if($item['complete_time'] > 0)
				$item['complete_time_format'] = a_toDate($item['complete_time'],a_L('XY_TIMES_MOD_2'));
			else
				$item['complete_time_format'] = "";
				
			$item['create_time_format']  = a_toDate($item['create_time']);
			$item['promote_begin_time_format']  = a_toDate($item['promote_begin_time']);
			$item['promote_end_time_format']  = a_toDate($item['promote_end_time']);
			$item['brief'] = $item['brief_1'];
			$item['earnest_money_format'] = (floatval($item['earnest_money']) == 0) ? a_L("COMMON_INFO_1") :a_formatPrice(floatval($item['earnest_money']));

			if(intval($item['promote_end_time']) <  $time)
				$item['is_end'] = true;

			if(floatval($item['market_price'])!=0)
			{
				if (a_fanweC('DEFAULT_LANG') == 'en-us')
				{
					$item['discountfb'] = round((1-($item['shop_price'] / $item['market_price'])) * 100,1);
				}
				else
				{
					$item['discountfb'] = round(($item['shop_price'] / $item['market_price']) * 10,2);
				}
			}
			else
				$item['discountfb'] = 0;

			$item['save'] = a_formatPrice(floatval($item['market_price'] - $item['shop_price']));
			$item['shop_price_format'] = a_formatPrice($item['shop_price']);
			$item['market_price_format'] = a_formatPrice($item['market_price']);
			$item['endtime'] = $item['promote_end_time'] - a_gmtTime();
			$item['user_count'] = intval($item['user_count']);
			$item['userBuyCount'] = 0;
			$item['rest_count'] = intval($item['group_user']) - intval($item['buy_count']);


			if(intval($_SESSION['user_id']) > 0)
			{
				//modify chenfq by 2011-03-01 不统计作废订单数量
				$sql = "select sum(og.number) as num from ".DB_PREFIX."order_goods as og "
					  ." left outer join ".DB_PREFIX."order o on o.id = og.order_id "
					  ."where o.status <> 2 and og.rec_id = ".intval($item['id'])." and og.user_id=".intval($_SESSION['user_id']);
				$num = $GLOBALS['db']->getOne($sql);
				$item['userBuyCount'] = intval($num);
			}

			$sql = "select mail_title, mail_content from  ".DB_PREFIX."mail_template where name = 'share'";
			$mail = $GLOBALS['db']->getRowCached($sql);
			$mail['mail_title'] = str_replace('{$title}',$item['name_1'], $mail['mail_title']);
			$mail['mail_content'] = str_replace('{$title}',$item['name_1'], $mail['mail_content']);

			$item['buy_url'] =  a_u("Cart/index","id-".intval($item['id']));
			$item['url'] = a_u("Goods/show","id-".intval($item['id']));
			$item['ref_urllink'] = a_getDomain().a_u("Goods/show","id-".intval($item['id'])."|ru-".intval($_SESSION['user_id']));
                        $item['ref_urllinkq'] = a_getDomain().a_u("Goods/show","id-".intval($item['id'])."|ru-".intval($_SESSION['user_id']));
			$item['ref_urllink']=urlencode($item['ref_urllink']);
			$item['urlname'] = urlencode($item['name_1']);

			if (a_fanweC('DEFAULT_LANG') == 'en-us'){
				$item['urlgbname'] = $mail['mail_title'];
				$item['urlgbbody'] = $mail['mail_content'];
			}else{
				$item['urlgbname'] = urlencode(a_utf8ToGB($mail['mail_title']));
				$item['urlgbbody'] = urlencode(a_utf8ToGB($mail['mail_content']));
			}
			$item['urllink'] = $item['ref_urllink'];
			$item['urlweb'] = $item['ref_urllink'];
			$item['urlbrief'] = urlencode($item['brief_1']);
			$item['surplusCount'] = 0;
			$item['group_user'] = intval($item['group_user']);


			if(intval($item['stock']) > 0)
			{
				$item['surplusCount'] = intval($item['stock']) - intval($item['buy_count']);
				if($item['surplusCount'] <= 0)
					$item['is_none'] = true;

				$item['stockbfb'] = ($item['surplusCount'] / intval($item['stock'])) * 100;
			}

			$list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."goods_attr where goods_id=".$id." and attr_value_1 <> ''");
			if($list)
			{
				foreach($list as $k=>$v)
				{
					$vv = $v['attr_value_1'];
					$value_item['value'] = $vv;
					$v['value_list'][] = $value_item;
					$v['value'] = $vv;

					$result[$v['attr_id']]['attr_value'][] = $v;
				}
				foreach($result as $k=>$v)
				{
					$result[$k]['attr_info'] = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."goods_type_attr where id=".$k);
					$result[$k]['attr_info']['name'] = $result[$k]['attr_info']['name_1'];
				}
			}

			$item['attrlist'] = $result;

			if(!$is_list)
			{
				if(!$Preview)
				{
					$item['reviews_list'] = $GLOBALS['db']->getAllCached("SELECT * FROM ".DB_PREFIX."goods_reviews WHERE goods_id='".$item['id']."' order by id asc");
					if(intval($item[suppliers_id])>0){
						$item['suppliers'] = $GLOBALS['db']->getRowCached("SELECT * FROM ".DB_PREFIX."suppliers where id='".$item['suppliers_id']."'");
						$item['suppliers_list'] = $GLOBALS['db']->getAllCached("SELECT * FROM ".DB_PREFIX."suppliers_depart where supplier_id=".$item['suppliers']['id']." order by is_main desc");
					}
				}
				else {
					$item['reviews_list'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."goods_reviews WHERE goods_id='".$item['id']."' order by id asc");
					if(intval($item[suppliers_id])>0){
						$item['suppliers'] = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."suppliers where id='".$item['suppliers_id']."'");
						$item['suppliers_list'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."suppliers_depart where supplier_id=".$item['suppliers_id']." order by is_main desc");
					}
				}

				if($item['suppliers_list'])
				{
					foreach($item['suppliers_list'] as $kk=>$vv)
					{
						if($vv['map']==''&&$vv['api_address']!='')
						{
							//$vv['map'] = "http://ditu.google.cn/maps?f=q&source=s_q&hl=zh-CN&geocode=&q=".$vv['api_address'];
							if (a_fanweC('DEFAULT_LANG') == 'en-us')
							{
								$vv['map'] = "http://ditu.google.cn/maps?f=q&source=s_q&hl=zh-CN&geocode=&q=".$vv['api_address'];
							}
							else
							{
								$url_b="&wd=".$vv['api_address']."".$vv['depart_name']."&c=131&src=0&wd2=&sug=0";
								$url_b=urlencode($url_b);						
								$vv['map']="http://map.baidu.com/?newmap=1&l=18&c=".$vv['xpoint'].",".$vv['ypoint']."&s=s".$url_b."&sc=0";
							}					
						
						}
						$item['suppliers_list'][$kk] = $vv;
					}
				}
				
				if($item['suppliers_list'][0]['api_address']!='')
				{
					$filename = ROOT_PATH."/Public/upload/ditu/".md5($item['suppliers_list'][0]['depart_name']).".jpg";
	
					if(is_file($filename))
						$item['map_img'] = "/Public/upload/ditu/".md5($item['suppliers_list'][0]['depart_name']).".jpg";
				}	
				if(!$Preview)
				{
					$item['gallery'] = $GLOBALS['db']->getAllCached("SELECT * FROM ".DB_PREFIX."goods_gallery where goods_id='{$item['id']}' order by is_default desc");
				}
				else
				{
					$item['gallery'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."goods_gallery where goods_id='{$item['id']}' order by is_default desc");
				}
			}
		}

		return $item;
	}

	/**
 * 递归方式的对变量中的特殊字符进行转义
 *
 * @access  public
 * @param   mix     $value
 *
 * @return  mix
 */
function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}

function delDir($directory)
	{
		if (is_dir($directory) == false)
		{
			return false;
		}

		$handle = @opendir($directory);
		while (($file = readdir($handle)) !== false)
		{
			if ($file != "." && $file != "..")
			{
					@unlink("$directory/$file");
			}
		}
		if (readdir($handle) == false)
		{
			@closedir($handle);
			@rmdir($directory);
		}
		return true;
}

function dotran($str)
{
	$str = str_replace("\r\n",'',$str);
	$str = str_replace("\t",'',$str);
	$str = str_replace("\b",'',$str);
	return $str;
}
			//支付返利
	function s_payReferrals($id)
	{
		$sql = "select * from ".DB_PREFIX."referrals where id =".$id;
		$referrals = $GLOBALS['db']->getRow($sql);

		if ($referrals)
		{
				//现金返利
				$sql = "select * from ".DB_PREFIX."user where id =".$referrals['user_id'];
				$user = $GLOBALS['db']->getRow($sql);
				if($referrals['money'] > 0)
				{
					$msg = addslashes(sprintf(a_L("PAY_REFERRALS_MONEY_INFO"),$user['user_name']));
					$sql_str = 'insert into '.DB_PREFIX."user_money_log(user_id, rec_id,money,create_time,rec_module,memo_1) values($referrals[parent_id],$id,$referrals[money],".a_gmtTime().",'Referrals','$msg')";
					$GLOBALS['db']->query($sql_str);
					$sql_str = 'update '.DB_PREFIX.'user set money = money + '.$referrals['money'].' where id = '.$referrals['parent_id'];
					$GLOBALS['db']->query($sql_str);
				}

				if($referrals['score'] > 0)
				{
					$msg = addslashes(sprintf(a_L("PAY_REFERRALS_SCORE_INFO"),$user['user_name']));
					$sql_str = 'insert into '.DB_PREFIX."user_score_log(user_id, rec_id,score,create_time,rec_module,memo_1) values($referrals[parent_id],$id,$referrals[score],".a_gmtTime().",'Referrals','$msg')";
					$GLOBALS['db']->query($sql_str);
					$sql_str = 'update '.DB_PREFIX.'user set score = score + '.$referrals['score'].' where id = '.$referrals['parent_id'];
					$GLOBALS['db']->query($sql_str);
				}

				$referrals['is_pay'] = 1;
				$referrals['pay_time'] = a_gmtTime();

				$GLOBALS['db']->autoExecute(DB_PREFIX."referrals", addslashes_deep($referrals), 'UPDATE', "id = ".intval($referrals['id']));
		}
	}

	//A8180
	//function searchGoodsList($page=1,$is_all=true,$is_other=false,$cate_id = 0,$suppliers_id = 0){
	function searchGoodsList($page=1,$type_id=0,$is_score = 0, $is_advance = 0,$is_other=0, $cate_id = 0,$suppliers_id = 0 ,$kwd ='',$extwhere='',$order='order by sort desc,promote_end_time desc,id desc',$quan_id=0){
		$filename = md5($_REQUEST["m"].$_REQUEST["a"].$page.$type_id.$is_score.$is_advance.$is_other.$cate_id.$suppliers_id.$kwd.$extwhere.$order.$quan_id.C_CITY_ID).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			$now = a_gmtTime();//取整，要做缓存一分钟
	
			$where = " status = 1 ";
			//只列出允许在首页显示的商品
	    	if($_REQUEST['m'] =="Index" && $_REQUEST['a'] =="index"){
				$where .= " and no_show_index = 0";
			}
	
			if($is_other ==0)
			{
				if(($_REQUEST['m'] =="Index" && $_REQUEST['a'] =="index")||($_REQUEST['m'] =="Goods" && $_REQUEST['a'] =="showcate")){
					$where .= " and (type_id = 0 or type_id = 1 or type_id = 2 or type_id = 3)";
				}
				else
				{
					//线下团购
					if ($type_id == 2){
						$where .= " and type_id = 2 ";
					}
					else
						$where .= " and (type_id = 0 or type_id = 1 or type_id = 3)";
				}
			}
			if ($is_score == 0){
				$where .= " and score_goods <> 1 ";//0:普通商品;1:积分商品;2:抽奖商品; add by chenfq 2011-01-05
			}elseif($is_score ==1){
				$where .= " and score_goods = 1 ";
			}
			if ($is_advance == 0){
				if($is_other ==0)
				{
					if(($_REQUEST['m'] =="Index" && $_REQUEST['a'] =="index")||($_REQUEST['m'] =="Goods" && $_REQUEST['a'] =="showcate")){
						$where .= " and ((promote_begin_time <=".$now." and promote_end_time>=".$now.") or (is_preview=1 and promote_end_time>=".$now."))";
					}
					else
					{
						$where .= " and promote_begin_time <= ".$now." and promote_end_time >= ".$now;
					}					
				}
				elseif($is_other ==1)
				{
					$sis_other = intval(a_fanweC("CLOSE_BEFORE_VIEW_NOW"));
					if ($sis_other == 0){
						$where .= "  and promote_end_time < ".$now;
					}elseif($sis_other ==1){
						$where .= " and promote_begin_time <= ".$now;
					}
				}
				elseif($is_other == 2)
				{
					$where .= " and promote_begin_time <= ".$now;
				}
			}else{
				$where .= " and promote_begin_time >".$now;
			}
	
	
			if ($cate_id > 0){
				$childIdsUtil = new ChildIds("goods_cate");
				 $cate_ids = $childIdsUtil->getChildIds($cate_id);
	             array_push($cate_ids,$cate_id);
				$where .= " AND (cate_id in (".implode(",",$cate_ids).") or extend_cate_id in (".implode(",",$cate_ids).")) ";
			}
			
			if ($quan_id > 0){
				$childIdsUtil = new ChildIds("coupon_region");
				 $quan_ids = $childIdsUtil->getChildIds($quan_id);
	             array_push($quan_ids,$quan_id);
				$where .= " AND (quan_id in (".implode(",",$quan_ids).")) ";
			}
	
			if($suppliers_id > 0)
			{
				$where .= " and suppliers_id =".$suppliers_id;
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
			if (($_REQUEST ['m'] == 'Goods' && $_REQUEST ['a'] == 'showcate')||($_REQUEST ['m'] == 'Index' && $_REQUEST ['a'] == 'index'))
			{
			  $page_count = a_fanweC("GOODS_LIST_NUM");
			}
			else
			{
			 $page_count = a_fanweC("GOODS_PAGE_LISTROWS");
			}
		    $limit = ($page_size-1)*$page_count.",".$page_count;
	
			$sql = "SELECT `id`,`name_1`,`sn`,`cate_id`,`quan_id`,`city_id`,`suppliers_id`,`score_goods`,`click_count`,`cost_price`,`shop_price`,`market_price`,`promote_price`,`promote_begin_time`,`promote_end_time`,`create_time`,`update_time`,`type_id`,`goods_type`,`stock`,`brief_1`,`brand_id`,`is_best`,".
				   "`is_hot`,`is_new`,`status`,`sort`,`seokeyword_1`,`seocontent_1`,`goods_desc_1`,`small_img`,`big_img`,`origin_img`,`define_small_img`,`is_define_small_img`,`is_inquiry`,`weight`,`spec_type`,`weight_unit`,`score`,`web_reviews`,`goods_reviews`,`min_user_time`,".
				   "`special_note`,`max_bought`,`is_group_fail`,`complete_time`,`buy_count`,`group_user`,`user_count`,`earnest_money`,`group_bond_end_time`,`expand1`,`expand2`,`expand3`,`expand4`,`u_name`,`referrals`,`close_referrals`,`goods_short_name`,`fail_buy_count`,`free_delivery_amount`,".
				   "`allow_combine_delivery`,`allow_sms`, (select count(*) from ".DB_PREFIX."message m where m.rec_module = 'Goods' and m.status = 1 and m.rec_id = a.id) as messageCount FROM ".
					DB_PREFIX."goods as a where 1 = 1 and ".$where." {$order} limit ".$limit;
			//echo $sql; exit;
			$goods_list = $GLOBALS['db']->getAll($sql); //getAllCached
	
			$sql = "SELECT count(*) FROM ".DB_PREFIX."goods as a where 1 = 1 and ".$where;
			$result['total'] = $GLOBALS['db']->getOne($sql);
			foreach($goods_list as $k=>$v)
			{
					if(a_fanweC("URL_ROUTE")==1)
					{
						if($v['u_name']!='')
							$goods_list[$k]['url'] = a_u("g/".rawurlencode($v['u_name']));
						else
							$goods_list[$k]['url'] = a_u("tg/".$v['id']);
					}
					else
					{
						$goods_list[$k]['url'] = 'index.php?m=Goods&a=show&id='.$v['id'];
					}
	
					if(intval($goods_list[$k]['stock']) > 0)
					{
						$goods_list[$k]['surplusCount'] = intval($goods_list[$k]['stock']) - intval($goods_list[$k]['buy_count']);
						if($goods_list[$k]['surplusCount'] <= 0)
							$goods_list[$k]['is_none'] = true;
					}
					
					if($goods_list[$k]['complete_time'] > 0)
						$goods_list[$k]['complete_time_format'] = a_toDate($v['complete_time'],a_L('XY_TIMES_MOD_2'));
					else
						$goods_list[$k]['complete_time_format'] = "";
	
				$goods_list[$k]['name'] = $v['name_1'];
				$goods_list[$k]['market_price'] = floatval($v['market_price']);
				$goods_list[$k]['shop_price'] = floatval($v['shop_price']);
				$goods_list[$k]['earnest_money'] = floatval($v['earnest_money']);
				$goods_list[$k]['market_price_format'] = a_formatPrice(floatval($v['market_price']));
				$goods_list[$k]['shop_price_format'] = a_formatPrice(floatval($v['shop_price']));
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
					$goods_list[$k]['discountfb'] = 0;
	
				$goods_list[$k]['save'] = a_formatPrice($v['market_price']-$v['shop_price']);
	
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

	//修改 by hc 增加city_id参数， 当大于0时取出相应城市的留言列表
	function getMessageList2($rec_module='Message',$rec_id=0,$page=1,$city_id=0,$f=0 ,$extwhere = false)
	{
		$where .= ' pid = 0 and reply_type = 0 and status = 1 ';
		if($rec_id != 0)
			$where .= ' and rec_id = '.intval($rec_id);

		if(!empty($rec_module))
			$where .= ' and rec_module = "'.$rec_module.'"';

		if(intval(a_fanweC("MSG_ALL_CITY_VIEW")) == 1)
		{
			if($city_id>0)
			{
				$childIdsUtil = new ChildIds("group_city");
				$city_ids = $childIdsUtil->getChildIds(C_CITY_ID);
				array_push($city_ids,C_CITY_ID);
				$where.=" and (city_id in (".implode(",",$city_ids).") or city_id=0 or city_id ='')";
			}
		}

		if($f>=1)
		{
			$where .= ' and flag = '.$f;
		}

		if($extwhere)
		{
			$where .=$extwhere;
		}

		$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".a_fanweC("PAGE_LISTROWS");

		$sql = "select count(*) from ".DB_PREFIX."message where ".$where;
		$result['total'] = $GLOBALS['db']->getOne($sql);
		$sql = "select * from ".DB_PREFIX."message where ".$where." order by is_top desc,create_time desc limit ".$limit;

		$list = $GLOBALS['db']->getAll($sql); //getAllCached; //$this->where($condition)->order('is_top desc,create_time desc')->limit($limit)->findAll();
		foreach($list as $k=>$v)
		{
			/*
			//$user_reply = $this->where("pid=".$v['id']." and reply_type=1 and status=1")->order('is_top desc,create_time desc')->findAll();
			$sql = "select * from ".DB_PREFIX."message where pid=".$v['id']." and reply_type=1 and status=1 order by is_top desc,create_time desc ";
			$user_reply = $GLOBALS['db']->getAllCached($sql);
			foreach($user_reply as $kk=>$vv)
			{
				$user_reply[$kk]['create_time_format'] = a_toDate($vv['create_time']);
				$user_reply[$kk]['update_time_format'] = a_toDate($vv['update_time']);
			}

			$sql = "select id,content,create_time,update_time from ".DB_PREFIX."message where pid=".$v['id']." and reply_type=2 and status=1 order by is_top desc,create_time desc ";
			$admin_reply = $GLOBALS['db']->getRow($sql);
			if($admin_reply)
			{
				$admin_reply['create_time_format'] = a_toDate($admin_reply['create_time']);
				$admin_reply['update_time_format'] = a_toDate($admin_reply['update_time']);
			}
			*/
			$admin_reply = array();
			if (!empty($v['adm_content'])){
				$admin_reply['id'] = 1;
				$admin_reply['content'] = $v['adm_content'];
				$admin_reply['create_time_format'] = a_toDate($v['create_time']);
				$admin_reply['update_time_format'] = a_toDate($v['update_time']);
			}

			//$list[$k]['user_reply'] = $user_reply;
			$list[$k]['admin_reply'] = $admin_reply;
			$list[$k]['reply_count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."message where pid='{$v['id']}' and rec_module='{$rec_module}' and status = 1");
			$list[$k]['last_reply'] = $GLOBALS['db']->getRow("select user_name,create_time from ".DB_PREFIX."message where (pid='{$v['id']}'  or id='{$v['id']}') and rec_module='{$rec_module}'and status = 1  order by create_time desc");
			$list[$k]['create_time_format'] = a_toDate($v['create_time']);
			$list[$k]['update_time_format'] = a_toDate($v['update_time']);
		}
		$result['list'] = $list;
		return $result;
	}

	function getForumList($rec_module='Message',$rec_id=0,$page=1,$city_id=0,$fiter,$orderby,$PAGE_LISTROWS)
	{
		$where = ' pid = 0 and reply_type = 0 and status = 1 ';
		if($rec_id != 0)
			$where .= ' and rec_id = '.intval($rec_id);

		if(!empty($rec_module))
			$where .= ' and rec_module = "'.$rec_module.'"';

		if(intval(a_fanweC("MSG_ALL_CITY_VIEW")) == 1)
		{
			if($city_id>0)
			{
				$childIdsUtil = new ChildIds("group_city");
				$city_ids = $childIdsUtil->getChildIds(C_CITY_ID);
				array_push($city_ids,C_CITY_ID);
				$where.=" and (city_id in (".implode(",",$city_ids).") or city_id=0 or city_id ='')";
			}
		}

		if(intval($fiter)>0)
		{
			$where .= " and create_time >= ".(a_gmtTime()-intval($fiter))." ";
		}

		switch ($orderby)
		{
			case 'lastpost':
			default:
				$order = ' order by is_top desc,create_time desc ';
				break;
		}

		$limit = ($page-1)*$PAGE_LISTROWS.",".$PAGE_LISTROWS;

		$sql = "select count(*) from ".DB_PREFIX."message where ".$where;
		$result['total'] = $GLOBALS['db']->getOne($sql);
		$sql = "select * from ".DB_PREFIX."message where ".$where." {$order} limit ".$limit;

		$list = $GLOBALS['db']->getAll($sql); //getAllCached; //$this->where($condition)->order('is_top desc,create_time desc')->limit($limit)->findAll();
		foreach($list as $k=>$v)
		{
			$admin_reply = array();
			if (!empty($v['adm_content'])){
				$admin_reply['id'] = 1;
				$admin_reply['content'] = $v['adm_content'];
				$admin_reply['create_time_format'] = a_toDate($v['create_time']);
				$admin_reply['update_time_format'] = a_toDate($v['update_time']);
			}

			//$list[$k]['user_reply'] = $user_reply;
			$list[$k]['admin_reply'] = $admin_reply;
			$list[$k]['reply_count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."message where pid='{$v['id']}' and rec_module='{$rec_module}'and status = 1");
			$list[$k]['last_reply'] = $GLOBALS['db']->getRow("select user_name,create_time from ".DB_PREFIX."message where (pid='{$v['id']}'  or id='{$v['id']}') and rec_module='{$rec_module}'and status = 1  order by create_time desc");
			$list[$k]['create_time_format'] = a_toDate($v['create_time']);
			$list[$k]['update_time_format'] = a_toDate($v['update_time']);
		}
		$result['list'] = $list;
		return $result;
	}

	    /**
     * 用于检测当前用户IP的可操作性,time_span为验证的时间间隔 秒
     *
     * @param string $ip_str  IP地址
     * @param string $module  操作的模块     *
     * @param integer $time_span 间隔
     * @param integer $id   操作的数据
     *
     * @return boolean
     */
    function check_ip_operation($ip_str,$module,$time_span=0,$id=0)
    {
    	if(empty($_SESSION[$module."_".$id."_ip"]))
    	{
    		$check['ip']	= $ip_str;//	get_client_ip();
    		$check['time']	=	a_gmtTime();
    		$_SESSION[$module."_".$id."_ip"] = $check;

    		return true;  //不存在session时验证通过
    	}
    	else
    	{
    		$check['ip']	=	$ip_str;// get_client_ip();
    		$check['time']	=	a_gmtTime();
    		$origin	=	$_SESSION[$module."_".$id."_ip"];

    		if($check['ip']==$origin['ip'])
    		{
    			if($check['time'] - $origin['time'] < $time_span)
    			{
    				return false;
    			}
    			else
    			{
    				$_SESSION[$module."_".$id."_ip"] = $check;
    				return true;  //不存在session时验证通过
    			}
    		}
    		else
    		{
    			$_SESSION[$module."_".$id."_ip"] = $check;
    			return true;  //不存在session时验证通过
    		}
    	}
    }

    function getTodayGoodsList($id,$cate_id)
	{
		$filename=md5("today_goods_list".C_CITY_ID.$id.$cate_id).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,600)){
			//$id = intval($id);
			if(intval ( a_fanweC ( "VIEW_GOODS_LIST" ) ) == 1 && $id ==0)
				$limit = a_fanweC ( "GOODS_LIST_NUM" );
			else
				$limit = a_fanweC ( "TODAY_OTHER_GROUP" );
	
	
			$limit = intval($limit) > 0 ? intval($limit) : 8;
			//0:普通商品;1:积分商品;2:抽奖商品; add by chenfq 2011-01-05
			$where = 'status = 1 and score_goods <> 1';
	
			//只列出允许在首页显示的商品
			if($_REQUEST['m'] =="Index" && $_REQUEST['a'] =="index"){
				$where .= " and no_show_index = 0";
			}
	
	
			if($id != "0" && !empty($id))
				 $where .= " and id not in (".$id.")";
	
			if ($cate_id > 0){
				$childIdsUtil = new ChildIds("goods_cate");
				 $cate_ids = $childIdsUtil->getChildIds($cate_id);
	             array_push($cate_ids,$cate_id);
				$where .= " AND (cate_id in (".implode(",",$cate_ids).") or extend_cate_id in (".implode(",",$cate_ids).")) ";
			}
	
			$now = a_gmtTime();
			$where .= " and ((promote_begin_time <=".$now." and promote_end_time>=".$now.") or (is_preview=1 and promote_end_time>=".$now."))";
	
			$childIdsUtil = new ChildIds("group_city");
			$city_ids = $childIdsUtil->getChildIds(C_CITY_ID);
			array_push($city_ids,C_CITY_ID);
			$where .= " and (city_id in (".implode(",",$city_ids).") or all_show = 1)";
			//$where .= " group by id "; del by chenfq 2011-06-03
			$sort = 'sort desc,id desc';
	
			$data = getGoodsList($where,$limit,$sort);
			setCaches($filename,$data,substr($filename,0,1));
			return $data;
		}
		return getCaches($filename,substr($filename,0,1));
	}

	//输出商品列表
	function getGoodsList($condition='1=1',$limit=10,$order='promote_end_time desc')
	{

		//$list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods where ".$condition." order by ".$order." limit ".$limit);
		$sql = "SELECT `id`,`name_1`,`sn`,`cate_id`,`quan_id`,`city_id`,`suppliers_id`,`score_goods`,`click_count`,`cost_price`,`shop_price`,`market_price`,`promote_price`,`promote_begin_time`,`promote_end_time`,`create_time`,`update_time`,`type_id`,`goods_type`,`stock`,`brief_1`,`brand_id`,`is_best`,`is_hot`,`is_new`,`status`,`sort`,`seokeyword_1`,`seocontent_1`,`goods_desc_1`,`small_img`,`big_img`,`origin_img`,`define_small_img`,`is_define_small_img`,`is_inquiry`,`weight`,`spec_type`,`weight_unit`,`score`,`web_reviews`,`goods_reviews`,`min_user_time`,`special_note`,`max_bought`,`is_group_fail`,`complete_time`,`buy_count`,`group_user`,`user_count`,`earnest_money`,`group_bond_end_time`,`expand1`,`expand2`,`expand3`,`expand4`,`u_name`,`referrals`,`close_referrals`,`goods_short_name`,`fail_buy_count`,`free_delivery_amount`,`allow_combine_delivery`,`allow_sms` FROM ".DB_PREFIX."goods where ".$condition." order by ".$order." limit ".$limit;
		//echo $sql;
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$v)
		{
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($v['u_name']!='')
					$list[$k]['url'] = a_u("g/".rawurlencode($v['u_name']));
				else
					$list[$k]['url'] = a_u("tg/".$v['id']);

				$list[$k]['ref_url'] = __ROOT__."/tg-".$v['id'].'-ru-'.intval($_SESSION['user_id']).'.html';
			}
			else{
				$list[$k]['url'] = a_u("Goods/show",'id-'.$v['id']);
				$list[$k]['ref_url'] = a_u("Goods/show","id-".$v['id']."|ru-".intval($_SESSION['user_id']));
			}

			$list[$k]['short'] = a_msubstr($v['name_1'],0,a_fanweC("GOODS_SHORT_NAME"));
			$list[$k]['update_time_format']  = a_toDate($v['update_time']);
			
			if($list[$k]['complete_time'] > 0)
				$list[$k]['complete_time_format'] = a_toDate($v['complete_time'],a_L('XY_TIMES_MOD_2'));
			else
				$list[$k]['complete_time_format'] = "";
				
			$list[$k]['promote_begin_time_format']  = a_toDate($v['promote_begin_time'],a_L('XY_TIMES_MOD_1'));
			$list[$k]['promote_end_time_format']  = a_toDate($v['promote_end_time']);
			$list[$k]['promote_price_format'] = a_formatPrice($v['promote_price']);
			$list[$k]['complete_time_format'] = a_toDate($v['complete_time']);
			$list[$k]['market_price_format'] = a_formatPrice(floatval($v['market_price']));
			$list[$k]['shop_price_format'] = a_formatPrice(floatval($v['shop_price']));
			$list[$k]['earnest_money_format'] = a_formatPrice(floatval($v['earnest_money']));
			if(floatval($v['market_price'])!=0)
			{
				if (a_fanweC('DEFAULT_LANG') == 'en-us')
				{
					$list[$k]['discountfb'] = round((1-($v['shop_price'] / $v['market_price'])) * 100,1);
				}
				else
				{
					$list[$k]['discountfb'] = round(($v['shop_price'] / $v['market_price']) * 10,2);
				}
			}
			else
			{
				$list[$k]['discountfb'] =0;
			}
			$list[$k]['buy_url'] = a_u("Cart/index","id-".intval($v['id']));


			if(intval($list[$k]['stock']) > 0)
			{
				$list[$k]['surplusCount'] = intval($list[$k]['stock']) - intval($list[$k]['buy_count']);
				if($list[$k]['surplusCount'] <= 0)
					$list[$k]['is_none'] = true;
			}
			if(intval($list[$k]['surplusCount'])!==0)
				$list[$k]['stockbfb'] = ($list[$k]['surplusCount'] / intval($v['stock'])) * 100;
			else
				$list[$k]['stockbfb'] = 0;

			$list[$k]['rest_count'] = $list[$k]['group_user'] - $list[$k]['buy_count'];
			$list[$k]['save'] = a_formatPrice(floatval($v['market_price'] - $v['shop_price']));



			$list[$k]['ref_urllink'] = a_getDomain().$list[$k]['ref_url'];


			$sql = "select mail_title, mail_content from  ".DB_PREFIX."mail_template where name = 'share'";
			$mail = $GLOBALS['db']->getRowCached($sql);
			$mail['mail_title'] = str_replace('{$title}',$v['name_1'], $mail['mail_title']);
			$mail['mail_content'] = str_replace('{$title}',$v['name_1'], $mail['mail_content']);

			if (a_fanweC('DEFAULT_LANG') == 'en-us'){
				$list[$k]['urlgbname'] = $mail['mail_title'];
				$list[$k]['urlgbbody'] = $mail['mail_content'];
			}else{
				$list[$k]['urlgbname'] = urlencode(a_utf8ToGB($mail['mail_title']));
				$list[$k]['urlgbbody'] = urlencode(a_utf8ToGB($mail['mail_content']));
			}
			$list[$k]['urllink'] = a_getDomain().$v['url'];
			$list[$k]['urlweb'] = a_getDomain().$v['url'];
			$list[$k]['urlname'] = urlencode($v['name_1']);
			$list[$k]['urlbrief'] = urlencode($v['brief_1']);



			if($list[$k]['is_group_fail']==1)
			{
				$list[$k]['buy_count'] = $list[$k]['fail_buy_count'];
			}

		}
		return $list;
	}

		//获取团购城市列表
	function getGroupCityList($tree=false,$pid=0)
	{
		$filename=md5("getGroupCityList".$tree.$pid).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,3600)){
			$pid = " and pid={$pid}";
			//add by chenfq 2011-06-20 status =1
			$sql = "select * from ".DB_PREFIX."group_city where status =1 and verify=1 {$pid} order by is_defalut desc,sort asc,id asc,py asc";

			$city_list = $GLOBALS['db']->getAll($sql);

			foreach($city_list as $k=>$v)
			{
				if(a_fanweC("URL_ROUTE") == 0)
					$city_list[$k]['url'] = a_u('Index/index','cityname-'.$v['py']);
				else
					$city_list[$k]['url'] = __ROOT__."/".$v['py'];
				if($tree===true)
				{
					$city_list[$k]['list'] = getGroupCityList(true,intval($v['id']));
				}
			}
			setCaches($filename,$city_list,substr($filename,0,1));
			return $city_list;
		}
		return getCaches($filename,substr($filename,0,1));
	}

	function assignNav($type=1)
    {
		$filename=md5("assignNav".$_REQUEST['m'].$_REQUEST['a'].intval($_REQUEST['id']).C_CITY_ID.$type).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,3600)){
			$curr_module = $_REQUEST['m'];
			$curr_action = $_REQUEST['a'];
			$curr_id = intval($_REQUEST['id']);

			$navs = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."nav where type = ".$type." and status = 1 order by sort desc");
			//开始判断菜单的状态
			foreach($navs as $k=>$v)
			{
				//排除不在此城市的菜单
				if(intval($v['all_city'])==0){
					$city_ids = explode(",",$v['city_ids']);
					$is_show = false;
					foreach($city_ids as $ck=>$cv)
					{
						if(intval(C_CITY_ID) ==intval($cv))
							$is_show = true;
					}
					if(!$is_show)
						unset($navs[$k]);
				}
				//判断是否是改城市的导航
				if($v['url']=='')
				{
					$navs[$k]['target'] = '';
					if($v['rec_module']==""&&$v['rec_action']=="")
					{
						if(a_fanweC("URL_ROUTE")==1)
						{
							if(a_fanweC("CITYNAME_URL")==1)
							$navs[$k]['url'] = $_SESSION['cityName'];
							else
							$navs[$k]['url'] = __ROOT__."/Index.html";
						}
						else
						{
							if(a_fanweC("CITYNAME_URL")==1)
							$navs[$k]['url'] = a_u('Index/index','cityname-'.$_SESSION['cityName']);
							else
							$navs[$k]['url'] = a_u('Index/index');
						}
					}
					else
					{
						//--开始
						$arr  = '';
						if($v['rec_id']!=0)$arr = 'id-'.$v['rec_id'];
						if($v['show_cate'] == 1) $arr .= "|sc-1";
						//开始重写模块的定义
						if(a_fanweC("URL_ROUTE")==1)
						{
							if($v['rec_module']=="Goods")
							{
								if($v['rec_action']=="index")
								{
									if(intval($v['rec_id'])>0)
									{
										if($v['show_cate']==1)
										$navs[$k]['url'] = a_u("c/".$v['rec_id'],"sc-1");
										else
										$navs[$k]['url'] = a_u("c/".$v['rec_id']);
									}
									else
									{
										if($v['show_cate']==1)
										$navs[$k]['url'] = "c-sc-1.html";
										else
										$navs[$k]['url'] = "c.html";
									}
								}
								elseif($v['rec_action']=="show")
								{
	//		    					$goods_data = M("Goods")->getById($v['rec_id']);
									$goods_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."goods where id = ".$v['rec_id']);
									if($goods_data['u_name']!='')
									$navs[$k]['url'] = a_u("g/".rawurlencode($goods_data['u_name']));
									else
									$navs[$k]['url'] = a_u("tg/".$v['rec_id']);
								}
								elseif($v['rec_module']=="otehr")
								{
									if($v['show_cate']==1)
									$navs[$k]['url'] = a_u("Goods/otehr","id-".$v['rec_id']."|sc-1");
									else
									$navs[$k]['url'] = a_u("Goods/otehr");

								}
								else
								{

									$navs[$k]['url'] = a_u($v['rec_module']."/".$v['rec_action'],$arr);
								}
							}
							elseif($v['rec_module']=="BelowLine")
							{
								if($v['show_cate']==1)
								$navs[$k]['url'] = a_u("BelowLine/index","id-".$v['rec_id']."|sc-1");
								else
								$navs[$k]['url'] = a_u("BelowLine/index");

							}
							elseif($v['rec_module']=="Article")
							{
								if($v['rec_action']=="index")
								{
									if(intval($v['rec_id'])>0){
										if($v['u_name']!='')
											$navs[$k]['url'] = a_u("a/".rawurlencode($v['u_name']));
										else
											$navs[$k]['url'] = a_u("ac/".$v['rec_id']);
									}
									else
										$navs[$k]['url'] = "ac.html";


								}
								elseif($v['rec_action']=="show")
								{
	//			    				$article_data = M("Article")->getById($v['rec_id']);
									$article_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."article where id = ".$v['rec_id']);
									if($article_data['u_name']!='')
									$navs[$k]['url'] = a_u("a/".rawurlencode($article_data['u_name']));
									else
									$navs[$k]['url'] = a_u("wz/".$v['rec_id']);
								}
								else
								{
									$navs[$k]['url'] = a_u($v['rec_module']."/".$v['rec_action'],$arr);
								}
							}
							else
							$navs[$k]['url'] = a_u($v['rec_module']."/".$v['rec_action'],$arr);
						}
						else
						$navs[$k]['url'] = a_u($v['rec_module']."/".$v['rec_action'],$arr);
						//--end
					}
				}
				else
				{
					$navs[$k]['target'] = '_blank';
				}

				if($v['rec_module']==$curr_module&&$v['rec_action']==$curr_action&&$v['url']=='')
				{
					$navs[$k]['act'] = 1;
				}
				else
				{
					$navs[$k]['act'] = 0;
				}

				if(($curr_module=="Article"||$curr_module=="Goods")&&$curr_id!=0)
				{
					if(($curr_module=="Article"||$curr_module=="Goods")&&($v['rec_id']==$curr_id&&$curr_id!=0))
					{
						$navs[$k]['act'] = 1;
					}
					else
					{
						$navs[$k]['act'] = 0;
					}
				}





				if(($curr_module=="Index"&&$curr_action==''&&$v['rec_module']==""&&$v['rec_action']==''
					||$curr_module=="Index"&&$curr_action=='index'&&$v['rec_module']==""&&$v['rec_action']=='')
					&&$v['url']==''
				)
				{
					$navs[$k]['act'] = 1;
				}
			}
			setCaches($filename,$navs,substr($filename,0,1));
			return $navs;
		}
		return getCaches($filename,substr($filename,0,1));
    }

    function assignSeo($data)
	{
		if ($GLOBALS['def_idx'] == 1)
		{
			$data = array();
		}
		//$current_lang = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."lang_conf where lang_name = '".a_fanweC("DEFAULT_LANG")."'");
		$current_lang = $GLOBALS['langItem'];
    	$shop_name = $current_lang['shop_name'];
    	$shop_keyword = $current_lang['seokeyword'];
    	$shop_description = $current_lang['seocontent'];
    	$shop_title = $current_lang['shop_title'];

    	$cityID = C_CITY_ID;
    	if(intval($cityID)==0)
    	$cityID = intval(unserialize(base64_decode($_COOKIE['cityID'])));
		if($cityID==0) $cityID = intval($_SESSION["cityID"]);

    	if($cityID > 0)
		{
			$city = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."group_city where id = ".$cityID." and verify = 1");
		}


    	if($_REQUEST['cityname'])
    	{
    		$shop_title = $city['seo_title']!=''?$city['seo_title']:$current_lang['shop_title'];
    		$shop_keyword = $city['seo_keywords']!=''?$city['seo_keywords']:$current_lang['seokeyword'];
    		$shop_description = $city['seo_description']!=''?$city['seo_description']:$current_lang['seocontent'];

    		$shop_title = str_replace("{\$city_name}",$city['name'],$shop_title);
    		$shop_keyword = str_replace("{\$city_name}",$city['name'],$shop_keyword);
    		$shop_description = str_replace("{\$city_name}",$city['name'],$shop_description);
    	}
    	else
    	{
    		$shop_title = str_replace("{\$city_name}",'',$shop_title);
    		$shop_keyword = str_replace("{\$city_name}",'',$shop_keyword);
    		$shop_description = str_replace("{\$city_name}",'',$shop_description);
    	}

    	$shop_url = __ROOT__."/";

    	$title_str = "";
    	$nav_str = "<a href='".$shop_url."'>".$shop_name."</a>";
    	foreach($data['navs'] as $k=>$item)
    	{
    		if(strtolower($_REQUEST['m'])!="index" )  //非前页标题添加导航
    		$title_str.= $data['navs'][count($data['navs'])-$k-1]['name']." - ";
    		$nav_str.= " - <a href='".$item['url']."'>".$item['name']."</a>";
    	}

    	$title_str .= $shop_title;
    	$rs['title'] = $title_str;
    	$rs['navs'] = $nav_str;

    	if($data['keyword']!="")
    	$rs['keyword'] = $data['keyword'];
    	else
    	$rs['keyword'] = $shop_keyword;

    	if($data['content']!='')
    	$rs['content'] = $data['content'];
    	else
    	$rs['content'] = $shop_description;

    	$GLOBALS['tpl']->assign('site_info',$rs);
    	$GLOBALS['tpl']->assign('SHOP_NAME',$shop_name);
	}

	function assignHelp()
	{
		$filename=md5("assignHelp".C_CITY_ID).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,3600)){
	    	$limit = a_fanweC("HELP_CENTER_LIMIT");
	    	$condition = "type = 2 and status = 1";
			$help_cates = getArticleCateList($condition,a_fanweC("HELP_CENTER_CATE_LIMIT"),'sort asc');
			$childIdsUtil = new ChildIds("article_cate");
			foreach($help_cates as $k=>$v)
			{
			    $article_condition = "type = 2 and status = 1";
			    $childIds = $childIdsUtil->getChildIds($v['id']);
			    $childIds[] = $v['id'];
			    $article_condition .= " and cate_id in (".implode(",",$childIds).")";
			    //$article_condition['cate_id'] = array('in',$childIds);
			    $help_cates[$k]['list'] = getArticleList($article_condition,$limit,'sort asc');
			}
			setCaches($filename,$help_cates,substr($filename,0,1));
			return $help_cates;
		}
		return getCaches($filename,substr($filename,0,1));
	 }

	 function assignLink($type=false)
	 {
	 	$sql = "select * from ".DB_PREFIX."link where status=1 order by `sort` desc , id desc ";
	 	$rs = $GLOBALS['db']->getAllCached($sql);

	 	$list = array();
	 	foreach($rs as $k=>$v)
	 	{
	 		if($type!==false)
	 		{
	 			if($type == $v['type'])
	 				$list[] = $v;
	 		}
	 		else
	 			$list[$k] = $v;
	 	}

	 	return $list;
	 }
 
	 function getGoodsCate($ext='',$limit=0)
	 {
	 	//只列出允许在首页显示的商品
	    	if($_REQUEST['m'] =="Index" && $_REQUEST['a'] =="index"){
				$index= " and no_show_index = 0";
			}
	 	$filename = md5("goods_cate".$ext.$limit.C_CITY_ID).$_REQUEST['m'].$_REQUEST['a'].".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,600)){
			if($limit !=0)
			{
				$limit ="limit $limit";
			}
			else
			{
				$limit = "";
			}
			$sql = "select *, name_1 as name from " . DB_PREFIX . "goods_cate where status=1 {$ext} order by sort desc {$limit}";
			$cate_list = $GLOBALS ['db']->getAll( $sql ); //getAllCached
			$time= a_gmtTime();
			if(($_REQUEST['m'] =="Index" && $_REQUEST['a'] =="index")||($_REQUEST['m'] =="Goods" && $_REQUEST['a'] =="showcate")){
				$or_type = " or type_id = 2 ";
			}
			foreach($cate_list as $k => $v)
			{
	            $sql = "SELECT count(*) FROM ".DB_PREFIX."goods where (type_id = 0 or type_id = 1 $or_type or type_id = 3) and status=1 AND ((promote_begin_time <= $time and promote_end_time >= $time) or (is_preview = 1 and promote_end_time >= $time)) AND `score_goods`<>1 {$index}";
				
	            $childIdsUtil = new ChildIds("group_city");
	            $city_ids = $childIdsUtil->getChildIds(C_CITY_ID);
	            array_push($city_ids,C_CITY_ID);
	            $sql .= " AND (city_id in (".implode(",",$city_ids).") or all_show = 1) ";
	            $childIdsUtil = new ChildIds("goods_cate");
				$cate_ids = $childIdsUtil->getChildIds($v['id']);
	            array_push($cate_ids,$v['id']);
	            $sql .= " AND (cate_id in (".implode(",",$cate_ids).") or extend_cate_id in (".implode(",",$cate_ids).")) ";	           
	            $cate_list[$k]['goods_count'] = $GLOBALS['db']->getOne($sql);
	   
	            $cate_list[$k]['url'] = a_u("Goods/showcate","id-".$v['id']);

			}
			setCaches($filename,$cate_list,substr($filename,0,1));
		}
		else{
			$cate_list = getCaches($filename,substr($filename,0,1));
		}

		return $cate_list;
	 }
	 
	 function getGoodsQuan($city_id= 0, $cate_id=0,$show_count = false,$exe='')
	 {
	 	if($city_id == 0){
	 		$city_id = C_CITY_ID;
	 	}
		 //只列出允许在首页显示的商品
	    	if($_REQUEST['m'] =="Index" && $_REQUEST['a'] =="index"){
				$index_q= " and no_show_index = 0";
			}
	 	$filename = md5("getGoodsQuan".$city_id.$cate_id.$_REQUEST['m'].$_REQUEST['a'].$_REQUEST['gp'].$_REQUEST['sc'].$show_count.$exe).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,600)){
			$quan_list =  $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."coupon_region WHERE {$exe} city_id=".intval($city_id));
			$time= a_gmtTime();
			foreach($quan_list as $k=>$v){
				$url_prame ="qid-".$v['id'];
				if(intval($cate_id) > 0)
					$url_prame .="|id-".intval($cate_id);
				if($_REQUEST['sc']!="")
					$url_prame .="|sc-".$_REQUEST['sc'];
				if($_REQUEST['gp']!="")
					$url_prame .="|gp-".$_REQUEST['gp'];
				$quan_list[$k]['url'] = a_u($_REQUEST['m']."/".$_REQUEST['a'],$url_prame);
				if($show_count)
				{
					 $sql = "SELECT count(*) FROM ".DB_PREFIX."goods where status=1 {$index_q} AND ((promote_begin_time <= $time and promote_end_time >= $time) or (is_preview = 1 and promote_end_time >= $time)) AND `score_goods`<>1";
					 $childIdsUtil = new ChildIds("group_city");
		             $city_ids = $childIdsUtil->getChildIds($city_id);
		             array_push($city_ids,$city_id);
		             $sql .= " AND (city_id in (".implode(",",$city_ids).") or all_show = 1) ";
		             
		             $childIdsUtil = new ChildIds("coupon_region");
		             $quan_ids = $childIdsUtil->getChildIds(intval($v['id']));
		             array_push($quan_ids,intval($v['id']));
		             $sql .= " AND (quan_id in (".implode(",",$quan_ids).")) ";
		             
		             if(intval($cate_id) > 0){
		             	$childIdsUtil = new ChildIds("goods_cate");
						$cate_ids = $childIdsUtil->getChildIds($cate_id);
			            array_push($cate_ids,$cate_id);
			            $sql .= " AND (cate_id in (".implode(",",$cate_ids).") or extend_cate_id in (".implode(",",$cate_ids).")) ";
		             }
		             $quan_list[$k]['goods_count'] = $GLOBALS['db']->getOne($sql);
				}
			}
			setCaches($filename,$quan_list,substr($filename,0,1));
		}
		else{
			$quan_list = getCaches($filename,substr($filename,0,1));
		}
		return $quan_list;
	 }

	 function getArticleCateList($condition='',$limit='',$order='sort desc')
	{
		$filename=md5("getArticleCateList".$condition.$limit.$order.C_CITY_ID).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			$curr_lang_id = $GLOBALS['db']->getOneCached("select id from ".DB_PREFIX."lang_conf where lang_name = '".a_fanweC("DEFAULT_LANG")."'");
			if($limit!='')
			$list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."article_cate where ".$condition." order by ".$order." limit ".$limit);
			else
			$list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."article_cate where ".$condition." order by ".$order);
	
			foreach($list as $k=>$v)
			{
				if(a_fanweC("URL_ROUTE")==1){
					if($v['u_name']!='')
						$list[$k]['url'] = a_u("a/".rawurlencode($v['u_name']));
					else
						$list[$k]['url'] = a_u("ac/".$v['id']);
				}
				else
				$list[$k]['url'] = a_u("Article/index",array('id'=>$v['id']));
				$list[$k]['short'] = a_msubstr($v['name_'.$curr_lang_id],0,a_fanweC("ARTICLE_SHORT_NAME"));
	
			}
			setCaches($filename,$list,substr($filename,0,1));
			return $list;
		}
		return getCaches($filename,substr($filename,0,1));
	}

	function getArticleList($condition='',$limit=10,$order='sort desc')
	{
		$curr_lang_id = FANWE_LANG_ID;
		$list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."article where ".$condition." order by ".$order." limit ".$limit);

		foreach($list as $k=>$v)
		{
			if(a_fanweC("URL_ROUTE")==1)
			{
				if($v['u_name']!='')
				$list[$k]['url'] = a_u("a/".rawurlencode($v['u_name']));
				else
				$list[$k]['url'] = a_u("wz/".$v['id']);
			}
			else
			$list[$k]['url'] = a_u("Article/show",'id-'.$v['id']);
			$list[$k]['short'] = a_msubstr($v['name_'.$curr_lang_id],0,a_fanweC("ARTICLE_SHORT_NAME"));
			$list[$k]['author'] = $v['author']==''?a_L("FANWE"):$v['author'];
			$list[$k]['update_time_format']  = a_toDate($v['update_time']);
			$list[$k]['create_time_format']  = a_toDate($v['create_time']);

			if($list[$k]['target'] == 1)
				$list[$k]['target'] = '_blank';
			else
				$list[$k]['target'] = '_self';

		}
		return $list;
	}
	/**
	 * 获取商户详情
	 *
	 * @param int $id
	 * @return 数组
	 */

	function getSupplierItem($id)
	{
		$supplier_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers where id={$id}");
		if($supplier_info)
		{
			$supplier_depart = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."suppliers_depart where supplier_id={$id} order by is_main desc");
		
			if($supplier_depart)
				{
					foreach($supplier_depart as $kk=>$vv)
					{
						if($vv['map']==''&&$vv['api_address']!='')
						{
							//$vv['map'] = "http://ditu.google.cn/maps?f=q&source=s_q&hl=zh-CN&geocode=&q=".$vv['api_address'];
							$url_b="&wd=".$vv['api_address']."".$vv['depart_name']."&c=131&src=0&wd2=&sug=0";
							$url_b=urlencode($url_b);						
							$vv['map']="http://map.baidu.com/?newmap=1&l=18&c=".$vv['xpoint'].",".$vv['ypoint']."&s=s".$url_b."&sc=0";
						}
						$supplier_depart[$kk] = $vv;
					}
				}
	
			$supplier_info['depart'] = $supplier_depart;
			$main_depart = $supplier_depart[0];
			if($main_depart['api_address']!='')
			{
				$filename =ROOT_PATH."/Public/upload/ditu/".md5($main_depart['depart_name']).".jpg";
				if(file_exists($filename))
					$supplier_info['map_img'] = "/Public/upload/ditu/".md5($main_depart['depart_name']).".jpg";
			}
		}
		

		return $supplier_info;
	}


    function rand_string($len=6,$type='',$addChars='') {
		$str ='';
		switch($type) {
			case 0:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
				break;
			case 1:
				$chars= str_repeat('0123456789',3);
				break;
			case 2:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
				break;
			case 3:
				$chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
				break;
			case 4:
				$chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
				break;
			default :
				// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
				$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
				break;
		}
		if($len>10 ) {//位数过长重复字符串一定次数
			$chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
		}
		if($type!=4) {
			$chars   =   str_shuffle($chars);
			$str     =   substr($chars,0,$len);
		}else{
			// 中文随机字
			for($i=0;$i<$len;$i++){
			  $str.= self::msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
			}
		}
		return $str;
	}

// 发送邮件/短信消息队列 by hc
function send_list($user_id)
{
	$user_id = intval($user_id);
	if ($user_id == 0){
		$msg_list = $GLOBALS['db']->getAll("select `id`,`dest`,`title`,`content`,`create_time`,`send_type`,`status`,`send_time`,`bond_id` from ".DB_PREFIX."send_list where status = 0 order by send_type desc,id desc limit 10");
	}else{
		$msg_list = $GLOBALS['db']->getAll("select `id`,`dest`,`title`,`content`,`create_time`,`send_type`,`status`,`send_time`,`bond_id` from ".DB_PREFIX."send_list where status = 0 and (user_id =".$user_id." or user_id = 0) order by user_id desc limit 10");
	}

	if (count($msg_list)> 0){
		$sms= new SmsPlf();
		$mail = new Mail();
	}

	foreach($msg_list as $msg)
	{
			$GLOBALS['db']->query("update ".DB_PREFIX."send_list set status = 1,send_time =".a_gmtTime()." where id=".$msg['id']." and status = 0");
			//默认为已发送
			if($GLOBALS['db']->affected_rows()==1)
			{
				if($msg['send_type'] == 1 && a_fanweC("IS_SMS")==1)
				{
					if($sms->sendSMS($msg['dest'],$msg['content']))
					{
						if($msg['bond_id']>0) //团购券的发送，记录发送状态
						{
							$GLOBALS['db']->query("update ".DB_PREFIX."group_bond set is_send_msg = 1,send_count = send_count+1 where id=".$msg['bond_id']);
						}
					}
					else
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."send_list set err_msg = '".addslashes($sms->message)."' where id=".$msg['id']);
					}
					//print_r($sms);
				}elseif($msg['send_type'] == 0)
				{
					if(a_fanweC("MAIL_ON")==1)
					{
						$mail->ClearAddresses();
						$mail->AddAddress($msg['dest']);
						$mail->IsHTML(1);
						$mail->Subject = $msg['title']; // 标题
						$mail->Body = $msg['content']; // 内容
						$mail->Send();
						if($mail->ErrorInfo!='')
						{
							$GLOBALS['db']->query("update ".DB_PREFIX."send_list set err_msg = '".addslashes($mail->ErrorInfo)."' where id=".$msg['id']);
						}
					}
				}
		}
	} //end foreach
}

//用户菜单
function com_userMenu()
{
	$user_menu = array(
		array('name'=>sprintf(a_L('MY_GROUPBOND'),a_fanweC('GROUPBOTH')), 'url'=>a_u("UcGroupBond/index"),'module'=>'UcGroupBond'),
		array('name'=>a_L("UCORDER_INDEX"), 'url'=>a_u("UcOrder/index"),'module'=>'UcOrder'),
		array('name'=>a_L("UCBELOWORDER_INDEX"), 'url'=>a_u("UcBelowOrder/index"),'module'=>'UcBelowOrder'),
		array('name'=>a_L("UCLOG_INDEX"), 'url'=>a_u("UcLog/index"),'module'=>'UcLog'),
		array('name'=>a_L("UCMODIFY_INDEX"), 'url'=>a_u("UcModify/index"),'module'=>'UcModify'),
		array('name'=>a_L("UCSCORE_EXCHANGE"), 'url'=>a_u("UcScore/exchange"),'module'=>'UcScore'),
		array('name'=>a_L("UCREFERRALS_INDEX"), 'url'=>a_u("UcReferrals/index"),'module'=>'UcReferrals'),
	);
	$offline_count = 0;
	if(intval($_SESSION['user_id']) >0)
		$offline_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."order where offline=1 and user_id = ".intval($_SESSION['user_id']));
	
	if($offline_count == 0)
	{
		unset($user_menu[2]);
	}

	if(intval(a_fanweC("OPEX_SCORE")) == 0)
	{
		unset($user_menu[5]);
	}

	if(a_fanweC("OPEN_ECV")==1)
	{
		$ecv_menu = array(
			array('name'=>a_L("UCECV_INDEX"), 'url'=>a_u("UcEcv/index"),'module'=>'UcEcv'),
		);
		$user_menu = array_merge($user_menu,$ecv_menu);
	}
	return $user_menu;
}
//JS语言包
function com_jsLang()
{
	if(!is_file(ROOT_PATH."app/Runtime/js_lang.js"))
	{
		if (is_file(ROOT_PATH.'app/Lang/'.LANG.'/js_lang.php'))
		{
			$lang_pack = require ROOT_PATH.'app/Lang/'.LANG.'/js_lang.php';
		}
		$str = "var LANG = {";
		foreach($lang_pack as $k=>$lang)
		{
			$str .= "\"".$k."\":\"".str_replace("nbr","\\n",addslashes($lang))."\",";
		}
		$str = substr($str,0,-1);
		$str .="};";

		@file_put_contents(ROOT_PATH."app/Runtime/js_lang.js",$str);
	}
}

// lin 16:16 2011-4-28
function get_buy_comment( $page, $user_id = 0 ) {
	if( $page <= 0 ) $page = 1;
	if( $user_id ) $my = " and m.user_id=" . $user_id . " ";
	$page_count = a_fanweC( "GOODS_PAGE_LISTROWS" );

	// 城市
	$childIdsUtil = new ChildIds( "group_city" );
	$city_ids = $childIdsUtil->getChildIds( C_CITY_ID );
	array_push( $city_ids, C_CITY_ID );

	// 评论
	$count_sql = "select count(*) as count " .
					"from ".DB_PREFIX."message as m " .
					"left join ".DB_PREFIX."goods as g on g.id=m.rec_id " .
					"where m.rec_module='Goods' and m.status=1 and g.status=1 and (g.city_id in(" . implode( ',', $city_ids ) . ") or g.all_show=1) " . $my .
					"order by m.is_top, m.create_time desc";
	$comments_count = $GLOBALS['db']->getOne( $count_sql );

	$f = $page * $page_count - $page_count;

	$comments_sql = "select m.*, g.small_img, g.name_1, g.goods_short_name " .
					"from ".DB_PREFIX."message as m " .
					"left join ".DB_PREFIX."goods as g on g.id=m.rec_id " .
					"where m.rec_module='Goods' and m.status=1 and g.status=1 and (g.city_id in(" . implode( ',', $city_ids ) . ") or g.all_show=1) " . $my .
					"order by m.is_top, m.create_time desc limit ".$f.", ".$page_count;
	$comments = $GLOBALS['db']->getAll( $comments_sql );

	return array( 'total' => $comments_count, 'list' => $comments );
}

function check_ecvverify($sn,$password){
	
	if (intval ( $_SESSION ['user_id'] ) < 1) {
		return array ("type" => 0, "msg" => a_L ( 'PLEASE_LOGIN' ), "ecv" => "" );
	}
	$sn = addslashes($sn);
	$password = addslashes($password);
	$result = array ("type" => 0, "msg" => "", "ecv" => "" );
	$ecv = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "ecv where sn='{$sn}' and password='{$password}' and type=0" );
	$ecv ['ecvType'] = $GLOBALS ['db']->getRowCached ( "select `money`,`use_start_date`,`use_end_date`,`status`,use_count from " . DB_PREFIX . "ecv_type where id='{$ecv['ecv_type']}'" );
	if ($ecv) {
		//计算会员，已经获得的同类代金券数量 add by chenfq 2011-03-09
		$sql = "select count(*) from " . DB_PREFIX . "ecv where id <> ".intval($ecv['id'])." and use_user_id = ".intval ( $_SESSION ['user_id'] )." and ecv_type =" .intval ( $ecv['ecv_type'] );
		$use_count = intval($GLOBALS ['db']->getOne($sql));
		if ($ecv['ecvType']['use_count'] <= $use_count && intval($ecv['user_id']) != intval($_SESSION ['user_id'])){ //
			$result ['msg'] = a_L ( "INVALID_VOUCHER" );
		}else{
			$time = a_gmtTime ();
			if (intval ( $ecv ['user_id'] ) > 0 && intval ( $ecv ['user_id'] ) != intval ( $_SESSION ['user_id'] ))
				$result ['msg'] = a_L ( "HC_ECV_HAS_DELIVERY_TO_OTHER_USER" );
			elseif (intval ( $ecv ['use_date_time'] ) > 0)
				$result ['msg'] = sprintf ( a_L ( "HC_ECV_HAS_USE_STR" ), $ecv ['useUser'] ['user_name'], a_toDate ( $ecv ['use_date_time'], a_L ( "HC_DATETIME_FORMAT" ) ) );
			elseif (intval ( $ecv ['ecvType'] ['status'] ) == 0)
				$result ['msg'] = a_L ( "HC_ECV_HAS_FORBID" );
			elseif ($time < intval ( $ecv ['ecvType'] ['use_start_date'] ))
				$result ['msg'] = sprintf ( a_L ( "HC_ECV_NOT_BEGIN_STR" ), a_toDate ( $ecv ['ecvType'] ['use_start_date'], a_L ( "HC_DATETIME_SHORT_FORMAT" ) ) );
			elseif ($time > intval ( $ecv ['ecvType'] ['use_end_date'] ) && intval ( $ecv ['ecvType'] ['use_end_date'] ) > 0)
				$result ['msg'] = sprintf ( a_L ( "HC_ECV_EXPIRED_STR" ), a_toDate ( $ecv ['ecvType'] ['use_end_date'], a_L ( "HC_DATETIME_SHORT_FORMAT" ) ) );
			else {
				$ecv ['money'] = a_formatPrice ( floatval ( $ecv ['ecvType'] ['money'] ) );
				$ecv ['use_start_date'] = (intval ( $ecv ['ecvType'] ['use_start_date'] ) > 0) ? a_toDate ( $ecv ['ecvType'] ['use_start_date'], a_L ( "HC_DATETIME_SHORT_FORMAT" ) ) : a_L ( "HC_NOT_LIMIT" );
				$ecv ['use_end_date'] = (intval ( $ecv ['ecvType'] ['use_end_date'] ) > 0) ? a_toDate ( $ecv ['ecvType'] ['use_end_date'], a_L ( "HC_DATETIME_SHORT_FORMAT" ) ) : a_L ( "HC_NOT_LIMIT" );
				$result ['msg'] = "";
				$result ['type'] = 1;
				$result ['ecv'] = $ecv;
			}			
		}
	} else {
		$result ['msg'] = a_L ( "HC_ECV_NOT_EXIST" );
	}	
	
	return $result;
}

/**
 * 检测缓存文件是否需要更新
 * @param string $cache_file 缓存文件路径
 * @param int $time_out 缓存时间(秒)
 * @return bool 需要更新返回 true
 */
function getCacheIsUpdate($cache_file,$time_out)
{
	if (!file_exists($cache_file))
		return true;

	$mtime = filemtime($cache_file);
	$nowtime = time();
	if(intval($nowtime) - intval($mtime) > $time_out)
	{
		removeFile($cache_file);
		return true;
	}
	else
		return false;
}

/**
 * 删除文件
 * @param string $filepat 文件路径
 * @return bool
 */
function removeFile($filepath)
{
	$is_success = false;
	@unlink($filepath);
	if(!file_exists($filepath))
		$is_success = true;
	return $is_success;
}

/**
 * 设置缓存
 */
function setCaches($filename,$data,$dir=""){
	$data = serialize($data);
	if($dir!="")
	{
		@mkdir(ROOT_PATH."/app/Runtime/caches/".$dir,0777);
	}
	@file_put_contents(ROOT_PATH."/app/Runtime/caches/".$dir."/".$filename,$data);
}

/**
 * 获取缓存
 */
function getCaches($filename,$dir=""){
	$data = serialize($data);
	return unserialize(file_get_contents(ROOT_PATH."/app/Runtime/caches/".$dir."/".$filename));
}

function getNewURL($ma ,$cateid="",$qid = "",$gp="",$sc=""){
 	$ext = "";
	if(!empty($cateid) )
	{
		$ext .= "id-".$cateid."|";
	}
 	if(!empty($qid))
	{
		$ext .= "qid-".$qid."|";
	}
	
	if(!empty($gp) )
	{
		$ext .= "gp-".$gp."|";
	}
	
	if(!empty($sc) )
	{
		$ext .= "sc-".$sc."|";
	}
	if($ext)
	{
		$ext = substr($ext,0,strlen($ext)-1);
	}
	
	return a_u($ma,$ext);
 }
 //发货获取状态
 function get_dstatus($id)
{ 
                $sql_str =  'SELECT a.*,'.
					'       b.sn as order_sn,'.
                                         '       b.goods_status as goods_status,'.
					'       b.order_total_price as final_amount,'.
					'       c.name_1 as fname,'.
					'       e.name as express_name,'.
					'       d.user_name as mname'.
					'  FROM '.DB_PREFIX.'order_consignment a'.
					'  LEFT OUTER JOIN '.DB_PREFIX.'order b ON a.order_id = b.id'.
					'  LEFT OUTER JOIN '.DB_PREFIX.'delivery c ON a.delivery_id = c.id'.
					'  LEFT OUTER JOIN '.DB_PREFIX.'user d ON b.user_id = d.id'.
					'  LEFT OUTER JOIN '.DB_PREFIX.'express e ON a.express_id = e.id '.
					'  where b.id ='.$id.
					' ORDER BY a.create_time desc';
                $order_incharge_list =  $GLOBALS['db']->getRow($sql_str);
                //var_dump($order_incharge_list);exit;
		if($order_incharge_list)
		{	
			return a_L('ORDER_GOODS_STATUS_'.$order_incharge_list[goods_status])."，发货单号：".$order_incharge_list['express_name'].$order_incharge_list['delivery_code']."，发货时间：".a_toDate($order_incharge_list['create_time'])."</span>";
		}
                else
		return "未发货";
}
?>