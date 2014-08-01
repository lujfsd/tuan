<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_sms_mobile_verify`;");
E_C("CREATE TABLE `ylife_sms_mobile_verify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile_phone` varchar(50) NOT NULL DEFAULT '',
  `code` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `add_time` int(10) DEFAULT NULL,
  `send_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `inx_smv_0001` (`mobile_phone`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>