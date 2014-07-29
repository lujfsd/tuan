<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_order_re_consignment`;");
E_C(\"CREATE TABLE `fanwe_order_re_consignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `delivery_code` varchar(255) NOT NULL,
  `delivery_fee` float(10,4) NOT NULL,
  `protect_fee` float(10,4) NOT NULL,
  `cost_calc` tinyint(1) NOT NULL,
  `region_lv1` int(11) NOT NULL,
  `region_lv2` int(11) NOT NULL,
  `region_lv3` int(11) NOT NULL,
  `region_lv4` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile_phone` varchar(255) NOT NULL,
  `fix_phone` varchar(255) NOT NULL,
  `consignee` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `memo` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `express_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>