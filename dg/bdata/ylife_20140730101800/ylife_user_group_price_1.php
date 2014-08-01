<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_user_group_price`;");
E_C("CREATE TABLE `ylife_user_group_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `user_price` float(10,4) NOT NULL,
  `spec_item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `user_group_id` (`user_group_id`),
  KEY `index_1` (`goods_id`,`user_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>