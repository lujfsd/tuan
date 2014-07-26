<?php
// +----------------------------------------------------------------------
// | Fanwe 多语商城建站系统 (Build on ThinkPHP)
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: awfigq(67877605@qq.com)
// +----------------------------------------------------------------------

// 调查管理
class VoteOptionModel extends MultiLangModel  {
	protected $_validate = array(
		array('title','require',VOTE_ITEM_TITLE_REQUIRE),
	);
	
	protected $_auto = array ( 		
		array('status','1'),
	);
}
?>