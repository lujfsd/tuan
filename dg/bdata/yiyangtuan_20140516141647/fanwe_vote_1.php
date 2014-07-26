<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_vote`;");
E_C("CREATE TABLE `fanwe_vote` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `city_id` int(11) NOT NULL default '0',
  `start_time` int(11) NOT NULL default '0',
  `end_time` int(11) NOT NULL default '0',
  `vote_count` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `inx_vote_001` (`status`,`start_time`,`end_time`,`city_id`),
  KEY `inx_vote_002` (`sort`,`id`),
  KEY `sort` (`sort`),
  KEY `city_id` (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>