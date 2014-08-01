<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `vote_options`;");
E_C("CREATE TABLE `vote_options` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `is_br` char(1) NOT NULL DEFAULT '0',
  `is_input` char(1) NOT NULL DEFAULT '0',
  `is_show` char(1) NOT NULL DEFAULT '1',
  `order` mediumint(8) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>