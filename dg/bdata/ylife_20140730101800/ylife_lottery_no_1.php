<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_lottery_no`;");
E_C("CREATE TABLE `ylife_lottery_no` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '奖抽商品',
  `order_id` int(11) NOT NULL COMMENT '单订ID',
  `sn` varchar(25) NOT NULL DEFAULT '' COMMENT '抽奖号',
  `user_id` int(11) NOT NULL COMMENT '受邀者用户ID',
  `invite_user_id` int(11) NOT NULL COMMENT '邀者用户ID',
  `invite_time` int(11) NOT NULL COMMENT '邀者时间',
  `status` tinyint(1) NOT NULL COMMENT '状态0:未抽奖;1:已抽奖;2:已中奖',
  PRIMARY KEY (`id`),
  KEY `inx_nav_001` (`order_id`,`status`),
  KEY `inx_nav_002` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>