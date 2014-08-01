<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_mail_list`;");
E_C("CREATE TABLE `ylife_mail_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_title` varchar(255) NOT NULL,
  `mail_content` text NOT NULL,
  `is_html` tinyint(4) NOT NULL,
  `send_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `goods_id` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>