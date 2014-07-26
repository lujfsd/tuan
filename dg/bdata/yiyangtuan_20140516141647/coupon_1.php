<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `coupon`;");
E_C("CREATE TABLE `coupon` (
  `id` varchar(16) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `partner_id` int(10) unsigned NOT NULL default '0',
  `team_id` int(10) unsigned NOT NULL default '0',
  `order_id` int(10) unsigned NOT NULL default '0',
  `type` enum('consume','credit') NOT NULL default 'consume',
  `credit` int(10) unsigned NOT NULL default '0',
  `secret` varchar(10) default NULL,
  `consume` enum('Y','N') NOT NULL default 'N',
  `ip` varchar(16) default NULL,
  `sms` int(10) unsigned NOT NULL default '0',
  `expire_time` int(10) unsigned NOT NULL default '0',
  `consume_time` int(10) unsigned NOT NULL default '0',
  `create_time` int(10) unsigned NOT NULL default '0',
  `sms_time` int(10) unsigned NOT NULL default '0',
  `buy_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>