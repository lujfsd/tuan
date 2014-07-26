<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_cart`;");
E_C("CREATE TABLE `fanwe_cart` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `rec_id` int(11) NOT NULL COMMENT '订购的关联ID（主要为goods_id）',
  `rec_module` varchar(255) NOT NULL COMMENT '暂为Goods',
  `data_name` varchar(255) NOT NULL,
  `data_sn` varchar(255) NOT NULL,
  `data_score` int(11) NOT NULL,
  `data_promote_score` int(11) NOT NULL,
  `data_total_score` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `data_unit_price` float(10,4) NOT NULL,
  `data_promote_unit_price` float(10,4) NOT NULL,
  `data_total_price` float(10,4) NOT NULL,
  `attr` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_inquiry` tinyint(1) NOT NULL,
  `promote_id` int(11) NOT NULL,
  `data_weight` float(10,4) NOT NULL,
  `data_total_weight` float(10,4) NOT NULL,
  `goods_type` tinyint(1) NOT NULL default '0',
  `attr_ids` varchar(255) default '',
  `data_total_referral_money` float(10,4) default '0.0000' COMMENT '购买商品返金额',
  PRIMARY KEY  (`id`),
  KEY `update_time` USING BTREE (`update_time`),
  KEY `session_id` USING BTREE (`session_id`),
  KEY `rec_id` (`rec_id`),
  KEY `pid` (`pid`),
  KEY `user_id` (`user_id`),
  KEY `promote_id` (`promote_id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>