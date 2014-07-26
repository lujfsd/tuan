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

// 邮件列表模型
class MailListModel extends CommonModel {
	protected $_validate = array(
			array('mail_title','require',MAIL_TITLE_REQUIRE), 
			array('send_time','require',SEND_TIME_REQUIRE), 
			array('send_time','check_time',TIME_FORMAT_ERROR,2,'function'), 
		);
	protected $_auto = array ( 		
		array('send_time','localStrToTime',3,'function'), 	 
	);

}
?>