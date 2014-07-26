<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_goods_spec`;");
E_C("CREATE TABLE `fanwe_goods_spec` (
  `id` int(11) NOT NULL auto_increment,
  `spec_name_1` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `spec_type_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `define_img` tinyint(4) NOT NULL,
  `spec_id` int(11) NOT NULL,
  `idx` tinyint(4) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>