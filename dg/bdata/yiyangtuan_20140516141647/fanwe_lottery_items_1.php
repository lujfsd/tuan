<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_lottery_items`;");
E_C("CREATE TABLE `fanwe_lottery_items` (
  `id` int(11) NOT NULL default '0',
  `index` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `type` smallint(2) NOT NULL default '0',
  `val` int(10) unsigned NOT NULL default '0',
  `img` varchar(255) NOT NULL default '',
  `total_num` int(11) NOT NULL default '-1',
  `day_num` int(11) NOT NULL default '0',
  `winning_num` int(11) NOT NULL default '0',
  `probability` int(11) NOT NULL default '0',
  `begin_time` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  KEY `id` (`id`,`index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>