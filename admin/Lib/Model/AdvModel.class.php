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

// 广告模型
class AdvModel extends CommonModel {
	public $_validate	=	array(
		array('name','require',ADV_NAME_REQUIRE),
	);

	public $_auto		=	array(
		array('status','1'),  // 新增的时候把status字段设置为1
		);
	protected $_map = array(
		'code'	=>	'code',  //用于单独操作的字段映射，在上传文件后更新
		'url'	=>	'url',
		'type'	=>	'type',
	);
}
?>