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

//后台节点
class RoleNodeModel extends CommonModel {
	protected $_auto = array ( 		
		array('status','1'),  // 新增的时候把status字段设置为1	
	);
	protected $_map = array(
		'auth_type'	=>	'auth_type',  //用于单独操作的字段映射
	);
}
?>