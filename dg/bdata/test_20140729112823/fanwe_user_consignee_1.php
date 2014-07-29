<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_user_consignee`;");
E_C(\"CREATE TABLE `fanwe_user_consignee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `consignee` varchar(255) NOT NULL,
  `region_lv1` varchar(255) NOT NULL,
  `region_lv2` varchar(255) NOT NULL,
  `region_lv3` varchar(255) NOT NULL,
  `region_lv4` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `fix_phone` varchar(255) NOT NULL,
  `fax_phone` varchar(255) NOT NULL,
  `mobile_phone` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>