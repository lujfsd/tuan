<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_nav`;");
E_C(\"CREATE TABLE `fanwe_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_1` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `rec_module` varchar(255) NOT NULL,
  `rec_action` varchar(255) NOT NULL,
  `rec_id` int(11) NOT NULL,
  `is_fix` tinyint(1) NOT NULL,
  `show_cate` tinyint(1) DEFAULT '0',
  `is_default` tinyint(1) DEFAULT '0',
  `all_city` tinyint(1) NOT NULL DEFAULT '1',
  `city_ids` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `inx_nav_001` (`type`,`status`),
  KEY `inx_nav_002` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_nav` values('39','线下团购','2','','40','0','BelowLine','index','0','0','0','0','1','');");
E_D("replace into `fanwe_nav` values('41','全部团购','2','','99','1','Index','index','0','1','1','0','1','');");
E_D("replace into `fanwe_nav` values('43','团购预告','2','','50','1','Advance','index','0','0','0','0','1','');");
E_D("replace into `fanwe_nav` values('44','今日团购','2','','100','1','Index','index','1','0','1','1','1','');");

require("../../inc/footer.php");
?>