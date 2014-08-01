<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_collect`;");
E_C("CREATE TABLE `ylife_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec_id` int(11) NOT NULL,
  `rec_module` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `rec_id` (`rec_id`),
  KEY `user_id` (`user_id`),
  KEY `rec_module` (`rec_module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>