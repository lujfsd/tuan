<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_goods_type_attr`;");
E_C("CREATE TABLE `ylife_goods_type_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_1` varchar(255) NOT NULL,
  `input_type` tinyint(4) NOT NULL COMMENT '1:手工录入 0:列表中选择',
  `attr_value_1` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `attr_type` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>