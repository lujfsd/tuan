<?php
//中文
require ROOT_PATH.'app/source/db_init.php';
require ROOT_PATH.'app/source/comm_init.php';
require ROOT_PATH.'app/source/func/com_func.php';
require ROOT_PATH.'app/source/user_init.php';

define('API_ROOT', str_replace('/mapi', '', __ROOT__));
if(!defined('APP_ROOT_PATH')) 
define('APP_ROOT_PATH', str_replace('mapi/comm.php', '', str_replace('\\', '/', __FILE__)));
//定义一个缓存类
class CacheFileService
{//类定义开始

	 protected $prefix='~@';
    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
	private $dir;
    public function __construct()
    {
        $this->dir = APP_ROOT_PATH."app/Runtime/caches/";
        $this->init();
    }

    /**
     +----------------------------------------------------------
     * 初始化检查
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    private function init()
    {
        $stat = @stat($this->dir);

        // 创建项目缓存目录
        if (!is_dir($this->dir)) {
            if (!  mkdir($this->dir))
                return false;
             chmod($this->dir, 0777);
        }
    }

    public function filename($name,$mdir=false, $filename_ext = '.php')
    {
        $name	=	md5($name);
        $filename	=  $name.$filename_ext;
       
        $hash_dir = $this->dir . '/c' . substr(md5($name), 0, 1)."/";
     	if ($mdir&&!is_dir($hash_dir))
        {
             mkdir($hash_dir);
             chmod($hash_dir, 0777);
        }
        return $hash_dir.$this->prefix.$filename;
    }

    public function getUrl($name, $filename_ext = '.php'){
    	
    	$root_path = str_replace( "/mapi", "", dirname(__ROOT__));
    	$url = 'http://'.$_SERVER['HTTP_HOST'].$root_path."/";
    	$url = str_replace("\\","",$url);
    	$filename  = $this->filename($name,false,$filename_ext);
    	if (file_exists($filename)){
    		return str_replace(APP_ROOT_PATH, $url, $filename);
    	}else{
    		return false;
    	}
    }
   
    /**
     +----------------------------------------------------------
     * 读取缓存
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $name 缓存变量名
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    public function get($name, $org = false, $filename_ext = '.php')
    {    	    
    	$var_name = md5($name);    	
    	global $$var_name;
    	if($$var_name)
    	{
    		return $$var_name;
    	}
    	
        $filename   =   $this->filename($name,false,$filename_ext);    
        $content = @file_get_contents($filename);
        if( false !== $content) { 
        	if ($org == false){
        		$expire  =  (int)substr($content,8, 12);
        		if($expire != -1 && time() > filemtime($filename) + $expire) {
        			//缓存过期删除缓存文件
        			@unlink($filename);
        			return false;
        		}
        		$content   =  substr($content,20, -3);
        		$content    =   unserialize($content);
        		$$var_name  = $content;        		
        	}
            return $content;
        }
        else {
            return false;
        }
    }

    /**
     +----------------------------------------------------------
     * 写入缓存
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param int $expire  有效时间 -1 为永久
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function set($name,$value,$expire ="-1",$org = false, $filename_ext = '.php')
    {
        
    	$filename   =   $this->filename($name, true, $filename_ext);
        
        if ($org == false){
        	$data   =   serialize($value);
        	$data    = "<?php\n//".sprintf('%012d',$expire).$data."\n?>";        	
        }else{
        	$data = $value;
        }
        
	    $rs = @file_put_contents($filename,$data);
	    if($rs)
        	return true;
        else
        	return false;
        
    }

    /**
     +----------------------------------------------------------
     * 删除缓存
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $name 缓存变量名
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function rm($name)
    {
        return unlink($this->filename($name));
    }

}//类定义结束
//end cache class
$cache = new CacheFileService(); 

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

function dispay($root){
	header("Content-Type:text/html; charset=utf-8");
	$r_type = intval($_REQUEST['r_type']);//返回数据格式类型; 0:base64;1;json_encode;2:array
	$root['act'] = ACT;
	$root['act_2'] = ACT_2;
	if ($r_type == 0){
		echo base64_encode(json_encode($root));
	}else if ($r_type == 1){
		print_r(json_encode($root));
	}else if ($r_type == 2){
		print_r($root);
	};
	exit;
}
	
	


function toTree($list=null, $pk='id',$pid = 'pid',$child = '_child')
 {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            
            foreach ($list as $key => $data) {
                $_key = is_object($data)?$data->$pk:$data[$pk];
                $refer[$_key] =& $list[$key];
            }            
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = is_object($data)?$data->$pid:$data[$pid];
                $is_exist_pid = false;
                foreach($refer as $k=>$v)
                {
                	if($parentId==$k)
                	{
                		$is_exist_pid = true;
                		break;
                	}
                }
                if ($is_exist_pid) { 
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                } else {
                    $tree[] =& $list[$key];
                }
            }
        }
        return $tree;
 }



/**
 * tree_list
 * array("id"=>id,"name"=>name,"py"=>py,"has_childs"=>1,"child"=>array("id"=>id,"name"=>name.....))
 * 
 * search_list 
 * array("id"=>id,"name"=>name,"py"=>py,"has_childs"=>1)
*/

function getCatalogArray($onle_lv_1 = false){
	
	$tree_list = $GLOBALS['cache']->get("CATELIST");
	if($tree_list===false)
	{
		if ($onle_lv_1)
			$sql = "select id, name_1 as name, pid, '' as py  from ".DB_PREFIX."goods_cate where pid = 0";
		else
			$sql = "select id, name_1 as name, pid, '' as py  from ".DB_PREFIX."goods_cate";
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
		$GLOBALS['cache']->set("CATELIST",$tree_list);
	}
	
	return $tree_list;
}

function getCatalogArraySearch($onle_lv_1 = false){
	
	$list = $GLOBALS['cache']->get("CATELISTSEARCH");
	if($list === false)
	{
		if ($onle_lv_1)
			$sql = "select id, name_1 as name, pid, '' as py  from ".DB_PREFIX."goods_cate where pid = 0";
		else
			$sql = "select id, name_1 as name, pid, '' as py  from ".DB_PREFIX."goods_cate";
					
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$v)
		{
			$count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."goods_cate where pid = ".$v['id']));
			if($count>0)
			{
				$list[$k]['has_child'] = 1;
				$child = new child("goods_cate");
				$ids = $child->getChildIds($v['id'], $pk_str='id' , $pid_str ='pid');
				$ids[] = 0;
				$child_list = $GLOBALS['db']->getAll( "select id, name_1 as name, pid, '' as py  from ".DB_PREFIX."goods_cate where id in (".implode(",",$ids).")");
				foreach($child_list as $kk=>$vv)
				{
					$count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."goods_cate where pid = ".$vv['id']));
					if($count>0)
					$child_list[$kk]['has_child'] = 1;
					else
					$child_list[$kk]['has_child'] = 0;
				}
				
				$list[$k]['child'] = toTree($child_list,"id","pid","child");
			}
			else
			$list[$k]['has_child'] = 0;
		}
		$GLOBALS['cache']->set("CATELISTSEARCH",$list);
	}
	
	return $list;
}
	
//获取所有子集的类
class child
{
	public function __construct($tb_name)
	{
		$this->tb_name = $tb_name;	
	}
	private $tb_name;
	private $childIds;
	private function _getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid')
	{
		$childItem_arr = $GLOBALS['db']->getAll("select id from ".DB_PREFIX.$this->tb_name." where ".$pid_str."=".$pid);
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
	
function getCityArray($pid){
	/**
	 * id: 城市ID
	 * pid: 城市分类父ID
	 * name: 城市名称
	 * image: 城市图标
	 * has_childs: 是否还有子分类; 0:未，>1：有
	 */
	$sql = "select id, name, pid, py, '' as image, 0 as has_child from ".DB_PREFIX."group_city where pid = ".intval($pid);
	$list = $GLOBALS['db']->getAll($sql);
	$cityArray = array();
	foreach($list as $item)
	{
		$city = array();
		$city['id'] = $item[id];
		$city['pid'] = $item[pid];
		$city['name'] = $item[name];
		$city['image'] = $item[image];
		$city['has_child'] = $item[has_child];
		$city['py'] = $item[py];
			
		$cityArray[] = $city;;			
	}
	
	return $cityArray;	
	
}

function getGoodsArray($item){
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
	$goods = array();
	
	//商品折扣
	if ($item['market_price'] > 0)
		$rebate = number_format($item['shop_price']/$item['market_price'] * 10, 1);
	else
		$rebate = 0;
			
	$item_brief = $item['goodsbrief']==''?$item['goods_name']:$item['goodsbrief'];

	$goods['city_name'] = $item[city_name];
	$goods['goods_id']=$item[id];
	$goods['title']=emptyTag($item['goods_name']);
	$goods['image']= make_img(ROOT_PATH.$item['big_img'],0) ;	
	$goods['buy_count']=$item['buy_count'];
	$goods['start_date']=$item['promote_begin_time'];
	$goods['end_date']=$item['promote_end_time'];
	$goods['ori_price']=round($item['market_price'],2);
	$goods['cur_price']=round($item['shop_price'],2);
	$goods['goods_brief'] = $item['goods_brief'];
	$goods['ori_price_format']=a_formatPrice($goods['ori_price']);
	$goods['cur_price_format']=a_formatPrice($goods['cur_price']);

	$discount=number_format(($item['shop_price']/$item['market_price'])*10, 1);
	$goods['discount']=$discount;
	$goods['address']=$item['sp_address'];
			
	$goods['num_unit']= "";//$item['num_unit'];
	$goods['limit_num']=$item['max_bought'];
	$goods['goods_desc']= $item['goods_desc']; 
	
	$goods['sp_detail'] = $item['supplier_name']."<br />".$item['sp_address']."<br />".$item['sp_tel'];

	//$pattern = "/<img([^>]*)\/>/i";
	$pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/i";
	//$replacement = "<img width=300 $1 />";
	$replacement = "<img src='$1' width='300' />";
	
			
	$goods['goods_desc'] = preg_replace($pattern, $replacement, get_abs_img_root($goods['goods_desc']));

	
	$goods['saving_format']= a_formatPrice(round($item['market_price'] - $item['shop_price'],2));
	
	if($goods['end_date']==0)
	$goods['less_time'] = "none"; //永不过期，无倒计时
	else
	$goods['less_time'] = $goods['end_date'] - a_gmtTime();
			
   /*
		0:团购券，序列号+密码
        1:实体商品，需要配送
        2:线下订购商品
        3:实体商品,有配送,有团购券
	*/
	//has_delivery: 0;商品无配送; 1:商品有配送
	//has_mcod:1:商品支持，现金支付(货到付款); 0:不支持
	if ($item['type_id']== 1 or $item['type_id']== 3){
		$goods['has_delivery'] = 1;
		$goods['has_mcod'] = 1;
	}else{
		$goods['has_delivery'] = 0;
		$goods['has_mcod'] = 0;
	}
	
	if ($goods['cur_price'] > 0){
		$goods['has_cart']=1;//1:可以跟其它商品一起放入购物车购买；0：不能放入购物车，只能独立购买
	}else{
		$goods['has_cart']=0;
	}
		
	$goods['change_cart_request_server']=1;
	
	$goods['attr'] = getAttrArray($item[id]);

	$goods['has_attr']=0;//has_attr: 0:无属性; 1:有属性
	if (intval($item['goods_type']) > 0 && (intval($goods['attr']['has_attr_1']) == 1 || intval($goods['attr']['has_attr_2']) == 1)){
		$goods['has_attr']=1;
	};
		
	return $goods;
}
function get_abs_img_root($content)
{
	return str_ireplace("./Public/",a_getDomain().API_ROOT."/Public/",$content);
}

function get_abs_img_root2($content)
{
	//http://t.fanwe.com/t1/mapi/m
	return str_ireplace("./Public/",a_getDomain().API_ROOT."/mapi/m/public/",$content);
}

function getAttrArray($id){
	/**
	 * 
	 * selected_attr_1: 默认选择属性a中的值
	 * selected_attr_2: 默认选择属性b中的值
	 * 
	 * attr_id: 属性a 关键字 (注：可能会作为商品图片中的颜色选择，关联id。比如：选择红色时，就显示红色的商品图片)
	 * attr_name: 属性a 的显示名称如：红色、黄色等等
	 * attr_image: 属性a 的显示小图标
	 * 
	 * 
	 * 	价格: attr_price_{$attr_1_id}_{$attr_2_id}
	 *	积分：attr_score_{$attr_1_id}_{$attr_2_id}
	 *	购买限制数量：attr_limit_num_{$attr_1_id}_{$attr_2_id}
	 */	
	
	$attrArray =$GLOBALS['cache']->get("mobile_goods_attr_".$id);
	if($attrArray === false)
	{
	
	$sql = "select id,goods_type,max_bought,shop_price,score from ".DB_PREFIX."goods where id = ".intval($id);
	$goods = $GLOBALS['db']->getRow($sql);
	$attrArray = array();

	$attrArray['has_attr_1']=0; //0:无属性; 1:有属性
	$attrArray['has_attr_2']=0; //0:无属性; 1:有属性

	//只取前面2个属性  input_type:1:手工录入 0:列表中选择
	$sql = "select id,name_1 as name from ".DB_PREFIX."goods_type_attr where input_type = 0 and type_id = ". intval($goods['goods_type'])." order by id limit 2";
    
	$attrlist = $GLOBALS['db']->getAll($sql); //getAllCached

	for ($i = 1; $i <= count($attrlist); $i++){
		//$attrArray["has_attr_{$i}"]=1;//有商品属性
		$attrArray["attr_id_{$i}"]=$attrlist[$i - 1]['id']; //商品属性名称如：颜色,尺码 的关键字
		$attrArray["attr_title_{$i}"]=$attrlist[$i - 1]['name']; //商品属性名称如：颜色,尺码			
		$attrArray["selected_attr_{$i}"] = 0; //默认选择的属性值id

		//商品属性值：如红色，黄色等等
		$attr_Array = array();
		$sql = "select id,attr_id, attr_value_1,price,stock from ".DB_PREFIX."goods_attr where attr_id = ".intval($attrlist[$i - 1]['id'])." and goods_id = ".intval($id);
		//echo $sql."<br>";
		$attr_list = $GLOBALS['db']->getAll($sql);
		foreach($attr_list as $value){
			$attr_value = array();
			$attr_value['attr_id'] = $value['id'];//属性值id
			$attr_value['attr_name'] = $value['attr_value_1']; //属性值名称如：红色，黄色
			$attr_value['attr_image'] = '';//属性值,对应图片

			$attr_value['attr_price'] = floatval($value['price']);//只对下面计算时有效,不作标准返回值
			$attr_value['attr_price_format'] = a_formatPrice(floatval($value['price']));
			$attr_Array[] = $attr_value;	
		}
		
		if (count($attr_list) >= 1){
			$attrArray["has_attr_{$i}"]=1;//有商品属性,只有真正有属性时,才赋值：has_attr_1 = 1
		}
		
		$attrArray["attr_{$i}"]=$attr_Array;
	}
	

	//价格: attr_price_{$attr_1_id}_{$attr_2_id}
	//积分：attr_score_{$attr_1_id}_{$attr_2_id}
	//库存：attr_limit_num_{$attr_1_id}_{$attr_2_id}

	$attr_1_2_value = array();
	if ($attrArray['has_attr_1'] == 1){
		for ($i = 1; $i <= count($attrArray['attr_1']); $i++){
			if ($attrArray['has_attr_2'] == 1){
				for ($j = 1; $j <= count($attrArray['attr_2']); $j++){					
					$attr_1_2_value["attr_price_".$attrArray['attr_1'][$i-1]['attr_id']."_".$attrArray['attr_2'][$j-1]['attr_id']] = $goods['shop_price'] + $attrArray['attr_1'][$i-1]['attr_price'] + $attrArray['attr_2'][$j-1]['attr_price'];
					$attr_1_2_value["attr_score_".$attrArray['attr_1'][$i-1]['attr_id']."_".$attrArray['attr_2'][$j-1]['attr_id']] = $goods['score'];
					$attr_1_2_value["attr_limit_num_".$attrArray['attr_1'][$i-1]['attr_id']."_".$attrArray['attr_2'][$j-1]['attr_id']] = $goods['max_bought'];
				}
			}else{
				$attr_1_2_value["attr_price_".$attrArray['attr_1'][$i-1]['attr_id']."_0"] = $goods['shop_price'] + $attrArray['attr_1'][$i-1]['attr_price'];
				$attr_1_2_value["attr_score_".$attrArray['attr_1'][$i-1]['attr_id']."_0"] = $goods['score'];
				$attr_1_2_value["attr_limit_num_".$attrArray['attr_1'][$i-1]['attr_id']."_0"] = $goods['max_bought'];
			}
		}
	}

	$attrArray['attr_1_2']= $attr_1_2_value;
	$GLOBALS['cache']->set("mobile_goods_attr_".$id,$attrArray);
	}

	return	$attrArray;
}

//调用前,要先执行insertCartData,购物车
//返回：0:无需配送;-1:无法将商品配送到该地区; >0:默认选择的：配送方式ID
function getDeliveryId($user_id,$session_id,$delivery_region,$order_id,$def_delivery_id){
	
	$delivery_id = intval($def_delivery_id);
	
	/*
	0:团购券，序列号+密码
	1:实体商品，需要配送
	2:线下订购商品
	3:实体商品,有配送,有团购券
	*/

	$order_id = intval($order_id);
	if ($order_id > 0){
		$sql = "select count(*) from ".DB_PREFIX."order_goods a left outer join ".DB_PREFIX."goods b on b.id = a.rec_id where (b.type_id = 1 or b.type_id =3 ) and a.user_id = '".$user_id."' and a.order_id = '".$order_id."'";
	}else{
		$sql = "select count(*) from ".DB_PREFIX."cart where (goods_type = 1 or goods_type =3 ) and user_id = '".$user_id."' and session_id = '".$session_id."'";
	}
	
	if (intval($GLOBALS['db']->getOne($sql)) > 0){
		//根据用户选择的地区,自动分配一个快递方式给用户(有默认的,则取默认的)
		if ($delivery_region['region_lv1'] > 0){
			$region_id = $delivery_region['region_lv1'];
		}
		if ($delivery_region['region_lv2'] > 0){
			$region_id = $delivery_region['region_lv2'];
		}
		if ($delivery_region['region_lv3'] > 0){
			$region_id = $delivery_region['region_lv3'];
		}
		if ($delivery_region['region_lv4'] > 0){
			$region_id = $delivery_region['region_lv4'];
		}
		
		//读取配送方式,可以是禁用状态下的
		//return $region_id;
		$delivery_ids = loadDelivery_3(intval($region_id));
		//return $delivery_ids;
		if (!in_array($delivery_id,$delivery_ids)){
			//is_smzq 上门自取,免费运费,不填地址
			//过滤掉上门自取的,再从$delivery_ids中，取一个,作为默认的配送方式
			//$root['info'] = "无法将商品配送到该地区.";
			$delivery_id = -1;
			foreach($delivery_ids as $k=>$v)
			{
				$sql = "select id from ".DB_PREFIX."delivery where is_smzq = 0 and id = ".intval($v['id']);
				$id = intval($GLOBALS['db']->getOne($sql));
				if ($id > 0){
					$delivery_id = $id;
					break;
				}
			}
		}
	}else{
		//商品无需配送
		$delivery_id = 0;
	}
	
	return $delivery_id;
}

function insertCartData($user_id,$session_id,$cartdata,$group_id){

	$user_id = intval($user_id);
	//清空购买车
	$sql = "delete from ".DB_PREFIX."cart where session_id='".$session_id."'";
	if ($user_id > 0){
		$sql .= " or user_id = ".$user_id;
	}
	$GLOBALS['db']->query($sql);
	
	//会员折扣
	$sql = "select discount from ".DB_PREFIX."user_group where id='".intval($group_id)."'";
	$discount =	floatval($GLOBALS['db']->getOne($sql));
	if ($discount == 0){
		$discount = 1;
	}
	
	$new_cartdata = array();
	
	$now = a_gmtTime();
	$count = count($cartdata);
	for ($i = 0; $i < $count; $i++) {
		$cart = $cartdata[$i];
	
		$goods_id = $cart['goods_id'];
    	//$sql = "select * from ".DB_PREFIX."goods where id='".$goods_id."'";
    	$goods_info = getGoodsItem($goods_id);

    	//$cart['status'] = 1;//商品可以正常购买
    	if(!$goods_info || ($goods_info['promote_begin_time'] > $now && $goods_info['type_id'] != 2)){
    		//$cart['status'] = 0;//商品不能再购买
    		//$cart['info'] = '商品已过期,或不支持手机版本购买.';
    	}
    	
		$number = $cart['num'];
		//$unit_price = $cart['price'];//注：商品价格，不是从客户端上传过来的,而是在后台计算的
		$score = $goods_info['score'];//$cart['score'];
		
		$name = $goods_info['goods_short_name'];
		if (strlen($name) == 0){
			$name = $goods_info['name_1'];
		}	
		
		$sn = $goods_info['sn'];
		$weight = $goods_info['weight'];
		$is_inquiry = $goods_info['is_inquiry'];
		$type_id = $goods_info['type_id'];

		
		if($goods_info['type_id'] == 2)
		$unit_price = floatval($goods_info['earnest_money']);
		else
		$unit_price = floatval($goods_info['shop_price']);
		
		$attrStr = '';
		$attr_ids = '';
		
		$attr_id_a = $cart['attr_id_a'];
		//$attr_value_a = $cart['attr_value_a'];
		$attr_id_b = $cart['attr_id_b'];
		//$attr_value_b = $cart['attr_value_b'];
				
		//print_r($cart);
		$goods_attr = array();
		if ($attr_id_a <> '')
			$goods_attr[] = $attr_id_a;
		if ($attr_id_b <> '')
			$goods_attr[] = $attr_id_b;
		
		//print_r($goods_attr); exit;
		
		if(is_array($goods_attr) && count($goods_attr)>0)
		{
			foreach($goods_attr as $attr)
			{
				$sql ="select ga.attr_value_1 as attr_value,ga.price,gta.name_1 as name from ".DB_PREFIX."goods_attr as ga left join ".DB_PREFIX."goods_type_attr as gta on gta.id = ga.attr_id where ga.id = ".intval($attr)." and ga.goods_id = ".$goods_info['id'];
		
				$attrItem = $GLOBALS['db']->getRow($sql);
		
				$unit_price += floatval($attrItem['price']);
		
				if(empty($attrStr))
				$attrStr.=$attrItem['name']."：".$attrItem['attr_value'];
				else
				$attrStr.= "\n".$attrItem['name']."：".$attrItem['attr_value'];
			}

			$attr_ids = implode(",",$goods_attr);
		}		
		
		//$unit_price 再按会员等级打折
		$unit_price = $unit_price * $discount;
		

		
		
		//插入购买车
		$sql = "insert into ".DB_PREFIX."cart (`id`,`pid`,`rec_id`,`rec_module`,`session_id`,`user_id`,`number`,`data_unit_price`,`data_score`,`data_promote_score`,`data_total_score`,`data_total_price`,`create_time`,`update_time`,`data_name`,`data_sn`,`data_weight`,`data_total_weight`,`is_inquiry`,`goods_type`,`attr`,`attr_ids`)".
						   " values (0,0,'".$goods_id."','PromoteGoods','".$session_id."','".$user_id."','".$number."','".floatval($unit_price)."','".$score."',0,'".($score*$number)."','".($unit_price*$number)."','".$now."','".$now."','".addslashes($name)."','".$sn."','".$weight."','".(weight*$number)."','".$is_inquiry."','".$type_id."','".$attrStr."','".$attr_ids."')";
		$GLOBALS['db']->query($sql);
		
		$new_cart = array();
		$new_cart['cart_id'] = $cart['cart_id'];
		$new_cart['new_price'] = $unit_price;
				
		$new_cartdata[] = $new_cart;
		
	}
	
	return $new_cartdata;
}

//检查用户，密码是否正确
function check_user($email,$pwd,$exit = false){
/**
email: 用户名
pwd: 密码
$exit: true用户密码，不对，则直接退出; false则返回: false
*/
	//$email = addslashes(trim($requestData['email']));//用户名或邮箱
	//$pwd = md5(trim($requestData['pwd']));//密码
		$sql = "select * from ".DB_PREFIX."user where (email = '$email' or user_name = '$email') and user_pwd='{$pwd}'";
        $user = $GLOBALS['db']->getRow($sql);
        $root = array();
      if ($user){
        $root['return'] = 1;
		$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
		$root['info'] = "用户登陆成功";		
		$root['user_id'] = $user['id'];
		$root['user_name'] = $user['user_name'];
		$root['user_email'] = $user['email'];
		$root['user_money'] = $user['money'];
		$root['user_money_format'] = a_formatPrice($user['money']);//用户金额
		$root['user'] = $user;//返回用户数据
		
		$_SESSION ['user_name'] = $user['user_name'];
		$_SESSION ['user_id'] = $user ['id'];
		$_SESSION ['group_id'] = $user ['group_id'];
		$_SESSION ['user_email'] = $user ['email'];
		$_SESSION ['score'] = $user ['score'];	
	}else{
		$pwd = $GLOBALS['requestData'][pwd];
                $SECRET_KEY = '@4!@#$%@';
		 if (strlen($pwd) != 32){
		$pwd = md5($pwd.$SECRET_KEY);
		}
		$sql1 = "select * from ".DB_PREFIX."user where (email = '$email' or user_name = '$email') and user_pwd='{$pwd}'";
                $user1 = $GLOBALS['db']->getRow($sql1);
		if ($user1){
		$root['return'] = 1;
		$root['user_login_status'] = 1;//用户登陆状态：1:成功登陆;0：未成功登陆
		$root['info'] = "用户登陆成功";		
		$root['user_id'] = $user1['id'];
		$root['user_name'] = $user1['user_name'];
		$root['user_email'] = $user1['email'];
		$root['user_money'] = $user1['money'];
		$root['user_money_format'] = a_formatPrice($user1['money']);//用户金额
		$root['user'] = $user1;//返回用户数据
		
		$_SESSION ['user_name'] = $user1['user_name'];
		$_SESSION ['user_id'] = $user1 ['id'];
		$_SESSION ['group_id'] = $user1 ['group_id'];
		$_SESSION ['user_email'] = $user1 ['email'];
		$_SESSION ['score'] = $user1 ['score'];	
	}
        else{
		$root['return'] = 0;
		$root['user_login_status'] = 0;//用户登陆状态：1:成功登陆;0：未成功登陆
		$root['info'] = "帐户或密码错误";		
		$root['user_id'] = 0;
		$root['user_name'] = $email;
		$root['user_email'] = $email;
		if ($exit)
	  		dispay($root);				
	}
        }
        
	return $root;
}

function getUserAddr($user_id,$all){
	$sql = "select a.*, r1.name as r1_name, r2.name as r2_name, r3.name as r3_name, r4.name as r4_name from ".DB_PREFIX."user_consignee a ".
			   "left outer join ".DB_PREFIX."region_conf as r1 on r1.id = a.region_lv1 ".
				"left outer join ".DB_PREFIX."region_conf as r2 on r2.id = a.region_lv2 ".
				"left outer join ".DB_PREFIX."region_conf as r3 on r3.id = a.region_lv3 ".
				"left outer join ".DB_PREFIX."region_conf as r4 on r4.id = a.region_lv4 ".
			   "where a.user_id = ".intval($user_id);
	if ($all){
		$list = $GLOBALS['db']->getAll($sql);
		$addr_list = array();
		foreach($list as $item)
		{
			$addr_list[] = getUserAddrItem($item);
		}	
		return $addr_list;
	}else{
		$sql .= " limit 1";
		$addr = $GLOBALS['db']->getRow($sql);
		return getUserAddrItem($addr);
	}
}

function getUserAddrItem($item){
	$addr = array();
	$addr['id'] = $item['id'];//联系人姓名
	$addr['consignee'] = $item['consignee'];//联系人姓名
	
	//不显示国家
	$addr['delivery'] = $item['r1_name'].$item['r2_name'].$item['r3_name'].$item['r4_name'];
	
	$addr['region_lv1'] = $item['region_lv1'];//国家
	$addr['region_lv2'] = $item['region_lv2'];//省
	$addr['region_lv3'] = $item['region_lv3'];//城市
	$addr['region_lv4'] = $item['region_lv4'];//地区/县
	
	$addr['delivery_detail'] = $item['address'];//详细地址
	$addr['phone'] = $item['mobile_phone'];//手机号码
	$addr['postcode'] = $item['zip'];//邮编
	
	return $addr;	
}

function getOrderItem2($item){
	
	$order = array();
	
	$num = 0;
	$money_status = intval($item['money_status']);
	$goods_status = intval($item['goods_status']);
	
	$order['id'] = $item['id'];//订单ID
	$order['sn'] = $item['sn'];//订单sn
	$order['create_time'] = $item['create_time']; //下单时间
	$order['create_time_format'] = a_toDate($item['create_time']);//下单时间格式化后
	$order['total_money'] = $item['total_money'];//订单总金额
	$order['money'] = $item['money'];//还应付金额
	$order['total_money_format'] = a_formatPrice($item['total_money']);//订单总金额格式化后
	$order['money_format'] = a_formatPrice($item['money']);//还应付金额格式化后
	$order['status'] = a_L("ORDER_MONEY_STATUS_".$money_status).".".a_L("ORDER_GOODS_STATUS_".$goods_status);//订单状态
	


	$order['order_total_price'] = $item['order_total_price'];
	$order['discount'] = $item['discount'];
	$order['total_price'] = $item['total_price'];
	$order['delivery_fee'] = $item['delivery_fee'];
	$order['protect_fee'] = $item['protect_fee'];
	$order['payment_fee'] = $item['payment_fee'];
	$order['tax_money'] = $item['tax_money'];
	$order['ecv_money'] = $item['ecv_money'];
	$order['order_incharge'] = $item['order_incharge'];

	$order['order_all_price_format'] = a_formatPrice($item['order_total_price'] - $item['discount'] );//费用总计
	$order['total_price_format'] = a_formatPrice( $item['total_price'] );//+商品总价
	$order['delivery_fee_format'] = a_formatPrice( $item ['delivery_fee'] );//+快递费用
	$order['protect_fee_format'] = a_formatPrice( $item ['protect_fee'] );//+快递保费
	$order['payment_fee_format'] = a_formatPrice( $item ['payment_fee'] );//+支付手续费
	$order['tax_money_format'] = a_formatPrice( $item ['tax_money'] );//+发票费用
	$order['discount_price_format'] = a_formatPrice( $item ['discount'] );//-优惠金额
	$order['order_total_price_format'] = a_formatPrice( $item ['order_total_price']);//=应付款金额
	$order['ecv_money_format'] = a_formatPrice( $item ['ecv_money'] );//-代金券金额
	$order['order_incharge_format'] = a_formatPrice($item ['order_incharge'] - $item ['ecv_money']);//-已收金额
	$order['total_price_pay_format'] = a_formatPrice ( $item ['order_total_price'] - $item ['order_incharge'] );//待付金额
	
	//$order['order_all_price'] = $order_info ['order_total_price'] + $order_info ['discount'];
	
	//$order_info ['promote_money_format'] = a_formatPrice ( $order_info ['promote_money'] ); 促销金额
	//$order_info ['order_incharge_format'] = a_formatPrice ( $order_info ['order_incharge'] - $order_info ['ecv_money'] );
		
	if ($money_status == 0 && ($goods_status == 0 || $goods_status == 5)){
		$order['has_cancel'] = 1;//0:不允许取消订单;1:允许取消订单
	}else{
		$order['has_cancel'] = 0;//0:不允许取消订单;1:允许取消订单
	}
	
	//调用：http://api.kuaidi100.com/api?id=您的授权KEY&com=快递公司代码&nu=快递单号&show=结果显示方式&muti=是否显示全部记录 接口
	//参数资料查看：http://code.google.com/p/kuaidi-api/wiki/Open_API_API_URL
	$order['kd_com'] = "";//快递公司代码
	$order['kd_sn'] = "";//快递单号
	if ($goods_status == 2){
		$sql = "select a.id, a.delivery_code,b.code from ".DB_PREFIX."order_consignment a left outer join ".DB_PREFIX."express b on b.id = a.express_id where a.order_id = {$item['id']} order by a.id desc";
		$express = $GLOBALS['db']->getRow($sql);
		if ($express && !empty($express['delivery_code']) && !empty($express['code'])){
			$order['kd_com'] = $express['code'];//快递公司代码 参数资料查看：http://code.google.com/p/kuaidi-api/wiki/Open_API_API_URL
			$order['kd_sn'] = $express['delivery_code'];//快递单跟踪号
		}
	}
	
	if ($money_status == 2){
		$order['has_pay'] = 0;//1:允许继续支付;0:不允许
		
		$order['has_edit_delivery'] = 0;//1:允许编辑配置地址;0:不允许编辑配置地址
		$order['has_edit_delivery_time'] = 0;//1:允许编辑配送时间;0:不允许编辑配送时间
		$order['has_edit_invoice'] = 0;//1:允许编辑发票;0:不允许编辑发票
		$order['has_edit_ecv'] = 0;//1:允许编辑优惠券;0:不允许编辑优惠券
		$order['has_edit_message'] = 0;//1:允许编辑订单留言;0:不允许编辑订单留言
		$order['has_edit_moblie'] = 0;//1:允许编辑手机号码;0:不允许编辑手机号码		
	}else{
		$order['has_pay'] = 1;//1:允许继续支付;0:不允许
		$order['has_edit_delivery'] = 1;//1:允许编辑配置地址;0:不允许编辑配置地址
		$order['has_edit_delivery_time'] = 1;//1:允许编辑配送时间;0:不允许编辑配送时间
		$order['has_edit_invoice'] = 1;//1:允许编辑发票;0:不允许编辑发票
		$order['has_edit_ecv'] = 0;//1:允许编辑优惠券;0:不允许编辑优惠券
		$order['has_edit_message'] = 1;//1:允许编辑订单留言;0:不允许编辑订单留言
		$order['has_edit_moblie'] = 1;//1:允许编辑手机号码;0:不允许编辑手机号码		
		/*
		//code: malipay,支付宝;mtenpay,财付通;mcod,货到付款
		$sql = "select a.class_name from ".DB_PREFIX."payment a where a.id = {$item['payment']}";
		$pay_name = strtolower($GLOBALS['db']->getOne($sql));
		if ($pay_name == 'malipay' or $pay_name == 'mtenpay'){
			$order['has_pay'] = 1;//1:允许继续支付;0:不允许
		}else{
			$order['has_pay'] = 0;//1:允许继续支付;0:不允许
		}
		
		//货到付款
		if ($pay_name == 'mcod'){
			if ($goods_status == 0){
				$order['has_pay'] = 1;//1:允许继续支付;0:不允许
			}else{
				$order['has_pay'] = 0;//1:允许继续支付;0:不允许
			}
		}
		
		if ($order['has_pay'] == 1){
			$order['has_edit_delivery'] = 1;//1:允许编辑配置地址;0:不允许编辑配置地址
			$order['has_edit_delivery_time'] = 1;//1:允许编辑配送时间;0:不允许编辑配送时间
			$order['has_edit_invoice'] = 1;//1:允许编辑发票;0:不允许编辑发票
			$order['has_edit_ecv'] = 0;//1:允许编辑优惠券;0:不允许编辑优惠券
			$order['has_edit_message'] = 1;//1:允许编辑订单留言;0:不允许编辑订单留言
			$order['has_edit_moblie'] = 1;//1:允许编辑手机号码;0:不允许编辑手机号码			
		}else{
			$order['has_edit_delivery'] = 0;//1:允许编辑配置地址;0:不允许编辑配置地址
			$order['has_edit_delivery_time'] = 0;//1:允许编辑配送时间;0:不允许编辑配送时间
			$order['has_edit_invoice'] = 0;//1:允许编辑发票;0:不允许编辑发票
			$order['has_edit_ecv'] = 0;//1:允许编辑优惠券;0:不允许编辑优惠券
			$order['has_edit_message'] = 0;//1:允许编辑订单留言;0:不允许编辑订单留言
			$order['has_edit_moblie'] = 0;//1:允许编辑手机号码;0:不允许编辑手机号码			
		}
		*/
	}
	
	
	
	$sql = "select a.id, a.rec_id as goods_id, a.data_name,a.attr,a.number,a.data_price,a.data_total_price,b.small_img as image,b.type_id from ".DB_PREFIX."order_goods a left outer join ".DB_PREFIX."goods b on b.id = a.rec_id where a.order_id = ".intval($item['id']);
	$goodslist = $GLOBALS['db']->getAll($sql);
	foreach($goodslist as $item2){
		$goods = array();
		$goods['id'] = $item2['id'];//订单明细ID
		$goods['goods_id'] = $item2['goods_id'];//商品ID
		$goods['name'] = $item2['data_name'];//商品名称
		$goods['num'] = $item2['number'];//商品数量
		$goods['price'] = $item2['data_price'];//商品单价
		$goods['total_money'] = $item2['data_total_price'];//商品总价
		$goods['price_format'] = a_formatPrice($item2['data_price']);//商品单价格式化后
		$goods['total_money_format'] = a_formatPrice($item2['data_total_price']);//商品总价格式化后
		$goods['image'] = a_getDomain().API_ROOT.$item2['image'];//商品图片
		$goods['attr_content'] = str_replace(array(chr(13),chr(10)),array("",";"),$item2['attr']);//商品属性描述
				
		$num = $num + intval($goods['num']);
		$order['orderGoods'][]= $goods;
	}
	
	$order['num'] = $num;//订单商品数量	
	
	return $order;
}

function getFeeItem($cart_total){
	$feeinfo[] = array("item"=>a_L('XY_TOTAL_PRICES'),"value"=>$cart_total['all_fee_format']);
	
	
	if ($cart_total['total_add_score'] <> 0){
		$feeinfo[] = array("item"=>a_L('SCORE_UNIT'),"value"=>$cart_total['total_add_score']);
	}
	
	if ($cart_total['goods_total_price'] > 0){
		$feeinfo[] = array("item"=>a_L('XY_TOTAL_G_PRICES'),"value"=>$cart_total['goods_total_price_format']);
	}
	
	if ($cart_total['delivery_fee'] <> 0){
		$feeinfo[] = array("item"=>a_L('DELIVERY_FEE'),"value"=>$cart_total['delivery_fee_format']);
	}
	
	if ($cart_total['protect_fee'] <> 0){
		$feeinfo[] = array("item"=>a_L('PROTECT_FEE'),"value"=>$cart_total['protect_fee_format']);
	}
	
	if ($cart_total['tax'] <> 0){
		$feeinfo[] = array("item"=>a_L('TAX_MONEY'),"value"=>$cart_total['tax_money_format']);
	}
	
	if ($cart_total['payment_fee'] <> 0){
		//$feeinfo[] = array("item"=>a_L('PAY_AMOUNT'),"value"=>$cart_total['payment_fee_format']);
		if ($cart_total['payment_fee'] < 0){
			$feeinfo[] = array("item"=>'优惠金额',"value"=>$cart_total['payment_fee_format']);
		}else{
			$feeinfo[] = array("item"=>a_L('PAYMENT_FEE'),"value"=>$cart_total['payment_fee_format']);
		}	
	}
	
	if ($cart_total['discount_price'] <> 0){
		$feeinfo[] = array("item"=>a_L('XY_RANK_DISCOUNT'),"value"=>$cart_total['discount_price_format']);
	}
	
	if ($cart_total['credit'] <> 0){
		$feeinfo[] = array("item"=>a_L('XY_BALANCE_PAY'),"value"=>$cart_total['credit_format']);
	}
	
	if ($cart_total['ecvFee'] <> 0){
		$feeinfo[] = array("item"=>a_L('XY_VOUCHER'),"value"=>$cart_total['ecvFee_format']);
	}
	
	if ($cart_total['incharge'] <> 0){
		$feeinfo[] = array("item"=>a_L('PAID_AMOUNT'),"value"=>$cart_total['incharge_format']);
	}
	
	$feeinfo[] = array("item"=>a_L('XY_MUSE_TOTAL_PAY'),"value"=>$cart_total['total_price_format']);	
	
	return $feeinfo;
}

function &init_users3_2() {
	$set_modules = false;
	static $cls = null;
	if ($cls != null) {
		return $cls;
	}
	$code = a_fanweC('INTEGRATE_CODE');
	if (empty($code))
	$code = 'fanwe';
	$code = $code . '3';
	include_once (VENDOR_PATH . 'integrates3/' . $code . '.php');
	$cfg = unserialize(a_fanweC('INTEGRATE_CONFIG'));
	$cls = new $code($cfg);

	return $cls;
}

function getMConfig(){
	
	$m_config = $GLOBALS['cache']->get("m_config");
	if($m_config===false)
	{	
		init_config_data();//检查初始化数据
		
		$m_config = array();
		$sql = "select code,val from ".DB_PREFIX."m_config";
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $item){
			$m_config[$item['code']] = $item['val'];		
		}
				
		//支付列表
		$sql = "select pay_id as id, code, title as name, has_calc from ".DB_PREFIX."m_config_list where `group` = 1 and is_verify = 1";
		$list = $GLOBALS['db']->getAll($sql);
		$payment_list = array();
		foreach($list as $item){
			$payment_list[] = array("id"=>$item['id'],"code"=>$item['code'],"name"=>$item['name'],"has_calc"=>$item['has_calc']);
		}
		$m_config['payment_list'] = $payment_list;
		
		//配送方式
		$sql = "select * from ".DB_PREFIX."delivery where status = 1";
		$list = $GLOBALS['db']->getAll($sql);
		$delivery_list = array();
		foreach($list as $item){
			$delivery_list[] = array("id"=>$item['id'],"code"=>$item['id'],"name"=>$item['name_1'],"has_calc"=>1);
		}
		$m_config['delivery_list'] = $delivery_list;
		
		//配送日期选择
		$sql = "select code, title as name from ".DB_PREFIX."m_config_list where `group` = 2 and is_verify = 1";
		$list = $GLOBALS['db']->getAll($sql);
		$delivery_time_list = array();
		foreach($list as $item){
			$delivery_time_list[] = array("id"=>$item['code'],"name"=>$item['name']);
		}
		$m_config['delivery_time_list'] = $delivery_time_list;	
		
		
		
		//购物车信息提示
		$sql = "select code as name,money from ".DB_PREFIX."m_config_list where `group` = 3 and is_verify = 1";
		$list = $GLOBALS['db']->getAll($sql);
		$yh = array();
		foreach($list as $item){
			$yh[] = array("info"=>$item['name'],"money"=>0);
		}
		$m_config['yh'] = $yh;	
		
		
		//新闻公告
		$sql = "select code as title, title as content from ".DB_PREFIX."m_config_list where `group` = 4 and is_verify = 1";
		$list = $GLOBALS['db']->getAll($sql);
		$newslist = array();
		foreach($list as $item){
			$newslist[] = array("title"=>$item['title'],"content"=>$item['content']);
		}
		$m_config['newslist'] = $newslist;	
		
		$GLOBALS['cache']->set("m_config",$m_config);
	}
	return $m_config;	
}

function init_config_data(){
	
	$install_lock = APP_ROOT_PATH."mapi/m/public/install.lock";
	if(!file_exists($install_lock)){
		$file = APP_ROOT_PATH."mapi/m/public/db_back/fanwe_m.sql";
		$sql = "";
		$sql = file_get_contents($file);
		$sql = remove_comment($sql);
		$sql = trim($sql);
		
		$sql = str_replace("\r", '', $sql);
		$segmentSql = explode(";\n", $sql);
		foreach($segmentSql as $k=>$itemSql)
		{
			$itemSql = str_replace("%DB_PREFIX%",DB_PREFIX,$itemSql);			
			$GLOBALS['db']->query($itemSql);
		}
		@file_put_contents($install_lock,"");
	};
}

/**
* 过滤SQL查询串中的注释。该方法只过滤SQL文件中独占一行或一块的那些注释。
*
* @access  public
* @param   string      $sql        SQL查询串
* @return  string      返回已过滤掉注释的SQL查询串。
*/
function remove_comment($sql)
{
	/* 删除SQL行注释，行注释不匹配换行符 */
	$sql = preg_replace('/^\s*(?:--|#).*/m', '', $sql);

	/* 删除SQL块注释，匹配换行符，且为非贪婪匹配 */
	//$sql = preg_replace('/^\s*\/\*(?:.|\n)*\*\//m', '', $sql);
	$sql = preg_replace('/^\s*\/\*.*?\*\//ms', '', $sql);

	return $sql;
}


function cart_done_3($goods_id = 0,$return_array = false){
	if (file_exists(APP_ROOT_PATH.'app/source/func/com_order_done_func.php')){
		require_once APP_ROOT_PATH.'app/source/func/com_order_done_func.php';
	}else{
		require_once APP_ROOT_PATH.'mapi/com_order_done_func.php';
	}	
	return cart_done_2($goods_id,$return_array);
}

function order_done_3($return_array = false){
	if (file_exists(APP_ROOT_PATH.'app/source/func/com_order_done_func.php')){
		require_once APP_ROOT_PATH.'app/source/func/com_order_done_func.php';
	}else{
		require_once APP_ROOT_PATH.'mapi/com_order_done_func.php';
	}	
	return order_done_2($return_array);
}

function loadDelivery_3($id){
	if (file_exists(APP_ROOT_PATH.'app/source/func/com_order_done_func.php')){
		require_once APP_ROOT_PATH.'app/source/func/com_order_done_func.php';
	}else{
		require_once APP_ROOT_PATH.'mapi/com_order_done_func.php';
	}
	return loadDelivery_2($id,false);
}

function check_ecvverify_3($id){
	if (file_exists(APP_ROOT_PATH.'app/source/func/com_order_done_func.php')){
		require_once APP_ROOT_PATH.'app/source/func/com_order_done_func.php';
	}else{
		require_once APP_ROOT_PATH.'mapi/com_order_done_func.php';
	}
	return check_ecvverify_2($id,false);
}


//初始化下单时的订单参数,要根据每个订单重新调整
function init_order_parm($MConfig){
	$order_parm = array();
	
	//===============以下的4个参数要根据每个订单的实际情况来重新调整
	$order_parm['has_delivery_time'] = intval($MConfig['has_delivery_time']);//有配送日期选择
	$order_parm['has_delivery'] = 0;//订单是否要配送;1:需要, 0:不需要
	$order_parm['has_moblie'] = 0;//intval($MConfig['has_moblie']);//有手机号码
	$order_parm['has_mcod'] = 0;//订单是否允许现金支付,1:允许，0:不允许
	//=========================
	
	$order_parm['has_ecv'] = intval($MConfig['has_ecv']);//有优惠券	
	$order_parm['has_invoice'] = intval($MConfig['has_invoice']);//有发票
	$order_parm['has_message'] = intval($MConfig['has_message']);//有留言框	
	
	
	$order_parm['select_payment_id'] = $MConfig['select_payment_id'];//默认支付方式
	$order_parm['select_delivery_time_id'] = $MConfig['select_delivery_time_id'];//默认配送日期
	
	/**支付方式列表
	 * id: 键值
	* name: 名称
	* code: malipay,支付宝;mtenpay,财付通;mcod,货到付款
	* has_calc: 选择该支付方式，需要重新返回服务器，计算购物车价格; 0:不需要，1:需要
	
	$payment_list = array();
	$payment_list[] = array("id"=>19,"code"=>"malipay","name"=>"支付宝","has_calc"=>0);
	//$payment_list[] = array("id"=>2,"code"=>"mtenpay","name"=>"财付通","has_calc"=>0);
	$payment_list[] = array("id"=>20,"code"=>"mcod","name"=>"现金支付","has_calc"=>0);
	*/
	$order_parm['payment_list'] = $MConfig['payment_list'];
	
	/**配送日期选择
	 * id: 键值
	* name: 名称
	
	$delivery_time_list = array();
	$delivery_time_list[] = array("id"=>1,"name"=>"周末");
	$delivery_time_list[] = array("id"=>2,"name"=>"都可以");
	*/
	$order_parm['delivery_time_list'] = $MConfig['delivery_time_list'];	
	$order_parm['delivery_list'] = $MConfig['delivery_list'];
	$order_parm['invoice_list'] = $MConfig['invoice_list'];	
	return $order_parm;
}
	

//services/index.php 的函数库
function check_order_goods($order_id,$user_id,&$error){

	//$sql = "select rec_module,rec_id,number,data_name,data_sn,data_score,data_total_score,data_unit_price,data_total_price,data_promote_score,data_total_score,data_score,attr,is_inquiry,data_weight from ".DB_PREFIX."cart where session_id = '".$session_id."' and user_id=".$user_id;
	//$sql = "select * from ".DB_PREFIX."cart where session_id = '".$session_id."' and user_id=".$user_id;
	
	
	$sql = "select a.rec_module,a.rec_id,a.number from ".DB_PREFIX."order_goods as a where a.order_id = '".$order_id."'";
	$list = $GLOBALS['db']->getAll($sql);

	if ($list){
		foreach($list as $cart_item)
		{
			if ($cart_item['rec_module'] == 'PromoteGoods' || $cart_item['rec_module'] == 'Goods'){
				//

				$goods_info = getGoodsItem($cart_item['rec_id']);
	   	
				if ($goods_info){
					
					$error = $goods_info['name_1'].":";
					if ($goods_info['score_goods'] != 0 || $goods_info['type_id'] == 2){
						$error .= '手机版本不支持,购物此类商品.';
						return false;
					}
					
					$bln = false;
					$err = "";
					$number = intval($cart_item['number']);
						
					if ($goods_info['promote_end_time'] < a_gmtTime() || $goods_info['is_group_fail'] == 1 || ($goods_info['stock'] > 0 && $goods_info['buy_count'] + $number > $goods_info['stock']))
					{
						if($goods_info['promote_end_time'] < a_gmtTime()|| $goods_info['is_group_fail'] == 1)
						{
							//$this->assign("jumpUrl",u("Goods/show",array("id"=>$cart_item['rec_id'])));
							//$this->error("团购已结束");
							$error .= $GLOBALS['Ln']['XY_GROUP_IS_END'];
							return false;
						}
						if($goods_info['stock'] > 0 && $goods_info['buy_count']+$cart_item['number'] > $goods_info['stock'])
						{
							//$this->assign("jumpUrl",u("Goods/show",array("id"=>$cart_item['rec_id'])));
							//$this->error("已售光");
							$error .= $GLOBALS['Ln']['XY_B_SORRY_SOLD_OUT'];
							return false;
						}
					}
						
					//modify chenfq by 2011-03-01 不统计作废订单数量
					//$sql = "select sum(number) as num from ".DB_PREFIX."order_goods where rec_id = ".intval($cart_item['rec_id'])." and user_id=".intval($_SESSION['user_id']);
					$sql = "select sum(og.number) as num from ".DB_PREFIX."order_goods as og "
					." left outer join ".DB_PREFIX."order o on o.id = og.order_id "
					."where o.status <> 2 and og.rec_id = ".intval($cart_item['rec_id'])." and og.order_id <> ".intval($order_id)." and og.user_id=".intval($user_id);
					$userBuyCount = intval($GLOBALS['db']->getOne($sql));

					$maxBought    = intval($goods_info['max_bought']);
					$surplusCount = intval($goods_info['stock']) - intval($goods_info['buy_count']);
					$goodsStock   = intval($goods_info['stock']);

					if($number + $userBuyCount > $maxBought && $maxBought > 0)
					{
						$number = $maxBought - $userBuyCount;
						$bln = true;
					}

					if($number > $surplusCount && $goodsStock > 0)
					{
						$number = $surplusCount;
						$bln = true;
					}

					if($bln)
					{
						if($maxBought > 0)
						$err.=sprintf($GLOBALS['Ln']["HC_USER_MAX_BUYCOUNT"],$maxBought);
							
						if($goodsStock > 0)

						$err.=sprintf($GLOBALS['Ln']["HC_ONLY_LESS_COUNT"],$surplusCount).(($err == "") ? $GLOBALS['Ln']["HC_GOODS"] : "")."，";
							
						if ($number < 0){
							$number = 0;
						}
						$err.= sprintf($GLOBALS['Ln']["HC_HASBUYCOUNT_LESSCOUNT"],$userBuyCount,$number);

						$error .= $err;
						return false;
					}
				}else{
					//$this->assign("jumpUrl", U('Index/index'));
					//$this->error('选中商品丢失，请申请选择商品！');
					$error .= $GLOBALS['Ln']['GOODS_LOSE_PLS_SELECT_OTHER'];
					return false;
				}
			}
		}

		return true;
	}else{
		$error = $GLOBALS['Ln']['GOODS_LOSE_PLS_SELECT_OTHER'];
		return false;
	}
}


function check_cart_goods($session_id,$user_id,&$error){

	$user_id = intval($user_id);
	$sql = "select * from ".DB_PREFIX."cart where session_id = '".$session_id."' and user_id=".$user_id;
	$list = $GLOBALS['db']->getAll($sql);

	if ($list){
		foreach($list as $cart_item)
		{
			if ($cart_item['rec_module'] == 'PromoteGoods' || $cart_item['rec_module'] == 'Goods'){
				//

				$goods_info = getGoodsItem($cart_item['rec_id']);
	   	
				if ($goods_info){
					$error = $goods_info['name_1'].":";
					if ($goods_info['score_goods'] != 0 || $goods_info['type_id'] == 2){
						$error .= '手机版本不支持,购物此类商品.';
						return false;
					}
					
					$bln = false;
					$err = "";
					$number = intval($cart_item['number']);
						
					if ($goods_info['promote_end_time'] < a_gmtTime() || $goods_info['is_group_fail'] == 1 || ($goods_info['stock'] > 0 && $goods_info['buy_count'] + $number > $goods_info['stock']))
					{
						if($goods_info['promote_end_time'] < a_gmtTime()|| $goods_info['is_group_fail'] == 1)
						{
							//$this->assign("jumpUrl",u("Goods/show",array("id"=>$cart_item['rec_id'])));
							//$this->error("团购已结束");
							$error .= $GLOBALS['Ln']['XY_GROUP_IS_END'];
							return false;
						}
						if($goods_info['stock'] > 0 && $goods_info['buy_count']+$cart_item['number'] > $goods_info['stock'])
						{
							//$this->assign("jumpUrl",u("Goods/show",array("id"=>$cart_item['rec_id'])));
							//$this->error("已售光");
							$error .= $GLOBALS['Ln']['XY_B_SORRY_SOLD_OUT'];
							return false;
						}
					}
						
					//modify chenfq by 2011-03-01 不统计作废订单数量
					$sql = "select sum(og.number) as num from ".DB_PREFIX."order_goods as og "
					." left outer join ".DB_PREFIX."order o on o.id = og.order_id "
					."where o.status <> 2 and og.rec_id = ".intval($cart_item['rec_id'])." and og.user_id=".intval($user_id);
					$userBuyCount = intval($GLOBALS['db']->getOne($sql));

					$maxBought    = intval($goods_info['max_bought']);
					$surplusCount = intval($goods_info['stock']) - intval($goods_info['buy_count']);
					$goodsStock   = intval($goods_info['stock']);

					if($number + $userBuyCount > $maxBought && $maxBought > 0)
					{
						$number = $maxBought - $userBuyCount;
						$bln = true;
					}

					if($number > $surplusCount && $goodsStock > 0)
					{
						$number = $surplusCount;
						$bln = true;
					}

					if($bln)
					{
						if($maxBought > 0)
						$err.=sprintf($GLOBALS['Ln']["HC_USER_MAX_BUYCOUNT"],$maxBought);
							
						if($goodsStock > 0)

						$err.=sprintf($GLOBALS['Ln']["HC_ONLY_LESS_COUNT"],$surplusCount).(($err == "") ? $GLOBALS['Ln']["HC_GOODS"] : "")."，";
							
						if ($number < 0){
							$number = 0;
						}
												
						$err.= sprintf($GLOBALS['Ln']["HC_HASBUYCOUNT_LESSCOUNT"],$userBuyCount,$number);

						$error .= $err;
						return false;
					}
				}else{
					//$this->assign("jumpUrl", U('Index/index'));
					//$this->error('选中商品丢失，请申请选择商品！');
					$error = $GLOBALS['Ln']['GOODS_LOSE_PLS_SELECT_OTHER'];
					return false;
				}
			}
		}

		return true;
	}else{
		$error = $GLOBALS['Ln']['GOODS_LOSE_PLS_SELECT_OTHER'];
		return false;
	}
}


//自动生成手机用图
//生成图片 $type: 0:小图 200x140  1:大图300 x 210宽 
//$img_url 为图片的物理路径
function make_img($img_url,$type)
{	
	$img_url = str_replace("/upyun","",$img_url);
	$filepath = ROOT_PATH."mapi/images";
	$urlpath = API_ROOT."/mapi/images";	
	$paths = pathinfo($img_url);			
	if($type == 0)
	{
		$w = 200;
		$h = 140;		
	}
	else
	{
		$w = 300;
		$h = 210;
	}
	
	$filedir = $filepath . '/c' . substr(md5($img_url), 0, 1);
	$filepath = $filepath . '/c' . substr(md5($img_url), 0, 1);
	$urlpath = $urlpath. '/c' . substr(md5($img_url), 0, 1);        
        
	$filepath = $filepath.'/'.$paths['filename'].'_'.$w.'x'.$h.'.jpg';
	$urlpath =  $urlpath.'/'.$paths['filename'].'_'.$w.'x'.$h.'.jpg';
	
	if(!file_exists($filepath))
	{
		require_once "imagecls.php";
		$imagecls = new imagecls();
		$img_path = $img_url;
		
		if (!is_dir(APP_ROOT_PATH."mapi/images")) { 
		             @mkdir(APP_ROOT_PATH."mapi/images");
		             @chmod(APP_ROOT_PATH."mapi/images", 0777);
		}
		
		if (!is_dir($filedir))
        {
             @mkdir($filedir);
             @chmod($filedir, 0777);
        }
		
		$img_rs = $imagecls->thumb($img_path,$w,$h,1,true,$filepath,$urlpath);
		return a_getDomain().$img_rs['url'];
	}
	else 
	{
		return a_getDomain().$urlpath;
	}
	
}
?>