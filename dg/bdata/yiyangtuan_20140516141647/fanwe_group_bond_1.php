<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_group_bond`;");
E_C("CREATE TABLE `fanwe_group_bond` (
  `id` int(11) NOT NULL auto_increment,
  `goods_id` int(11) NOT NULL default '0',
  `goods_name` varchar(255) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `order_id` varchar(200) NOT NULL default '',
  `sn` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `create_time` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `end_time` int(11) NOT NULL default '0',
  `use_time` int(11) NOT NULL default '0',
  `buy_time` int(11) NOT NULL default '0',
  `is_send_msg` tinyint(1) NOT NULL default '0',
  `msg_length` int(11) NOT NULL default '0',
  `is_lookat` tinyint(1) NOT NULL default '0',
  `send_count` int(10) default '0',
  `depart_id` int(10) default '0',
  `is_valid` tinyint(1) NOT NULL,
  `arr` varchar(255) default NULL,
  `order_goods_id` int(10) default '0',
  `is_balance` tinyint(1) NOT NULL default '0' COMMENT '0:未结算 1:待结算 2:已结算',
  `balance_memo` text NOT NULL,
  `profit` double(20,4) NOT NULL default '0.0000' COMMENT '结算单价',
  `balance_time` int(11) NOT NULL default '0' COMMENT '结算时间',
  PRIMARY KEY  (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `inx_group_bond_001` (`goods_id`,`user_id`,`is_lookat`),
  KEY `inx_group_bond_002` (`goods_id`,`user_id`),
  KEY `inx_group_bond_003` (`is_lookat`),
  KEY `inx_group_bond_004` (`sn`),
  KEY `inx_group_bond_005` (`order_id`),
  KEY `inx_group_bond_006` (`goods_id`,`user_id`,`is_lookat`,`is_valid`),
  KEY `inx_group_bond_007` (`order_goods_id`,`goods_id`,`order_id`),
  KEY `inx_group_bond_008` (`goods_id`,`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_group_bond` values('114','111','11(尺寸：12,颜色：红)','54','1312180934298','14039827','66623130','1387301669','1','1389893669','0','1387301669','1','0','0','0','0','1','尺寸：12\n颜色：红','77','0','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('110','88','梵雅葡萄酒品尝套餐(尺寸：12,颜色：红)','54','1009041112239','595867','36616163','1283541144','1','1388217600','0','1283541143','0','0','0','0','0','1','','0','0','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('111','88','梵雅葡萄酒品尝套餐(尺寸：12,颜色：红)','54','1009041112239','999285','66376461','1283541144','1','1388217600','0','1283541143','0','0','0','0','0','1','','0','0','','0.0000','0');");
E_D("replace into `fanwe_group_bond` values('113','101','养生活肤膏','54','1312180918398','18419418','34326466','1387301045','1','1389893045','0','1387300719','0','0','0','0','0','0','','76','0','','0.0000','0');");

require("../../inc/footer.php");
?>