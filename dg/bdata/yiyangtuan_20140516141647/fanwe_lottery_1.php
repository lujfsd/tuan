<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_lottery`;");
E_C("CREATE TABLE `fanwe_lottery` (
  `id` int(11) NOT NULL auto_increment,
  `lottery_type` varchar(100) NOT NULL default '',
  `goods_ids` varchar(255) NOT NULL default '',
  `integral_min` int(11) NOT NULL default '0',
  `integral_sub` int(11) NOT NULL default '0',
  `frequency_type` smallint(1) NOT NULL default '0',
  `frequency_unit` smallint(5) NOT NULL default '1',
  `frequency` smallint(5) NOT NULL default '1',
  `begin_time` int(11) NOT NULL default '0',
  `end_time` int(11) NOT NULL default '0',
  `user_group` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `img` varchar(255) NOT NULL default '',
  `desc` text,
  `status` tinyint(1) NOT NULL default '1',
  `create_time` int(11) NOT NULL default '0',
  `update_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>