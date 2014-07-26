<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `voucher`;");
E_C("CREATE TABLE `voucher` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(64) default NULL,
  `user_id` int(10) unsigned NOT NULL default '0',
  `team_id` int(10) unsigned NOT NULL default '0',
  `order_id` int(10) unsigned NOT NULL default '0',
  `sms` int(10) unsigned NOT NULL default '0',
  `sms_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNQ_ct` (`code`,`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>