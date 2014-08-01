<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_order`;");
E_C("CREATE TABLE `ylife_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL,
  `money_status` tinyint(1) NOT NULL COMMENT '0:未收款;1:部分收款;2:全部收款;3:部分退款;4:全部退款'',',
  `goods_status` tinyint(1) NOT NULL COMMENT '0:未发货;1:部分发货;2:全部发货;3:部分退货;4:全部退货'',',
  `status` tinyint(1) NOT NULL COMMENT '订单状态\r\n0: 未确认\r\n1: 完成\r\n2: 作废',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `region_lv1` int(11) NOT NULL,
  `region_lv2` int(11) NOT NULL,
  `region_lv3` int(11) NOT NULL,
  `region_lv4` int(11) NOT NULL,
  `address` text,
  `email` varchar(255) NOT NULL,
  `mobile_phone` varchar(255) NOT NULL,
  `fax_phone` varchar(255) NOT NULL,
  `fix_phone` varchar(255) NOT NULL,
  `alim` varchar(255) NOT NULL,
  `msn` varchar(255) NOT NULL,
  `qq` varchar(255) NOT NULL,
  `consignee` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `memo` varchar(255) NOT NULL,
  `adm_memo` varchar(255) NOT NULL,
  `payment` int(11) NOT NULL,
  `total_price` float(10,4) NOT NULL,
  `delivery` int(11) NOT NULL,
  `protect` tinyint(1) NOT NULL,
  `card_code` varchar(255) NOT NULL,
  `delivery_fee` float(10,4) NOT NULL,
  `protect_fee` float(10,4) NOT NULL,
  `payment_fee` float(10,4) NOT NULL,
  `tax` tinyint(1) NOT NULL,
  `tax_content` varchar(255) NOT NULL,
  `tax_money` float(10,4) NOT NULL,
  `order_weight` float(10,4) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_radio` float(10,4) NOT NULL,
  `order_score` int(11) NOT NULL,
  `promote_money` float(10,4) NOT NULL,
  `order_total_price` float(10,4) NOT NULL COMMENT 'order_total_price = total_price -  promote_money + delivery_fee + protect_fee + payment_fee + tax_money',
  `cost_total_price` float(10,4) NOT NULL,
  `cost_delivery_fee` float(10,4) NOT NULL,
  `cost_protect_fee` float(10,4) NOT NULL,
  `cost_payment_fee` float(10,4) NOT NULL,
  `cost_other_fee` float(10,4) NOT NULL,
  `order_incharge` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '已收金额',
  `order_profit` float(10,4) NOT NULL COMMENT 'order_profit = order_total_price - cost_total_price - cost_delivery_fee - cost_protect_fee - cost_payment_fee - cost_other_fee',
  `lang_conf_id` int(10) NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `discount` float(10,4) NOT NULL DEFAULT '0.0000',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `ecv_id` int(11) NOT NULL DEFAULT '0',
  `ecv_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `offline` tinyint(1) DEFAULT '0',
  `mobile_phone_sms` varchar(255) NOT NULL,
  `delivery_refer_order_id` int(10) DEFAULT '0',
  `repay_status` tinyint(1) DEFAULT '0',
  `bank_id` varchar(30) DEFAULT NULL,
  `order_referral_money` float(10,4) DEFAULT '0.0000',
  `tax_title` varchar(255) DEFAULT NULL,
  `goods_send_date` int(11) DEFAULT '0',
  `user_email` varchar(150) DEFAULT NULL,
  `is_360_post` tinyint(4) NOT NULL,
  `card_info` varchar(1000) DEFAULT NULL,
  `is_2345_post` tinyint(4) NOT NULL,
  `is_baidu_post` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inx_unique_sn` (`sn`),
  KEY `inx_order_001` (`offline`,`user_id`),
  KEY `inx_order_002` (`money_status`),
  KEY `inx_order_003` (`user_id`),
  KEY `inx_order_004` (`money_status`,`user_id`),
  KEY `inx_order_005` (`create_time`,`update_time`),
  KEY `index_1` (`money_status`,`goods_status`),
  KEY `index_2` (`money_status`,`delivery`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_order` values('87','1407291653469','2','5','0','1406595226','1406595226','','0','0','0','0','','lujfsd@163.com','','','','','','','','59','','','0','0.0000','0','0','','0.0000','0.0000','0.0000','0','','0.0000','0.0000','1','0.0000','10','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','1','0','0.0000','0','0','0.00','0','18116345506','0','0','','0.0000','','1406595226',NULL,'0',NULL,'0','0');");
E_D("replace into `ylife_order` values('88','1407291654501','2','5','0','1406595290','1406595290','','0','0','0','0','','lujfsd@163.com','','','','','','','','59','','','0','0.0000','0','0','','0.0000','0.0000','0.0000','0','','0.0000','0.0000','1','0.0000','20','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','1','0','0.0000','0','0','0.00','0','18116345506','0','0','','0.0000','','1406595290',NULL,'0',NULL,'0','0');");
E_D("replace into `ylife_order` values('81','1407251706065','2','5','0','1406250366','1406250366','','0','0','0','0','','lujfsd@163.com','','','','','','','','59','第一次买这个，看看好玩不','','0','0.0000','0','0','','0.0000','0.0000','0.0000','0','','0.0000','0.0000','1','0.0000','5','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','1','0','0.0000','0','0','0.00','0','18116345506','0','0','','0.0000','','1406250366',NULL,'0',NULL,'0','0');");
E_D("replace into `ylife_order` values('82','1407281433302','2','5','0','1406500410','1406500410','','0','0','0','0','','lujfsd@163.com','','','','','','','','59','','','0','0.0000','0','0','','0.0000','0.0000','0.0000','0','','0.0000','0.0000','1','0.0000','0','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','1','0','0.0000','0','0','0.00','0','18116345506','0','0','','0.0000','','1406500410',NULL,'0',NULL,'0','0');");
E_D("replace into `ylife_order` values('83','1407281435418','2','5','0','1406500541','1406500541','','0','0','0','0','','lujfsd@163.com','','','','','','','','59','','','0','0.0000','0','0','','0.0000','0.0000','0.0000','0','','0.0000','0.0000','1','0.0000','5','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','1','0','0.0000','0','0','0.00','0','18116345506','0','0','','0.0000','','1406500541',NULL,'0',NULL,'0','0');");
E_D("replace into `ylife_order` values('84','1407281540049','2','5','0','1406504404','1406504404','','0','0','0','0','','lujfsd@163.com','','','','','','','','59','','','0','0.0000','0','0','','0.0000','0.0000','0.0000','0','','0.0000','0.0000','1','0.0000','0','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','1','0','0.0000','0','0','0.00','0','18116345506','0','0','','0.0000','','1406504404',NULL,'0',NULL,'0','0');");
E_D("replace into `ylife_order` values('85','1407291648584','2','5','0','1406594938','1406594938','','0','0','0','0','','lujfsd@163.com','','','','','','','','59','','','0','0.0000','0','0','','0.0000','0.0000','0.0000','0','','0.0000','0.0000','1','0.0000','5','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','1','0','0.0000','0','0','0.00','0','18116345506','0','0','','0.0000','','1406594938',NULL,'0',NULL,'0','0');");
E_D("replace into `ylife_order` values('86','1407291652148','2','5','0','1406595133','1406595133','','0','0','0','0','','lujfsd@163.com','','','','','','','','59','','','0','0.0000','0','0','','0.0000','0.0000','0.0000','0','','0.0000','0.0000','1','0.0000','5','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','0.0000','1','0','0.0000','0','0','0.00','0','18116345506','0','0','','0.0000','','1406595134',NULL,'0',NULL,'0','0');");

require("../../inc/footer.php");
?>