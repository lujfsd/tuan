<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_sms`;");
E_C("CREATE TABLE `ylife_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(20) NOT NULL,
  `name` varchar(120) NOT NULL,
  `desc` text NOT NULL,
  `server_url` varchar(200) NOT NULL,
  `user_name` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `config` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_sms` values('7','QXT','企信通短信平台','','http://218.241.67.233:9000/QxtSms/QxtFirewall','dytg','dytg','a:1:{s:11:\"contentType\";s:1:\"8\";}','0');");
E_D("replace into `ylife_sms` values('8','S020','短信平台','','','','','a:1:{s:5:\"ecode\";s:0:\"\";}','1');");

require("../../inc/footer.php");
?>