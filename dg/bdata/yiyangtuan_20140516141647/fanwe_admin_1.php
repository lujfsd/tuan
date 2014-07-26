<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_admin`;");
E_C("CREATE TABLE `fanwe_admin` (
  `id` int(11) NOT NULL auto_increment,
  `adm_name` varchar(20) NOT NULL,
  `adm_pwd` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '管理员帐号状态',
  `last_ip` varchar(15) NOT NULL,
  `last_time` int(11) NOT NULL,
  `login_count` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `name` (`adm_name`),
  KEY `adm_pwd` (`adm_pwd`),
  KEY `status` (`status`),
  KEY `role_id` (`role_id`),
  KEY `index_1` (`adm_name`,`adm_pwd`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_admin` values('7','admin','21232f297a57a5a743894a0e4a801fc3','1','112.53.68.12','1387516300','12','1257841086','1262165528','8');");
E_D("replace into `fanwe_admin` values('8','fanwe','6714ccb93be0fda4e51f206b91b46358','1','127.0.0.1','1394944716','288','1258605916','1389391123','7');");

require("../../inc/footer.php");
?>