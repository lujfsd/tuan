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
class MailAddressListModel extends CommonModel {
	protected $_validate = array(
			array('mail_address','require',MAIL_ADDRESS_REQUIRE), 
			array('mail_address','check_mail',MAIL_FORMAT_ERROR,2,'function'), 
		);
	protected $_auto = array ( 		
		array('status','1'),  // 新增的时候把status字段设置为1
	);
}
?>