<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_coupon`;");
E_C("CREATE TABLE `ylife_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `city_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `txt` text NOT NULL,
  `is_sms` tinyint(1) NOT NULL,
  `count` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `sn` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `depart` varchar(255) NOT NULL,
  `is_best` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `city_id` (`city_id`),
  KEY `region_id` (`region_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `is_sms` (`is_sms`),
  KEY `index_1` (`city_id`,`region_id`,`supplier_id`,`is_sms`,`is_best`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>