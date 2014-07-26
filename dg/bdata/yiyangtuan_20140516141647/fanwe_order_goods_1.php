<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_order_goods`;");
E_C("CREATE TABLE `fanwe_order_goods` (
  `id` int(11) NOT NULL auto_increment,
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
  `data_total_referral_money` float(10,4) default '0.0000',
  `is_balance` tinyint(1) NOT NULL COMMENT '0:未结算 1:待结算 2:已结算 3:部份结算',
  `balance_unit_price` float(10,4) NOT NULL default '0.0000',
  `balance_memo` text NOT NULL,
  `balance_total_price` float(10,4) NOT NULL default '0.0000',
  `balance_time` int(11) NOT NULL COMMENT '结算时间',
  PRIMARY KEY  (`id`),
  KEY `inx_order_goods_001` (`order_id`),
  KEY `inx_order_goods_002` (`rec_id`),
  KEY `inx_order_goods_003` (`rec_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_order_goods` values('72','0','72','PromoteGoods','110','零度科技 7WLED灯（暖光白光）节能灯','178_1387151558','0','0','40.0000','40.0000','','1','0','0.0000','1387297917','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('73','0','73','PromoteGoods','105','负离子空气净化灯 3w','178_1387148501','0','0','45.0000','45.0000','','1','0','0.0000','1387299537','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('74','0','74','PromoteGoods','110','零度科技 7WLED灯（暖光白光）节能灯','178_1387151558','0','0','40.0000','40.0000','','1','0','0.0000','1387299755','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('71','0','71','PromoteGoods','91','索珞深层洁肤凝胶','178_1386559148','0','0','231.0000','231.0000','','1','0','0.0000','1387259901','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('70','0','70','PromoteGoods','93','纯天然去斑美白产品','178_1386561446','0','0','827.0000','827.0000','','1','0','0.0000','1387209690','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('68','0','69','PromoteGoods','92','索珞魔幻隔离乳','178_1386560709','0','0','459.0000','459.0000','','1','0','0.0000','1387209625','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('69','0','69','PromoteGoods','93','纯天然去斑美白产品','178_1386561446','0','0','827.0000','827.0000','','1','0','0.0000','1387209625','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('65','0','66','PromoteGoods','93','纯天然去斑美白产品','178_1386561446','0','0','827.0000','827.0000','','1','0','0.0000','1386896001','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('66','0','67','PromoteGoods','93','纯天然去斑美白产品','178_1386561446','0','0','827.0000','827.0000','','1','0','0.0000','1386896761','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('67','0','68','PromoteGoods','99','多用途洗宝王','178_1386636580','0','0','99.0000','99.0000','','1','0','0.0000','1386902367','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('64','0','65','PromoteGoods','102','法国纯植物DNA配方 索珞化妆品高级眼霜','178_1386887088','1','1','0.1000','0.1000','','1','0','0.0000','1386889685','0','1','0.0000','54','0.0000','1','0.0200','','0.0200','0');");
E_D("replace into `fanwe_order_goods` values('75','0','75','PromoteGoods','92','索珞魔幻隔离乳','178_1386560709','0','0','459.0000','459.0000','','1','0','0.0000','1387299801','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('76','0','76','PromoteGoods','101','养生活肤膏','178_1386638075','0','0','159.0000','159.0000','','1','0','0.0000','1387300719','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('77','0','77','PromoteGoods','111','11','111111111111111111','0','0','0.0000','0.0000','尺寸：12\n颜色：红','1','0','0.0000','1387301669','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('78','0','78','PromoteGoods','111','11','111111111111111111','0','0','1.0000','1.0000','尺寸：12\n颜色：红','1','0','0.0000','1387301954','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('79','0','79','PromoteGoods','111','11','111111111111111111','0','0','1.0000','1.0000','尺寸：12\n颜色：红','1','0','0.0000','1387302590','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");
E_D("replace into `fanwe_order_goods` values('80','0','80','PromoteGoods','111','11','111111111111111111','0','0','1.0000','1.0000','尺寸：12\n颜色：红','1','0','0.0000','1387303111','0','0','0.0000','54','0.0000','0','0.0000','','0.0000','0');");

require("../../inc/footer.php");
?>