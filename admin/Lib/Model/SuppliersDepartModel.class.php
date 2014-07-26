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


class SuppliersDepartModel extends CommonModel {
	protected $_validate = array(
			array('depart_name','require','部门名称不能为空'), 
			array('pwd','require','密码不能为空','','',1), // 自定义函数验证密码格式
			array('depart_name','','部门名称已存在',0,'unique',1), 
	
		);
}
?>