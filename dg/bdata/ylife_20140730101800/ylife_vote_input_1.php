<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_vote_input`;");
E_C("CREATE TABLE `ylife_vote_input` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `vote_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `option_id` (`option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>