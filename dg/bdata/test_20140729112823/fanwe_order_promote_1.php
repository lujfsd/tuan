<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_order_promote`;");
E_C(\"CREATE TABLE `fanwe_order_promote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `promote_id` int(11) NOT NULL,
  `memo` varchar(255) NOT NULL,
  `promote_money` float(10,4) NOT NULL,
  `card_id` tinyint(1) NOT NULL COMMENT '优惠券id',
  `promote_data_name` varchar(255) NOT NULL COMMENT '赠品名称/ 优惠券名称，用，号分隔,其中赠品数量直接拼接在名称后',
  `promote_data_number` int(11) NOT NULL COMMENT '优惠券数量',
  `priority` int(11) NOT NULL COMMENT '优惠计算优先级 由大到小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>