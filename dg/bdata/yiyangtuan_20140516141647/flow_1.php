<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `flow`;");
E_C("CREATE TABLE `flow` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL default '0',
  `admin_id` int(10) unsigned NOT NULL default '0',
  `detail_id` varchar(32) default NULL,
  `detail` varchar(255) default NULL,
  `direction` enum('income','expense') NOT NULL default 'income',
  `money` double(10,2) NOT NULL default '0.00',
  `action` varchar(16) NOT NULL default 'buy',
  `create_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>