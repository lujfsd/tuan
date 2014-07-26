<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_brand`;");
E_C("CREATE TABLE `fanwe_brand` (
  `id` int(11) NOT NULL auto_increment,
  `name_1` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `brand_url` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `desc_1` varchar(255) NOT NULL,
  `seokeyword_1` varchar(255) NOT NULL,
  `seocontent_1` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>