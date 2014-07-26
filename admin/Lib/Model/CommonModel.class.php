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

// 系统模型的基类

//Admin
define('ADM_NAME_FORMAT_ERROR',L('ADM_NAME_FORMAT_ERROR'));
define('ADM_NAME_EXIST',L('ADM_NAME_EXIST'));
define('ADM_PWD_REQUIRE',L('ADM_PWD_REQUIRE'));
define('CONFIRM_PWD_REQUIRE',L('CONFIRM_PWD_REQUIRE'));
define('CONFIRM_PWD_ERROR',L('CONFIRM_PWD_ERROR'));
//Adv
define('ADV_NAME_REQUIRE',L('ADV_NAME_REQUIRE'));
//ArticleCate
define('CATE_NAME_REQUIRE',L('CATE_NAME_REQUIRE'));
//Article
define('ARTICLE_NAME_REQUIRE',L('ARTICLE_NAME_REQUIRE'));
define('ARTICLE_CATE_REQUIRE',L('ARTICLE_CATE_REQUIRE'));
define('SORT_MUST_BE_NUM',L('SORT_MUST_BE_NUM'));
define('URL_FORMAT_ERROR',L('URL_FORMAT_ERROR'));

//Brand
define('BRAND_NAME_REQUIRE',L('BRAND_NAME_REQUIRE'));

//Delivery
define('DELIVERY_NAME_REQUIRE',L('DELIVERY_NAME_REQUIRE'));

//Goods
define('GOODS_NAME_REQUIRE',L('GOODS_NAME_REQUIRE'));
define('GOODS_CATE_REQUIRE',L('GOODS_CATE_REQUIRE'));
define('MARKET_PRICE_MUST_BE_NUM',L('MARKET_PRICE_MUST_BE_NUM'));
define('SHOP_PRICE_MUST_BE_NUM',L('SHOP_PRICE_MUST_BE_NUM'));
define('PROMOTE_PRICE_MUST_BE_NUM',L('PROMOTE_PRICE_MUST_BE_NUM'));
define('STOCK_MUST_BE_NUM',L('STOCK_MUST_BE_NUM'));
define('PROMOTE_BEGIN_TIME_ERROR',L('PROMOTE_BEGIN_TIME_ERROR'));
define('PROMOTE_END_TIME_ERROR',L('PROMOTE_END_TIME_ERROR'));

//Currency
define('CURRENCY_NAME_REQUIRE',L('CURRENCY_NAME_REQUIRE'));

//Weight
define('WEIGHT_NAME_REQUIRE',L('WEIGHT_NAME_REQUIRE'));

//GoodsType
define('TYPE_NAME_REQUIRE',L('TYPE_NAME_REQUIRE'));

//LangConf
define('LANG_NAME_REQUIRE',L('LANG_NAME_REQUIRE'));
define('SHOW_NAME_REQUIRE',L('SHOW_NAME_REQUIRE'));
define('TIME_ZONE_MUST_BE_NUM',L('TIME_ZONE_MUST_BE_NUM'));
define('CURRENCY_UNIT_REQUIRE',L('CURRENCY_UNIT_REQUIRE'));
define('CURRENCY_RADIO_REQUIRE',L('CURRENCY_RADIO_REQUIRE'));
define('CURRENCY_RADIO_MUST_BE_NUM',L('CURRENCY_RADIO_MUST_BE_NUM'));

//Link
define('LINK_NAME_REQUIRE',L('LINK_NAME_REQUIRE'));
define('LINK_URL_REQUIRE',L('LINK_URL_REQUIRE'));

//Message
define('MESSAGE_TITLE_REQUIRE',L('MESSAGE_TITLE_REQUIRE'));
define('MESSAGE_CONTENT_REQUIRE',L('MESSAGE_CONTENT_REQUIRE'));
define('SCORE_ERROR',L('SCORE_ERROR'));

//Nav
define('NAV_NAME_REQUIRE',L('NAV_NAME_REQUIRE'));
define('NAV_URL_REQUIRE',L('NAV_URL_REQUIRE'));

//Order
define('UNIT_PRICE_MUST_BE_NUM',L('UNIT_PRICE_MUST_BE_NUM'));
define('NUMBER_MUST_BE_NUM',L('NUMBER_MUST_BE_NUM'));
define('TOTAL_MUST_BE_NUM',L('TOTAL_MUST_BE_NUM'));

//Region
define('REGION_NAME_REQUIRE',L('REGION_NAME_REQUIRE'));

//Role
define('NODE_ID_REQUIRE',L('NODE_ID_REQUIRE'));
define('ROLE_NAME_REQUIRE',L('ROLE_NAME_REQUIRE'));
define('ROLE_NAV_NAME_REQUIRE',L('ROLE_NAV_NAME_REQUIRE'));

//User
define('CONSIGNEE_NAME_REQUIRE',L('CONSIGNEE_NAME_REQUIRE'));
define('CONSIGNEE_ADDRESS_REQUIRE',L('CONSIGNEE_ADDRESS_REQUIRE'));
define('GROUP_NAME_REQUIRE',L('GROUP_NAME_REQUIRE'));
define('DISCOUNT_FORMAT_ERROR',L('DISCOUNT_FORMAT_ERROR'));
define('USER_NAME_REQUIRE',L('USER_NAME_REQUIRE'));
define('USER_NAME_EXIST',L('USER_NAME_EXIST'));
define('USER_PWD_REQUIRE',L('USER_PWD_REQUIRE'));
define('USER_PWD_CONFIRM_REQUIRE',L('USER_PWD_CONFIRM_REQUIRE'));
define('USER_PWD_CONFIRM_ERROR',L('USER_PWD_CONFIRM_ERROR'));

define('PAYMENT_NAME_REQUIRE',L('PAYMENT_NAME_REQUIRE'));

define('SPEC_TYPE_NAME_REQUIRE',L("SPEC_TYPE_NAME_REQUIRE"));

define('MAIL_ADDRESS_REQUIRE',L('MAIL_ADDRESS_REQUIRE'));
define('MAIL_FORMAT_ERROR',L('MAIL_FORMAT_ERROR'));

define('MAIL_TITLE_REQUIRE',L('MAIL_TITLE_REQUIRE'));
define('SEND_TIME_REQUIRE',L('SEND_TIME_REQUIRE'));
define('MAIL_CONTENT_REQUIRE',L('MAIL_CONTENT_REQUIRE'));
define('TIME_FORMAT_ERROR',L('TIME_FORMAT_ERROR'));

define('KEYWORDS_REQUIRE',L('KEYWORDS_REQUIRE'));

define('LAYOUT_ID_REQUIRE',L('LAYOUT_ID_REQUIRE'));
define('TMPL_REQUIRE',L('TMPL_REQUIRE'));
define('PAGE_REQUIRE',L('PAGE_REQUIRE'));
define('VOTE_TITLE_REQUIRE',L('VOTE_TITLE_REQUIRE'));
define('VOTE_START_TIME_ERROR',L('VOTE_START_TIME_ERROR'));
define('VOTE_END_TIME_ERROR',L('VOTE_END_TIME_ERROR'));

class CommonModel extends BaseModel {
	// 获取当前用户的ID
    public function getMemberId() {
        return isset($_SESSION[C('USER_AUTH_KEY')])?$_SESSION[C('USER_AUTH_KEY')]:0;
    }

   /**
     +----------------------------------------------------------
     * 根据条件禁用表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function forbid($options,$field='status'){
        if(FALSE === $this->where($options)->setField($field,0)){        	
            $this->error =  L('INVALID_OP');
            return false;
        }else {
        	
            return True;
        }
    }

	 /**
     +----------------------------------------------------------
     * 根据条件批准表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */

    public function checkPass($options,$field='status'){
        if(FALSE === $this->where($options)->setField($field,1)){
            $this->error =  L('INVALID_OP');
            return false;
        }else {
            return True;
        }
    }


    /**
     +----------------------------------------------------------
     * 根据条件恢复表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function resume($options,$field='status'){
        if(FALSE === $this->where($options)->setField($field,1)){
            $this->error =  L('INVALID_OP');
            return false;
        }else {
            return True;
        }
    }

    /**
     +----------------------------------------------------------
     * 根据条件恢复表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function recycle($options,$field='status'){
        if(FALSE === $this->where($options)->setField($field,0)){
            $this->error =  L('INVALID_OP');
            return false;
        }else {
            return True;
        }
    }

    public function recommend($options,$field='is_recommend'){
        if(FALSE === $this->where($options)->setField($field,1)){
            $this->error =  L('INVALID_OP');
            return false;
        }else {
            return True;
        }
    }

    public function unrecommend($options,$field='is_recommend'){
        if(FALSE === $this->where($options)->setField($field,0)){
            $this->error =  L('INVALID_OP');
            return false;
        }else {
            return True;
        }
    }
    
     /**
     +----------------------------------------------------------
     * 把返回的数据集转换成Tree
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    public function toTree($list=null, $pk='id',$pid = 'pid',$child = '_child')
    {
        if(null === $list) {
            // 默认直接取查询返回的结果集合
            $list   =   &$this->dataList;
        }
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
	 * 将格式数组转换为树
	 *
	 * @param array $list
	 * @param integer $level 进行递归时传递用的参数
	 * @param string dispname 显示的名称的列的集合
	 */
	private $formatTree; //用于树型数组完成递归格式的全局变量
	private function _toFormatTree($list,$level=0,$dispname_arr=array('title')) 
	{
			  foreach($list as $key=>$val)
			  {
				$tmp_str=str_repeat("&nbsp;&nbsp;",$level*2);
				$tmp_str.="|--";
				
				foreach($dispname_arr as $dispname)
				{
					$val[$dispname]=$tmp_str."&nbsp;&nbsp;".$val[$dispname];
				}
				$val['level'] = $level;
				if(!array_key_exists('_child',$val))
				{
				   array_push($this->formatTree,$val);
				}
				else
				{
				   $tmp_ary = $val['_child'];
				   unset($val['_child']);
				   array_push($this->formatTree,$val);
				   $this->_toFormatTree($tmp_ary,$level+1,$dispname_arr); //进行下一层递归
				}
			  }
			  return;
	}
	
	public function toFormatTree($list,$dispname_arr=array('title'))
	{
		$list = $this->toTree($list);
		$this->formatTree = array();
		$this->_toFormatTree($list,0,$dispname_arr);
		return $this->formatTree;
	}
	
	
	//无限递归获取子数据ID集合
	private $childIds;
	private function _getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid')
	{
		$childItem_arr = $this->field('id')->where($pid_str."=".$pid)->findAll();
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
?>