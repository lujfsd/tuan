<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_vote_item`;");
E_C("CREATE TABLE `ylife_vote_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_multi` tinyint(1) NOT NULL DEFAULT '0',
  `vote_count` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `vote_id` (`vote_id`),
  KEY `index_1` (`vote_id`,`status`,`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>