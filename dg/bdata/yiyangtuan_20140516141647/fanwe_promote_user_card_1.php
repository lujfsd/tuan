<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_promote_user_card`;");
E_C("CREATE TABLE `fanwe_promote_user_card` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>