<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_cart_card`;");
E_C(\"CREATE TABLE `fanwe_cart_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_code` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `update_time` (`update_time`) USING BTREE,
  KEY `id` (`id`),
  KEY `card_code` (`card_code`),
  KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>