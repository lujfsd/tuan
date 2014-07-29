<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_supplier_goods`;");
E_C(\"CREATE TABLE `fanwe_supplier_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `max_count` int(11) NOT NULL,
  `min_count` int(11) NOT NULL,
  `user_max_count` int(11) NOT NULL,
  `promote_begin_time` int(11) NOT NULL,
  `promote_end_time` int(11) NOT NULL,
  `brief` text NOT NULL,
  `contents` text NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `origin_price` double(20,4) NOT NULL,
  `shop_price` double(20,4) NOT NULL,
  `other_desc` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `goods_type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>