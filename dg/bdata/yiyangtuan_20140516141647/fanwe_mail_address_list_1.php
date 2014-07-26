<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_mail_address_list`;");
E_C("CREATE TABLE `fanwe_mail_address_list` (
  `id` int(11) NOT NULL auto_increment,
  `mail_address` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_mail_address_list` values('23','573873125@qq.com','0','55','16');");
E_D("replace into `fanwe_mail_address_list` values('21','97139915@qq.com','0','54','16');");
E_D("replace into `fanwe_mail_address_list` values('22','925635661@qq.com','0','54','16');");
E_D("replace into `fanwe_mail_address_list` values('24','292744004@qq.com','1','56','16');");
E_D("replace into `fanwe_mail_address_list` values('25','1139893504@qq.com','1','57','7');");

require("../../inc/footer.php");
?>