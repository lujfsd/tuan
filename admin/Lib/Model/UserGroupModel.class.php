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

//用户组
class UserGroupModel extends MultiLangModel {
	protected $_validate = array(
			array('name','require',GROUP_NAME_REQUIRE), 
			array('discount','checkDiscount',DISCOUNT_FORMAT_ERROR,1,'function'),
		);
	protected $_auto = array ( 		
		array('status','1'),  // 新增的时候把status字段设置为1
		array('discount','priceVal',3,'function'), 
	);
}
?>