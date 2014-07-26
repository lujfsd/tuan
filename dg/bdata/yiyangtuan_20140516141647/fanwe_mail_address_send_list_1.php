<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_mail_address_send_list`;");
E_C("CREATE TABLE `fanwe_mail_address_send_list` (
  `id` int(11) NOT NULL auto_increment,
  `mail_address_id` int(11) NOT NULL,
  `mail_id` int(11) NOT NULL,
  `is_push` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>