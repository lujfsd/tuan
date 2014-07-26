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
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL default '0',
  `admin_id` int(10) unsigned NOT NULL default '0',
  `detail_id` varchar(32) default NULL,
  `detail` varchar(255) default NULL,
  `score` double(10,2) NOT NULL default '0.00',
  `action` varchar(16) NOT NULL default 'buy',
  `rname` varchar(32) default NULL,
  `rmobile` varchar(32) default NULL,
  `rcode` char(6) default NULL,
  `raddress` varchar(128) default NULL,
  `send_time` int(10) default NULL,
  `create_time` int(10) unsigned NOT NULL default '0',
  `state` enum('unpay','pay') NOT NULL default 'unpay',
  `remark` text,
  `op_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>