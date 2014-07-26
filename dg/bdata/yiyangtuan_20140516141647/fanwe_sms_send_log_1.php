<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_sms_send_log`;");
E_C("CREATE TABLE `fanwe_sms_send_log` (
  `id` int(11) NOT NULL auto_increment,
  `class_name` varchar(20) NOT NULL default '',
  `send_title` varchar(255) NOT NULL default '',
  `send_content` varchar(255) NOT NULL default '',
  `success_count` int(11) NOT NULL default '0',
  `fail_count` int(11) NOT NULL default '0',
  `send_mobiles` text,
  `success_mobiles` text,
  `fail_mobiles` text,
  `expense_count` decimal(10,1) NOT NULL default '0.0',
  `action_message` varchar(255) default NULL,
  `send_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>