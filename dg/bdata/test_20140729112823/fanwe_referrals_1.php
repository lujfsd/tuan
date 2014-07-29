<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_referrals`;");
E_C(\"CREATE TABLE `fanwe_referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `money` float(10,3) NOT NULL,
  `is_pay` tinyint(1) NOT NULL,
  `score` float(10,0) NOT NULL,
  `create_time` int(10) DEFAULT '0',
  `pay_time` int(10) DEFAULT '0',
  `city_id` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>