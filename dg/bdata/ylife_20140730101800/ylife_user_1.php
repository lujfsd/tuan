<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_user`;");
E_C("CREATE TABLE `ylife_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `money` double(15,2) DEFAULT '0.00',
  `city_id` int(11) NOT NULL,
  `subscribe` tinyint(1) NOT NULL DEFAULT '0',
  `active_sn` varchar(200) NOT NULL DEFAULT '',
  `reset_sn` varchar(200) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `sync_flag` int(11) DEFAULT '0',
  `birthday` int(10) DEFAULT '0',
  `buy_count` int(11) NOT NULL DEFAULT '0',
  `is_receive_sms` tinyint(1) NOT NULL DEFAULT '1',
  `ucenter_id` int(10) DEFAULT NULL,
  `ucenter_id_tmp` int(10) DEFAULT NULL,
  `qq_id` varchar(50) DEFAULT NULL,
  `sina_id` varchar(50) DEFAULT NULL,
  `360_id` varchar(50) DEFAULT NULL,
  `alipay_id` varchar(50) DEFAULT NULL,
  `800_id` varchar(50) DEFAULT NULL,
  `txqq_id` varchar(50) DEFAULT NULL,
  `2345_id` varchar(50) DEFAULT NULL,
  `baidu_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inx_user_001` (`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_user` values('59','lujiefeng','e10adc3949ba59abbe56e057f20f883e','','1','1406250344','1406250344','127.0.0.1','0','lujfsd@163.com','','','','','1','','','18116345506','','','','80','0.00','18','1','','','0','0','0','8','1','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);");

require("../../inc/footer.php");
?>