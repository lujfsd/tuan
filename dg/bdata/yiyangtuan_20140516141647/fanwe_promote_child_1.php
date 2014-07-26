<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_promote_child`;");
E_C("CREATE TABLE `fanwe_promote_child` (
  `id` int(11) NOT NULL auto_increment,
  `promote_id` int(11) NOT NULL,
  `module_name` varchar(20) NOT NULL COMMENT 'Goods/BindGoods/Promote',
  `rec_id` int(11) NOT NULL,
  `score` int(11) NOT NULL COMMENT '用于Goods',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>