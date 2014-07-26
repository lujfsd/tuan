<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `invite`;");
E_C("CREATE TABLE `invite` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL default '0',
  `admin_id` int(10) unsigned NOT NULL default '0',
  `user_ip` varchar(16) default NULL,
  `other_user_id` int(10) unsigned NOT NULL default '0',
  `other_user_ip` varchar(16) default NULL,
  `team_id` int(10) unsigned NOT NULL default '0',
  `pay` enum('Y','N','C') NOT NULL default 'N',
  `credit` int(10) unsigned NOT NULL default '0',
  `buy_time` int(10) unsigned NOT NULL default '0',
  `create_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNQ_uo` (`user_id`,`other_user_id`),
  UNIQUE KEY `UNQ_o` (`other_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>