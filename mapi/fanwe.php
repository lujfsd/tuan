<?php
/**
 * xml公共部分
 * <return></return>; 0：操作失败；1：操作成功
 * <info></info>; 操作返回提示：如操作失败原因等
 * 
 * app/source/comm_init.php
 * 
 * app/source/func/com_order_pay_func.php 有更新：cart_done,order_done这2个函数
 */

error_reporting(E_ALL ^ E_NOTICE);
 $i_type = 0;//上传数据格式类型; 0:base64;1;REQUEST;2:json
//r_type: 返回数据格式类型; 0:base64;1;json_encode;2:array
if (isset($_REQUEST['i_type']))
{
	$i_type = intval($_REQUEST['i_type']);
}


if ($i_type == 1){
	$requestData = $_REQUEST;
}else{
	if (isset($_REQUEST['requestData'])){
		if ($i_type == 2){
			$requestData = json_decode(trim($_REQUEST['requestData']), 1);		
		}else{
			$requestData = base64_decode(trim($_REQUEST['requestData']));
			$requestData = json_decode($requestData, 1);
		}
	}else{
		$requestData = $_REQUEST;
	}
}
//安卓苹果密码统一
if(isset($requestData['pwd']))
{
    $requestData['pwd'] =$requestData['pwd'];
}
elseif(isset($requestData['password'])){
    $requestData['pwd'] =$requestData['password'];
    unset($requestData['password']);
}

$act = $requestData['act'];


$class = strtolower($requestData['act']);
$act2 = strtolower($requestData['act_2'])?strtolower($requestData['act_2']):"";
define('ACT',$class); //act常量
define('ACT_2',$act2);

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('mapi/fanwe.php', '', str_replace('\\', '/', __FILE__)));	
	
require ROOT_PATH.'mapi/comm.php';

$MConfig = getMConfig();//初始化配送数据

define('PAGE_SIZE',intval($MConfig['page_size'])); //分页的常量
define('VERSION',1.0); //接口版本号,float 类型


if(false) 
{
	$url = a_getDomain().API_ROOT."/mapi/index.php?requestData=".$_REQUEST['requestData']."&r_type=2";
	$api_log = array();
	$api_log['api'] = $url;
	$api_log['act'] = $class;
	$api_log['act_2'] = ACT_2;
	$api_log['param'] = print_r($requestData,1);
	/*
	$def_url  = '<form style="text-align:center;" action="'.a_getDomain().API_ROOT.'/mapi/index.php" target="_blank" style="margin:0px;padding:0px" >';
    foreach ($requestData AS $key=>$val)
    {
       $def_url  .= "<input type='text' name='$key' value='$val' />";
    }	
		
	$def_url .= "<input type='submit' class='paybutton' value='提交'></form>";	
	$api_log['form'] = $def_url; 	
	//print_r($api_log); exit;
	*/
	$GLOBALS['db']->autoExecute(DB_PREFIX."m_api_log", addslashes_deep($api_log), 'INSERT');
	/*
	
	CREATE TABLE `fanwe_m_api_log` (
  `id` int(11) NOT NULL auto_increment,
  `act` varchar(30) NOT NULL,
  `act_2` varchar(30) NOT NULL,
  `api` text NOT NULL,
  `param` text NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=91725 DEFAULT CHARSET=utf8;

*/
}

//print_r($MConfig); exit;

if ($act == 'init'){
		$cur_city_id = intval($requestData['cur_city_id']);
		if ($cur_city_id == 0){
			$cur_city_id = C_CITY_ID;//默认城市id	
		}
		
	$root = array();
	$root['return'] = 1;
	$root['city_id'] = $cur_city_id;
	$root['city_name'] = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."group_city where id = ".$cur_city_id);
	$root['catalog_id'] = intval($MConfig['catalog_id']);//默认分类id

	$root['citylist'] = getCityArray(0);
	//$root['cataloglist'] = getCatalogArray(false);//默认不显示2级分类
	//$root['cataloglistsearch'] = getCatalogArraySearch(false);//默认不显示2级分类

	$root['region_version'] = intval($MConfig['region_version']);//当前配送地区的数据版本(如果大于客户端的版本号,则客户端在选择，配送地区时会提示升级),int 数字类型
	$root['only_one_delivery'] = intval($MConfig['only_one_delivery']);//1：会员只有一个配送地址；0：会员可以有多个配送地址
	$root['has_region'] = intval($MConfig['has_region']);//1：有配送地区选择项；0：无, 在会员中心中的：配送地址中有使用
	
	$root['kf_phone'] = $MConfig['kf_phone'];//客服电话
	$root['kf_email'] = $MConfig['kf_email'];//客服邮箱
	$root['about_info'] = $MConfig['about_info'];
	
	$root['page_size'] = PAGE_SIZE;//默认分页大小
	 
	$root['version'] = VERSION; //接口版本号,float 类型
	$root['newslist'] = $MConfig['newslist'];//新闻列表
	$root['index_logo'] = get_abs_img_root2($MConfig['index_logo']);
	dispay($root);

}else if ($act == 'city'){
	$pid = intval($requestData['id']);//城市ID

	$root = array();
	$root['return'] = 1;
	$root['citylist'] = getCityArray($pid);

	dispay($root);
	
}else if($act == 'catalog'){
	$pid = intval($requestData['id']);//分类ID

	$root = array();
	$root['return'] = 1;
	$root['cataloglist'] = getCatalogArray($pid);

	dispay($root);
}else if($act == 'goodsattr'){
	$goods_id = intval($requestData['id']);//商品ID

	$root = array();
	$root['return'] = 1;
	$root['attr'] = getAttrArray($goods_id);

	dispay($root);
	
}else if($act == 'nearbygoodses'){
	/**
	 * has_attr: 0:无属性; 1:有属性
	 * 有商品属性在要购买时，要选择属性后，才能购买(用户在列表中点：购买时，要再弹出一个：商品属性选择对话框)
	 
	 * change_cart_request_server: 
	 * 编辑购买车商品时，需要提交到服务器端，让服务器端通过一些判断返回一些信息回来(如：满多少钱，可以免运费等一些提示)
	 * 0:提交，1:不提交；
	 * 	 
	 * num_unit: 单位
	 
	 * limit_num: 库存数量	 
	 * 
	 */
	
	$catalog_id = intval($requestData['catalog_id']);//商品分类ID
	$city_id = intval($requestData['city_id']);//城市分类ID

	
	$page = intval($requestData['page']); //分页
	$page=$page==0?1:$page;
	
	$now = a_gmtTime();
		
	$page_size = PAGE_SIZE;
	$limit = (($page-1)*$page_size).",".$page_size;
		
	$sql = "SELECT s.name as supplier_name,s.brief as supplier_biref,sp.tel as sp_tel,sp.address as sp_address,g.id,g.city_id,g.type_id,".
			"w.name_1 as num_unit,g.max_bought,g.goods_type,".
			"g.name_1 as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.brief_1 as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count,gc.py ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'weight as w on w.id = g.weight_unit '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sp on s.id = sp.supplier_id '.
					"where sp.is_main=1 and g.status = 1 and g.type_id != 2 and g.score_goods = 0 and g.promote_begin_time <= $now and g.promote_end_time >= $now ";
	$sql_count = 'SELECT count(*) FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'weight as w on w.id = g.weight_unit '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sp on s.id = sp.supplier_id '.
					"where sp.is_main=1 and g.status = 1 and g.type_id != 2 and g.score_goods = 0 and g.promote_begin_time <= $now and g.promote_end_time >= $now ";
					
		if ($catalog_id > 0)
		{
			$sql .= " and (g.cate_id = $catalog_id or g.extend_cate_id = $catalog_id)";		
			$sql_count .= " and (g.cate_id = $catalog_id or g.extend_cate_id = $catalog_id)";			
		}
			
		if ($city_id > 0)
		{
			$sql .= " and (g.city_id = $city_id or g.all_show = 1)";
			$sql_count .= " and (g.city_id = $city_id or g.all_show = 1)";
		}
					
		$keyword = trim($requestData['keyword']);//keyword
		if ($keyword && $keyword <> ''){
			$sql .= " and (g.name_1 like '%$keyword%' )";	
			$sql_count .= " and (g.name_1 like '%$keyword%' )";			
		}
					
		$sql .= " group by g.id order by g.sort desc,g.id desc";
		$sql_count .= " order by g.sort desc,g.id desc";

		$sql.=" limit ".$limit;

		$list = $GLOBALS['db']->getAll($sql);
		$total = $GLOBALS['db']->getOne($sql_count);
		$page_total = ceil($total/$page_size);
		
		$root = array();
		$root['return'] = 1;

		
		$goodses = array();
		foreach($list as $item)
		{
			//$goods = array();
			$goods = getGoodsArray($item);
			$goodses[] = $goods;
		}
		$root['item'] = $goodses;
		$root['page'] = array("page"=>$page,"page_total"=>$page_total);

		
		dispay($root);

}else if($act == 'goodsdesc'){
	/**

	 * has_attr: 0:无属性; 1:有属性
	 * 有商品属性在要购买时，要选择属性后，才能购买
	 
	 * change_cart_request_server: 
	 * 编辑购买车商品时，需要提交到服务器端，让服务器端通过一些判断返回一些信息回来(如：满多少钱，可以免运费等一些提示)
	 * 0:提交，1:不提交；
	 
	 * image_attr_a_id_{$attr_a_id} 图片列表，可以根据属性ID值，来切换图片列表;默认为：0
	 * limit_num: 库存数量
	 
	 */
	
	$id = intval($requestData['id']);//商品ID

	$sql = "SELECT g.brief_1 as goods_brief,g.goods_desc_1 as goods_desc,s.name as supplier_name,s.brief as supplier_biref,sp.tel as sp_tel,sp.address as sp_address,g.id,g.city_id,g.type_id,".
			"w.name_1 as num_unit,g.max_bought,g.goods_type,".
			"g.name_1 as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.brief_1 as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count,gc.py ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'weight as w on w.id = g.weight_unit '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sp on s.id = sp.supplier_id '.
					"where sp.is_main=1 and g.status = 1 and g.id = ".$id;
//	echo $sql; exit;		
	$item = $GLOBALS['db']->getRow($sql);

	$root = getGoodsArray($item);		

    $root['return'] = 1;
	$root['attr'] = getAttrArray($id);
	
	$images = array();
	//image_attr_1_id_{$attr_1_id} 图片列表，可以根据属性ID值，来切换图片列表
	$sql = "select small_img,big_img from ".DB_PREFIX."goods_gallery where goods_id = ".intval($id);
	$list = $GLOBALS['db']->getAll($sql);
	foreach($list as $image){
		$images['image_attr_1_id_0'][] = make_img(ROOT_PATH.$image['big_img'],0) ;	
	}
	$root['images'] = $images;
	
	$gallery = array();
	$big_gallery = array();
	foreach($list as $k=>$image){
		$gallery[] = make_img(ROOT_PATH.$image['big_img'],0) ;
		$big_gallery[] = make_img(ROOT_PATH.$image['big_img'],1) ;
	}
	$root['gallery'] = $gallery;
	$root['big_gallery'] = $big_gallery;
		
	dispay($root);	
	
}else if($act == 'login'){
//用户登陆
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码
	
	dispay(check_user($email,$pwd, true));	
}else if($act == 'register'){
	$root = array();
	//用户注册
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = trim($requestData['pwd']);//密码
	$user_name = trim($requestData['user_name']);//用户名	
	if (strlen($pwd) < 4){
		$root['return'] = 0;
		$root['info'] = "注册密码不能少于4位";
		dispay($root);		
	}
	
	
	$pwd = md5($pwd);//密码

	

	if (empty($email) || empty($pwd)){
		$root['return'] = 0;
		$root['info'] = "注册失败邮箱或密码不能为空";
		dispay($root);	
	}
	
	$sql = "select id from ".DB_PREFIX."user where (email = '$email' or user_name = '$user_name')";
	$user = $GLOBALS['db']->getRow($sql);
	if ($user){
		$root['return'] = 0;
		$root['info'] = "用户已经成存,请重新注册";		
	}else{
    	$data ['user_name'] = $user_name;
    	$data ['email'] = $email;
   		$data ['user_pwd'] = $pwd;
        $data ['status'] = 1;
        
        $data ['last_ip'] = $_SESSION['CLIENT_IP'];
        $data ['score'] = intval (a_fanweC( "DEFAULT_SCORE" ));
        $data ['status'] = a_fanweC ('USER_AUTO_REG');
        $data ['create_time'] = a_gmtTime ();
        $data ['update_time'] = a_gmtTime ();
        $data ['group_id'] = a_fanweC ("DEFAULT_USER_GROUP");
        $data ['sex'] = intval($requestData['gender']); 
        /*没有测试,暂时屏蔽uc整合注册
        $code = a_fanweC('INTEGRATE_CODE');
        if (empty($code))
        $code = 'fanwe';
        if ($code == 'ucenter') {
        	$users = &init_users3_2();
        	$users->need_sync = false;
        	$is_add = $users->add_user(trim($data['user_name']), trim($data['user_pwd']), trim($data ['email']));
        	if ($is_add) {
        		$user_id_arr = $users->get_profile_by_name($_REQUEST ['user_name']);
        		$data ['ucenter_id'] = $user_id_arr ['id'];
        	}
        }
		*/
        $GLOBALS ['db']->autoExecute(DB_PREFIX . "user", addslashes_deep($data), 'INSERT');
        $rs = intval($GLOBALS ['db']->insert_id());
                
		if ($rs> 0){
			$root['return'] = 1;
			$root['info'] = "用户注册成功";		
			$root['user_id'] = $rs;
			$root['user_name'] = $email;
			$root['user_email'] = $email;		
		}else{
			$root['return'] = 0;
			$root['info'] = "注册失败:".$GLOBALS ['db']->ErrorMsg();	
		}
	}	
	dispay($root);
	
}else if($act == 'postcart'){
	/**
	 * 提交购买车内容
	 * 
	 * 
	 */
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码
	//检查用户,用户密码
	$user = check_user($email,$pwd, false);
	$user_id  = intval($user['user_id']);
	
	
	//把数据插入购买车
	$session_id=session_id();
	$cartdata = insertCartData($user_id,$session_id,$requestData['cartdata'],$user['user']['group_id']);

	require ROOT_PATH.'app/source/func/com_order_pay_func.php';
	$root = array();
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	$error = '';
	if (!check_cart_goods($session_id,$user_id,$error)){
		$root['return'] = 0;
		$root['info'] = $error."<br>请编辑后,请刷新按钮.";//简单的html格式字符串
	}else{
		$root['return'] = 1;
	}
	
	
/*
	$yh = array();
	$yh[] = array("info"=>"手机下单立减5元","money"=>0);
	$yh[] = array("info"=>"全场购物满200元免运费","money"=>0); 
*/	
	$root['cartinfo'] = $MConfig['yh'];
	
	
	$root['cartdata'] = $cartdata;
	
	
	//清空购买车
	$sql = "delete from ".DB_PREFIX."cart where session_id='".$session_id."'";
	if ($user_id > 0){
		$sql .= " or user_id = ".$user_id;
	}
	$GLOBALS['db']->query($sql);
		
	dispay($root);

}else if($act == 'calc_cart'){
	
/**
	 * 计算订单价格
	 * 
	 * 
	 */
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码	
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);
	$user_id  = intval($user['user_id']);
	
	$money = floatval($user['user_money']);
	$_SESSION['user_id'] = $user_id;
	
	$cartdata = $requestData['cartdata'];

	$root = array();
	$root['return'] = 1;
	$root['status'] = 1;
	$root['first_calc'] = $requestData['first_calc'];
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	//第一次计算,主要是处理一些初始化参数,比如：默认配送地址
	if ($requestData['first_calc']==1){
		$delivery = getUserAddr($user_id,false);
		
		$root['delivery'] = $delivery;
		$delivery_region = array(
		   		'region_lv1'=>intval($delivery['region_lv1']),
		   		'region_lv2'=>intval($delivery['region_lv2']),
		   		'region_lv3'=>intval($delivery['region_lv3']),
		   		'region_lv4'=>intval($delivery['region_lv4'])
		);	
		
		$root['send_mobile'] = $user['user']['mobile_phone'];//默认填上用户手机号码
		
		$payment_id = intval($MConfig['select_payment_id']);//默认支付方式
	}else{
		$delivery_region = array(
		   		'region_lv1'=>intval($requestData['region_lv1']),
		   		'region_lv2'=>intval($requestData['region_lv2']),
		   		'region_lv3'=>intval($requestData['region_lv3']),
		   		'region_lv4'=>intval($requestData['region_lv4'])
		);	

		$payment_id = intval($requestData['payment_id']);
	}
	
	require ROOT_PATH.'app/source/func/com_order_pay_func.php';
	//把数据插入购买车
	$session_id=session_id();
	insertCartData($user_id,$session_id,$cartdata,$user['user']['group_id']);

	$error = '';
	if (!check_cart_goods($session_id,$user_id,$error)){
		$root['status'] = 0;
		$root['info'] = $error;
	}
			
	$delivery_id = intval($requestData['delivery_id']);//配送方式;
	if ($delivery_id == 0)
		$delivery_id = intval($MConfig['delivery_id']);//取系统配置
				
	$root['select_delivery_id'] = $delivery_id;
				
	$delivery_id = getDeliveryId($user_id,$session_id,$delivery_region,0,$delivery_id);
	if ($delivery_id == -1){
		$root['info'] = "无法将商品配送到该地区.";
		$root['status'] = 0;
	}
	
	if ($requestData['first_calc']==1){
		$root['order_parm'] = init_order_parm($MConfig);
		
		if ($delivery_id == 0){			
			$root['order_parm']['has_delivery_time'] = 0;//有配送日期选择;0:无,1:有
			$root['order_parm']['has_delivery'] = 0;//订单是否要配送;1:需要, 0:不需要
		}	
		else{
			//$root['order_parm']['has_delivery_time'] = 0;//有配送日期选择;0:无,1:有
			$root['order_parm']['has_delivery'] = 1;//订单是否要配送;1:需要, 0:不需要
		}
		
		/*
		0:团购券，序列号+密码
		1:实体商品，需要配送
		2:线下订购商品
		3:实体商品,有配送,有团购券
		*/		
		$sql = "select count(*) from ".DB_PREFIX."cart where (goods_type = 0 or goods_type = 2 or goods_type =3 ) and user_id = '".$user_id."' and session_id = '".$session_id."'";
		if (intval($GLOBALS['db']->getOne($sql)) > 0){
			$root['order_parm']['has_moblie'] = 1;//有手机号码
		}else{
			$root['order_parm']['has_moblie'] = 0;
		}
		
		//判断商品是否允许现金支付（货到付款)
		/*
		0:团购券，序列号+密码
		1:实体商品，需要配送
		2:线下订购商品
		3:实体商品,有配送,有团购券
		*/
		//has_delivery: 0;商品无配送; 1:商品有配送
		//has_mcod:1:商品支持，现金支付(货到付款); 0:不支持
		$sql = "select count(*) from ".DB_PREFIX."cart where (goods_type = 0 or goods_type = 2) and user_id = '".$user_id."' and session_id = '".$session_id."'";
		if (intval($GLOBALS['db']->getOne($sql)) > 0){
			$root['order_parm']['has_mcod'] = 0;//0:不支持(团购券，序列号+密码,线下订购商品)
		}else{
			$root['order_parm']['has_mcod'] = 1;//现金支付(货到付款)
		}				
	}
	
	$region_id = $delivery_region['region_lv4'];
	if ($region_id == 0){
		$region_id = $delivery_region['region_lv3'];
	}
	
	if ($region_id == 0){
		$region_id = $delivery_region['region_lv2'];
	}

	if ($region_id == 0){
		$region_id = $delivery_region['region_lv1'];
	}
   
	$root['order_parm']['delivery_list'] = $MConfig['delivery_list'];   
	//输出配送列表
	$delivery_ids = loadDelivery($region_id);	
    foreach($root['order_parm']['delivery_list'] as $k=>$v)
    {
    	if(!in_array($v['id'],$delivery_ids))
    	{
    		unset($root['order_parm']['delivery_list'][$k]);
    	}
    }
    $delivery_list_tmp = array();
	foreach($root['order_parm']['delivery_list'] as $k=>$v)
    {
    	$delivery_list_tmp[] = $root['order_parm']['delivery_list'][$k];
    }
	$root['order_parm']['delivery_list'] = $delivery_list_tmp;
	
    //$GLOBALS['tpl']->assign('delivery_list',$delivery_list);
	
		
   	$is_protect = 0;//intval($requestData['is_protect']);//是否有快递保费;默认为：0

   	
	$tax = 0;//intval($requestData['tax']);//税率,默认为：0

	$ecvSn = trim($requestData['ecv_sn']);//优惠券
	$ecvPassword = trim($requestData['ecv_pwd']);//优惠券密码   			
	
	//$root['has_delivery'] = intval($MConfig['has_delivery']);//有配送地址 ;快递方式，不让选择(系统默认的了)，只填配送地址，即可
	
	//$root['address'] = array();//会员地址列表 
	/*
	//优惠列表提示
	$yh = array();
	$yh[] = array("info"=>"手机下单立减5元","money"=>0);
	$yh[] = array("info"=>"全场购物满200元免运费","money"=>0); 
	
	$root['cartinfo'] = $yh;
	*/
	
	
	
	$credit = 0;//会员余额支付
	$isCreditAll = 0;
		
	$cart_total = s_countCartTotal($payment_id,$delivery_id,$is_protect,$delivery_region,$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);
	if ($cart_total['total_price'] > 0 && $money > 0){
		//有会员余额，使用会员余额支付一部分
		$isCreditAll = 1;
		if ($cart_total['total_price'] > $money){
			$credit = $money;//会员余额不足
		}else{
			$credit = $cart_total['total_price'];//会员余额足够
		}
		//$feeinfo[] = array("item"=>"isCreditAll:","value"=>$credit);
		//使用会员余额支付，重新计算剩余费用
		$cart_total = s_countCartTotal($payment_id,$delivery_id,$is_protect,$delivery_region,$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);
	}
	//echo $delivery_id."<br>";
	//print_r($cart_total); 
	$root['feeinfo'] = getFeeItem($cart_total);

	$root['use_user_money'] = floatval($cart_total['credit']);//使用会员余额支付金额
	$root['pay_money'] = floatval($cart_total['total_price']);//还需要支付金额
	
	//清空购买车
	$sql = "delete from ".DB_PREFIX."cart where session_id='".$session_id."'";
	if ($user_id > 0){
		$sql .= " or user_id = ".$user_id;
	}
	$GLOBALS['db']->query($sql);
		
	dispay($root);		
	
}else if($act == 'done_cart'){
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码	
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);

	$user_id  = intval($user['user_id']);
	$money = floatval($user['user_money']);
	$_SESSION['user_id'] = $user_id;

	$root = array();
	$root['return'] = 1;
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	
	$cartdata = $requestData['cartdata'];
	
	//把数据插入购买车
	$session_id=session_id();
	insertCartData($user_id,$session_id,$cartdata,$user['user']['group_id']);
	
	

	$delivery_id = intval($requestData['delivery_id']);//配送方式;
	if ($delivery_id == 0)
		$delivery_id = intval($MConfig['delivery_id']);//取系统配置
				
	$root['select_delivery_id'] = $delivery_id;	

		$delivery_region = array(
				 'region_lv1'=>intval($requestData['region_lv1']),
				 'region_lv2'=>intval($requestData['region_lv2']),
				 'region_lv3'=>intval($requestData['region_lv3']),
				 'region_lv4'=>intval($requestData['region_lv4'])
		);

	$delivery_id = getDeliveryId($user_id,$session_id,$delivery_region,0,$delivery_id);
	if ($delivery_id == -1){
		$root['info'] = "无法将商品配送到该地区.";
		$root['status'] = 0;
		$root['return'] = 0;
		dispay($root);		
	}
		
	$error = '';
	if (!check_cart_goods($session_id,$user_id,$error)){
		$root['status'] = 0;
		$root['info'] = $error;
		$root['return'] = 0;
		dispay($root);
	}
		


	$_SESSION['user_email'] = '';
	
	$_REQUEST['payment_id'] = $requestData['payment_id'];//支付方式
	$_REQUEST['delivery_id'] = $delivery_id;//配送方式
	$_REQUEST['is_protect'] = 0;//是否保价
	
	$_REQUEST['credit'] = $requestData['use_user_money'];//使用会员余额支付金额
	$_REQUEST['iscreditall'] = 1;//使用全额支付
	
	//提交的地区
	$_REQUEST['region_lv1'] = intval($requestData['region_lv1']);
	$_REQUEST['region_lv2'] = intval($requestData['region_lv2']);
	$_REQUEST['region_lv3'] = intval($requestData['region_lv3']);
	$_REQUEST['region_lv4'] = intval($requestData['region_lv4']);
	$_REQUEST['address'] = $requestData['delivery_detail'];//配送地址
	$_REQUEST['mobile_phone'] = $requestData['phone'];//收件人手机	   		
	$_REQUEST['consignee'] = $requestData['consignee'];//收件人
	$_REQUEST['zip'] = $requestData['postcode'];//邮编
	$_REQUEST['fix_phone'] = '';//固定号码
	
	$_REQUEST['tax_title'] = $requestData['tax_title'];//发票抬头
	if ($_REQUEST['tax_title'] <> ''){
		$_REQUEST['tax'] = 1;//是否开票	
	}else{
		$_REQUEST['tax'] = 0;//是否开票	
	}
		
	$_REQUEST['ecv_sn'] = $requestData['ecv_sn'];//优惠券序号
	$_REQUEST['ecv_password'] = $requestData['ecv_pwd'];//优惠券密码
	$_REQUEST['memo'] = $requestData['content'];//订单备注
	$_REQUEST['user_mobile_phone'] = $requestData['send_mobile'];//接收团购券的手机号码
	
	if (!empty($requestData['send_mobile']) && empty($user['user']['mobile_phone'])){
		//如果会员手机号码为空的话，则将手机号码赋值给会员
		$sql = "update ".DB_PREFIX."user set mobile_phone = '{$requestData['send_mobile']}' where id = '{$user_id}' limit 1";
		$GLOBALS['db']->query($sql);
	}
	
	//$_REQUEST['goods_send_date'];//推迟发货时间	
	//$_REQUEST['tax_content'];
	$_REQUEST['delivery_refer_order_id'] = 0;//拼单ID
	

		

	require ROOT_PATH.'app/source/func/com_order_pay_func.php';
	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	$cartinfo = cart_done_3(0,true);
	$root['status'] = $cartinfo['status'];//false处理失败,true处理成功
	if ($root['status'] == true){
		$root['status'] = 1;
		$root['info'] = UrlDecode($cartinfo['accountpay_str'])."\n".UrlDecode($cartinfo['ecvpay_str']);
		if (strtolower($requestData['payment_code']) == 'mcod'){//现金支付、货到付款 支付方式
			$root['info'] = '下单成功,购物愉快';
		}
	}else{
		$root['info'] = $cartinfo['error'];//错误信息
		$root['status'] = 0;
		$root['return'] = 0;
		dispay($root);
	}
	
	$root['order_id'] = $cartinfo['order_id'];//处理成功时，返回的订单ID
	
	$root['pay_status'] = 0;//0:订单未收款(全额);1:订单已经收款(全额)
	if ($cartinfo['money_status'] == 2)
		$root['pay_status'] = 1;
    @file_get_contents(a_getDomain()."/services/ajax.php?run=autoSendList&user_id=".$user_id);

	dispay($root);
}else if($act == 'pay_order'){
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);
	
	$user_id  = intval($user['user_id']);
	
	$order_id = addslashes(trim($requestData['order_id']));
	
	$root = array();
	$root['pay_status'] = 0;//0:订单未收款(全额);1:订单已经收款(全额)
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	$order = $GLOBALS['db']->getRow("select sn,money_status,payment from ".DB_PREFIX."order where user_id = {$user_id} and id = ".$order_id);
	if (empty($order)){
		$root['pay_status'] = 1;
		$root['pay_info'] = '订单不存在.';
		$root['show_pay_btn'] = 0;
		dispay($root);		
	}
	
	if ($order['money_status'] == 2){
		$root['pay_status'] = 1;
		$root['pay_info'] = '订单已经收款.';
		$root['show_pay_btn'] = 0;
		dispay($root);
	}
	//print_r($order);exit;	
	$payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id=".intval($order['payment']));
	$pay_code = strtolower($payment_info['class_name']);
	if (!($pay_code == 'malipay' || $pay_code == 'mtenpay' || $pay_code == 'mcod')){
		$root['return'] = 0;
		$root['pay_info'] = '手机版本不支付,无法在手机上支付.'.$pay_code;
		$root['show_pay_btn'] = 0;	
		dispay($root);
	}
	
	require ROOT_PATH.'app/source/func/com_order_pay_func.php';
	$pay = getPayment($order_id,0);//out_trade_no:支付单号(用来传给支付接口如：支付宝，财付通)，如果使用：order_id 的话，可能会产生重复问题

	$root['return'] = 1;
	$root['pay_code'] = $pay['pay_code'];
	$root['order_id'] = $order_id;
	$root['order_sn'] = $order['sn'];
	$root['show_pay_btn'] = 0;//0:不显示，支付按钮; 1:显示支付按钮
	
	//支付接口支付 malipay,支付宝;mtenpay,财付通;mcod,货到付款/现金支付
	if ($pay['pay_code'] == 'malipay'){
		$root['pay_money_format'] = $pay['total_fee_format'];
		$root['pay_money'] = $pay['total_fee'];
		$root['pay_info'] = $pay['body'];
		$root['malipay'] = $pay;
		
		if ($root['pay_money'] > 0){
			$root['show_pay_btn'] = 1;
		}
	}else if ($pay['pay_code'] == 'mtenpay'){
		$root['pay_money_format'] = $pay['total_fee_format'];
		$root['pay_money'] = $pay['total_fee'];
		$root['pay_info'] = $pay['body'];
		$root['mtenpay'] = $pay;
		if ($root['pay_money'] > 0){
			$root['show_pay_btn'] = 1;
		}		
	}else if ($pay['pay_code'] == 'mcod'){
		$root['pay_money_format'] = $pay['total_fee_format'];
		$root['pay_money'] = $pay['total_fee'];
		$root['pay_info'] = $pay['body'];
		$root['mcod'] = $pay;
		
		$root['show_pay_btn'] = 0;
	}else{
		$root['return'] = 0;
		$root['pay_info'] = '手机版本不支付,无法在手机上支付.';
		$root['show_pay_btn'] = 0;
	}
	@file_get_contents(a_getDomain()."/services/ajax.php?run=autoSendList&user_id=".$user_id);	
	dispay($root);
	
}else if($act == 'searchgoods')
{

	$city_id = intval($requestData['city_id']);//城市分类ID
	$page = intval($requestData['page']); //分页
	
	$keyword = addslashes(trim($requestData['keyword']));

	$now = a_gmtTime();
		
	$page=$page==0?1:$page;

	$page_size = PAGE_SIZE;
	$limit = (($page-1)*$page_size).",".$page_size;
		
	$sql = "SELECT s.name as supplier_name,s.brief as supplier_biref,sp.tel as sp_tel,sp.address as sp_address,g.id,g.city_id,g.type_id,".
			"w.name_1 as num_unit,g.max_bought,g.goods_type,".
			"g.name_1 as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.brief_1 as goodsbrief,gc.name as city_name,s.name as suppliers_name,g.buy_count,gc.py ".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'weight as w on w.id = g.weight_unit '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sp on s.id = sp.supplier_id '.
					"where sp.is_main=1 and g.status = 1 and g.type_id != 2 and g.score_goods = 0 and g.promote_begin_time <= $now and g.promote_end_time >=". $now ;
	$sql_count = 'SELECT count(*) FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'weight as w on w.id = g.weight_unit '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sp on s.id = sp.supplier_id '.
					"where sp.is_main=1 and g.status = 1 and g.type_id != 2 and g.score_goods = 0 and g.promote_begin_time <= $now and g.promote_end_time >=". $now ;
					
		if($keyword!='')
		{
			$sql.=" and g.name_1 like '%".$keyword."%' ";
			$sql_count.=" and g.name_1 like '%".$keyword."%' ";
		}
		
		
		if ($city_id > 0)
		{
			$sql .= " and (g.city_id = $city_id or g.all_show = 1)";
			$sql_count .= " and (g.city_id = $city_id or g.all_show = 1)";
		}
						
		$sql .= " order by g.sort desc,g.id desc";
		$sql_count .= " order by g.sort desc,g.id desc";
		
		$sql.=" limit ".$limit;
	
		$list = $GLOBALS['db']->getAll($sql);
		$total = $GLOBALS['db']->getOne($sql_count);
		$page_total = ceil($total/$page_size);
		
		
		
		$root = array();
		$root['return'] = 1;
		
				
		$goodses = array();
		foreach($list as $item)
		{
			//$goods = array();
			$goods = getGoodsArray($item);
			$goodses[] = $goods;
		}
		$root['item'] = $goodses;
		$root['page'] = array("page"=>$page,"page_total"=>$page_total);
		
		dispay($root);
}else if($act == 'check_ecv'){
//检查优惠券是否有效
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码
	
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);	
	$user_id = intval($user['user_id']);
	$_SESSION ['user_id'] = $user_id;
	$root = array();
	$root['return'] = 1;
	$root['info'] = "";	
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	
	$ecvSn = trim($requestData['ecv_sn']);
	$ecvPassword = trim($requestData['ecv_pwd']);
		
	$root['check_ecv_state'] = 0;//0:无效,1:有效
		
	if (!empty($ecvSn)){
		$chk = check_ecvverify_3($ecvSn,$ecvPassword);
		if ($chk['type'] == 0){
			$root['info'] = $chk['msg'];	
		}else{
			$root['check_ecv_state'] = 1;
			$root['info'] = "验证成功!";	
		}		
	}else{
		$root['info'] = "卡号不能为空!";	
	}	
	dispay($root);
}else if($act == 'check_order_status'){
	/**
	* info: 返回信息显示内容
	* pay_status 0:订单未收款(全额);1:订单已经收款(全额)
	*/	
	$out_trade_no = trim($requestData['out_trade_no']);
	
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码
		
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);
	$user_id = intval($user['user_id']);
	$_SESSION['user_id'] = $user_id;
	
	
	$root = array();
	$root['return'] = 1;
	$root['pay_status'] = 0;//0:订单未收款(全额);1:订单已经收款(全额)
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆	
	$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module,money,payment_id from ".DB_PREFIX."payment_log where id=".intval($out_trade_no));
		
	if($payment_log['rec_module']=='Order'){	
		$order = $GLOBALS['db']->getRow("select money_status from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
		if ($order){
			if ($order['money_status'] == 2){
				$root['pay_status'] = 1;
				$root['info'] = "下单成功,购物愉快";
				require_once ROOT_PATH.'app/source/func/com_send_sms_func.php';
				//require ROOT_PATH."app/source/func/com_order_pay_func.php";
				s_autoRun();//支付成功后，运行自动发放团购券
								
				$user2_id = intval($_REQUEST['user_id']);		
				require_once ROOT_PATH.'services/Sms/SmsPlf.class.php';
				require_once ROOT_PATH.'services/Mail/Mail.class.php';
				send_list($user2_id);							
			}else{
				$root['pay_status'] = 0;
				$root['info'] = "订单末处理成功,请联系客服人员处理";
			}
		}else{
			$root['info'] = "订单不存在:".$payment_log['rec_id'];	
		}
	}else{
		$root['info'] = "无效的支付单号:".$out_trade_no;	
	}
					
	dispay($root);	
}else if($act == 'user_addr_list'){
	//用户注册
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码

	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);

	
		
	$root = array();
	$root['return'] = 1;
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆	
	$addr_list = getUserAddr($user['user_id'],true);
	$root['item'] = $addr_list;

	dispay($root);
}else if($act == 'add_addr'){
	//用户添加配送地址
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码

	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);

	$user_id = intval($user['user_id']);
	$id = intval($requestData['id']);//id,有ID值则更新，无ID值，则插入


	$root = array();
	$root['return'] = 1;
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	$addr = array();
	$addr['user_id'] = $user_id;

	$addr['region_lv1'] = intval($requestData['region_lv1']);//国家
	$addr['region_lv2'] = intval($requestData['region_lv2']);//省
	$addr['region_lv3'] = intval($requestData['region_lv3']);//城市
	$addr['region_lv4'] = intval($requestData['region_lv4']);//地区/县

	$addr['consignee'] = addslashes(trim($requestData['consignee']));//联系人姓名
	$addr['address'] = addslashes(trim($requestData['delivery_detail']));//详细地址
	$addr['mobile_phone'] = addslashes(trim($requestData['phone']));//手机号码
	$addr['zip'] = addslashes(trim($requestData['postcode']));//邮编

	if ($id == 0){
		$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee", addslashes_deep($addr), 'INSERT');
		$addr_id = $GLOBALS['db']->insert_id();
	}else{
		$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee", addslashes_deep($addr), 'UPDATE', "user_id = {$user_id} and id = {$id}");
		$addr_id = $id;
	}
	
	$root['id'] = $addr_id;
	dispay($root);
}else if($act == 'del_addr'){
	//用户删除配送地址
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码
	
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);

	$user_id = intval($user['user_id']);
	$id = intval($requestData['id']);//id,有ID值则更新，无ID值，则插入

	
	$sql = "delete from ".DB_PREFIX."user_consignee where user_id = {$user_id} and id = {$id}";
	$GLOBALS['db']->query($sql);

	$root = array();
	$root['return'] = 1;
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	$root['info'] = "数据删除成功!";
	dispay($root);

}else if($act == 'down_region_conf'){
	
	//$region_list =$GLOBALS['cache']->get("mobile_goods_down_region_conf",true,'.txt');
	
	$filenamezip = $GLOBALS['cache']->filename("mobile_goods_down_region_conf",true,'.zip');
	if(!file_exists($filenamezip)){
		$sql = "select id,pid,name,'' as postcode,'' as py from ".DB_PREFIX."region_conf";
		$list = $GLOBALS['db']->getAll($sql);
		
		$root = array();
		$root['return'] = 1;
		
		$region_list = "";
		foreach($list as $item)
		{
			$sql = "insert into region_conf(id,pid,name,postcode,py) values('{$item['id']}','{$item['pid']}','{$item['name']}','{$item['postcode']}','{$item['py']}');";
			if ($region_list == ""){
				$region_list = $sql;
			}	
			else{
			   $region_list = $region_list."\n".$sql;
			}   
		}		
		//$GLOBALS['cache']->set("mobile_goods_down_region_conf",$region_list,"-1",true,'.txt');
		
		require_once ROOT_PATH.'mapi/zipfile.php';
		$ziper = new zipfile();
		$ziper->addFile($region_list,"region_conf.txt");
		$ziper->output($filenamezip);
		/*		
		$filename = $GLOBALS['cache']->filename("mobile_goods_down_region_conf",false,'.txt');
		if (file_exists($filename)){
			$filenamezip = $filename.".zip";
			if (!file_exists($filenamezip)){
				require_once ROOT_PATH.'mapi/zipfile.php';
				$ziper = new zipfile();
				$ziper->addFile($region_list,"region_conf.txt");
				$ziper->output($filenamezip);
			}
		}
		*/		
	}
		
	$root = array();
	$root['return'] = 1;
	if (file_exists($filenamezip)){
		$root['file_exists'] = 1;
	}else{
		$root['file_exists'] = 0;
	}
	$sql = "select count(*) as num from ".DB_PREFIX."region_conf";
	$root['region_num'] = $GLOBALS['db']->getOneCached($sql);//配置地区数量
	$root['file_url'] = $GLOBALS['cache']->getUrl("mobile_goods_down_region_conf",'.zip');
	$root['file_size'] = abs(filesize($filenamezip));
	dispay($root);
		
}else if($act == 'my_order_list'){
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码
	
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);

	$user_id = $user['user_id'];
	
	$pageRows = PAGE_SIZE;//每页显示记录数
	
	$nowPage = intval($requestData['page']); //当前分页
	$totalRows = intval($requestData['totalRows']); //总记录数	
	if ($totalRows == 0){		
		$sql = "select count(*) from ".DB_PREFIX."order where user_id = {$user_id}";
		$totalRows = $GLOBALS['db']->getOne($sql);
	}
	$totalPages = ceil($totalRows / $pageRows); //总页数

	$root = array();
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	$root['totalPages'] = $totalPages; //总页数
	$root['pageRows'] = $pageRows; //页记录数
	$root['nowPage'] = $nowPage; //当前页
	$root['totalRows'] = $totalRows;//总记录数

	$pageSize = $pageRows * ($nowPage - 1);
	$sql = "select id,sn,order_total_price as total_money,order_total_price - order_incharge as money,money_status,goods_status,create_time,payment,".
			"a.order_total_price,a.discount,a.total_price,a.delivery_fee,a.protect_fee,a.payment_fee,a.tax_money,a.ecv_money,a.order_incharge,".
			"a.zip,a.address,a.mobile_phone,a.mobile_phone_sms,a.consignee,a.memo,a.tax_title,a.ecv_id,a.delivery,a.protect,a.tax".
	 " from ".DB_PREFIX."order a where status<>2 and user_id = {$user_id} order by id desc limit {$pageSize}, {$pageRows}";
	$list = $GLOBALS['db']->getAll($sql);

	
	$root['return'] = 1;

	$orderlist = array();
	foreach($list as $item)
	{
		$orderlist[] = getOrderItem2($item);
	}
	$root['item'] = $orderlist;
	
	dispay($root);

}else if($act == 'my_edit_pwd'){
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$requestData['new_pwd'] = trim($requestData['new_pwd']);
	if (strlen($requestData['new_pwd']) < 4){
		$root['return'] = 0;
		$root['info'] = "注册密码不能少于4位";
		dispay($root);
	}
		
	$pwd = md5(trim($requestData['pwd']));//密码
	
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);

	$new_pwd = md5(trim($requestData['new_pwd']));//新密码
	$user_id = intval($user['user_id']);
	
	if ($new_pwd != $pwd){
		$sql = "update ".DB_PREFIX."user set user_pwd = '{$new_pwd}' where id = {$user_id} limit 1";
		$GLOBALS['db']->query($sql);
		$rs = $GLOBALS['db']->affected_rows();
	}else{
		$rs = 1;//新旧密码一至
	}
	
	$root = array();
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	if ($rs > 0){
		$root['return'] = 1;
		$root['info'] = "密码更新成功!";
	}else{
		$root['return'] = 0;
		$root['info'] = "密码更新失败!";
	}

	dispay($root);	

}else if($act == 'my_order_del'){
		//删除订单
		$email = addslashes(trim($requestData['email']));//用户名或邮箱
		$pwd = md5(trim($requestData['pwd']));//密码
	
		//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
		$user = check_user($email,$pwd, true);
		$user_id = intval($user['user_id']);
		$order_id = intval(addslashes(trim($requestData['id'])));//订单ID
	
		$root = array();
		$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
		$order_info = $GLOBALS ['db']->getRow ( "select id,money_status,goods_status,user_id from " . DB_PREFIX . "order where user_id = " . $user_id . " and id=" . $order_id );
		if ($order_info ['money_status'] > 0 || $order_info ['status'] > 0 || ($order_info ['goods_status'] > 0 && $order_info ['goods_status'] != 5)) {
			$root['return'] = 0;
			$root['info'] = a_L("ORDER_STATUS_CANT_DELETE");
		}else{
			$sql = "update " . DB_PREFIX . "order set status=2 where user_id={$user_id} and id= {$order_id} limit 1";
			$GLOBALS ['db']->query ($sql);
			$root['return'] = 1;
			$root['info'] = a_L("DEL_SUCCESS");
		}
	
		dispay($root);
	
}else if($act == 'calc_order'){
	
		/**
		 * 计算订单价格
		 *
		 *
		 */
		$email = addslashes(trim($requestData['email']));//用户名或邮箱
		$pwd = md5(trim($requestData['pwd']));//密码
		//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
		$user = check_user($email,$pwd, true);
		$user_id  = intval($user['user_id']);
	
		$money = floatval($user['user_money']);
		$order_id = intval(addslashes(trim($requestData['id'])));//订单ID
	
		$sql = "select a.id,a.sn,a.order_total_price as total_money,a.order_total_price - a.order_incharge as money,a.money_status,a.goods_status,a.create_time,a.payment, ".
						"a.zip,a.address,a.mobile_phone,a.mobile_phone_sms,a.consignee,a.memo,a.tax_title,a.ecv_id,a.ecv_money,a.delivery,a.protect,a.tax from ".DB_PREFIX."order a ".
						" where a.user_id = {$user_id} and a.id = {$order_id} limit 1";
	
		$item = $GLOBALS['db']->getRow($sql);
	
		$root = array();
		$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
		$root['return'] = 1;
		$root['status'] = 1;
		$root['info'] = "";
		$root['first_calc'] = $requestData['first_calc'];
		$root['order_parm'] = init_order_parm($MConfig);
		
		$delivery_region = array(
				 'region_lv1'=>intval($requestData['region_lv1']),
				 'region_lv2'=>intval($requestData['region_lv2']),
				 'region_lv3'=>intval($requestData['region_lv3']),
				 'region_lv4'=>intval($requestData['region_lv4'])
		);
	
		//把数据插入购买车
		$session_id=session_id();
		$delivery_id = intval($requestData['delivery_id']);//配送方式;
		if ($delivery_id == 0)
			$delivery_id = intval($MConfig['delivery_id']);//取系统配置
					
		$root['select_delivery_id'] = $delivery_id;	
		
		$delivery_id = getDeliveryId($user_id,$session_id,$delivery_region,$order_id,$delivery_id);//  intval($item['delivery']);//intval($requestData['delivery_id']);//配送方式;取系统配置
		if ($delivery_id == -1){
			$root['info'] = "无法将商品配送到该地区.";
			$root['status'] = 0;
			$root['return'] = 0;
		}
	
		$error = '';
		if (!check_order_goods($order_id,$user_id,$error)){
			$root['status'] = 0;//不允许再次支付购买
			$root['info'] = $error;
		}
				
		$is_protect = 0;//intval($requestData['is_protect']);//是否有快递保费;默认为：0
	
	
		$payment_id = intval($requestData['payment_id']);
		$tax = 0;//intval($requestData['tax']);//税率,默认为：0
	
		$ecvSn = "";//trim($requestData['ecv_sn']);//优惠券
		$ecvPassword = "";//trim($requestData['ecv_pwd']);//优惠券密码
	
	
		require ROOT_PATH.'app/source/func/com_order_pay_func.php';
	
	
		$region_id = $delivery_region['region_lv4'];
		if ($region_id == 0){
			$region_id = $delivery_region['region_lv3'];
		}
		
		if ($region_id == 0){
			$region_id = $delivery_region['region_lv2'];
		}

		if ($region_id == 0){
			$region_id = $delivery_region['region_lv1'];
		}
	   
		//$root['order_parm']['delivery_list'] = $MConfig['delivery_list'];   
		//输出配送列表
		$delivery_ids = loadDelivery($region_id);	
		foreach($root['order_parm']['delivery_list'] as $k=>$v)
		{
			if(!in_array($v['id'],$delivery_ids))
			{
				unset($root['order_parm']['delivery_list'][$k]);
			}
		}
		
	   $delivery_list_tmp = array();
		foreach($root['order_parm']['delivery_list'] as $k=>$v)
		{
			$delivery_list_tmp[] = $root['order_parm']['delivery_list'][$k];
		}
		$root['order_parm']['delivery_list'] = $delivery_list_tmp;
	
		$credit = 0;//会员余额支付
		$isCreditAll = 0;
	
		$cart_total = s_countOrderTotal($order_id,$payment_id,$delivery_id,$is_protect,$delivery_region,$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);
		if ($cart_total['total_price'] > 0 && $money > 0){
			//有会员余额，使用会员余额支付一部分
			$isCreditAll = 1;
			if ($cart_total['total_price'] > $money){
				$credit = $money;//会员余额不足
			}else{
				$credit = $cart_total['total_price'];//会员余额足够
			}
			//$feeinfo[] = array("item"=>"isCreditAll:","value"=>$credit);
			//使用会员余额支付，重新计算剩余费用
			$cart_total = s_countOrderTotal($order_id,$payment_id,$delivery_id,$is_protect,$delivery_region,$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);
		}
	
	
		if (floatval($item['ecv_money']) <> 0){
			$cart_total['ecvFee'] = $item['ecv_money'];
			$cart_total['ecvFee_format'] = a_formatPrice(floatval($item['ecv_money']));
		}
		$root['feeinfo'] = getFeeItem($cart_total);
	
		$root['use_user_money'] = floatval($cart_total['credit']);//使用会员余额支付金额
		$root['pay_money'] = floatval($cart_total['total_price']);//还需要支付金额
	
		dispay($root);
	
}else if($act == 'my_order_detail'){
	//需要根据订单状态来来确认,是否需要重新计算订单金额(可能配送方式，支付方式会有变动)
	
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码
	
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);
	$user_id = intval($user['user_id']);
	$money = floatval($user['user_money']);
	
	$order_id = addslashes(trim($requestData['id']));//订单ID
	
	$sql = "select a.id,a.sn,a.order_total_price as total_money,a.order_total_price - a.order_incharge as money,a.money_status,a.goods_status,a.create_time,a.payment, ".
			"a.order_total_price,a.discount,a.total_price,a.delivery_fee,a.protect_fee,a.payment_fee,a.tax_money,a.ecv_money,a.order_incharge,".
			"a.zip,a.address,a.mobile_phone,a.mobile_phone_sms,a.consignee,a.memo,a.tax_title,a.ecv_id,a.delivery,a.protect,a.tax,".
			"a.region_lv1,a.region_lv2,a.region_lv3,a.region_lv4,r1.name as r1_name, r2.name as r2_name, r3.name as r3_name, r4.name as r4_name from ".DB_PREFIX."order a ".
				   "left outer join ".DB_PREFIX."region_conf as r1 on r1.id = a.region_lv1 ".
					"left outer join ".DB_PREFIX."region_conf as r2 on r2.id = a.region_lv2 ".
					"left outer join ".DB_PREFIX."region_conf as r3 on r3.id = a.region_lv3 ".
					"left outer join ".DB_PREFIX."region_conf as r4 on r4.id = a.region_lv4 ".
			" where a.user_id = {$user_id} and a.id = {$order_id} limit 1";
		
	$item = $GLOBALS['db']->getRow($sql);
	//print_r($item);exit;
	$root = getOrderItem2($item);
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆

	$deliveryAddr = array();
	$deliveryAddr['consignee'] = $item['consignee'];//联系人姓名
	$deliveryAddr['delivery'] = $item['r1_name'].$item['r2_name'].$item['r3_name'].$item['r4_name'];
	$deliveryAddr['region_lv1'] = $item['region_lv1'];//国家
	$deliveryAddr['region_lv2'] = $item['region_lv2'];//省
	$deliveryAddr['region_lv3'] = $item['region_lv3'];//城市
	$deliveryAddr['region_lv4'] = $item['region_lv4'];//地区/县
	
	$deliveryAddr['delivery_detail'] = $item['address'];//详细地址
	$deliveryAddr['phone'] = $item['mobile_phone'];//手机号码
	$deliveryAddr['postcode'] = $item['zip'];//邮编

	$root['deliveryAddr'] = $deliveryAddr;
		
	$root['tax_title'] = $item['tax_title'];//发票抬头
	$root['content'] = $item['memo'];//订单备注
	if (empty($item['mobile_phone_sms'])){
		$root['send_mobile'] = $user['user']['mobile_phone'];//团购券手机
	}else{
		$root['send_mobile'] = $item['mobile_phone_sms'];//团购券手机
	}
	
	$root['deliver_time_id'] = 0;//配送日期ID 方维默认没有这个参数，所以填0
	

	
	$delivery_region = array(
			   		'region_lv1'=>intval($item['region_lv1']),
			   		'region_lv2'=>intval($item['region_lv2']),
			   		'region_lv3'=>intval($item['region_lv3']),
			   		'region_lv4'=>intval($item['region_lv4'])
	);
	
	
	require_once ROOT_PATH.'app/source/func/com_order_pay_func.php';
	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	
	$payment_id = $item['payment'];//支付方式
	$delivery_id = $item['delivery'];//配送方式
	//echo $payment_id; exit;
	if ($item['money_status'] != 2){
		$tax = 0;//$item['tax'];//发票
		$ecvSn = '';//$root['evc_sn'];
		$ecvPassword = '';//$root['evc_pwd'];
		$isCreditAll=1;
		$credit = 0;//会员余额支付
						
		//$delivery_id = intval($requestData['delivery_id']);//配送方式;
		if ($delivery_id == 0)
			$delivery_id = intval($MConfig['delivery_id']);//默认配送方式
		
		if ($payment_id == 0)			
			$payment_id = intval($MConfig['select_payment_id']);//默认支付方式
		$is_protect = 0;//是否保价	

		$delivery_id = getDeliveryId($user_id,$session_id,$delivery_region,$order_id,$delivery_id);
		if ($delivery_id == -1){
			$root['info'] = "无法将商品配送到该地区.";
			$root['has_pay'] = 0;//不允许再次支付购买
		}
		
		$error = '';
		if (!check_order_goods($order_id,$user_id,$error)){
			$root['has_pay'] = 0;//不允许再次支付购买
			$root['info'] = $error;
		}

		//1:允许继续支付;0:不允许
		if ($root['has_pay'] == 0){
			$root['has_edit_delivery'] = 0;//1:允许编辑配置地址;0:不允许编辑配置地址
			$root['has_edit_delivery_time'] = 0;//1:允许编辑配送时间;0:不允许编辑配送时间
			$root['has_edit_invoice'] = 0;//1:允许编辑发票;0:不允许编辑发票
			$root['has_edit_ecv'] = 0;//1:允许编辑优惠券;0:不允许编辑优惠券
			$root['has_edit_message'] = 0;//1:允许编辑订单留言;0:不允许编辑订单留言
			$root['has_edit_moblie'] = 0;//1:允许编辑手机号码;0:不允许编辑手机号码			
		}
		
		
		$cart_total = s_countOrderTotal($order_id,$payment_id,$delivery_id,$is_protect,$delivery_region,$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);
		if ($cart_total['total_price'] > 0 && $money > 0){
			//有会员余额，使用会员余额支付一部分
			$isCreditAll = 1;
			if ($cart_total['total_price'] > $money){
				$credit = $money;//会员余额不足
			}else{
				$credit = $cart_total['total_price'];//会员余额足够
			}
			//$feeinfo[] = array("item"=>"isCreditAll:","value"=>$credit);
			//使用会员余额支付，重新计算剩余费用
			$cart_total = s_countOrderTotal($order_id,$payment_id,$delivery_id,$is_protect,$delivery_region,$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);
		}		
		
		if (floatval($item['ecv_money']) <> 0){
			$cart_total['ecvFee'] = $item['ecv_money'];
			$cart_total['ecvFee_format'] = a_formatPrice(floatval($item['ecv_money']));
		}
		
		$root['use_user_money'] = floatval($cart_total['credit']);//使用会员余额支付金额
		$root['pay_money'] = floatval($cart_total['total_price']);//还需要支付金额		
		$root['feeinfo'] = getFeeItem($cart_total);
		$root['payment_id'] = $payment_id;//支付方式
		$root['delivery_id'] = $delivery_id;//配送方式
	}else{
		$feeinfo[] = array("item"=>a_L('XY_TOTAL_PRICES'),"value"=>$root['order_all_price_format']);//费用总计
		
		if ($root['total_price'] > 0){
			$feeinfo[] = array("item"=>a_L('XY_TOTAL_G_PRICES'),"value"=>"+".$root['total_price_format']);//+商品总价
		}
		if ($root['delivery_fee'] > 0){
			$feeinfo[] = array("item"=>a_L('DELIVERY_FEE'),"value"=>"+".$root['delivery_fee_format']);//+快递费用
		}
		
		if ($root['protect_fee'] > 0){
			$feeinfo[] = array("item"=>a_L('PROTECT_FEE'),"value"=>"+".$root['protect_fee_format']);//+快递保费
		}
		
		if ($root['payment_fee'] > 0){
			//$feeinfo[] = array("item"=>a_L('PAYMENT_FEE'),"value"=>"+".$root['payment_fee_format']);//+支付手续费			
			if ($root['payment_fee'] < 0){
				$feeinfo[] = array("item"=>'优惠金额',"value"=>$root['payment_fee_format']);
			}else{
				$feeinfo[] = array("item"=>a_L('PAYMENT_FEE'),"value"=>$root['payment_fee_format']);
			}			
		}
		
		if ($root['tax_money'] > 0){
			$feeinfo[] = array("item"=>a_L('TAX_MONEY'),"value"=>"+".$root['tax_money_format']);//+发票费用
		}
		
		if ($root['discount'] > 0){
			$feeinfo[] = array("item"=>a_L('XY_RANK_DISCOUNT'),"value"=>"-".$root['discount_price_format']);//-优惠金额
		}
		
		if ($root['order_total_price'] > 0){
			$feeinfo[] = array("item"=>a_L('XY_MUSE_TOTAL_PAY'),"value"=>"=".$root['order_total_price_format']);//=应付款金额
		}
		
		if ($root['ecv_money'] > 0){
			$feeinfo[] = array("item"=>a_L('XY_VOUCHER'),"value"=>"-".$root['ecv_money_format']);//-代金券金额
		}
		
		$feeinfo[] = array("item"=>a_L('PAID_AMOUNT'),"value"=>"-".$root['order_incharge_format']);//-已收金额
		
		$feeinfo[] = array("item"=>a_L('XY_WAIT_MONEY'),"value"=>"=".$root['total_price_pay_format']);//待付金额
		$root['feeinfo'] = $feeinfo;
		$root['use_user_money'] = 0;//使用会员余额支付金额
		$root['pay_money'] = 0;//还需要支付金额
		$root['delivery_id'] = $delivery_id;//配送方式
	}
	

	$root['order_parm'] = init_order_parm($MConfig);//订单初始化参数
	
	$region_id = $delivery_region['region_lv4'];
		if ($region_id == 0){
			$region_id = $delivery_region['region_lv3'];
		}
		
		if ($region_id == 0){
			$region_id = $delivery_region['region_lv2'];
		}

		if ($region_id == 0){
			$region_id = $delivery_region['region_lv1'];
		}
	   
		//$root['order_parm']['delivery_list'] = $MConfig['delivery_list'];   
		//输出配送列表
		$delivery_ids = loadDelivery($region_id);	
		foreach($root['order_parm']['delivery_list'] as $k=>$v)
		{
			if(!in_array($v['id'],$delivery_ids))
			{
				unset($root['order_parm']['delivery_list'][$k]);
			}
		}
		
   $delivery_list_tmp = array();
	foreach($root['order_parm']['delivery_list'] as $k=>$v)
    {
    	$delivery_list_tmp[] = $root['order_parm']['delivery_list'][$k];
    }
	$root['order_parm']['delivery_list'] = $delivery_list_tmp;
	
	if ($delivery_id == 0){
		$root['order_parm']['has_delivery_time'] = 0;//有配送日期选择;0:无,1:有
		$root['order_parm']['has_delivery'] = 0;//订单是否要配送;1:需要, 0:不需要
	}
	else{
		//$root['order_parm']['has_delivery_time'] = 0;//有配送日期选择;0:无,1:有
		$root['order_parm']['has_delivery'] = 1;//订单是否要配送;1:需要, 0:不需要
	}
	
	$sql = "select sn,password from ".DB_PREFIX."ecv where id = ".intval($item['ecv_id']);
	$ecv = $GLOBALS['db']->getRow($sql);
	if ($ecv){
		$root['evc_sn'] = $ecv['sn'];//优惠券序号
		$root['evc_pwd'] = $ecv['password'];//优惠券序号
	
		$root['order_parm']['has_ecv'] = 1;//在订单编辑中,不让编辑优惠券(可显示）
	}else{
		$root['evc_sn'] = "";//优惠券序号
		$root['evc_pwd'] = "";//优惠券序号
	
		$root['order_parm']['has_ecv'] = 0;//在订单编辑中,不让编辑优惠券(不显示)
	}
	
	/*
	 0:团购券，序列号+密码
	1:实体商品，需要配送
	2:线下订购商品
	3:实体商品,有配送,有团购券
	*/
	$sql = "select count(*) from ".DB_PREFIX."order_goods a left outer join ".DB_PREFIX."goods b on b.id = a.rec_id where (b.type_id = 0 or goods_type = 2 or b.type_id =3 ) and a.user_id = '".$user_id."' and a.order_id = '".$order_id."'";
	if (intval($GLOBALS['db']->getOne($sql)) > 0){
		$root['order_parm']['has_moblie'] = 1;//有手机号码
	}else{
		$root['order_parm']['has_moblie'] = 0;
	}
	
	//判断商品是否允许现金支付（货到付款)
	/*
	 0:团购券，序列号+密码
	1:实体商品，需要配送
	2:线下订购商品
	3:实体商品,有配送,有团购券
	*/
	//has_delivery: 0;商品无配送; 1:商品有配送
	//has_mcod:1:商品支持，现金支付(货到付款); 0:不支持
	$sql = "select count(*) from ".DB_PREFIX."order_goods a left outer join ".DB_PREFIX."goods b on b.id = a.rec_id where (b.type_id = 0 or goods_type = 2) and a.user_id = '".$user_id."' and a.order_id = '".$order_id."'";
	if (intval($GLOBALS['db']->getOne($sql)) > 0){
		$root['order_parm']['has_mcod'] = 0;//0:不支持(团购券，序列号+密码,线下订购商品)
	}else{
		$root['order_parm']['has_mcod'] = 1;//现金支付(货到付款)
	}
		

	$root['return'] = 1;
	
	dispay($root);	

}else if($act == 'done_order'){

	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码	
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);

	$user_id  = intval($user['user_id']);
	$money = floatval($user['user_money']);

	$order_id = intval(addslashes(trim($requestData['id'])));//订单ID

	$_REQUEST['order_id'] = $order_id;

	$root = array();
	$root['return'] = 1;
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
	$delivery_region = array(
		'region_lv1'=>intval($requestData['region_lv1']),
		'region_lv2'=>intval($requestData['region_lv2']),
		'region_lv3'=>intval($requestData['region_lv3']),
	    'region_lv4'=>intval($requestData['region_lv4'])
	);
	
	$delivery_id = getDeliveryId($user_id,$session_id,$delivery_region,$order_id,intval($MConfig['delivery_id']));
	if ($delivery_id == -1){
		$root['info'] = "无法将商品配送到该地区.";
		$root['status'] = 0;
		dispay($root);
	}
		
	
	$error = '';
	if (!check_order_goods($order_id,$user_id,$error)){
		$root['status'] = 0;//不允许再次支付购买
		$root['info'] = $error;
		dispay($root);
	}	
	

	$_SESSION['user_email'] = '';
		
	$_REQUEST['payment_id'] = $requestData['payment_id'];//支付方式
	$_REQUEST['delivery_id'] = $delivery_id;//配送方式,直接取，服务器中的配置
	$_REQUEST['is_protect'] = 0;//是否保价
	
	$_REQUEST['credit'] = $requestData['use_user_money'];//使用会员余额支付金额
	$_REQUEST['iscreditall'] = 1;//使用全额支付
	
	//提交的地区
	$_REQUEST['region_lv1'] = intval($requestData['region_lv1']);
	$_REQUEST['region_lv2'] = intval($requestData['region_lv2']);
	$_REQUEST['region_lv3'] = intval($requestData['region_lv3']);
	$_REQUEST['region_lv4'] = intval($requestData['region_lv4']);
	$_REQUEST['address'] = $requestData['delivery_detail'];//配送地址
	$_REQUEST['mobile_phone'] = $requestData['phone'];//收件人手机	   		
	$_REQUEST['consignee'] = $requestData['consignee'];//收件人
	$_REQUEST['zip'] = $requestData['postcode'];//邮编
	$_REQUEST['fix_phone'] = '';//固定号码
	
	$_REQUEST['tax_title'] = $requestData['tax_title'];//发票抬头
	if ($_REQUEST['tax_title'] <> ''){
		$_REQUEST['tax'] = 1;//是否开票	
	}else{
		$_REQUEST['tax'] = 0;//是否开票	
	}
		
	$_REQUEST['ecv_sn'] = "";//$requestData['ecv_sn'];//优惠券序号
	$_REQUEST['ecv_password'] = "";//$requestData['ecv_pwd'];//优惠券密码
	$_REQUEST['memo'] = $requestData['content'];//订单备注
	$_REQUEST['user_mobile_phone'] = $requestData['send_mobile'];//接收团购券的手机号码
	
	if (!empty($requestData['send_mobile']) && empty($user['user']['mobile_phone'])){
		//如果会员手机号码为空的话，则将手机号码赋值给会员
		$sql = "update ".DB_PREFIX."user set mobile_phone = '{$requestData['send_mobile']}' where id = '{$user_id}' limit 1";
		$GLOBALS['db']->query($sql);
	}
		
	//$_REQUEST['goods_send_date'];//推迟发货时间	
	//$_REQUEST['tax_content'];
	$_REQUEST['delivery_refer_order_id'] = 0;//拼单ID
	
	
	//把数据插入购买车
	$session_id=session_id();


	require ROOT_PATH.'app/source/func/com_order_pay_func.php';
	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	$cartinfo = order_done_3(true);
	$root['status'] = $cartinfo['status'];//false处理失败,true处理成功
	if ($root['status'] == true){
		$root['status'] = 1;
		$root['info'] = UrlDecode($cartinfo['accountpay_str'])."\n".UrlDecode($cartinfo['ecvpay_str']);
		if (strtolower($requestData['payment_code']) == 'mcod'){//现金支付、货到付款 支付方式
			$root['info'] = '下单成功,购物愉快';
		}
	}else{
		$root['info'] = $cartinfo['error'];//错误信息
		$root['status'] = 0;
		$root['return'] = 0;
		dispay($root);
	}
	
	$root['order_id'] = $cartinfo['order_id'];//处理成功时，返回的订单ID

	dispay($root);
	
}else if($act == 'couponlist'){
	$email = addslashes(trim($requestData['email']));//用户名或邮箱
	$pwd = md5(trim($requestData['pwd']));//密码
	
	//检查用户,用户密码不存在刚直接退出;存在则返回用户信息
	$user = check_user($email,$pwd, true);
	$user_id = intval($user['user_id']);
	$status = intval($requestData['tag']);
        $order_sort = intval($requestData['order_sort']); //排序
                        
                        $sql_order_sort = "DESC";
                        if($order_sort == 1){
                            $sql_order_sort = "ASC";
                        }
	
	$page = intval($requestData['page']); //分页
	$page=$page==0?1:$page;
	
       
		
	$page_size = PAGE_SIZE;
	$limit = (($page-1)*$page_size).",".$page_size;
		
	$sql = "select " .
			"id,sn as couponSn," .
			"password as couponPw," .
			"create_time as createTime," .
			"end_time as endTime," .
			"use_time as useTime," .
			"goods_name as dealName," .
			"goods_id as dealId,use_time,end_time " .
			"from ".DB_PREFIX."group_bond ";
	$sql_count = "select count(*) from ".DB_PREFIX."group_bond ";
	
	$time = a_gmtTime();
	$where = " status = 1 and is_valid = 1 and user_id = ".$user_id;
		if($status==1)
			$where .= " and (use_time = 0 or use_time is null) and end_time > 0 and end_time > $time and end_time - ".$time." < ".(72*3600);
		elseif($status == 2)
			$where .= " and (use_time = 0 or use_time is null) and (end_time = 0 or (end_time>0 and end_time > $time))";
		elseif($status == 3)
			$where .= " and (use_time > 0 or (end_time < $time and end_time > 0))";

	$sql_count.=" where ".$where;
	$sql.=" where ".$where;
        $sql .= " order by order_id desc , id $sql_order_sort";
	$sql_count .= " order by order_id desc , id $sql_order_sort";
	$sql.=" limit ".$limit;


	$list = $GLOBALS['db']->getAll($sql);
	$total = $GLOBALS['db']->getOne($sql_count);
	$page_total = ceil($total/$page_size);
		
	$root = array();
	$root['return'] = 1;
	$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆	
	//补充字段
	foreach($list as $k=>$v)
	{
		$list[$k]['createTime'] = a_toDate($list[$k]['createTime'],"Y-m-d");
		$list[$k]['endTime'] = a_toDate($list[$k]['endTime'],"Y-m-d");
		if($list[$k]['useTime']>0)
		$list[$k]['useTime'] = a_toDate($list[$k]['useTime'],"Y-m-d");
		else
		$list[$k]['useTime'] = "";
		$list[$k]['beginTime'] = "";
		$list[$k]['dealIcon'] = make_img(ROOT_PATH.$GLOBALS['db']->getOne("select big_img from ".DB_PREFIX."goods where id = ".$v['dealId']),0) ; 
		
		$list[$k]['lessTime'] = $v['endTime'] - a_gmtTime();
		
		$supplier_id = intval($GLOBALS['db']->getOne("select suppliers_id from ".DB_PREFIX."goods where id = ".$v['dealId']));
		$supplier_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".$supplier_id." and is_main = 1");
		
		$list[$k]['spName'] = $supplier_info['depart_name']?$supplier_info['depart_name']:"";
		$list[$k]['spTel'] = $supplier_info['tel']?$supplier_info['tel']:"";
		$list[$k]['spAddress'] = $supplier_info['address']?$supplier_info['address']:"";

	}
	
	$root['item'] = $list;
	$root['page'] = array("page"=>$page,"page_total"=>$page_total);

		
	dispay($root);
}
elseif ($act=='version')
{
	$root['version'] = VERSION;
	dispay($root);
}
elseif($act=='newslist')
{
	$root['newslist'] = $MConfig['newslist'];	
	dispay($root);
}elseif($act=='cate_list'){

	$pid = intval($requestData['pid']);
	$tree_list = $GLOBALS['cache']->get("CATELIST_".$pid);
	if($tree_list===false)
	{
		$sql = "select id, name_1 as name, pid, '' as py  from ".DB_PREFIX."goods_cate where pid = ".$pid;
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$v)
		{
			$count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."goods_cate where pid = ".$v['id']));
			if($count>0)
			$list[$k]['has_child'] = 1;
			else
			$list[$k]['has_child'] = 0;
		}
		$tree_list = toTree($list,"id","pid","child");
		$GLOBALS['cache']->set("CATELIST_".$pid,$tree_list);
	}
	
		if ($pid == 0){
			$all = array();
			$all[] = array("id"=>0,"name"=>"全部分类",pid=>0,py=>"",icon=>"",has_child=>0);
			$tree_list = array_merge($all, $tree_list);			
		}	
	$root = array();
	$root['return'] = 1;
	$root['item'] = $tree_list;

	dispay($root);
}elseif($act=='index')
{
		$root = array();
		$root['return'] = 1;
		$adv_list = $GLOBALS['cache']->get("MOBILE_INDEX_ADVS");
		if($adv_list===false)
		{
					$advs = $GLOBALS['db']->getAll(" select * from ".DB_PREFIX."m_adv where page = 'index' and status = 1 order by sort desc ");
					$adv_list = array();
					foreach($advs as $k=>$v)
					{
						$adv_list[$k]['id'] = $v['id'];
						$adv_list[$k]['name'] = $v['name'];
						$adv_list[$k]['img'] = get_abs_img_root2($v['img']);
						$adv_list[$k]['type'] = $v['type'];
						$adv_list[$k]['data'] = $v['data'] = unserialize($v['data']);
						if($v['type'] == 1)
						{
							$tag_count = count($v['data']['tags']);
							$adv_list[$k]['data']['count'] = $tag_count;
						}
						
						if(in_array($v['type'],array(9,10,11,12,13))) //列表取分类ID
						{
							$adv_list[$k]['data']['cate_name'] = $GLOBALS['db']->getOne("select name_1 from ".DB_PREFIX."goods_cate where id = ".intval($v['data']['cate_id']));								
							$adv_list[$k]['data']['cate_name'] = $adv_list[$k]['data']['cate_name']?$adv_list[$k]['data']['cate_name']:"全部";
						}
					}
					$GLOBALS['cache']->set("MOBILE_INDEX_ADVS",$adv_list);
		}
		$root['advs'] = $adv_list;
		
		
		$indexs_list = $GLOBALS['cache']->get("MOBILE_INDEX_INDEX");
		if($indexs_list===false)
		{
					$indexs = $GLOBALS['db']->getAll(" select * from ".DB_PREFIX."m_index where status = 1 order by sort desc ");
					$indexs_list = array();
					foreach($indexs as $k=>$v)
					{
						$indexs_list[$k]['id'] = $v['id'];
						$indexs_list[$k]['name'] = $v['name'];
						$indexs_list[$k]['vice_name'] = $v['vice_name'];
						$indexs_list[$k]['desc'] = $v['desc'];
						$indexs_list[$k]['is_hot'] = $v['is_hot'];
						$indexs_list[$k]['is_new'] = $v['is_new'];
						$indexs_list[$k]['img'] = get_abs_img_root2($v['img']);
						$indexs_list[$k]['type'] = $v['type'];
						$indexs_list[$k]['data'] = $v['data'] = unserialize($v['data']);
						if($v['type'] == 1)
						{
							$tag_count = count($v['data']['tags']);
							$indexs_list[$k]['data']['count'] = $tag_count;
						}
						if(in_array($v['type'],array(9,10,11,12,13))) //列表取分类ID
						{
							$indexs_list[$k]['data']['cate_name'] = $GLOBALS['db']->getOne("select name_1 from ".DB_PREFIX."goods_cate where id = ".intval($v['data']['cate_id']));															
							$indexs_list[$k]['data']['cate_name'] = $indexs_list[$k]['data']['cate_name']?$indexs_list[$k]['data']['cate_name']:"全部";
						}
					}
					$GLOBALS['cache']->set("MOBILE_INDEX_INDEX",$indexs_list);
		}
		$root['indexs'] = $indexs_list;
	dispay($root);
	
}elseif($act=='searchcate')
{
		$root = array();
		$root['return'] = 1;

		$cate_list = $GLOBALS['cache']->get("MOBILE_SEARCHCATE_CATELIST");
		if($cate_list === false)
		{
			//取出标签分类
			$cate_list_data = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."m_topic_tag_cate where showin_mobile = 1 order by sort desc");
			$cate_list = array();
			foreach($cate_list_data as $k=>$v)
			{
				$cate_list[$k]['id'] = $v['id'];
				$cate_list[$k]['name'] = $v['name'];
				$cate_list[$k]['bg'] = get_abs_img_root2($v['mobile_title_bg']);
				

				
				//查询分类下的标签
				$txt_tags_data = $GLOBALS['db']->getAll("select t.* from ".DB_PREFIX."m_topic_tag as t left join ".DB_PREFIX."m_topic_tag_cate_link as l on l.tag_id = t.id where l.cate_id =".$v['id']." order by t.sort desc limit 12");
				$txt_tags = array();
				foreach($txt_tags_data as $kk=>$vv)
				{
					$txt_tags[$kk]['tag_name'] = $vv['name'];
					$txt_tags[$kk]['color'] = $vv['color'];
				}
				$cate_list[$k]['tags'] = $txt_tags;
			}
			$GLOBALS['cache']->set("MOBILE_SEARCHCATE_CATELIST",$cate_list,CACHE_TIME);
		}
		$root['item'] = $cate_list;
		
		dispay($root);
}elseif($act=='mapsearch')
{
	$root = array();
	$root['return'] = 1;
	
	$ytop = $latitude_top = floatval($requestData['latitude_top']);//最上边纬线值 ypoint
	$ybottom = $latitude_bottom = floatval($requestData['latitude_bottom']);//最下边纬线值 ypoint
	$xleft = $longitude_left = floatval($requestData['longitude_left']);//最左边经度值  xpoint
	$xright = $longitude_right = floatval($requestData['longitude_right']);//最右边经度值 xpoint
	$ypoint =  $m_latitude = doubleval($requestData['m_latitude']);  //ypoint 
	$xpoint = $m_longitude = doubleval($requestData['m_longitude']);  //xpoint
	//$type = intval($requestData['type']); //-1:全部，0：优惠券；1：活动；2：团购；3：代金券；4：商家		
			
	$pi = 3.14159265;  //圆周率
	$r = 6378137;  //地球平均半径(米)
	$field_append = ", (ACOS(SIN(($ypoint * $pi) / 180 ) *SIN((sp.ypoint * $pi) / 180 ) +COS(($ypoint * $pi) / 180 ) * COS((sp.ypoint * $pi) / 180 ) *COS(($xpoint * $pi) / 180 - (sp.xpoint * $pi) / 180 ) ) * $r) as distance ";
        //$condition = "  ypoint > $ybottom and ypoint < $ytop and xpoint > $xleft and xpoint < $xright ";
	//$limit = 10;

	$now = a_gmtTime();
	
	$sql = "SELECT s.name as supplier_name,s.brief as supplier_biref,sp.tel as sp_tel,sp.address as sp_address,g.id,g.city_id,g.type_id,".
			"w.name_1 as num_unit,g.max_bought,g.goods_type,".
			"g.name_1 as goods_name,g.small_img,g.big_img,g.origin_img,g.shop_price,g.market_price,g.promote_begin_time,g.promote_end_time,g.brief_1 as goods_brief,gc.name as city_name,s.name as suppliers_name,g.buy_count,gc.py,sp.xpoint as xpoint, sp.ypoint as ypoint $field_append".
					'FROM '.DB_PREFIX.'goods as g '.
					'left join '.DB_PREFIX.'group_city as gc on gc.id = g.city_id '.
					'left join '.DB_PREFIX.'weight as w on w.id = g.weight_unit '.
					'left join '.DB_PREFIX.'suppliers as s on s.id = g.suppliers_id '.
					'left join '.DB_PREFIX.'suppliers_depart as sp on s.id = sp.supplier_id '.
					"where sp.is_main=1 and g.status = 1 and g.type_id != 2 and g.score_goods = 0 and g.promote_begin_time <= $now and g.promote_end_time >=". $now ;

	$sql.= "  and sp.ypoint > $ybottom and sp.ypoint < $ytop and sp.xpoint > $xleft and sp.xpoint < $xright limit 0,10";
	
	$list = $GLOBALS['db']->getAll($sql);
		

	$tuan_list = array();
	foreach($list as $item){	
		$distance = $item['distance'];
		$small_img = $item['small_img'];
		$xpoint =  $item['xpoint'];
		$ypoint =  $item['ypoint'];
		$item['goods_brief'] = strip_tags($item['goods_brief']);
		$item = getGoodsArray($item);
		$item['name'] = $item['title'];	
		$item['icon'] = make_img(ROOT_PATH.$small_img,0);	
		$item['type'] = 2;	
		$item['distance'] = round($distance);//0;
		$item['xpoint'] = $xpoint;//0;
		$item['ypoint'] = $ypoint;//0;
		$tuan_list[] = $item;
	}
			
	$root['item'] = $tuan_list;

	dispay($root);		
}
?>