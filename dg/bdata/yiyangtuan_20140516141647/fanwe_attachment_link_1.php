<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_attachment_link`;");
E_C("CREATE TABLE `fanwe_attachment_link` (
  `module` varchar(255) NOT NULL COMMENT '所关联的模块名称(Article/Goods)',
  `attachment_id` int(11) NOT NULL,
  `rec_id` int(11) NOT NULL,
  KEY `rec_id` (`rec_id`),
  KEY `attachment_id` (`attachment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>