<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_m_config_list`;");
E_C("CREATE TABLE `ylife_m_config_list` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pay_id` varchar(50) DEFAULT NULL,
  `group` int(10) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `has_calc` int(1) DEFAULT NULL,
  `money` float(10,2) DEFAULT NULL,
  `is_verify` int(1) DEFAULT '0' COMMENT '0:无效；1:有效',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_m_config_list` values('1','0','1','Malipay','支付宝/各银行','0',NULL,'1');");
E_D("replace into `ylife_m_config_list` values('2','20','1','Mcod','现金支付/货到付款','1',NULL,'1');");
E_D("replace into `ylife_m_config_list` values('3','','5','1','家',NULL,NULL,'1');");
E_D("replace into `ylife_m_config_list` values('4','','5','2','公司',NULL,NULL,'1');");
E_D("replace into `ylife_m_config_list` values('6','','4','新闻公告','<p>登陆抚州团购网——开启快乐生活！</p>',NULL,NULL,'1');");
E_D("replace into `ylife_m_config_list` values('7','','6','2','办法用品',NULL,NULL,'1');");
E_D("replace into `ylife_m_config_list` values('8','','6','1','服装',NULL,NULL,'1');");
E_D("replace into `ylife_m_config_list` values('10','','2','1','周末配送',NULL,NULL,'1');");
E_D("replace into `ylife_m_config_list` values('11','','2','21','周一至周五',NULL,NULL,'1');");
E_D("replace into `ylife_m_config_list` values('19',NULL,'2','3','不限',NULL,NULL,'1');");

require("../../inc/footer.php");
?>