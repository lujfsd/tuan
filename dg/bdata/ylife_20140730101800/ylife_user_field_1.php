<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_user_field`;");
E_C("CREATE TABLE `ylife_user_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(255) NOT NULL,
  `field_show_name` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '0文本 1下拉 2图片',
  `val_scope` text NOT NULL COMMENT '取值范围，仅供下拉选择用',
  `is_show` tinyint(1) NOT NULL,
  `is_must` tinyint(1) NOT NULL COMMENT '是否必选',
  `sort` int(11) NOT NULL,
  `field_show_desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `is_show` (`is_show`),
  KEY `is_must` (`is_must`),
  KEY `sort` (`sort`),
  KEY `index_1` (`is_show`,`is_must`,`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_user_field` values('1','sex','性别','1','男,女','1','1','1','');");
E_D("replace into `ylife_user_field` values('2','other_contact','其他联系方式','0','','1','0','2','');");

require("../../inc/footer.php");
?>