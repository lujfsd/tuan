<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_mail_template`;");
E_C("CREATE TABLE `ylife_mail_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mail_title` varchar(255) NOT NULL,
  `mail_content` text NOT NULL,
  `is_html` tinyint(1) NOT NULL COMMENT '0:text 1:html',
  PRIMARY KEY (`id`),
  KEY `inx_mail_template_001` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_mail_template` values('4','get_password','{\$shop_name}重设密码','hi {\$user.user_name},<br><br>您在{\$shop_name}申请了重设密码，请点击下面的链接，然后根据页面提示完成密码重设：<br><br><br><a href=\"{\$user.reset_url}\" target=\"_blank\">{\$user.reset_url}</a><br><br>-- <br>{\$shop_name}','1');");
E_D("replace into `ylife_mail_template` values('5','share','有兴趣么：{\$title}','发现一好网站--178团购网，他们每天组织一次团购，超值！今天的团购是：{\$title}我想您会感兴趣的：','1');");
E_D("replace into `ylife_mail_template` values('6','user_active','感谢注册{\$shop_name}，请验证Email','hi {\$user.user_name},<br />\r\n<br />\r\n感谢您注册{\$shop_name}，请点击下面的链接验证您的Email：<br />\r\n<br />\r\n<a href=\"{\$user.active_url}\" target=\"_blank\"><u><font color=\"#0066cc\">点此处验证您的邮件</font></u></a><br />\r\n<br />\r\n-- <br />\r\n{\$shop_name}','1');");
E_D("replace into `ylife_mail_template` values('7','group_bond_sms','手机团购券','你好，{\$user_name}。感谢您团购“{\$bond.goods_name}”！您的订单号：{\$bond.order_sn}，ID:{\$bond.id}，您的{\$bond.name}序列号为{\$bond.sn}，密码是{\$bond.password}，消费时请出示此短信。','0');");
E_D("replace into `ylife_mail_template` values('11','payment_mail','付款邮件通知','178团购通知您，您的订单：{\$payment_notify.order_sn} 付款 {\$payment_notify.money} 成功','1');");
E_D("replace into `ylife_mail_template` values('12','delivery_mail','发货邮件通知','178团购通知您，您的订单：{\$delivery_notify.order_sn} 已经发货，发货单号为{\$delivery_notify.delivery_code}','1');");
E_D("replace into `ylife_mail_template` values('9','payment_sms','付款短信通知','178团购通知您，您的订单：{\$payment_notify.order_sn} 付款 {\$payment_notify.money} 成功','1');");
E_D("replace into `ylife_mail_template` values('10','delivery_sms','发货短信通知','方维团购通知您，您的订单：{\$delivery_notify.order_sn} 已经发货，发货单号为{\$delivery_notify.delivery_code}','0');");
E_D("replace into `ylife_mail_template` values('13','group_bond_mail','团购券邮件通知','你好，{\$user_name}。感谢您团购“{\$bond.goods_name}”！您的订单号：{\$bond.order_sn}，ID:{\$bond.id}，您的{\$bond.name}序列号为{\$bond.sn}，密码是{\$bond.password}，消费时请出示。','1');");
E_D("replace into `ylife_mail_template` values('14','goods_sms','团购短信通知','178团购网又有新团购呢！{\$goods_name} （{\$begin_time}）','1');");
E_D("replace into `ylife_mail_template` values('15','sms_subscribe_code','短信订阅认证码','订阅短信认证码：{\$code} [178团购网]','1');");
E_D("replace into `ylife_mail_template` values('16','sms_unsubscribe_code','短信退订，退订码','短信订阅退订码：{\$code} [178团购网]','1');");
E_D("replace into `ylife_mail_template` values('19','group_bond_use_sms','团购券消费提示短信','你好，{\$user_name}。您序列号为{\$bond.sn}的{\$bond.name}已于{\$time}消费了。','0');");
E_D("replace into `ylife_mail_template` values('17','sms_lottery_code','抽奖短信','欢迎您参加：{\$goods_name}活动,您的验证码为{\$code},祝您好运!','0');");
E_D("replace into `ylife_mail_template` values('18','sms_mobile_verify','注册手机验证','欢迎您参加注册:{\$shop_name},您的验证码为{\$code}!','1');");

require("../../inc/footer.php");
?>