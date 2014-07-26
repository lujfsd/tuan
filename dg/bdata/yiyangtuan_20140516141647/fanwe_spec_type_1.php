<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_spec_type`;");
E_C("CREATE TABLE `fanwe_spec_type` (
  `id` int(11) NOT NULL auto_increment,
  `name_1` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '0:文字 1:图片',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>