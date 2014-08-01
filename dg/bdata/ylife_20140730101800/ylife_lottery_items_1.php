<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_lottery_items`;");
E_C("CREATE TABLE `ylife_lottery_items` (
  `id` int(11) NOT NULL DEFAULT '0',
  `index` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` smallint(2) NOT NULL DEFAULT '0',
  `val` int(10) unsigned NOT NULL DEFAULT '0',
  `img` varchar(255) NOT NULL DEFAULT '',
  `total_num` int(11) NOT NULL DEFAULT '-1',
  `day_num` int(11) NOT NULL DEFAULT '0',
  `winning_num` int(11) NOT NULL DEFAULT '0',
  `probability` int(11) NOT NULL DEFAULT '0',
  `begin_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  KEY `id` (`id`,`index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>