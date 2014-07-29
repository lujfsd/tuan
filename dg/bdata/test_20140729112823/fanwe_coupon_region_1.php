<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_coupon_region`;");
E_C(\"CREATE TABLE `fanwe_coupon_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `city_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `api_address` text NOT NULL,
  `xpoint` varchar(255) NOT NULL,
  `ypoint` varchar(255) NOT NULL,
  `zoom` int(11) NOT NULL,
  `pid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `city_id` (`city_id`),
  KEY `sort` (`sort`),
  KEY `status` (`status`),
  KEY `index_1` (`city_id`,`status`,`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_coupon_region` values('4','嘉定区','18','1','1','','','','16','0');");
E_D("replace into `fanwe_coupon_region` values('6','江桥万达广场','18','2','1','','','','16','4');");
E_D("replace into `fanwe_coupon_region` values('7','闵行区','18','3','1','','','','16','0');");
E_D("replace into `fanwe_coupon_region` values('8','浦东新区','18','4','1','','','','16','0');");
E_D("replace into `fanwe_coupon_region` values('9','龙华','18','5','1','上海市龙华','121.457595','31.178875','16','7');");
E_D("replace into `fanwe_coupon_region` values('10','陆家嘴','18','6','1','','','','16','8');");

require("../../inc/footer.php");
?>