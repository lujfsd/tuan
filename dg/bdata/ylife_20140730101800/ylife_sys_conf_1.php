<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_sys_conf`;");
E_C("CREATE TABLE `ylife_sys_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `val` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `list_type` tinyint(1) NOT NULL COMMENT '0:手动输入 1:单选 2:下拉 3:文本域 4:图像',
  `val_arr` varchar(255) NOT NULL COMMENT '可选的值的集合。序列化存放',
  `group_id` tinyint(2) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `inx_sys_conf_001` (`status`,`name`),
  KEY `inx_sys_conf_002` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=186 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_sys_conf` values('3','SHOP_CLOSED','0','1','3','1','0,1','1','1');");
E_D("replace into `ylife_sys_conf` values('4','SHOP_REG_CLOSED','0','1','4','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('5','VIEW_GOODS_LIST','1','1','3','1','0,1','1','1');");
E_D("replace into `ylife_sys_conf` values('6','SHOP_LOGO','/Public/upload/public/201403/53259c1f3179f.png','1','6','4','','1','1');");
E_D("replace into `ylife_sys_conf` values('7','SN_PREFIX','DIY_','1','7','0','','2','1');");
E_D("replace into `ylife_sys_conf` values('8','WATER_MARK','0','1','8','1','0,1','2','1');");
E_D("replace into `ylife_sys_conf` values('9','BIG_WIDTH','440','1','9','0','','2','1');");
E_D("replace into `ylife_sys_conf` values('10','BIG_HEIGHT','280','1','10','0','','2','1');");
E_D("replace into `ylife_sys_conf` values('11','SMALL_WIDTH','110','1','11','0','','2','1');");
E_D("replace into `ylife_sys_conf` values('12','SMALL_HEIGHT','70','1','12','0','','2','1');");
E_D("replace into `ylife_sys_conf` values('13','WATER_IMAGE','/Public/upload/public/201312/529dd6cc56b3a.gif','1','13','4','','2','1');");
E_D("replace into `ylife_sys_conf` values('14','WATER_POSITION','4','1','14','2','1,2,3,4,5','2','1');");
E_D("replace into `ylife_sys_conf` values('15','WATER_ALPHA','70','1','15','0','','2','1');");
E_D("replace into `ylife_sys_conf` values('16','DB_VOL_MAXSIZE','8000000','1','16','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('35','CLOSE_CART','0','1','3','1','0,1','6','1');");
E_D("replace into `ylife_sys_conf` values('18','GOODS_PAGE_LISTROWS','14','1','18','0','','2','1');");
E_D("replace into `ylife_sys_conf` values('20','ARTICLE_PAGE_LISTROWS','10','1','20','0','','4','0');");
E_D("replace into `ylife_sys_conf` values('21','GOODS_SHORT_NAME','7','1','21','0','','2','0');");
E_D("replace into `ylife_sys_conf` values('22','ARTICLE_SHORT_NAME','5','1','22','0','','4','0');");
E_D("replace into `ylife_sys_conf` values('23','USE_STOCK','1','1','23','1','0,1','2','0');");
E_D("replace into `ylife_sys_conf` values('25','USER_AUTO_REG','1','1','25','1','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('26','MESSAGE_INTEVAL','5','1','26','0','','5','1');");
E_D("replace into `ylife_sys_conf` values('27','MESSAGE_AUTO_CHECK','1','1','27','1','0,1','5','1');");
E_D("replace into `ylife_sys_conf` values('28','MESSAGE_USER_ONLY','1','1','28','1','0,1','5','1');");
E_D("replace into `ylife_sys_conf` values('29','USER_CART','0','1','29','1','0,1','6','0');");
E_D("replace into `ylife_sys_conf` values('30','CART_MAX_TIME','36000','1','30','0','','6','1');");
E_D("replace into `ylife_sys_conf` values('31','URL_ROUTE','1','1','31','2','0,1','1','1');");
E_D("replace into `ylife_sys_conf` values('32','APP_LOG','1','1','32','2','0,1','1','1');");
E_D("replace into `ylife_sys_conf` values('33','URL_MODEL','2','1','33','2','0,2','1','1');");
E_D("replace into `ylife_sys_conf` values('34','PAGE_LISTROWS','8','1','34','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('106','PAYMENT_SMS','1','1','81','2','0,1','11','1');");
E_D("replace into `ylife_sys_conf` values('36','VERIFY_ON','0','1','36','2','0,1','1','0');");
E_D("replace into `ylife_sys_conf` values('37','RELATE_GOODS_COUNT','2','1','37','0','','2','0');");
E_D("replace into `ylife_sys_conf` values('38','RELATE_ARTICLE_COUNT','10','1','38','0','','4','0');");
E_D("replace into `ylife_sys_conf` values('39','GOODS_LIST_NUM','10','1','1','0','','2','1');");
E_D("replace into `ylife_sys_conf` values('42','DEFAULT_LANG','zh-cn','1','40','2','','1','0');");
E_D("replace into `ylife_sys_conf` values('43','SYS_ADMIN','fanwe','1','41','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('44','NO_PIC','','1','42','4','','1','0');");
E_D("replace into `ylife_sys_conf` values('45','MAX_UPLOAD','1000000','1','43','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('46','ALLOW_UPLOAD_EXTS','jpg,gif,png,jpeg,rar,zip,swf','1','44','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('47','IS_INQUIRY','0','1','45','2','0,1','2','0');");
E_D("replace into `ylife_sys_conf` values('48','TOKEN_ON','0','1','46','2','0,1','1','1');");
E_D("replace into `ylife_sys_conf` values('50','MAIL_ON','1','1','48','2','0,1','7','1');");
E_D("replace into `ylife_sys_conf` values('51','GET_PASSWORD','0','1','49','2','0,1','1','0');");
E_D("replace into `ylife_sys_conf` values('53','SMTP_SERVER','smtp.163.com','1','51','0','','7','1');");
E_D("replace into `ylife_sys_conf` values('54','SMTP_PORT','25','1','52','0','','7','1');");
E_D("replace into `ylife_sys_conf` values('55','SMTP_ACCOUNT','yiyangtuan@163.com','1','53','0','','7','1');");
E_D("replace into `ylife_sys_conf` values('56','SMTP_PASSWORD','WANGsheng1981410','1','54','0','','7','1');");
E_D("replace into `ylife_sys_conf` values('57','REPLY_ADDRESS','yiyangtuan@163.com','1','55','0','','7','1');");
E_D("replace into `ylife_sys_conf` values('58','SMTP_AUTH','1','1','56','2','0,1','7','1');");
E_D("replace into `ylife_sys_conf` values('62','SCORE_RADIO','0.1','1','57','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('63','DEFAULT_SCORE','10','1','58','0','','4','1');");
E_D("replace into `ylife_sys_conf` values('64','KEYWORDS_LIMIT','8','1','59','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('65','BG_COLOR','#ffffff','1','60','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('66','FLASH_STYLE','default','1','61','2','default,dynfocus,redfocus,pinkfocus','0','1');");
E_D("replace into `ylife_sys_conf` values('69','BRAND_LIMIT','6','1','64','0','','2','0');");
E_D("replace into `ylife_sys_conf` values('70','USER_AUTH_KEY','178_shop','1','65','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('71','DEFAULT_USER_GROUP','1','1','66','2','','4','1');");
E_D("replace into `ylife_sys_conf` values('72','TIME_ZONE','8','1','67','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('73','TAX_RADIO','0','1','68','0','','6','1');");
E_D("replace into `ylife_sys_conf` values('74','BASE_CURRENCY_UNIT','￥%s','1','69','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('75','HISTORY_COUNT','5','1','70','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('76','COMPARE_COUNT','5','1','71','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('77','PRICE_LEVEL','5','1','72','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('78','TOP_SALES','5','1','73','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('79','COMMENT_LIMIT','5','1','74','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('80','SHOP_NEWS_LIMIT','5','1','75','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('81','HELP_CENTER_LIMIT','4','1','76','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('82','HELP_CENTER_CATE_LIMIT','4','1','77','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('83','REFERRALS_MONEY','10','1','78','0','','4','1');");
E_D("replace into `ylife_sys_conf` values('85','MSN_SERVICES','','1','1','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('86','QQ_SERVICES','','1','1','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('100','IS_SMS','1','1','80','2','0,1','11','1');");
E_D("replace into `ylife_sys_conf` values('99','CLOSE_USERMONEY','1','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('91','GROUPBOTH','团购券','1','1','0',' ','1','1');");
E_D("replace into `ylife_sys_conf` values('92','PAGE_BOTTOM','<span style=\"background-color:#ffffff;color:#3d3d3d;\">系统名称：Y生活&nbsp; 版权所有&copy; Y生活</span>','1','1','5','\r\n','1','1');");
E_D("replace into `ylife_sys_conf` values('94','AUTO_SEND_SMS','1','1','81','2','0,1','11','1');");
E_D("replace into `ylife_sys_conf` values('95','AUTO_RUN_ING','0','1','1','0','0,1','1','0');");
E_D("replace into `ylife_sys_conf` values('96','AUTO_RUN_BEGIN_TIME','1278012877','1','1','0',' ','1','0');");
E_D("replace into `ylife_sys_conf` values('97','AUTO_REFERRAL','1','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('98','SYS_VERSION','4.3','1','1','0','','0','0');");
E_D("replace into `ylife_sys_conf` values('115','AUTO_GEN_IMAGE','1','1','1','2','0,1','2','1');");
E_D("replace into `ylife_sys_conf` values('116','SMS_SEND_LOG','1','1','83','2','0,1','11','1');");
E_D("replace into `ylife_sys_conf` values('117','SMS_SEND_NEW_GOODS','0','1','84','2','0,1','11','1');");
E_D("replace into `ylife_sys_conf` values('118','PAY_SHOW_TYPE','0','1','0','2','0,1','6','1');");
E_D("replace into `ylife_sys_conf` values('103','IS_SSL','0','1','1','1','0,1','7','1');");
E_D("replace into `ylife_sys_conf` values('104','FOOT_LOGO','/Public/upload/public/201403/53259cd7a11e3.png','1','1','4','','1','1');");
E_D("replace into `ylife_sys_conf` values('105','TEL','','1','80','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('107','DELIVERY_SMS','0','1','82','2','0,1','11','1');");
E_D("replace into `ylife_sys_conf` values('108','GROUP_MAIL_TMPL','meituan','1','1','0',' ','1','0');");
E_D("replace into `ylife_sys_conf` values('109','SEND_PAID_MAIL','1','1','1','2','0,1','7','1');");
E_D("replace into `ylife_sys_conf` values('110','SEND_DELIVERY_MAIL','1','1','1','2','0,1','7','1');");
E_D("replace into `ylife_sys_conf` values('111','SEND_GROUPBOND_MAIL','1','1','1','2','0,1','7','1');");
E_D("replace into `ylife_sys_conf` values('112','FREE_DELIVERY_LIMIT','999999','1','1','0',' ','6','1');");
E_D("replace into `ylife_sys_conf` values('113','REFERRAL_TIME','72','1','1','0',' ','4','1');");
E_D("replace into `ylife_sys_conf` values('114','REFERRAL_TYPE','1','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('119','OPEN_ECV','1','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('120','CLOSE_USERUNCHARGE','1','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('121','EXPIRED_TIME','60','1','1','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('122','ALLOW_TK','0','1','2','2','0,1','5','1');");
E_D("replace into `ylife_sys_conf` values('123','ALLOW_TH','0','1','1','2','0,1','5','1');");
E_D("replace into `ylife_sys_conf` values('124','CITYNAME_URL','1','1','1','2','0,1','1','1');");
E_D("replace into `ylife_sys_conf` values('125','SMS_SUBSCRIBE','0','1','84','2','0,1','11','1');");
E_D("replace into `ylife_sys_conf` values('126','SMS_SUBSCRIBE_EXPIRE','24','1','84','0','','11','1');");
E_D("replace into `ylife_sys_conf` values('127','GROUPBOND_PRINTTYPE','0','1','1','2','0,1','1','1');");
E_D("replace into `ylife_sys_conf` values('128','GROUP_IMG_TMPL','/Public/printbond.jpg','1','1','4','','1','1');");
E_D("replace into `ylife_sys_conf` values('129','AUTO_SEND_ING','0','1','1','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('130','AUTO_SEND_BEGIN_TIME','1278012872','1','1','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('131','SMS_LIMIT','2','1','1','0','','11','1');");
E_D("replace into `ylife_sys_conf` values('132','AUTO_SEND_MAIL_ING','0','1','1','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('133','AUTO_SEND_MAIL_BEGIN_TIME','1278012872','1','1','0','','1','0');");
E_D("replace into `ylife_sys_conf` values('134','MOBILE_PHONE_MUST','1','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('135','REFERRALS_LIMIT_TIME','24','1','1','0','','4','1');");
E_D("replace into `ylife_sys_conf` values('136','ADMIN_FILE_NAME','admin.php','1','1','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('137','GOODS_SMS_SEND_TYPE','1','1','84','2','0,1,2','11','1');");
E_D("replace into `ylife_sys_conf` values('138','GZIP_ON','1','1','1','2','0,1','1','1');");
E_D("replace into `ylife_sys_conf` values('139','SMS_SEND_OTHER','1','1','1','2','0,1','6','1');");
E_D("replace into `ylife_sys_conf` values('140','TODAY_OTHER_GROUP','3','1','1','0','','2','1');");
E_D("replace into `ylife_sys_conf` values('141','DB_PCONNECT','0','1','1','2','0,1','1','1');");
E_D("replace into `ylife_sys_conf` values('142','REFERRALS_IP_LIMIT','0','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('144','WORK_TIMES','周一至周六 9:00-18:00','1','1','3','','1','1');");
E_D("replace into `ylife_sys_conf` values('145','CLOSE_NOTICE','网站维护中...','1','4','5','','1','1');");
E_D("replace into `ylife_sys_conf` values('146','FREE_DELIVERY_NUM_LIMIT','9999','1','1','0','','6','1');");
E_D("replace into `ylife_sys_conf` values('147','CLOSE_BUY_MSG','0','1','3','1','0,1','5','1');");
E_D("replace into `ylife_sys_conf` values('148','MSG_ALL_CITY_VIEW','0','1','3','1','0,1','5','1');");
E_D("replace into `ylife_sys_conf` values('149','CLOSE_BEFORE_VIEW_NOW','0','1','3','1','0,1','2','1');");
E_D("replace into `ylife_sys_conf` values('150','CND_URL','','1','1','0','','1','1');");
E_D("replace into `ylife_sys_conf` values('151','FROM_ADDRESS','','1','50','0','','7','1');");
E_D("replace into `ylife_sys_conf` values('152','OPEX_SCORE','1','1','3','1','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('153','EX_SCORE_SCALE','100','1','3','0','','4','1');");
E_D("replace into `ylife_sys_conf` values('154','OPEN_PY_ROUTE','0','1','3','1','0,1','2','1');");
E_D("replace into `ylife_sys_conf` values('156','REGISTER_VERIFY','0','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('158','SINA_KEY','','1','1','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('159','SINA_SECRET','','1','2','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('160','QQ_KEY','','1','3','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('161','QQ_SECRET','','1','4','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('162','SHOW_TAX','0','1','1','2','0,1','6','1');");
E_D("replace into `ylife_sys_conf` values('163','REGISTER_MOBILE_VERIFY','0','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('164','MESSAGE_SCORE_CLS','0','1','1','2','0,1','5','1');");
E_D("replace into `ylife_sys_conf` values('165','MESSAGE_SCORE','5','1','3','0','','5','1');");
E_D("replace into `ylife_sys_conf` values('166','LOGIN_SCORE_CLS','0','1','1','2','0,1','4','1');");
E_D("replace into `ylife_sys_conf` values('167','LOGIN_SCORE','10','1','3','0','','4','1');");
E_D("replace into `ylife_sys_conf` values('168','STATS_CODE','','1','1','3','','1','1');");
E_D("replace into `ylife_sys_conf` values('169','360_KEY','','1','6','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('170','360_SECRET','','1','7','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('171','360_ORDER_INFO','0','1','8','2','0,1','12','1');");
E_D("replace into `ylife_sys_conf` values('172','ALIAPY_PARTNER','','1','9','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('173','ALIAPY_KEY','','1','10','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('174','SMS_GROUPBOND_USE','0','1','81','2','0,1','11','1');");
E_D("replace into `ylife_sys_conf` values('175','800_KEY','','1','6','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('176','800_SECRET','','1','7','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('177','TXQQ_KEY','','1','6','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('178','TXQQ_SECRET','','1','7','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('179','ALIPAY_INFO','0','1','7','2','0,1','12','1');");
E_D("replace into `ylife_sys_conf` values('180','2345_KEY','','1','6','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('181','2345_SECRET','','1','7','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('182','2345_ORDER_INFO','0','1','7','2','0,1','12','1');");
E_D("replace into `ylife_sys_conf` values('183','KUAIDI_APP_KEY','','1','0','0','','12','1');");
E_D("replace into `ylife_sys_conf` values('184','YOUHUI_LIMIT','1','1','10','0','','11','1');");
E_D("replace into `ylife_sys_conf` values('185','FIRST_VISIT_CITY','0','1','81','2','0,1','1','1');");

require("../../inc/footer.php");
?>