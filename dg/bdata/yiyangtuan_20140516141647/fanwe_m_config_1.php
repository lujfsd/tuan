<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_m_config`;");
E_C("CREATE TABLE `fanwe_m_config` (
  `id` int(10) NOT NULL auto_increment,
  `code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `val` text NOT NULL,
  `type` tinyint(1) NOT NULL default '0' COMMENT '配置输入的类型 0:文本输入 1:下拉框输入 2:图片上传 3:编辑器',
  `value_scope` text NOT NULL COMMENT '取值范围',
  `title_scope` text NOT NULL,
  `sort` int(11) NOT NULL default '0',
  `is_effect` tinyint(1) NOT NULL default '1',
  `group_id` int(11) NOT NULL default '0',
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_m_config` values('1','catalog_id','默认分类id','0','1','','','3','1','0',NULL);");
E_D("replace into `fanwe_m_config` values('19','index_logo','首页logo','','2','','','2','1','0',NULL);");
E_D("replace into `fanwe_m_config` values('8','region_version','配送地区版本','1','0','','','8','1','0','默认填写为1');");
E_D("replace into `fanwe_m_config` values('9','only_one_delivery','只有一个配送地区','0','1','0,1','否,是','9','1','0',NULL);");
E_D("replace into `fanwe_m_config` values('10','kf_phone','客服电话','','0','','','5','1','0',NULL);");
E_D("replace into `fanwe_m_config` values('11','kf_email','客服邮箱','qq@1342474013.com','0','','','4','1','0',NULL);");
E_D("replace into `fanwe_m_config` values('12','select_payment_id','默认支付方式id','0','1','','','5','1','1',NULL);");
E_D("replace into `fanwe_m_config` values('15','delivery_id','默认配送方式id','1','1','','','5','1','1',NULL);");
E_D("replace into `fanwe_m_config` values('16','page_size','分页大小','10','0','','','6','1','0',NULL);");
E_D("replace into `fanwe_m_config` values('17','about_info','关于我们','技术支持 群星1314 qq:1342474013&nbsp;','3','','','10','1','0',NULL);");
E_D("replace into `fanwe_m_config` values('18','program_title','程序标题名称','团购网','0','','','1','1','0',NULL);");
E_D("replace into `fanwe_m_config` values('31','has_delivery_time','有配送日期选择','1','1','0,1','否,是','13','1','1',NULL);");
E_D("replace into `fanwe_m_config` values('32','has_ecv','有优惠券','0','1','0,1','否,是','9','1','1',NULL);");
E_D("replace into `fanwe_m_config` values('33','has_invoice','有发票','0','1','0,1','否,是','10','1','1',NULL);");
E_D("replace into `fanwe_m_config` values('34','has_message','有留言框','1','1','0,1','否,是','11','1','1',NULL);");
E_D("replace into `fanwe_m_config` values('35','has_region','有配送地区选择项','1','1','0,1','否,是','7','1','0',NULL);");
E_D("replace into `fanwe_m_config` values('36','select_delivery_time_id','默认配送日期id','3','1','','','6','1','1','dsads');");
E_D("replace into `fanwe_m_config` values('43','admin_pwd','后台管理帐户密码','6714ccb93be0fda4e51f206b91b46358','0','','','0','1','0',' 默认值为:fanwe');");

require("../../inc/footer.php");
?>