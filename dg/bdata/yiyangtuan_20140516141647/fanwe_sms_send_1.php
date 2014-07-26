<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_sms_send`;");
E_C("CREATE TABLE `fanwe_sms_send` (
  `id` int(11) NOT NULL auto_increment,
  `send_type` tinyint(1) NOT NULL default '1' COMMENT '1:按会员分组发送 2: 自定义发送会员',
  `send_title` varchar(255) NOT NULL default '',
  `type` smallint(1) NOT NULL default '1',
  `rec_id` int(11) NOT NULL default '0',
  `send_content` text NOT NULL,
  `custom_mobiles` text NOT NULL,
  `user_group` int(11) NOT NULL default '0',
  `custom_users` text NOT NULL,
  `send_time` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0' COMMENT '0:未发送 1: 发送中 2:已发送',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_sms_send` values('1','2','1','1','0','1111111111','18653991358','0','','0','0');");

require("../../inc/footer.php");
?>