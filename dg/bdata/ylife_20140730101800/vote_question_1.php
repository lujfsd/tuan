<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `vote_question`;");
E_C("CREATE TABLE `vote_question` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'radio',
  `is_show` char(1) NOT NULL DEFAULT '1',
  `addtime` char(10) NOT NULL,
  `order` mediumint(8) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>