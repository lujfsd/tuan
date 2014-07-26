<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_user`;");
E_C("CREATE TABLE `fanwe_user` (
  `id` int(11) NOT NULL auto_increment,
  `user_name` varchar(255) NOT NULL,
  `user_pwd` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `email` varchar(255) NOT NULL,
  `qq` varchar(255) NOT NULL,
  `msn` varchar(255) NOT NULL,
  `alim` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `fix_phone` varchar(255) NOT NULL,
  `fax_phone` varchar(255) NOT NULL,
  `mobile_phone` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `pwd_question` varchar(255) NOT NULL,
  `pwd_answer` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `money` double(15,2) default '0.00',
  `city_id` int(11) NOT NULL,
  `subscribe` tinyint(1) NOT NULL default '0',
  `active_sn` varchar(200) NOT NULL default '',
  `reset_sn` varchar(200) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `sync_flag` int(11) default '0',
  `birthday` int(10) default '0',
  `buy_count` int(11) NOT NULL default '0',
  `is_receive_sms` tinyint(1) NOT NULL default '1',
  `ucenter_id` int(10) default NULL,
  `ucenter_id_tmp` int(10) default NULL,
  `qq_id` varchar(50) default NULL,
  `sina_id` varchar(50) default NULL,
  `360_id` varchar(50) default NULL,
  `alipay_id` varchar(50) default NULL,
  `800_id` varchar(50) default NULL,
  `txqq_id` varchar(50) default NULL,
  `2345_id` varchar(50) default NULL,
  `baidu_id` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `inx_user_001` (`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_user` values('55','白雪','e10adc3949ba59abbe56e057f20f883e','','0','1386887260','1386887260','112.53.68.12','0','573873125@qq.com','','','','','1','','','18669621042','','','','10','0.00','16','1','U93038ACF157F212EB405E032E207605B','','0','0','0','0','1','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `fanwe_user` values('56','ai黑天鹅','0c39ac7a13f39281765fedaa98996620','','1','1388367387','1388367387','112.251.19.141','0','292744004@qq.com','','','','','1','','','15376099791','','','','10','0.00','16','1','','','0','0','0','0','1','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `fanwe_user` values('54','醉清风','d407c601d7bbab205adb7144923c84c9','','1','1386543962','1386543962','112.53.68.12','0','925635661@qq.com','','','','','1','','','18653991358','','','','41','0.00','16','1','','','0','0','0','3','1','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `fanwe_user` values('57','爱在我心','538fa04d50b50ca103f064ec9b533718','','1','1388724358','1388724358','118.212.251.186','0','1139893504@qq.com','','','','','1','','','13117982756','','','','10','0.00','7','1','','','0','0','0','0','1','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);");
E_D("replace into `fanwe_user` values('58','yeamu','25f9e794323b453885f5181f1b624d0b','','1','1389162657','1389162657','111.75.86.62','0','1651350777@qq.com','','','','','1','','','','','','','10','0.00','0','0','','','0','0','0','0','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);");

require("../../inc/footer.php");
?>