<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `pay`;");
E_C("CREATE TABLE `pay` (
  `id` varchar(32) NOT NULL default '',
  `vid` varchar(32) default NULL,
  `order_id` int(10) unsigned NOT NULL default '0',
  `bank` varchar(32) default NULL,
  `money` double(10,2) default NULL,
  `currency` enum('CNY','USD') NOT NULL default 'CNY',
  `service` varchar(16) NOT NULL default 'alipay',
  `create_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNQ_o` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>