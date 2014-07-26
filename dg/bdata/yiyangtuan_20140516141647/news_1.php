<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `news`;");
E_C("CREATE TABLE `news` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(128) default NULL,
  `detail` text,
  `begin_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `news` values('1','cvnbn','zdfasgsfhd','1385913600');");

require("../../inc/footer.php");
?>