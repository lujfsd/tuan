<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_sms_send_log`;");
E_C(\"CREATE TABLE `fanwe_sms_send_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(20) NOT NULL DEFAULT '',
  `send_title` varchar(255) NOT NULL DEFAULT '',
  `send_content` varchar(255) NOT NULL DEFAULT '',
  `success_count` int(11) NOT NULL DEFAULT '0',
  `fail_count` int(11) NOT NULL DEFAULT '0',
  `send_mobiles` text,
  `success_mobiles` text,
  `fail_mobiles` text,
  `expense_count` decimal(10,1) NOT NULL DEFAULT '0.0',
  `action_message` varchar(255) DEFAULT NULL,
  `send_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>