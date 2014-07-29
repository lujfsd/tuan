<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_order_goods`;");
E_C(\"CREATE TABLE `fanwe_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '用于礼包配件的相关购买的父ID',
  `order_id` int(11) NOT NULL,
  `rec_module` varchar(255) NOT NULL COMMENT '用于关联相应的购物组如：Goods/Gift...可扩展',
  `rec_id` int(11) NOT NULL,
  `data_name` varchar(255) NOT NULL,
  `data_sn` varchar(255) NOT NULL,
  `data_score` int(11) NOT NULL,
  `data_total_score` int(11) NOT NULL,
  `data_price` float(10,4) NOT NULL,
  `data_total_price` float(10,4) NOT NULL,
  `attr` varchar(255) NOT NULL,
  `number` int(11) NOT NULL,
  `is_inquiry` tinyint(1) NOT NULL,
  `data_cost_price` float(10,4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `send_number` int(11) NOT NULL,
  `data_weight` float(10,4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `data_total_referral_money` float(10,4) DEFAULT '0.0000',
  `is_balance` tinyint(1) NOT NULL COMMENT '0:未结算 1:待结算 2:已结算 3:部份结算',
  `balance_unit_price` float(10,4) NOT NULL DEFAULT '0.0000',
  `balance_memo` text NOT NULL,
  `balance_total_price` float(10,4) NOT NULL DEFAULT '0.0000',
  `balance_time` int(11) NOT NULL COMMENT '结算时间',
  PRIMARY KEY (`id`),
  KEY `inx_order_goods_001` (`order_id`),
  KEY `inx_order_goods_002` (`rec_id`),
  KEY `inx_order_goods_003` (`rec_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_order_goods` values('86','0','86','PromoteGoods','114','缘始陶艺单人体验套餐','DIY_1406250082','5','5','0.0000','0.0000','','1','0','0.0000','1406595133','0','0','0.0000','59','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('87','0','87','PromoteGoods','114','缘始陶艺单人体验套餐','DIY_1406250082','5','10','0.0000','0.0000','','2','0','0.0000','1406595226','0','0','0.0000','59','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('88','0','88','PromoteGoods','114','缘始陶艺单人体验套餐','DIY_1406250082','5','20','0.0000','0.0000','','4','0','0.0000','1406595290','0','0','0.0000','59','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('81','0','81','PromoteGoods','114','缘始陶艺单人体验套餐','DIY_1406250082','5','5','0.0000','0.0000','','1','0','0.0000','1406250366','0','0','0.0000','59','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('82','0','82','PromoteGoods','115','缘始陶艺双人','DIY_1406251410','0','0','0.0000','0.0000','','1','0','0.0000','1406500410','0','0','0.0000','59','0.0000','2','0.0000','','0.0000','1406591344');");
E_D("replace into `fanwe_order_goods` values('83','0','83','PromoteGoods','114','缘始陶艺单人体验套餐','DIY_1406250082','5','5','0.0000','0.0000','','1','0','0.0000','1406500541','0','0','0.0000','59','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('84','0','84','PromoteGoods','115','缘始陶艺双人','DIY_1406251410','0','0','0.0000','0.0000','','2','0','0.0000','1406504404','0','0','0.0000','59','0.0000','3','0.0000','','0.0000','1406588038');");
E_D("replace into `fanwe_order_goods` values('85','0','85','PromoteGoods','114','缘始陶艺单人体验套餐','DIY_1406250082','5','5','0.0000','0.0000','','1','0','0.0000','1406594938','0','0','0.0000','59','0.0000','0','0.0000','','0.0000','0');");

require("../../inc/footer.php");
?>