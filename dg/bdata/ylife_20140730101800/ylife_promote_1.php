<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_promote`;");
E_C("CREATE TABLE `ylife_promote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promote_type_id` tinyint(2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `promote_begin_time` int(11) NOT NULL,
  `promote_end_time` int(11) NOT NULL,
  `is_card` tinyint(1) NOT NULL COMMENT '是否以优惠券形式发放',
  `card_name_1` varchar(255) NOT NULL,
  `card_score` int(11) NOT NULL COMMENT '发放该优惠券所需积分',
  `card_total` int(11) NOT NULL COMMENT '优惠券发放总量',
  `card_limit` int(11) NOT NULL COMMENT '发放优惠券使用的次数',
  `order_price_min` float(10,4) NOT NULL COMMENT '订单促销所需的最小金额条件',
  `order_price_max` float(10,4) NOT NULL COMMENT '订单促销所需的最大金额条件',
  `use_card` tinyint(1) NOT NULL COMMENT '非优惠券促销时能否使用优惠券',
  `discount_radio` float(10,4) NOT NULL,
  `dilivery_free` tinyint(4) NOT NULL,
  `priority` int(11) NOT NULL COMMENT '优先级 由大到小',
  `memo_1` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>