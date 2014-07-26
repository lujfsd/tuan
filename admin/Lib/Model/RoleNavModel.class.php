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


//后台菜单
class RoleNavModel extends CommonModel {
	protected $_validate = array(
			array('name','require',ROLE_NAV_NAME_REQUIRE), 
		);
	protected $_auto = array ( 		
		array('status','1'),  // 新增的时候把status字段设置为1	
	);
}
?>