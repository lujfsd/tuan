<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `credit`;");
E_C("CREATE TABLE `credit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `detail_id` varchar(32) DEFAULT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `score` double(10,2) NOT NULL DEFAULT '0.00',
  `action` varchar(16) NOT NULL DEFAULT 'buy',
  `rname` varchar(32) DEFAULT NULL,
  `rmobile` varchar(32) DEFAULT NULL,
  `rcode` char(6) DEFAULT NULL,
  `raddress` varchar(128) DEFAULT NULL,
  `send_time` int(10) DEFAULT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `state` enum('unpay','pay') NOT NULL DEFAULT 'unpay',
  `remark` text,
  `op_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>