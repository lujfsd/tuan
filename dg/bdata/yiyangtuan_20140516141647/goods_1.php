<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `goods`;");
E_C("CREATE TABLE `goods` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(128) default NULL,
  `score` int(11) NOT NULL default '0',
  `image` varchar(128) default NULL,
  `time` int(11) NOT NULL default '0',
  `number` int(11) NOT NULL default '0',
  `per_number` int(11) NOT NULL default '1',
  `sort_order` int(11) NOT NULL default '0',
  `consume` int(11) NOT NULL default '0',
  `display` enum('Y','N') NOT NULL default 'Y',
  `enable` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>