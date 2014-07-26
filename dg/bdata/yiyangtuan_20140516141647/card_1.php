<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `card`;");
E_C("CREATE TABLE `card` (
  `id` varchar(16) NOT NULL,
  `code` varchar(16) default NULL,
  `partner_id` int(10) unsigned NOT NULL default '0',
  `team_id` int(10) unsigned NOT NULL default '0',
  `order_id` int(10) unsigned NOT NULL default '0',
  `credit` int(10) unsigned NOT NULL default '0',
  `consume` enum('Y','N') NOT NULL default 'N',
  `ip` varchar(16) default NULL,
  `begin_time` int(10) unsigned NOT NULL default '0',
  `end_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>