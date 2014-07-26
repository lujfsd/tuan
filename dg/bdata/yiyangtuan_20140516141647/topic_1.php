<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `topic`;");
E_C("CREATE TABLE `topic` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(128) default NULL,
  `team_id` int(10) unsigned NOT NULL default '0',
  `city_id` int(10) unsigned NOT NULL default '0',
  `public_id` int(10) unsigned NOT NULL default '0',
  `content` text,
  `head` int(10) unsigned NOT NULL default '0',
  `reply_number` int(10) unsigned NOT NULL default '0',
  `view_number` int(10) unsigned NOT NULL default '0',
  `last_user_id` int(10) unsigned NOT NULL default '0',
  `last_time` int(10) unsigned NOT NULL default '0',
  `create_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>