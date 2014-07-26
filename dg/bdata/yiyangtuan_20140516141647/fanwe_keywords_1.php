<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_keywords`;");
E_C("CREATE TABLE `fanwe_keywords` (
  `id` int(11) NOT NULL auto_increment,
  `keywords` varchar(255) NOT NULL,
  `click_count` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `inx_keywords_001` (`lang_id`,`click_count`),
  KEY `inx_keywords_002` (`lang_id`),
  KEY `inx_keywords_003` (`click_count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>