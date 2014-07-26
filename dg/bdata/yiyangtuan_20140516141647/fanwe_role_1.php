<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_role`;");
E_C("CREATE TABLE `fanwe_role` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_role` values('3','产品管理员','1');");
E_D("replace into `fanwe_role` values('5','文章管理员','1');");
E_D("replace into `fanwe_role` values('7','高级管理员','1');");
E_D("replace into `fanwe_role` values('8','游客','1');");

require("../../inc/footer.php");
?>