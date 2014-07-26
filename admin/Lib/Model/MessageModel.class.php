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

//留言
class MessageModel extends CommonModel {
	protected $_validate = array(
			array('title','require',MESSAGE_TITLE_REQUIRE), 
			array('content','require',MESSAGE_CONTENT_REQUIRE), 
			array('score',array(0,1,2,3,4,5),SCORE_ERROR,2,'in'), 
		);
		
	protected $_auto = array ( 		
		array('create_time','gmtTime',1,'function'), // 对create_time字段在插入的时候写入当前时间戳			
	);
}
?>