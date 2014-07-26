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

//代金券
class SmsSendModel extends MultiLangModel {
	protected $_validate = array(
			array('send_title','require','短信标题不能为空'), 
			array('send_content','require','短信内容不能为空'),
		);
		
	protected $_auto = array ( 		
		array('send_time','localStrToTimeMax',3,'function'),
	);
}
?>