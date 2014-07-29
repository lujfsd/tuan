<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_group_bond`;");
E_C(\"CREATE TABLE `fanwe_group_bond` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_id` varchar(200) NOT NULL DEFAULT '',
  `sn` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `use_time` int(11) NOT NULL DEFAULT '0',
  `buy_time` int(11) NOT NULL DEFAULT '0',
  `is_send_msg` tinyint(1) NOT NULL DEFAULT '0',
  `msg_length` int(11) NOT NULL DEFAULT '0',
  `is_lookat` tinyint(1) NOT NULL DEFAULT '0',
  `send_count` int(10) DEFAULT '0',
  `depart_id` int(10) DEFAULT '0',
  `is_valid` tinyint(1) NOT NULL,
  `arr` varchar(255) DEFAULT NULL,
  `order_goods_id` int(10) DEFAULT '0',
  `is_balance` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未结算 1:待结算 2:已结算',
  `balance_memo` text NOT NULL,
  `profit` double(20,4) NOT NULL DEFAULT '0.0000' COMMENT '结算单价',
  `balance_time` int(11) NOT NULL DEFAULT '0' COMMENT '结算时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `inx_group_bond_001` (`goods_id`,`user_id`,`is_lookat`),
  KEY `inx_group_bond_002` (`goods_id`,`user_id`),
  KEY `inx_group_bond_003` (`is_lookat`),
  KEY `inx_group_bond_004` (`sn`),
  KEY `inx_group_bond_005` (`order_id`),
  KEY `inx_group_bond_006` (`goods_id`,`user_id`,`is_lookat`,`is_valid`),
  KEY `inx_group_bond_007` (`order_goods_id`,`goods_id`,`order_id`),
  KEY `inx_group_bond_008` (`goods_id`,`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_group_bond` values('118','115','缘始陶艺双人','59','1407281540049','576456909575','1406504404','1','1409096404','1406504417','1406504404','1','0','0','0','21','1','','84','2','','0.0000','1406588038');");
E_D("replace into `fanwe_group_bond` values('117','114','缘始陶艺单人体验套餐','59','1407281435418','617893646612','1406500541','1','1409092541','1406594668','1406500541','1','0','0','0','21','1','','83','1','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('115','114','缘始陶艺单人体验套餐','59','1407251706065','15205566','1406250366','1','1408842366','1406252755','1406250366','1','0','1','0','0','1','','81','1','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('116','115','缘始陶艺双人','59','1407281433302','788723937789','1406500410','1','1409092410','1406586687','1406500410','1','0','0','0','21','1','','82','2','','0.0000','1406591344');");
E_D("replace into `fanwe_group_bond` values('119','114','缘始陶艺单人体验套餐','59','1407291648584','422867950423','1406594938','1','1409186938','1406594954','1406594938','1','0','0','0','21','1','','85','1','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('120','114','缘始陶艺单人体验套餐','59','1407291652148','719889862715','1406595134','1','1409187134','1406595204','1406595133','1','0','0','0','21','1','','86','1','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('121','114','缘始陶艺单人体验套餐','59','1407291653469','857968627853','1406595226','1','1409187226','1406595249','1406595226','1','0','0','0','21','1','','87','1','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('122','114','缘始陶艺单人体验套餐','59','1407291653469','622474914625','1406595226','1','1409187226','1406595234','1406595226','1','0','0','0','21','1','','87','1','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('123','114','缘始陶艺单人体验套餐','59','1407291654501','142450469147','1406595290','1','1409187290','1406595297','1406595290','1','0','0','0','21','1','','88','1','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('124','114','缘始陶艺单人体验套餐','59','1407291654501','958208953955','1406595290','1','1409187290','0','1406595290','1','0','0','0','0','1','','88','0','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('125','114','缘始陶艺单人体验套餐','59','1407291654501','617318511615','1406595290','1','1409187290','0','1406595290','1','0','0','0','0','1','','88','0','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('126','114','缘始陶艺单人体验套餐','59','1407291654501','978040863979','1406595290','1','1409187290','0','1406595290','1','0','0','0','0','1','','88','0','','0.0000','0');");

require("../../inc/footer.php");
?>