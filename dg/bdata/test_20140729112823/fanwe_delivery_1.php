<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_delivery`;");
E_C(\"CREATE TABLE `fanwe_delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'type 地区费用类型：0:统一设置;1:指定配送地区	',
  `min_protect_price` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '最低保价费',
  `is_smzq` tinyint(1) DEFAULT '0' COMMENT '1:上门自取,免费运费，不填地址',
  `express_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
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

require("../../inc/footer.php");
?>