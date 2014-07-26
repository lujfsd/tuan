<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_promote_card`;");
E_C("CREATE TABLE `fanwe_promote_card` (
  `id` int(11) NOT NULL auto_increment,
  `promote_id` int(11) NOT NULL,
  `card_code` varchar(30) NOT NULL,
  `card_limit` int(11) NOT NULL COMMENT '使用上限',
  `card_used` int(11) NOT NULL COMMENT '已使用次数',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>