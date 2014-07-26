<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `feedback`;");
E_C("CREATE TABLE `feedback` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `city_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  `category` enum('suggest','seller') NOT NULL default 'suggest',
  `title` varchar(128) default NULL,
  `contact` varchar(255) default NULL,
  `content` text,
  `create_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>