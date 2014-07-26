<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_goods_type_attr`;");
E_C("CREATE TABLE `fanwe_goods_type_attr` (
  `id` int(11) NOT NULL auto_increment,
  `name_1` varchar(255) NOT NULL,
  `input_type` tinyint(4) NOT NULL COMMENT '1:手工录入 0:列表中选择',
  `attr_value_1` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `attr_type` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_goods_type_attr` values('1','颜色','0','红\r\n黄\r\n蓝','1','0');");
E_D("replace into `fanwe_goods_type_attr` values('2','尺寸','0','12\r\n13\r\n14','1','0');");

require("../../inc/footer.php");
?>