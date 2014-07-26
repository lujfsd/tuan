<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `vote_feedback`;");
E_C("CREATE TABLE `vote_feedback` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(32) default NULL,
  `user_id` int(10) unsigned NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `addtime` char(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>