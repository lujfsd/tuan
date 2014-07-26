<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_ajax_send`;");
E_C("CREATE TABLE `fanwe_ajax_send` (
  `id` int(11) NOT NULL auto_increment,
  `send_type` varchar(60) NOT NULL,
  `rec_id` int(11) NOT NULL default '0',
  `data` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `rec_id` (`rec_id`),
  KEY `send_type` (`send_type`),
  KEY `index_1` (`send_type`,`rec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>