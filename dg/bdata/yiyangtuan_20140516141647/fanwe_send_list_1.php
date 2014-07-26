<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_send_list`;");
E_C("CREATE TABLE `fanwe_send_list` (
  `id` int(11) NOT NULL auto_increment,
  `dest` varchar(255) NOT NULL COMMENT '发送目标(邮件地址/手机号)',
  `title` varchar(255) NOT NULL COMMENT '发送的标题(邮件用)',
  `content` text NOT NULL COMMENT '发送内容',
  `create_time` int(11) NOT NULL COMMENT '记录时间',
  `send_type` tinyint(1) NOT NULL COMMENT '发送类型：0:邮件 1:短信',
  `status` tinyint(4) NOT NULL,
  `send_time` int(11) NOT NULL,
  `bond_id` int(11) NOT NULL COMMENT '如为团购券，这里为团购券的ID号',
  `user_id` int(11) default '0',
  `err_msg` varchar(2000) default NULL,
  `order_id` varchar(255) default '0',
  PRIMARY KEY  (`id`),
  KEY `inx_send_list_001` (`status`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='邮件/短信 发送的队列数据'");
E_D("replace into `fanwe_send_list` values('1','18653991358','','178团购通知您，您的订单：1312131508058 付款 ¥0.1 成功','1386889813','1','1','1386889818','0','54','1条短信中，有1条未成功发送到手机18653991358','65');");
E_D("replace into `fanwe_send_list` values('2','925635661@qq.com','付款邮件通知','178团购通知您，您的订单：1312131508058 付款 ¥0.1 成功','1386889813','0','1','1386889820','0','54','z','65');");
E_D("replace into `fanwe_send_list` values('3','925635661@qq.com','发货邮件通知','178团购通知您，您的订单：1312131508058 已经发货，发货单号为','1386897546','0','1','1386897547','0','54','z','65');");
E_D("replace into `fanwe_send_list` values('4','18653991358','','178团购通知您，您的订单：1312180918398 付款 ¥159 成功','1387301045','1','1','1387301671','0','54','1条短信中，有1条未成功发送到手机18653991358','76');");
E_D("replace into `fanwe_send_list` values('5','925635661@qq.com','付款邮件通知','178团购通知您，您的订单：1312180918398 付款 ¥159 成功','1387301045','0','1','1387301672','0','54','z','76');");
E_D("replace into `fanwe_send_list` values('6','18653991358','','你好，醉清风。感谢您团购“11(尺寸：12,颜色：红)”！您的订单号：1312180934298，ID:114，您的178券序列号为14039827，密码是66623130，消费时请出示此短信。','1387301669','1','1','1387301670','114','54','1条短信中，有1条未成功发送到手机18653991358','77');");
E_D("replace into `fanwe_send_list` values('7','925635661@qq.com','团购券邮件通知','你好，醉清风。感谢您团购“11(尺寸：12,颜色：红)”！您的订单号：1312180934298，ID:114，您的178券序列号为14039827，密码是66623130，消费时请出示。','1387301669','0','1','1387301672','114','54','z','77');");

require("../../inc/footer.php");
?>