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
class AdvPositionModel extends CommonModel {
	public $_validate	=	array(
		array('name','require',ADV_POSITION_NAME_REQUIRE),
	);
	protected $_map = array(
		'is_flash'	=>	'is_flash', 
		'flash_style'	=>	'flash_style',
	);
}
?>