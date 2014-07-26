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

//订单商品
class OrderGoodsModel extends CommonModel {
	protected $_validate = array(
			array('unit_price','is_numeric',UNIT_PRICE_MUST_BE_NUM,1,'function'), 
			array('number','is_numeric',NUMBER_MUST_BE_NUM,1,'function'), 
			array('total_price','is_numeric',TOTAL_MUST_BE_NUM,1,'function'), 
		);
}
?>