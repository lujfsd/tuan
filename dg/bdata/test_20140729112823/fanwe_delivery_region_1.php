<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_delivery_region`;");
E_C(\"CREATE TABLE `fanwe_delivery_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_ids` varchar(255) NOT NULL,
  `first_price` float(10,4) NOT NULL,
  `continue_price` float(10,4) NOT NULL,
  `allow_cod` tinyint(4) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `delivery_id` (`delivery_id`),
  KEY `allow_cod` (`allow_cod`),
  KEY `index_1` (`allow_cod`,`delivery_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>