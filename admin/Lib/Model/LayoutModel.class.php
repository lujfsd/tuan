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

//布局
class LayoutModel extends CommonModel {
	protected $_validate = array(
			array('tmpl','require',TMPL_REQUIRE), 			
			array('layout_id','require',LAYOUT_ID_REQUIRE), 
		);

}
?>