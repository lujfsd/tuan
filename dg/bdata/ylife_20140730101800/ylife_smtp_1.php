<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_smtp`;");
E_C("CREATE TABLE `ylife_smtp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smtp_server` varchar(255) NOT NULL,
  `smtp_account` varchar(255) NOT NULL,
  `smtp_password` varchar(255) NOT NULL,
  `smtp_port` varchar(255) NOT NULL,
  `smtp_auth` tinyint(4) NOT NULL,
  `is_ssl` tinyint(4) NOT NULL,
  `batch_limit` int(11) NOT NULL COMMENT '每次批量发送的数量',
  `batch_count` int(11) NOT NULL,
  `auto_reset` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `from_address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `status` (`status`),
  KEY `batch_limit` (`batch_limit`),
  KEY `is_ssl` (`is_ssl`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_smtp` values('1','smtp.qq.com','','','25','1','0','50','4','1','1','');");

require("../../inc/footer.php");
?>