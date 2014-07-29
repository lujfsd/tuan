<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_group_message`;");
E_C(\"CREATE TABLE `fanwe_group_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tg_title` varchar(255) NOT NULL COMMENT '发起团购的标题',
  `tg_content` text NOT NULL COMMENT '发起团购的内容',
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `follows` int(11) NOT NULL COMMENT '被跟随的次数',
  `city_id` int(11) NOT NULL COMMENT '发起团购的城市',
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>