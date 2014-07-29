<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_mail_send_list`;");
E_C(\"CREATE TABLE `fanwe_mail_send_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_address` varchar(255) NOT NULL,
  `mail_title` varchar(255) NOT NULL,
  `mail_content` text NOT NULL,
  `send_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `rec_module` varchar(255) NOT NULL COMMENT '群发邮件的模块',
  `rec_id` int(11) NOT NULL COMMENT '数据相关ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>