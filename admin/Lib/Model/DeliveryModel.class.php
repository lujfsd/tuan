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

// 配送方式
class DeliveryModel extends MultiLangModel {
	protected $_validate = array(
			array('name','require',DELIVERY_NAME_REQUIRE), 
			array('site','checkUrl',URL_FORMAT_ERROR,2,'function'), // 自定义函数验证密码格式
		);
		
	protected $_auto = array ( 		
		array('status','1'),  // 新增的时候把status字段设置为1	
	);
}
?>