<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_suppliers_cate`;");
E_C("CREATE TABLE `fanwe_suppliers_cate` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `sort` (`sort`),
  KEY `index_1` (`name`,`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_suppliers_cate` values('4','默认的分类','1','');");

require("../../inc/footer.php");
?>