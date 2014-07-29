<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_spec`;");
E_C(\"CREATE TABLE `fanwe_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spec_name_1` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `spec_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `spec_type_id` (`spec_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>