<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_vote_option`;");
E_C("CREATE TABLE `fanwe_vote_option` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `is_input` tinyint(1) NOT NULL default '0',
  `vote_count` int(11) NOT NULL default '0',
  `sort` int(11) NOT NULL default '0',
  `separator` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>