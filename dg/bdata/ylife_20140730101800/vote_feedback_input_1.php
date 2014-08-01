<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `vote_feedback_input`;");
E_C("CREATE TABLE `vote_feedback_input` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feedback_id` bigint(20) unsigned NOT NULL,
  `options_id` mediumint(8) unsigned NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>