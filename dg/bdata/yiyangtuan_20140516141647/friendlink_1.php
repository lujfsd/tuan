<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `friendlink`;");
E_C("CREATE TABLE `friendlink` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(32) default NULL,
  `url` varchar(255) default NULL,
  `logo` varchar(255) default NULL,
  `sort_order` int(11) default NULL,
  `display` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNQ_l` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>