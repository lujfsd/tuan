<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_delivery`;");
E_C("CREATE TABLE `fanwe_delivery` (
  `id` int(11) NOT NULL auto_increment,
  `name_1` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `first_weight` float(10,4) NOT NULL,
  `continue_weight` float(10,4) NOT NULL,
  `weight_unit` int(11) NOT NULL,
  `first_price` float(10,4) NOT NULL,
  `continue_price` float(10,4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `sort` int(11) NOT NULL,
  `desc_1` varchar(255) NOT NULL,
  `protect` tinyint(4) NOT NULL,
  `protect_price` float(10,4) NOT NULL,
  `protect_radio` float(10,4) NOT NULL,
  `allow_default` tinyint(4) NOT NULL,
  `allow_cod` tinyint(4) NOT NULL,
  `type` tinyint(1) NOT NULL default '0' COMMENT 'type 地区费用类型：0:统一设置;1:指定配送地区	',
  `min_protect_price` float(10,4) NOT NULL default '0.0000' COMMENT '最低保价费',
  `is_smzq` tinyint(1) default '0' COMMENT '1:上门自取,免费运费，不填地址',
  `express_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `status` (`status`),
  KEY `allow_cod` (`allow_cod`),
  KEY `is_smzq` (`is_smzq`),
  KEY `express_id` (`express_id`),
  KEY `type` (`type`),
  KEY `index_1` (`type`,`status`,`allow_cod`),
  KEY `index_2` (`is_smzq`,`status`),
  KEY `index_3` (`status`,`express_id`,`type`,`allow_cod`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_delivery` values('1','快递','http://www.1230530.com','1000.0000','1000.0000','5','5.0000','5.0000','1','1','','0','0.0000','0.0000','1','0','0','0.0000','1','2');");
E_D("replace into `fanwe_delivery` values('2','货到付款','http://www.1230530.com','1000.0000','1000.0000','5','5.0000','2.0000','1','2','','1','1.0000','1.0000','0','1','0','0.0000','0','1');");
E_D("replace into `fanwe_delivery` values('3','到商家消费','','0.0000','0.0000','5','0.0000','0.0000','1','3','','0','0.0000','0.0000','0','0','0','0.0000','0','5');");

require("../../inc/footer.php");
?>