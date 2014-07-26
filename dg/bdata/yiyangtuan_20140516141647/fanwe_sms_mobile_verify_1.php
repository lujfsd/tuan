<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_sms_mobile_verify`;");
E_C("CREATE TABLE `fanwe_sms_mobile_verify` (
  `id` int(11) NOT NULL auto_increment,
  `mobile_phone` varchar(50) NOT NULL default '',
  `code` varchar(20) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `add_time` int(10) default NULL,
  `send_count` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `inx_smv_0001` (`mobile_phone`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>