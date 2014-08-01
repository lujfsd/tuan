<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_user_incharge`;");
E_C("CREATE TABLE `ylife_user_incharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL,
  `money` float(10,4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment` int(11) NOT NULL,
  `payment_fee` float(10,4) NOT NULL,
  `payment_money` float(10,4) NOT NULL,
  `bank_id` varchar(30) DEFAULT NULL,
  `card_info` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inx_unique_sn` (`sn`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  KEY `index_0` (`user_id`,`sn`),
  KEY `index_1` (`user_id`,`sn`,`status`),
  KEY `index_2` (`bank_id`,`user_id`,`status`),
  KEY `index_3` (`bank_id`,`user_id`,`status`,`payment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>