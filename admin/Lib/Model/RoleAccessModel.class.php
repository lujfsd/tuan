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

//角色权限列表
class RoleAccessModel extends CommonModel {
	protected $_validate = array(
			array('node_id','gtZero',NODE_ID_REQUIRE,0,'function'), // 自定义函数验证密码格式
		);
}
?>