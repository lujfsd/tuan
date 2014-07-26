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

//配送地址列表
class UserConsigneeModel extends CommonModel   {
	protected $_validate = array(
			array('consignee','require',CONSIGNEE_NAME_REQUIRE), 
			array('address','require',CONSIGNEE_ADDRESS_REQUIRE), 
		);
}
?>