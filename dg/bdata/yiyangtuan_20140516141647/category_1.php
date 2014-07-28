<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `category`;");
E_C("CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `zone` varchar(16) default NULL,
  `czone` varchar(32) default NULL,
  `name` varchar(32) default NULL,
  `ename` varchar(16) default NULL,
  `letter` char(1) default NULL,
  `sort_order` int(11) NOT NULL default '0',
  `display` enum('Y','N') NOT NULL default 'Y',
  `relate_data` text,
  `fid` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNQ_zne` (`zone`,`name`,`ename`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8");
E_D("replace into `category` values('1','city','','临沂','linyi','L','1','Y',NULL,'0');");
E_D("replace into `category` values('2','city','热门','北京','beijing','B','2','Y',NULL,'0');");
E_D("replace into `category` values('3','group',NULL,'生活类','shenghuolei','S','1','Y',NULL,'0');");
E_D("replace into `category` values('4','group',NULL,'商品类','shangpinlei','S','2','Y',NULL,'0');");
E_D("replace into `category` values('5','group',NULL,'理发','lifa','L','1','Y',NULL,'3');");
E_D("replace into `category` values('6','group',NULL,'化妆品','huazhuangpin','H','1','Y',NULL,'4');");
E_D("replace into `category` values('7','grade','初级会员111','初级会员','chujihuiyuan','C','1','Y',NULL,'0');");
E_D("replace into `category` values('8','partner','11111','vip客户','vipkehu','V','1','Y',NULL,'0');");
E_D("replace into `category` values('9','express','ppp','pp','pp','P','1','Y','5','0');");
E_D("replace into `category` values('10','grade','y','中级会员','zhongjiuiyuan','Z','1','Y',NULL,'0');");

require("../../inc/footer.php");
?>